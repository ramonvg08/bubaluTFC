<?php
session_start();

function home()
{
    require_once("model/users_model.php");
    $datos = new Users_model();

    // Obtener solo los datos del usuario logueado
    $usuario = $datos->get_user_by_email($_SESSION["nombre"]); // Cambia "nombre" por "email" si es necesario

    require_once("view/users_view.php");
}

function reservas()
{
    require_once("model/users_model.php");
    $datos = new Users_model();

    // Obtener el ID del usuario de la sesión
    if (!isset($_SESSION["id_user"])) {
        // Redirigir o manejar error si el usuario no está logueado
        header("Location: index.php?controlador=business&action=iniciar");
        exit;
    }
    $user_id = $_SESSION["id_user"];

    // Obtener las reservas del usuario
    $reservas = $datos->get_appointments_by_user_id($user_id);

    // Cargar la vista de reservas específica del usuario
    // Esta vista es user_specific_reservations_view.php, no appointment_view.php
    require_once("view/user_specific_reservations_view.php");
}

// Función para ver el perfil de un usuario por ID
function verPerfil()
{
    // Verificar que el usuario esté logueado y sea administrador
    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] != "administrator") {
        header("Location: index.php");
        exit;
    }

    // Verificar que se haya proporcionado el ID del usuario
    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        header("Location: index.php?controlador=administrator&action=home");
        exit;
    }

    $user_id = $_GET["id"];

    // Cargar el modelo
    require_once("model/users_model.php");
    $model = new Users_model();

    // Obtener los datos del usuario
    $usuario = $model->get_user_by_id($user_id);

    // Si no se encuentra el usuario, redirigir
    if (!$usuario) {
        header("Location: index.php?controlador=administrator&action=home");
        exit;
    }

    // Cargar la vista de solo lectura del perfil de cliente
    require_once("view/client_profile_view.php");
}

function cancelarReserva()
{
    // Verificar que el usuario esté logueado
    if (!isset($_SESSION["id_user"])) {
        echo json_encode(["success" => false, "message" => "No autorizado"]);
        exit;
    }

    // Verificar que se haya proporcionado el ID de la cita
    if (!isset($_POST["id_appointment"]) || empty($_POST["id_appointment"])) {
        echo json_encode(["success" => false, "message" => "ID de cita no proporcionado"]);
        exit;
    }

    $appointment_id = $_POST["id_appointment"];
    $user_id = $_SESSION["id_user"];

    // Cargar el modelo
    require_once("model/users_model.php");
    $model = new Users_model();

    // Intentar cancelar la cita
    $result = $model->cancel_appointment($appointment_id, $user_id);

    // Devolver respuesta JSON
    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo cancelar la reserva"]);
    }
    exit;
}

// Función para actualizar un campo del perfil de usuario
function actualizarPerfil()
{
    // Verificar que el usuario esté logueado
    if (!isset($_SESSION["id_user"])) {
        header("Content-Type: application/json");
        echo json_encode(["success" => false, "message" => "No autorizado"]);
        exit;
    }

    // Verificar que se hayan proporcionado los datos necesarios
    if (!isset($_POST["field"]) || !isset($_POST["value"])) {
        header("Content-Type: application/json");
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit;
    }

    $field = $_POST["field"];
    $value = $_POST["value"];
    $user_id = $_SESSION["id_user"];

    // Cargar el modelo
    require_once("model/users_model.php");
    $model = new Users_model();

    // Actualizar el campo
    $result = $model->update_user_field($user_id, $field, $value);

    // Si se actualizó el email, actualizar también la sesión
    if ($result["success"] && $field === "email") {
        $_SESSION["nombre"] = $value;
    }

    // IMPORTANTE: Añadir header JSON antes del echo
    if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest") {
        header("Content-Type: application/json");
        echo json_encode($result);
        exit; // ¡Importantísimo!
    }
}

// Funcion para eliminar la cuenta de usuario
function deleteProfile()
{
    // Verificar que el usuario esté logueado
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["id_user"])) {
        header("Content-Type: application/json");
        echo json_encode(["success" => false, "message" => "No hay sesión activa"]);
        exit;
    }

    $id_user = $_SESSION["id_user"];
    $role = $_SESSION["role"];

    // Cargar el modelo
    require_once("model/users_model.php");
    $model = new Users_model();

    // Eliminar el perfil
    $result = $model->delete_user($id_user);

    if ($result) {
        // Cerrar la sesión del usuario
        session_unset();
        session_destroy();

        header("Content-Type: application/json");
        echo json_encode(["success" => true, "message" => "Perfil eliminado correctamente"]);
    } else {
        header("Content-Type: application/json");
        echo json_encode(["success" => false, "message" => "Error al eliminar el perfil"]);
    }
    exit;
}


// Función para subir la imagen de perfil
function subirImagenPerfil()
{
    // Verificar que el usuario esté logueado
    if (!isset($_SESSION["id_user"])) {
        echo json_encode(["success" => false, "message" => "No autorizado"]);
        exit;
    }

    // Verificar que se haya enviado un archivo
    if (!isset($_FILES["avatar_image"]) || $_FILES["avatar_image"]["error"] !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => "No se ha enviado ninguna imagen o ha ocurrido un error"]);
        exit;
    }

    $file = $_FILES["avatar_image"];
    $user_id = $_SESSION["id_user"];

    // Validar el tipo de archivo
    $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    if (!in_array($file["type"], $allowed_types)) {
        echo json_encode(["success" => false, "message" => "Tipo de archivo no permitido. Solo se permiten imágenes JPEG, PNG, GIF y WEBP"]);
        exit;
    }

    // Validar el tamaño del archivo (máximo 2MB)
    if ($file["size"] > 2 * 1024 * 1024) {
        echo json_encode(["success" => false, "message" => "La imagen es demasiado grande. El tamaño máximo es 2MB"]);
        exit;
    }

    // Crear un nombre único para el archivo
    $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $filename = "avatar_" . $user_id . "_" . time() . "." . $extension;
    $upload_path = "avatar_images/" . $filename;
    if (!is_dir("avatar_images/")) mkdir("avatar_images/", 0755, true);

    // Cargar el modelo
    require_once("model/users_model.php");
    $model = new Users_model();

    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($file["tmp_name"], $upload_path)) {
        // Actualizar la ruta de la imagen en la base de datos
        $result = $model->update_avatar_image($user_id, $upload_path);

        // Si la actualización fue exitosa y hay una imagen antigua
        if ($result["success"] && isset($result["old_image"])) {
            $old_image = $result["old_image"];

            // Verificar que no sea la imagen predeterminada
            if ($old_image && $old_image !== "avatar_images/default_avatar.png" && file_exists($old_image)) {
                // Eliminar la imagen antigua
                unlink($old_image);
            }
        }

        // Devolver respuesta JSON
        echo json_encode([
            "success" => $result["success"],
            "image_path" => $result["image_path"] ?? ""
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar la imagen"]);
    }
    exit;
}

// Función para mostrar el formulario de registro
function registro()
{
    // Inicializar variables para mantener los datos del formulario
    $formData = [
        'name' => '',
        'surnames' => '',
        'phone_number' => '',
        'email' => '',
        'business_name' => '',
        'business_category' => '',
        'business_description' => '',
        'business_address' => '',
        'business_postal_code' => '',
        'business_phone' => '',
        'role' => 'customer'
    ];

    // Inicializar array de errores
    $errors = [];

    // Mensaje general (para compatibilidad con código existente)
    $message = "";

    require_once("view/register_view.php");
}

// Función para mostrar mensaje de confirmación
function showConfirmation()
{
    $message_title = isset($_GET["message"]) ? $_GET["message"] : "¡Operación completada con éxito!";
    require_once("view/confirmation_view.php");
    exit; // Importante para evitar que se incluya el HTML del index.php
}

// Función para procesar el registro de usuarios
function procesarRegistro()
{
    // Inicializar arrays para datos del formulario y errores
    $formData = [];
    $errors = [];
    $message = ""; // Para compatibilidad con código existente

    // Recoger todos los datos del formulario
    foreach ($_POST as $key => $value) {
        $formData[$key] = htmlspecialchars(trim($value));
    }

    // Verificar que se hayan enviado los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $message = "Método no permitido";
        $errors['general'] = "Método no permitido";
        require_once("view/register_view.php");
        return;
    }

    // Validar campos obligatorios
    $required_fields = [
        "name" => "El nombre es obligatorio",
        "surnames" => "Los apellidos son obligatorios",
        "email" => "El correo electrónico es obligatorio",
        "password" => "La contraseña es obligatoria",
        "confirm_password" => "Debes confirmar la contraseña",
        "phone_number" => "El número de teléfono es obligatorio",
        "role" => "El tipo de usuario es obligatorio"
    ];

    foreach ($required_fields as $field => $errorMsg) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors[$field] = $errorMsg;
        }
    }

    // Validar formato de email
    if (!empty($formData['email']) && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "El formato del correo electrónico no es válido";
    }

    // Validar requisitos de contraseña
    if (!empty($formData['password'])) {
        if (strlen($formData['password']) < 8) {
            $errors['password'] = "La contraseña debe tener al menos 8 caracteres";
        } elseif (!preg_match('/[a-zA-Z]/', $formData['password']) || !preg_match('/[0-9]/', $formData['password'])) {
            $errors['password'] = "La contraseña debe contener letras y números";
        }
    }

    // Validar que las contraseñas coincidan
    if (!empty($formData['password']) && !empty($formData['confirm_password'])) {
        if ($formData['password'] !== $formData['confirm_password']) {
            $errors['confirm_password'] = "Las contraseñas no coinciden";
            $message = "Las contraseñas no coinciden";
        }
    }

    // Validar campos adicionales para administradores
    if ($formData['role'] === "administrator") {
        $admin_fields = [
            "business_name" => "El nombre del negocio es obligatorio",
            "business_category" => "La categoría del negocio es obligatoria",
            "business_description" => "La descripción del negocio es obligatoria",
            "business_address" => "La dirección del negocio es obligatoria",
            "business_postal_code" => "El código postal es obligatorio",
            "business_phone" => "El teléfono del negocio es obligatorio"
        ];

        foreach ($admin_fields as $field => $errorMsg) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $errors[$field] = $errorMsg;
            }
        }

        // Validar formato del código postal (5 dígitos)
        if (!empty($formData['business_postal_code']) && !preg_match("/^[0-9]{5}$/", $formData['business_postal_code'])) {
            $errors['business_postal_code'] = "El código postal debe contener exactamente 5 dígitos";
            $message = "El código postal debe contener exactamente 5 dígitos";
        }

        // Validar formato del teléfono (9 dígitos)
        if (!empty($formData['business_phone']) && !preg_match("/^[0-9]{9}$/", $formData['business_phone'])) {
            $errors['business_phone'] = "El teléfono debe contener 9 dígitos";
        }
    }

    // Si hay errores, volver al formulario con los datos y errores
    if (!empty($errors)) {
        // Establecer un mensaje general para compatibilidad con código existente
        if (empty($message)) {
            $message = "Por favor, corrige los errores en el formulario";
        }
        require_once("view/register_view.php");
        return;
    }

    // Preparar datos del usuario
    $userData = [
        "name" => $formData["name"],
        "surnames" => $formData["surnames"],
        "email" => $formData["email"],
        "password" => $formData["password"],
        "phone_number" => $formData["phone_number"],
        "role" => $formData["role"],
        "birthdate" =>"2000-01-01"
    ];

    // Preparar datos del negocio si es administrador
    $businessData = null;
    if ($formData["role"] === "administrator") {
        $businessData = [
            "name" => $formData["business_name"],
            "category" => $formData["business_category"],
            "description" => $formData["business_description"],
            "address" => $formData["business_address"],
            "postal_code" => $formData["business_postal_code"],
            "phone_number" => $formData["business_phone"],
            "business_image" => "images/default_business.png" // Imagen por defecto
        ];
    }

    // Cargar el modelo
    require_once("model/users_model.php");
    $model = new Users_model();

    // Verificar si el email ya existe
    if ($model->email_exists($formData["email"])) {
        $errors['email'] = "El correo electrónico ya está registrado";
        $message = "El correo electrónico ya está registrado";
        require_once("view/register_view.php");
        return;
    }

    // Registrar usuario
    $result = $model->register_user($userData, $businessData);

    if ($result) {
        // Determinar el mensaje según el tipo de usuario
        $confirmationMessage = ($formData["role"] === "administrator")
            ? "¡Usuario y negocio creados con éxito!"
            : "¡Usuario creado con éxito!";

        // Mostrar mensaje de confirmación mediante JavaScript
        echo "<script>
            // Guardar los datos en sessionStorage para recuperarlos después de la redirección
            sessionStorage.setItem(\"showConfirmation\", \"true\");
            sessionStorage.setItem(\"confirmationMessage\", \"" . $confirmationMessage . "\");
            
            // Redirigir a la página de inicio de sesión
            window.location.href = \"index.php?controlador=business&action=iniciar\";
        </script>";
        exit;
    } else {
        // Error en el registro
        $errors['general'] = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
        $message = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
        require_once("view/register_view.php");
    }
}
