<?php
session_start();

function home(){
    //Aqui tenemos las variables de datos model
    require_once("model/business_model.php");
    $datos = new Business_model();
    
    // Verificar si se ha enviado una búsqueda
    if(isset($_GET["search"]) && !empty($_GET["search"])) {
        $search_term = $_GET["search"];
        $array = $datos->search_business($search_term);
    } else {
        $array = $datos->get_business();
    }

    //Y aqui todos los datos de view
    require_once("view/business_view.php");
}

function iniciar(){
    require_once("model/business_model.php");
    $datos = new Business_model();
    $message = "";

    if(!isset($_SESSION["nombre"])){
        if (isset($_POST["submit"])) {
            $usuario = isset($_POST["nombre"])? $_POST["nombre"]: "";
            $paswd = isset($_POST["pswd"])? $_POST["pswd"]: "";
            //Si los datos con login son iguales a alguno de la base de datos pase
            $user_data = $datos->login($usuario, $paswd);

            if ($user_data) {
                $_SESSION["nombre"] = $usuario;
                $_SESSION["role"] = $user_data["role"]; // Guardar el rol en la sesión
                $_SESSION["id_user"] = $user_data["id_user"];
                $message = "<p>Bienvenido $usuario !!</p>";
            } else {
                $message = "<p>Usuario o contraseña incorrectos</p>";
            }
        }
    }
    $array = $datos->get_business();
    require_once("view/session_view.php");
}

function detail() { 
    $business_id = $_GET["id"] ?? null;

    if (!$business_id) {
        die("Error: No se proporcionó un ID de negocio.");
    }

    require_once __DIR__ . "/../model/business_model.php";
    $businessModel = new Business_Model();

    $business = $businessModel->get_business_by_id($business_id);

    if (!$business) {
        die("Error: El negocio no existe.");
    }

    $services = $businessModel->get_services_by_business($business_id);

    // Esta acción ahora es para la página completa, la modal usará get_detail_content
    require_once __DIR__ . "/../view/business_detail_view.php"; 
}

// Nueva acción para cargar el contenido de la modal vía AJAX
function get_detail_content() {
    $business_id = $_GET["id"] ?? null;

    if (!$business_id) {
        // No usar die() para respuestas AJAX, mejor un error HTTP o un mensaje simple
        http_response_code(400);
        echo "Error: No se proporcionó un ID de negocio.";
        return;
    }

    require_once __DIR__ . "/../model/business_model.php";
    $businessModel = new Business_Model();
    $business = $businessModel->get_business_by_id($business_id);

    if (!$business) {
        http_response_code(404);
        echo "Error: El negocio no existe.";
        return;
    }

    $services = $businessModel->get_services_by_business($business_id);

    // Cargar solo el fragmento de la vista para la modal
    // Asegúrate de que business_detail_modal_content.php no incluya layouts completos (como menu.php)
    // y que las variables $business y $services estén disponibles para ella.
    include __DIR__ . "/../view/business_detail_modal_content.php";
}


function guardar_sesion() {
    if (!isset($_SESSION["id_user"])) {
        header("Location: index.php?controlador=business&action=iniciar");
        exit;
    }
    
    $service_id = $_POST["service_id"] ?? null;
    $_SESSION["service_id"] = $service_id;
    
    if (isset($_SESSION["role"]) && $_SESSION["role"] == "administrator") {
        if (isset($_POST["external_customer"])) {
            $_SESSION["external_customer"] = [
                "name" => $_POST["customer_name"] ?? 
                "",
                "phone" => $_POST["customer_phone"] ?? 
                "",
                "comments" => $_POST["customer_comments"] ?? 
                ""
            ];
        }
    }

    header("Location: index.php?controlador=booking&action=show");
    exit;
}

function desconectar(){
    session_destroy();
    header("Location: index.php");
}
?>
