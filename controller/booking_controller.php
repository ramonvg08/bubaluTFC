<?php
session_start();

function show() {
    $rol = $_SESSION['role'];

    // Verifica si el usuario está logueado
    if (!isset($_SESSION['id_user'])) {
        header("Location: index.php?controlador=business&action=iniciar"); // Redirige al inicio/login
        exit;
    }

    // Recuperar el service_id de la sesión
    $service_id = $_SESSION['service_id'] ?? null;

    if ($service_id === null) {
        die("Error: ID de servicio no proporcionado.");
    }

    require_once __DIR__ . '/../model/booking_model.php';
    $model = new Booking_Model();

    // Obtener datos del servicio
    $service = $model->get_service_details($service_id);
    
    if (!$service) {
        die("Error: Servicio no encontrado.");
    }

    // Generar slots disponibles
    $availableSlots = $model->get_available_slots($service['id_business'], $service['duration']);

    // Verificar si hay información de cliente externo (para administradores)
    $externalCustomer = isset($_SESSION['external_customer']) ? $_SESSION['external_customer'] : null;

    require_once __DIR__ . '/../view/booking_form_view.php';

    // Limpiar el service_id de la sesión después de usarlo
    unset($_SESSION['service_id']);
}

function create() {
    // Validar sesión
    if (!isset($_SESSION['id_user'])) {
        header("Location: index.php?controlador=business&action=iniciar");
        exit;
    }

    // Validar datos
    $required = ['service_id', 'business_id', 'datetime'];
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            die("Error: Datos incompletos");
        }
    }

    // Crear cita
    require_once __DIR__ . '/../model/booking_model.php';
    $model = new Booking_Model();
    
    $appointmentData = [
        'user_id' => $_SESSION['id_user'],
        'service_id' => $_POST['service_id'],
        'business_id' => $_POST['business_id'],
        'datetime' => $_POST['datetime']
    ];
    
    // Si es administrador y hay información de cliente externo, añadirla a los datos
    if ($_SESSION['role'] == 'administrator' && isset($_SESSION['external_customer'])) {
        $appointmentData['external_customer'] = $_SESSION['external_customer'];
        
        // Añadir los datos del cliente externo como comentarios en la cita
        $customerInfo = "Cliente: " . $_SESSION['external_customer']['name'] . 
                       ", Teléfono: " . $_SESSION['external_customer']['phone'];
        
        if (!empty($_SESSION['external_customer']['comments'])) {
            $customerInfo .= ", Comentarios: " . $_SESSION['external_customer']['comments'];
        }
        
        $appointmentData['comments'] = $customerInfo;
    }
    
    $result = $model->create_appointment($appointmentData);

    // Limpiar la información del cliente externo de la sesión
    if (isset($_SESSION['external_customer'])) {
        unset($_SESSION['external_customer']);
    }

    if ($result) {
        // No redirigimos aquí, la redirección se maneja en el frontend
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear la reserva']);
    }
    exit;
}

function getAvailableSlots() {
    // Importante: Establecer los headers antes de cualquier salida
    header('Content-Type: application/json');
    
    // Desactivar la salida de buffer para asegurar que solo se envíe el JSON
    ob_clean();
    
    error_log("📅 getAvailableSlots llamado");

    $date = $_GET['date'] ?? null;
    $service_id = $_GET['service_id'] ?? null;
    $business_id = $_GET['business_id'] ?? null;

    if (!$date || !$service_id || !$business_id) {
        echo json_encode([]);
        exit; // Terminar la ejecución para evitar que se incluya HTML
    }

    require_once __DIR__ . '/../model/booking_model.php';
    $model = new Booking_Model();

    $service = $model->get_service_details($service_id);

    if (!$service) {
        echo json_encode([]);
        exit; // Terminar la ejecución para evitar que se incluya HTML
    }

    $availableSlots = $model->get_available_slots_for_date($business_id, $service['duration'], $date);
    
    // Convertir el formato de los slots para que sea compatible con el frontend
    $formattedSlots = [];
    foreach ($availableSlots as $slot) {
        $formattedSlots[] = $slot['formatted'];
    }

    echo json_encode($formattedSlots);
    exit; // Terminar la ejecución para evitar que se incluya HTML
}

function showConfirmation() {
    $message_title = '¡Cita reservada con éxito!';
    require_once __DIR__ . '/../view/confirmation_view.php';
    exit;
}

?>
