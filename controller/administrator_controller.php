<?php
// session_start(); // Eliminado para evitar duplicados, index.php ya debería manejar la sesión.

// Funciones del controlador de administrador
require_once __DIR__ . "/../model/administrator_model.php";

// Manejar solicitudes AJAX GET para el contador de pendientes
// Es importante que este bloque esté ANTES de cualquier otro bloque que pueda generar salida HTML si no es una solicitud AJAX específica.
if (isset($_GET["action"]) && $_GET["action"] == "get_pending_requests_count_ajax") {
    header("Content-Type: application/json");
    // Asegurarse de que no haya salida antes de este header.
    // session_start(); // Si se necesitara aquí y no estuviera ya iniciada, pero es mejor que index.php la maneje.
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Iniciar solo si no está activa, aunque idealmente index.php lo hace.
    }

    $adminModelForCount = new Administrator_Model();
    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "administrator") {
        echo json_encode(["success" => false, "pending_count" => 0, "message" => "No autorizado"]);
        exit;
    }
    $id_business_for_count = $adminModelForCount->get_id_business($_SESSION["id_user"]);
    if (!$id_business_for_count) {
        echo json_encode(["success" => false, "pending_count" => 0, "message" => "Administrador no asociado a un negocio"]);
        exit;
    }
    $pending_count = $adminModelForCount->count_pending_appointments($id_business_for_count);
    echo json_encode(["success" => true, "pending_count" => $pending_count]);
    exit;
}

// Manejar solicitudes AJAX generales (servicios, horarios, etc.) POST
if (isset($_POST["accion"])) {
    // session_start(); // Asegurar que la sesión esté activa si no lo está ya.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $datos = new Administrator_Model();

    if (!isset($_SESSION["id_user"])) {
        header("Content-Type: application/json"); // Asegurar que la respuesta de error también sea JSON
        echo json_encode(["success" => false, "message" => "Error de sesión."]);
        exit;
    }
    $id_business = $datos->get_id_business($_SESSION["id_user"]);

    // Esta validación de id_business podría necesitar ser más granular por acción
    if (!$id_business && !in_array($_POST["accion"], [/* Acciones que no necesitan id_business */])) {
        // No hacer nada o manejar error específico si es necesario
    } elseif (!$id_business && in_array($_POST["accion"], ["addService", "updateService", "addSchedule", "updateSchedule", "deleteService", "deleteSchedule", "modificarEstadoCita", "actualizarNegocio", "subirImagenNegocio"])) {
        header("Content-Type: application/json");
        echo json_encode(["success" => false, "message" => "No se encontró un negocio asociado a este administrador."]);
        exit;
    }

    header("Content-Type: application/json"); // Establecer cabecera JSON para todas las respuestas POST AJAX
    switch ($_POST["accion"]) {
        case "addService":
            $name = $_POST["name"];
            $duration = $_POST["duration"];
            $price = $_POST["price"];
            $result = $datos->add_service($name, $duration, $price, $id_business);
            echo json_encode($result ? ["success" => true, "message" => "Servicio añadido correctamente", "service_id" => $result] : ["success" => false, "message" => "Error al añadir el servicio"]);
            break;
        case "updateService":
            $serviceId = $_POST["service_id"];
            $name = $_POST["name"];
            $duration = $_POST["duration"];
            $price = $_POST["price"];
            $service = $datos->get_service_by_id($serviceId);
            if ($service && $service["id_business"] == $id_business) {
                $result = $datos->update_service($serviceId, $name, $duration, $price);
                echo json_encode($result ? ["success" => true, "message" => "Servicio actualizado correctamente"] : ["success" => false, "message" => "Error al actualizar el servicio"]);
            } else {
                echo json_encode(["success" => false, "message" => "No tienes permiso para editar este servicio"]);
            }
            break;
        case "addSchedule":
            $dayWeek = $_POST["day_week"];
            $openingHour = $_POST["opening_hour"];
            $closingHour = $_POST["closing_hour"];
            $result = $datos->add_schedule($dayWeek, $openingHour, $closingHour, $id_business);
            echo json_encode($result["success"] ? ["success" => true, "message" => "Horario añadido correctamente", "schedule_id" => $result["id"]] : ["success" => false, "message" => $result["message"] ?? "Error al añadir el horario"]);
            break;
        case "updateSchedule":
            $scheduleId = $_POST["schedule_id"];
            $dayWeek = $_POST["day_week"];
            $openingHour = $_POST["opening_hour"];
            $closingHour = $_POST["closing_hour"];
            $schedule = $datos->get_schedule_by_id($scheduleId);
            if ($schedule && $schedule["id_business"] == $id_business) {
                $result = $datos->update_schedule($scheduleId, $dayWeek, $openingHour, $closingHour, $id_business);
                echo json_encode($result["success"] ? ["success" => true, "message" => "Horario actualizado correctamente"] : ["success" => false, "message" => $result["message"] ?? "Error al actualizar el horario"]);
            } else {
                echo json_encode(["success" => false, "message" => "No tienes permiso para editar este horario"]);
            }
            break;
        case "deleteService":
            $serviceId = $_POST["service_id"];
            $result = $datos->delete_service($serviceId, $id_business);
            if ($result === false) {
                echo json_encode(["success" => false, "message" => "No tienes permiso para eliminar este servicio o el servicio no existe."]);
            } else if (isset($result["success"]) && $result["success"]) {
                echo json_encode(["success" => true, "message" => "Servicio eliminado correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => $result["message"] ?? "Error al eliminar el servicio"]);
            }
            break;
        case "deleteSchedule":
            $scheduleId = $_POST["schedule_id"];
            $result = $datos->delete_schedule($scheduleId, $id_business);
            if ($result === false) {
                echo json_encode(["success" => false, "message" => "No tienes permiso para eliminar este horario o el horario no existe."]);
            } else if (isset($result["success"]) && $result["success"]) {
                echo json_encode(["success" => true, "message" => "Horario eliminado correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => $result["message"] ?? "Error al eliminar el horario"]);
            }
            break;
        case "modificarEstadoCita":
            $id_appointment = $_POST["id_appointment"];
            $state = $_POST["state"];
            $result = $datos->update_appointment_state($id_appointment, $state, $id_business);
            echo json_encode($result ? ["success" => true, "message" => "Estado actualizado correctamente"] : ["success" => false, "message" => "Error al actualizar o no tienes permiso"]);
            break;
        case "actualizarNegocio":
            $field = $_POST["field"];
            $value = $_POST["value"];
            if ($field == "phone_number" && !preg_match("/^[0-9]{9}$/", $value)) {
                echo json_encode(["success" => false, "message" => "El número de teléfono debe tener 9 dígitos"]);
                exit;
            }
            if ($field == "category" && empty($value)) {
                echo json_encode(["success" => false, "message" => "La categoría del negocio no puede estar vacía"]);
                exit;
            }
            if ($field == "name" && empty($value)) {
                echo json_encode(["success" => false, "message" => "El nombre del negocio no puede estar vacío"]);
                exit;
            }
            if ($field == "address" && empty($value)) {
                echo json_encode(["success" => false, "message" => "La dirección del negocio no puede estar vacía"]);
                exit;
            }
            if ($field == "description" && empty($value)) {
                echo json_encode(["success" => false, "message" => "La descripción del negocio no puede estar vacía"]);
                exit;
            }
            if ($field == "postal_code" && !preg_match("/^[0-9]{5}$/", $value)) {
                echo json_encode(["success" => false, "message" => "El código postal debe tener 5 dígitos"]);
                exit;
            }
            $result = $datos->update_business_field($id_business, $field, $value);
            echo json_encode($result ? ["success" => true, "message" => "Información actualizada correctamente"] : ["success" => false, "message" => "Error al actualizar la información"]);
            break;
        case "deleteBusiness":
            // Obtener el ID del usuario actual
            $id_user = $_SESSION["id_user"];

            // Verificar que el usuario es administrador y tiene un negocio asociado
            if ($_SESSION["role"] !== "administrator" || !$id_business) {
                echo json_encode(["success" => false, "message" => "No tienes permiso para eliminar este negocio o no tienes un negocio asociado."]);
                exit;
            }

            // Eliminar el negocio y todos los datos asociados
            $result = $datos->delete_business($id_business, $id_user);

            if ($result) {
                // Cerrar la sesión del usuario
                session_unset();
                session_destroy();

                echo json_encode(["success" => true, "message" => "Negocio eliminado correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al eliminar el negocio"]);
            }
            break;
        case "deleteBusinessAndUser":
            $id_user = $_SESSION["id_user"];
            if ($_SESSION["role"] !== "administrator" || !$id_business) {
                echo json_encode(["success" => false, "message" => "No tienes permiso para eliminar este negocio o no tienes un negocio asociado."]);
                exit;
            }

            // Eliminar el negocio y todos los datos asociados
            $resultBusiness = $datos->delete_business($id_business, $id_user);

            // Eliminar el usuario administrador
            $resultUser = $datos->delete_user($id_user);

            if ($resultBusiness && $resultUser) {
                session_unset();
                session_destroy();
                echo json_encode(["success" => true, "message" => "Negocio y usuario eliminados correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al eliminar el negocio o el usuario"]);
            }
            break;
        case "subirImagenNegocio":
            if (isset($_FILES["business_image"]) && $_FILES["business_image"]["error"] === UPLOAD_ERR_OK) {
                $file = $_FILES["business_image"];
                $fileName = $file["name"];
                $fileTmpName = $file["tmp_name"];
                $fileSize = $file["size"];
                $fileError = $file["error"];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
                if (in_array($fileExt, $allowedExtensions)) {
                    if ($fileError === 0) {
                        if ($fileSize < 5000000) { // Menos de 5MB
                            $currentBusinessInfo = $datos->get_business_info($id_business);
                            $currentImage = $currentBusinessInfo["business_image"] ?? null;
                            $fileNameNew = "business_" . $id_business . "_" . uniqid("", true) . "." . $fileExt;

                            // Ruta para guardar el archivo físico (subiendo un nivel desde controller)
                            $uploadPath = "../images/";

                            // Crear la carpeta si no existe
                            if (!is_dir($uploadPath)) {
                                mkdir($uploadPath, 0777, true); // Permisos más permisivos para asegurar escritura
                            }

                            // Ruta completa para el archivo físico
                            $fileDestination = $uploadPath . $fileNameNew;

                            // Ruta para la base de datos (relativa a la raíz del proyecto)
                            $dbFilePath = "images/" . $fileNameNew;

                            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                                $result = $datos->update_business_image($id_business, $dbFilePath);
                                if ($result) {
                                    // Eliminar la imagen anterior si existe y no es la predeterminada
                                    if ($currentImage && $currentImage != "images/default_business.png") {
                                        $oldImagePath = "../" . $currentImage;
                                        if (file_exists($oldImagePath)) {
                                            unlink($oldImagePath);
                                        }
                                    }
                                    echo json_encode(["success" => true, "message" => "Imagen actualizada correctamente", "image_path" => $dbFilePath]);
                                } else {
                                    echo json_encode(["success" => false, "message" => "Error al actualizar la imagen en la base de datos"]);
                                }
                            } else {
                                echo json_encode(["success" => false, "message" => "Error al mover el archivo subido. Verifica los permisos de la carpeta."]);
                            }
                        } else {
                            echo json_encode(["success" => false, "message" => "El archivo es demasiado grande (máximo 5MB)"]);
                        }
                    } else {
                        echo json_encode(["success" => false, "message" => "Error al subir el archivo: código " . $fileError]);
                    }
                } else {
                    echo json_encode(["success" => false, "message" => "Tipo de archivo no permitido (solo jpg, jpeg, png, gif)"]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "No se ha seleccionado ningún archivo o ha ocurrido un error en la subida."]);
            }
            break;
    }
    exit; // Terminar después de manejar la acción AJAX POST
}


// Funciones de controlador para vistas completas (estas no deberían generar salida si se llamó a un endpoint AJAX antes)
function home()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "administrator") {
        header("Location: index.php?controlador=business&action=iniciar");
        exit;
    }
    $datos = new Administrator_model();
    $id_business = $datos->get_id_business($_SESSION["id_user"]);
    $negocio = $datos->get_business_info($id_business);
    $selected_date = isset($_GET["date"]) ? $_GET["date"] : date("Y-m-d");
    $servicios = $datos->get_service($id_business);
    $citas = $datos->get_appointments_by_date($id_business, $selected_date);
    $horario = $datos->get_schedule($id_business);
    $current_date = date("Y-m-d");
    require_once("view/administrator_view.php");
}

function view_appointments_calendar()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "administrator") {
        header("Location: index.php?controlador=business&action=iniciar");
        exit;
    }
    $adminModel = new Administrator_Model();
    $id_user = $_SESSION["id_user"];
    $id_business = $adminModel->get_id_business($id_user);
    $nombre_negocio = "Mi Negocio";
    $horario_procesado = [];
    $reservas_semana = [];
    $week_offset = isset($_GET["week_offset"]) ? (int)$_GET["week_offset"] : 0;
    $current_date_for_week = new DateTime();
    if ($week_offset !== 0) {
        $current_date_for_week->modify(($week_offset * 7) . " days");
    }
    $day_of_week_num = $current_date_for_week->format("N");
    $current_week_start = clone $current_date_for_week;
    $current_week_start->modify("-" . ($day_of_week_num - 1) . " days")->setTime(0, 0, 0);

    if (!$id_business) {
        $nombre_negocio = "Negocio no encontrado";
    } else {
        $business_info = $adminModel->get_business_info($id_business);
        $nombre_negocio = $business_info["name"] ?? "Mi Negocio";
        $raw_horario = $adminModel->get_schedule($id_business);
        if ($raw_horario) {
            foreach ($raw_horario as $dia_h) {
                $db_day_val = intval($dia_h["day_week"]);
                $iso_day_num = ($db_day_val >= 1 && $db_day_val <= 7) ? $db_day_val : null;
                if ($iso_day_num !== null) {
                    $horario_procesado[$iso_day_num][] = [
                        "open" => (int)substr($dia_h["opening_hour"], 0, 2),
                        "close" => (int)substr($dia_h["closing_hour"], 0, 2),
                        "opening_full" => $dia_h["opening_hour"],
                        "closing_full" => $dia_h["closing_hour"]
                    ];
                }
            }
        }
        $reservas_semana = $adminModel->get_appointments_by_business_for_week($id_business, $current_week_start->format("Y-m-d"));
    }
    require_once("view/appointment_view.php");
}

function get_appointment_detail_ajax()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "administrator") {
        http_response_code(403);
        echo "Acceso denegado."; // Podría ser JSON también para consistencia
        exit;
    }
    $adminModel = new Administrator_Model();
    $appointment_id = $_GET["id"] ?? null;
    if (!$appointment_id) {
        http_response_code(400);
        echo "Error: No se proporcionó ID de la reserva."; // JSON?
        exit;
    }
    $id_business = $adminModel->get_id_business($_SESSION["id_user"]);
    if (!$id_business) {
        http_response_code(403);
        echo "Error: Administrador no asociado a un negocio."; // JSON?
        exit;
    }
    $reserva_detalle = $adminModel->get_appointment_details_for_admin($appointment_id, $id_business);
    if (!$reserva_detalle) {
        http_response_code(404);
        echo "Error: Reserva no encontrada o no pertenece a este negocio."; // JSON?
        exit;
    }
    // Esta acción devuelve HTML, no JSON, lo cual es correcto para cargar en una modal.
    include __DIR__ . "/../view/appointment_detail_modal_content.php";
    exit;
}

function view_pending_requests()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "administrator") {
        header("Location: index.php?controlador=business&action=iniciar");
        exit;
    }
    $adminModel = new Administrator_Model();
    $id_user = $_SESSION["id_user"];
    $id_business = $adminModel->get_id_business($id_user);
    $nombre_negocio = "Mi Negocio";
    $pending_appointments = [];

    if (!$id_business) {
        $nombre_negocio = "Negocio no encontrado";
    } else {
        $business_info = $adminModel->get_business_info($id_business);
        $nombre_negocio = $business_info["name"] ?? "Mi Negocio";
        $pending_appointments = $adminModel->get_pending_appointments($id_business);
    }
    require_once("view/admin_pending_requests_view.php");
}

function process_pending_appointment()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "administrator") {
        header("Location: index.php?controlador=business&action=iniciar");
        exit;
    }
    $adminModel = new Administrator_Model();
    $id_user = $_SESSION["id_user"];
    $id_business = $adminModel->get_id_business($id_user);

    if (!$id_business) {
        header("Location: index.php?controlador=administrator&action=view_pending_requests&error=nobusiness");
        exit;
    }

    $id_appointment = $_GET["id_appointment"] ?? null;
    $new_status = $_GET["status"] ?? null;

    if (!$id_appointment || !in_array($new_status, ["confirmed", "cancelled"])) {
        header("Location: index.php?controlador=administrator&action=view_pending_requests&error=invalidparams");
        exit;
    }

    $result = $adminModel->update_appointment_state($id_appointment, $new_status, $id_business);

    if ($result) {
        header("Location: index.php?controlador=administrator&action=view_pending_requests&success=statusupdated");
    } else {
        header("Location: index.php?controlador=administrator&action=view_pending_requests&error=updatefailed");
    }
    exit;
}
