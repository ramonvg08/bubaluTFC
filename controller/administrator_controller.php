<?php
session_start();
// Manejar solicitudes AJAX para servicios y horarios
if (isset($_POST["accion"])) {
    require_once __DIR__ . "/../model/administrator_model.php";
    $datos = new Administrator_Model();
    
    // Obtener el id_business del administrador actual
    $id_business = $datos->get_id_business($_SESSION["id_user"]);
    
    // Gestión de servicios
    if ($_POST["accion"] == "addService") {
        $name = $_POST["name"];
        $duration = $_POST["duration"];
        $price = $_POST["price"];
        
        $result = $datos->add_service($name, $duration, $price, $id_business);
        
        if ($result) {
            echo json_encode([
                "success" => true, 
                "message" => "Servicio añadido correctamente",
                "service_id" => $result
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "message" => "Error al añadir el servicio"
            ]);
        }
        exit;
    }
    
    if ($_POST["accion"] == "updateService") {
        $serviceId = $_POST["service_id"];
        $name = $_POST["name"];
        $duration = $_POST["duration"];
        $price = $_POST["price"];
        
        // Verificar que el servicio pertenece al negocio del administrador
        $service = $datos->get_service_by_id($serviceId);
        if ($service && $service['id_business'] == $id_business) {
            $result = $datos->update_service($serviceId, $name, $duration, $price);
            
            if ($result) {
                echo json_encode([
                    "success" => true, 
                    "message" => "Servicio actualizado correctamente"
                ]);
            } else {
                echo json_encode([
                    "success" => false, 
                    "message" => "Error al actualizar el servicio"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false, 
                "message" => "No tienes permiso para editar este servicio"
            ]);
        }
        exit;
    }
    
    // Gestión de horarios
    if ($_POST["accion"] == "addSchedule") {
        $dayWeek = $_POST["day_week"];
        $openingHour = $_POST["opening_hour"];
        $closingHour = $_POST["closing_hour"];
        
        $result = $datos->add_schedule($dayWeek, $openingHour, $closingHour, $id_business);
        
        if ($result['success']) {
            echo json_encode([
                "success" => true, 
                "message" => "Horario añadido correctamente",
                "schedule_id" => $result['id']
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "message" => $result['message'] ?? "Error al añadir el horario"
            ]);
        }
        exit;
    }
    
    if ($_POST["accion"] == "updateSchedule") {
        $scheduleId = $_POST["schedule_id"];
        $dayWeek = $_POST["day_week"];
        $openingHour = $_POST["opening_hour"];
        $closingHour = $_POST["closing_hour"];
        
        // Verificar que el horario pertenece al negocio del administrador
        $schedule = $datos->get_schedule_by_id($scheduleId);
        if ($schedule && $schedule['id_business'] == $id_business) {
            $result = $datos->update_schedule($scheduleId, $dayWeek, $openingHour, $closingHour, $id_business);
            
            if ($result['success']) {
                echo json_encode([
                    "success" => true, 
                    "message" => "Horario actualizado correctamente"
                ]);
            } else {
                echo json_encode([
                    "success" => false, 
                    "message" => $result['message'] ?? "Error al actualizar el horario"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false, 
                "message" => "No tienes permiso para editar este horario"
            ]);
        }
        exit;
    }
    if ($_POST["accion"] == "deleteService") {
        $serviceId = $_POST["service_id"];
        
        $result = $datos->delete_service($serviceId, $id_business);
        
        if ($result === false) {
            echo json_encode([
                "success" => false, 
                "message" => "No tienes permiso para eliminar este servicio"
            ]);
        } else if ($result['success']) {
            echo json_encode([
                "success" => true, 
                "message" => "Servicio eliminado correctamente"
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "message" => $result['message'] ?? "Error al eliminar el servicio"
            ]);
        }
        exit;
    }
    
    if ($_POST["accion"] == "deleteSchedule") {
        $scheduleId = $_POST["schedule_id"];
        
        $result = $datos->delete_schedule($scheduleId, $id_business);
        
        if ($result === false) {
            echo json_encode([
                "success" => false, 
                "message" => "No tienes permiso para eliminar este horario"
            ]);
        } else if ($result['success']) {
            echo json_encode([
                "success" => true, 
                "message" => "Horario eliminado correctamente"
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "message" => $result['message'] ?? "Error al eliminar el horario"
            ]);
        }
        exit;
    }

    if ($_POST["accion"] == "modificarEstadoCita") {
        $id_appointment = $_POST["id_appointment"];
        $state = $_POST["state"];
    
        // Actualizar el estado de la cita en la base de datos
        require_once __DIR__ . "/../model/administrator_model.php";
        $datos = new Administrator_Model();
        $result = $datos->update_appointment_state($id_appointment, $state);
    
        if ($result) {
            echo json_encode(["success" => true, "message" => "Estado actualizado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar"]);
        }
        exit; // Importante: detener la ejecución después de la respuesta AJAX
    }

    if ($_POST["accion"] == "actualizarNegocio") {
        $field = $_POST["field"];
        $value = $_POST["value"];
        
        // Validaciones específicas según el campo
        if ($field == "phone_number" && !preg_match('/^[0-9]{9}$/', $value)) {
            echo json_encode([
                "success" => false,
                "message" => "El número de teléfono debe tener 9 dígitos"
            ]);
            exit;
        }
        
        $result = $datos->update_business_field($id_business, $field, $value);
        
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Información actualizada correctamente"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al actualizar la información"
            ]);
        }
        exit;
    }
    
    // Manejar la subida de la imagen del negocio
    if ($_POST["accion"] == "subirImagenNegocio") {
        if (isset($_FILES['business_image']) && $_FILES['business_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['business_image'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            
            // Obtener la extensión del archivo
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Extensiones permitidas
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($fileExt, $allowedExtensions)) {
                if ($fileError === 0) {
                    if ($fileSize < 5000000) { // Menos de 5MB
                        // Primero, obtener la imagen actual para eliminarla después
                        $currentBusinessInfo = $datos->get_business_info($id_business);
                        $currentImage = $currentBusinessInfo['business_image'];
                        
                        // Crear un nombre único para el archivo
                        $fileNameNew = "business_" . $id_business . "_" . uniqid('', true) . "." . $fileExt;
                        
                        // Usar ruta absoluta o relativa según lo que funcionó para ti
                        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tfc/images/';
                        $fileDestination = $uploadDir . $fileNameNew;
                        $dbFilePath = 'images/' . $fileNameNew; // Para guardar en la base de datos
                        
                        // Mover el archivo a la carpeta de destino
                        if (move_uploaded_file($fileTmpName, $fileDestination)) {
                            // Actualizar la ruta de la imagen en la base de datos
                            $result = $datos->update_business_image($id_business, $dbFilePath);
                            
                            if ($result) {
                                // Eliminar la imagen anterior si existe y no es la imagen por defecto
                                if ($currentImage && $currentImage != 'images/default_business.png' && file_exists($_SERVER['DOCUMENT_ROOT'] . '/tfc/' . $currentImage)) {
                                    unlink($_SERVER['DOCUMENT_ROOT'] . '/tfc/' . $currentImage);
                                }
                                
                                echo json_encode([
                                    "success" => true,
                                    "message" => "Imagen actualizada correctamente",
                                    "image_path" => $dbFilePath
                                ]);
                            } else {
                                echo json_encode([
                                    "success" => false,
                                    "message" => "Error al actualizar la imagen en la base de datos"
                                ]);
                            }
                        } else {
                            echo json_encode([
                                "success" => false,
                                "message" => "Error al mover el archivo"
                            ]);
                        }
                    } else {
                        echo json_encode([
                            "success" => false,
                            "message" => "El archivo es demasiado grande (máximo 5MB)"
                        ]);
                    }
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Error al subir el archivo"
                    ]);
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Tipo de archivo no permitido (solo jpg, jpeg, png, gif)"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No se ha seleccionado ningún archivo o ha ocurrido un error"
            ]);
        }
        exit;
    }
}

function home(){
    require_once __DIR__ . "/../model/administrator_model.php";
    $datos = new Administrator_model();

    // Obtener el id_business del administrador actual
    $id_business = $datos->get_id_business($_SESSION["id_user"]);
    
    // Obtener información del negocio
    $negocio = $datos->get_business_info($id_business);
    
    // Obtener la fecha seleccionada o usar la fecha actual por defecto
    $selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    
    // Obtener servicios, citas filtradas por fecha y horario del negocio
    $servicios = $datos->get_service($id_business);
    $citas = $datos->get_appointments_by_date($id_business, $selected_date);
    $clientes = $datos->get_customers_with_appointments($id_business);
    $horario = $datos->get_schedule($id_business);
    
    // Fecha actual para comparar con la fecha seleccionada
    $current_date = date('Y-m-d');

    // Cargar la vista con toda la información
    require_once("view/administrator_view.php");
}
 