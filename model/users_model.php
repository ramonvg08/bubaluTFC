<?php

class Users_Model
{
    private $db; //Para conectar a labase de datos
    private $datos; //Para alamcenar los datos de la base de datos en el array de datos
    //La funcion para conectar a mi base de dastos
    public function __construct()
    {
        //Es para llamar al codigo que tenemos en la carpeta conectar, asique con ese codigo enm nuestra funcion, lo conectamos a nuestra variable
        require_once("model/conectar.php");
        $this->db = Conectar::conexion();
        $this->datos = array();
    }

    // Método para obtener un usuario por email
    public function get_user_by_email($email)
    {
        $sql = "SELECT * FROM users WHERE email = '$email'"; // Ajusta "email" al nombre de tu columna
        $consulta = $this->db->query($sql);
        return $consulta->fetch_assoc(); // Devuelve un array asociativo, no un array de arrays
    }

    // Método para obtener un usuario por ID
    public function get_user_by_id($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_appointments_by_user_id($user_id)
    {
        $sql = "
            SELECT a.*, b.name AS business_name, s.name AS service_name
            FROM appointment a
            INNER JOIN business b ON a.id_business = b.id_business
            INNER JOIN service s ON a.id_service = s.id_service
            WHERE a.id_customer = ?
            ORDER BY a.date_time ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Método para cancelar una reserva
    public function cancel_appointment($appointment_id, $user_id)
    {
        // Primero verificamos que la cita pertenezca al usuario
        $sql_check = "
            SELECT id_appointment 
            FROM appointment 
            WHERE id_appointment = ? AND id_customer = ?
        ";
        $stmt_check = $this->db->prepare($sql_check);
        $stmt_check->bind_param("ii", $appointment_id, $user_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        // Si no hay resultados, la cita no pertenece al usuario
        if ($result->num_rows === 0) {
            return false;
        }

        // Si la cita pertenece al usuario, actualizamos su estado a 'canceled'
        $sql_update = "
            UPDATE appointment 
            SET state = 'canceled' 
            WHERE id_appointment = ?
        ";
        $stmt_update = $this->db->prepare($sql_update);
        $stmt_update->bind_param("i", $appointment_id);
        return $stmt_update->execute();
    }

    // Método para verificar si un email ya existe en la base de datos
    public function email_exists($email)
    {
        $stmt = $this->db->prepare("SELECT id_user FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Método para registrar un nuevo usuario
    public function register_user($userData, $businessData = null)
    {
        // Iniciar transacción
        $this->db->begin_transaction();

        try {
            // Insertar usuario
            $stmt = $this->db->prepare("
                INSERT INTO users (name, surnames, phone_number, email, password, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssssss",
                $userData['name'],
                $userData['surnames'],
                $userData['phone_number'],
                $userData['email'],
                $userData['password'],
                $userData['role']
            );

            $stmt->execute();

            // Obtener el ID del usuario recién insertado
            $user_id = $this->db->insert_id;

            // Si es administrador, insertar también los datos del negocio
            if ($userData['role'] === 'administrator' && $businessData !== null) {
                $stmt = $this->db->prepare("
                    INSERT INTO business (name, category, description, address, postal_code, phone_number, business_image, id_administrator) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->bind_param(
                    "sssssssi",
                    $businessData['name'],
                    $businessData['category'],
                    $businessData['description'],
                    $businessData['address'],
                    $businessData['postal_code'],
                    $businessData['phone_number'],
                    $businessData['business_image'],
                    $user_id
                );

                $stmt->execute();
            }

            // Confirmar transacción
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->db->rollback();
            error_log("Error en register_user: " . $e->getMessage());
            return false;
        }
    }

    // Método para actualizar un campo específico del perfil de usuario
    public function update_user_field($user_id, $field, $value)
    {
        // Lista de campos permitidos para actualizar
        $allowed_fields = ['name', 'surnames', 'email', 'password', 'birthdate', 'phone_number'];

        // Verificar que el campo sea válido
        if (!in_array($field, $allowed_fields)) {
            return ['success' => false, 'message' => 'Campo no válido'];
        }

        // Si el campo es email, verificar que no exista ya para otro usuario
        if ($field === 'email') {
            $stmt = $this->db->prepare("SELECT id_user FROM users WHERE email = ? AND id_user != ?");
            $stmt->bind_param("si", $value, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return ['success' => false, 'message' => 'El email ya está en uso por otro usuario'];
            }
        }

        // Preparar la consulta SQL
        $sql = "UPDATE users SET $field = ? WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $value, $user_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el campo: ' . $this->db->error];
        }
    }

    // Función para eliminar un usuario y todos sus datos asociados
    function delete_user($id_user)
{
    // Iniciar transacción para asegurar la integridad de los datos
    $this->db->begin_transaction();

    try {
        // Obtener información del usuario para eliminar archivos asociados
        $stmt = $this->db->prepare("SELECT avatar_image FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();

        // Eliminar citas asociadas al usuario
        $stmt = $this->db->prepare("DELETE FROM appointment WHERE id_customer = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $stmt->close();

        // Eliminar el usuario
        $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $deleted = $stmt->affected_rows > 0;
        $stmt->close();

        // Si todo ha ido bien, confirmar los cambios
        $this->db->commit();

        // Eliminar la imagen de avatar si existe y no es la predeterminada
        if ($deleted && $user_data && isset($user_data['avatar_image']) && $user_data['avatar_image'] != 'avatar_images/default_avatar.png') {
            if (file_exists($user_data['avatar_image'])) {
                unlink($user_data['avatar_image']);
            }
        }

        return $deleted;
    } catch (Exception $e) {
        // Si algo ha fallado, revertir los cambios
        $this->db->rollback();
        return false;
    }
}



    // Método para obtener la ruta de la imagen de perfil actual del usuario
    public function get_avatar_image($user_id)
    {
        $stmt = $this->db->prepare("SELECT avatar_image FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['avatar_image'];
        }

        return null;
    }

    // Método para actualizar la imagen de perfil del usuario
    public function update_avatar_image($user_id, $image_path)
    {
        // Obtener la imagen actual antes de actualizarla
        $current_image = $this->get_avatar_image($user_id);

        // Actualizar la imagen en la base de datos
        $sql = "UPDATE users SET avatar_image = ? WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $image_path, $user_id);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'image_path' => $image_path,
                'old_image' => $current_image
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al actualizar la imagen de perfil: ' . $this->db->error
            ];
        }
    }
}
