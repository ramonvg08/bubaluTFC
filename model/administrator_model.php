<?php
class Administrator_Model
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . "/conectar.php";
        $this->db = Conectar::conexion();
    }

    public function get_id_business($administratorId)
    {
        $sql = "SELECT id_business FROM business WHERE id_administrator = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $administratorId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result["id_business"] : null;
    }

    public function get_service($businessId)
    {
        $stmt = $this->db->prepare("SELECT * FROM service WHERE id_business = ?");
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_appointment($businessId) // This seems to get ALL appointments, not used by calendar directly
    {
        $sql = "
            SELECT a.*, u.name AS customer_name, u.id_user, u.phone_number
            FROM appointment a
            LEFT JOIN users u ON a.id_customer = u.id_user
            WHERE a.id_business = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_appointments_by_date($businessId, $date) // Gets appointments for a single day
    {
        $start_date = $date . " 00:00:00";
        $end_date = $date . " 23:59:59";

        $sql = "
            SELECT a.*, u.name AS customer_name, u.id_user, u.phone_number, s.name as service_name
            FROM appointment a
            LEFT JOIN users u ON a.id_customer = u.id_user
            LEFT JOIN service s ON a.id_service = s.id_service
            WHERE a.id_business = ?
            AND a.date_time BETWEEN ? AND ?
            ORDER BY a.date_time ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $businessId, $start_date, $end_date);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Nueva función para el calendario semanal
    public function get_appointments_by_business_for_week($businessId, $start_of_week_string)
    {
        $start_date = new DateTime($start_of_week_string);
        $end_date = clone $start_date;
        $end_date->modify("+6 days")->setTime(23, 59, 59);

        $start_date_formatted = $start_date->format("Y-m-d H:i:s");
        $end_date_formatted = $end_date->format("Y-m-d H:i:s");

        $sql = "
            SELECT 
                a.id_appointment, 
                a.date_time, 
                a.state, 
                a.comments,
                s.name AS service_name, 
                s.price AS service_price, 
                s.duration AS service_duration,
                u.name AS customer_name,
                u.phone_number AS customer_phone,
                u.email AS customer_email,
                b.name AS business_name
            FROM appointment a
            LEFT JOIN users u ON a.id_customer = u.id_user
            LEFT JOIN service s ON a.id_service = s.id_service
            LEFT JOIN business b ON a.id_business = b.id_business
            WHERE a.id_business = ? 
            AND a.date_time BETWEEN ? AND ?
            ORDER BY a.date_time ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $businessId, $start_date_formatted, $end_date_formatted);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Nueva función para los detalles de la cita en la modal
    public function get_appointment_details_for_admin($appointmentId, $businessId)
    {
        $sql = "
            SELECT 
                a.id_appointment, 
                a.date_time, 
                a.state,
                a.id_customer, 
                a.comments,
                s.name AS service_name, 
                s.price AS service_price, 
                s.duration AS service_duration,
                u.name AS customer_name,
                u.phone_number AS customer_phone,
                u.email AS customer_email,
                b.name AS business_name
            FROM appointment a
            LEFT JOIN users u ON a.id_customer = u.id_user
            LEFT JOIN service s ON a.id_service = s.id_service
            LEFT JOIN business b ON a.id_business = b.id_business
            WHERE a.id_appointment = ? AND a.id_business = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $appointmentId, $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_pending_appointments($businessId)
    {
        $sql = "
            SELECT 
                a.id_appointment, 
                a.date_time, 
                a.state, 
                a.comments,
                s.name AS service_name, 
                u.name AS customer_name
            FROM appointment a
            LEFT JOIN users u ON a.id_customer = u.id_user
            LEFT JOIN service s ON a.id_service = s.id_service
            WHERE a.id_business = ? AND a.state = 'earring'
            ORDER BY a.date_time ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function count_pending_appointments($businessId)
    {
        $sql = "SELECT COUNT(*) as pending_count FROM appointment WHERE id_business = ? AND state = 'earring'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? (int)$result["pending_count"] : 0;
    }

    public function get_customers_with_appointments($businessId)
    {
        $sql = "
            SELECT DISTINCT u.id_user, u.name
            FROM users u
            INNER JOIN appointment a ON u.id_user = a.id_customer
            WHERE a.id_business = ?
            AND u.role = 'customer' 
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_schedule($businessId)
    {
        $sql = "
            SELECT id_schedule, day_week, opening_hour, closing_hour
            FROM schedule 
            WHERE id_business = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function update_appointment_state($id_appointment, $state, $businessId) // Added businessId for verification
    {
        // First, verify the appointment belongs to the business
        $verify_sql = "SELECT id_appointment FROM appointment WHERE id_appointment = ? AND id_business = ?";
        $verify_stmt = $this->db->prepare($verify_sql);
        $verify_stmt->bind_param("ii", $id_appointment, $businessId);
        $verify_stmt->execute();
        $result = $verify_stmt->get_result();
        if ($result->num_rows == 0) {
            return false; // Appointment not found or doesn't belong to this business
        }

        $sql = "
            UPDATE appointment 
            SET state = ? 
            WHERE id_appointment = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $state, $id_appointment);
        return $stmt->execute();
    }

    public function add_service($name, $duration, $price, $businessId)
    {
        $sql = "INSERT INTO service (name, duration, price, id_business) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sidi", $name, $duration, $price, $businessId);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            return false;
        }
    }

    public function update_service($serviceId, $name, $duration, $price)
    {
        $sql = "UPDATE service SET name = ?, duration = ?, price = ? WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sidi", $name, $duration, $price, $serviceId);
        return $stmt->execute();
    }

    public function get_service_by_id($serviceId)
    {
        $sql = "SELECT * FROM service WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function add_schedule($dayWeek, $openingHour, $closingHour, $businessId)
    {
        // Comprobar solapamientos
        $sql = "SELECT opening_hour, closing_hour FROM schedule WHERE day_week = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $dayWeek, $businessId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            // Si el horario es exactamente igual
            if ($row['opening_hour'] == $openingHour && $row['closing_hour'] == $closingHour) {
                return ["success" => false, "message" => "Ya existe un horario idéntico para ese día"];
            }
            // Comprobar solapamiento
            if (
                ($openingHour < $row['closing_hour']) && ($closingHour > $row['opening_hour'])
            ) {
                return ["success" => false, "message" => "El horario se solapa con otro existente para ese día"];
            }
        }
        $sql = "INSERT INTO schedule (day_week, opening_hour, closing_hour, id_business) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issi", $dayWeek, $openingHour, $closingHour, $businessId);
        if ($stmt->execute()) {
            return ["success" => true, "id" => $this->db->insert_id];
        } else {
            return ["success" => false, "message" => "Error al añadir el horario"];
        }
    }

    public function update_schedule($scheduleId, $dayWeek, $openingHour, $closingHour, $businessId)
    {
        // Comprobar solapamientos, excluyendo el propio horario
        $sql = "SELECT opening_hour, closing_hour FROM schedule WHERE day_week = ? AND id_business = ? AND id_schedule != ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $dayWeek, $businessId, $scheduleId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            // Si el horario es exactamente igual
            if ($row['opening_hour'] == $openingHour && $row['closing_hour'] == $closingHour) {
                return ["success" => false, "message" => "Ya existe un horario idéntico para ese día"];
            }
            // Comprobar solapamiento
            if (
                ($openingHour < $row['closing_hour']) && ($closingHour > $row['opening_hour'])
            ) {
                return ["success" => false, "message" => "El horario se solapa con otro existente para ese día"];
            }
        }
        $sql = "UPDATE schedule SET day_week = ?, opening_hour = ?, closing_hour = ? WHERE id_schedule = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issii", $dayWeek, $openingHour, $closingHour, $scheduleId, $businessId);
        if ($stmt->execute()) {
            return ["success" => true];
        } else {
            return ["success" => false, "message" => "Error al actualizar el horario"];
        }
    }

    public function get_schedule_by_id($scheduleId)
    {
        $sql = "SELECT * FROM schedule WHERE id_schedule = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $scheduleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function delete_service($serviceId, $businessId)
    {
        $sql = "SELECT id_service FROM service WHERE id_service = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $serviceId, $businessId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return false;
        }
        $sql = "SELECT id_appointment FROM appointment WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["success" => false, "message" => "No se puede eliminar el servicio porque tiene citas asociadas"];
        }
        $sql = "DELETE FROM service WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        if ($stmt->execute()) {
            return ["success" => true];
        } else {
            return ["success" => false, "message" => "Error al eliminar el servicio"];
        }
    }

    public function delete_schedule($scheduleId, $businessId)
    {
        $sql = "SELECT id_schedule FROM schedule WHERE id_schedule = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $scheduleId, $businessId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return false;
        }
        $sql = "DELETE FROM schedule WHERE id_schedule = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $scheduleId);
        if ($stmt->execute()) {
            return ["success" => true];
        } else {
            return ["success" => false, "message" => "Error al eliminar el horario"];
        }
    }

    public function get_business_info($businessId)
    {
        $sql = "SELECT * FROM business WHERE id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update_business_field($businessId, $field, $value)
    {
        $allowedFields = ["name", "category", "description", "address", "phone_number", "postal_code"];
        if (!in_array($field, $allowedFields)) {
            return false;
        }
        $sql = "UPDATE business SET $field = ? WHERE id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $value, $businessId);
        return $stmt->execute();
    }

    public function update_business_image($businessId, $imagePath)
    {
        $sql = "UPDATE business SET business_image = ? WHERE id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $imagePath, $businessId);
        return $stmt->execute();
    }

    public function delete_business($businessId, $userId)
    {
        // Iniciar transacción
        $this->db->begin_transaction();

        try {
            // 1. Eliminar todas las citas asociadas al negocio
            $sql = "DELETE FROM appointment WHERE id_business = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $businessId);
            $stmt->execute();

            // 2. Eliminar todos los servicios asociados al negocio
            $sql = "DELETE FROM service WHERE id_business = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $businessId);
            $stmt->execute();

            // 3. Eliminar todos los horarios asociados al negocio
            $sql = "DELETE FROM schedule WHERE id_business = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $businessId);
            $stmt->execute();

            // 4. Eliminar el negocio
            $sql = "DELETE FROM business WHERE id_business = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $businessId);
            $stmt->execute();

            // 5. Actualizar el rol del usuario a cliente
            $sql = "UPDATE users SET role = 'customer' WHERE id_user = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            // Confirmar la transacción
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->db->rollback();
            return false;
        }
    }

    public function delete_user($id_user)
    {
        // Elimina el usuario de la base de datos
        $sql = "DELETE FROM users WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_user]);
    }
}
