<?php
session_start();

function home(){
    //Aqui tenemos las variables de datos model
    require_once("model/business_model.php");
    $datos = new Business_model();
    
    // Verificar si se ha enviado una búsqueda
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = $_GET['search'];
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
                $_SESSION["role"] = $user_data['role']; // Guardar el rol en la sesión
                $_SESSION["id_user"] = $user_data['id_user'];
                $message = "<p>Bienvenido $usuario !!</p>";
            } else {
                $message = "<p>Usuario o contraseña incorrectos</p>";
            }
        }
    }
    $array = $datos->get_business();
    require_once("view/session_view.php");
}

function detail() {  // Cambiar business_detail por detail
    $business_id = $_GET['id'] ?? null;

    if (!$business_id) {
        die("Error: No se proporcionó un ID de negocio.");
    }

    require_once __DIR__ . "/../model/business_model.php";
    $businessModel = new Business_Model();

    // Obtener datos del negocio
    $business = $businessModel->get_business_by_id($business_id);

    if (!$business) {
        die("Error: El negocio no existe.");
    }

    // Obtener servicios del negocio
    $services = $businessModel->get_services_by_business($business_id);

    require_once __DIR__ . "/../view/business_detail_view.php";
}

function guardar_sesion() {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['id_user'])) {
        header("Location: index.php?controlador=business&action=iniciar"); // Redirige al inicio/login
        exit;
    }
    
    // Recibir el service_id del formulario
    $service_id = $_POST['service_id'] ?? null;
    // Guardar el service_id en la sesión
    $_SESSION['service_id'] = $service_id;
    
    // Si es administrador y hay información de cliente externo, guardarla en la sesión
    if ($_SESSION['role'] == 'administrator') {
        if (isset($_POST['external_customer'])) {
            $_SESSION['external_customer'] = [
                'name' => $_POST['customer_name'] ?? '',
                'phone' => $_POST['customer_phone'] ?? '',
                'comments' => $_POST['customer_comments'] ?? ''
            ];
        }
    }

    header("Location: index.php?controlador=booking&action=show");
    exit; // Importante: agregar exit() para detener la ejecución del script
}

function desconectar(){
    session_destroy();
    //Para que se vaya a la pagina de inicio
    header("Location: index.php");
}
?>
