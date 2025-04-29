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
        return $result ? $result['id_business'] : null;
    }

    public function get_service($businessId)
    {
        $stmt = $this->db->prepare("SELECT * FROM service WHERE id_business = ?");
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_appointment($businessId)
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

    public function get_appointments_by_date($businessId, $date)
    {
        // Crear fechas para el inicio y fin del día seleccionado
        $start_date = $date . ' 00:00:00';
        $end_date = $date . ' 23:59:59';
        
        $sql = "
            SELECT a.*, u.name AS customer_name, u.id_user, u.phone_number
            FROM appointment a
            LEFT JOIN users u ON a.id_customer = u.id_user
            WHERE a.id_business = ?
            AND a.date_time BETWEEN ? AND ?
            ORDER BY a.date_time ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $businessId, $start_date, $end_date);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

    public function update_appointment_state($id_appointment, $state)
    {
        $sql = "
            UPDATE appointment 
            SET state = ? 
            WHERE id_appointment = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $state, $id_appointment);
        return $stmt->execute();
    }

    // Métodos para gestión de servicios
    public function add_service($name, $duration, $price, $businessId)
    {
        $sql = "INSERT INTO service (name, duration, price, id_business) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sidi", $name, $duration, $price, $businessId);

        if ($stmt->execute()) {
            return $this->db->insert_id; // Devuelve el ID del nuevo servicio
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

    // Métodos para gestión de horarios
    public function add_schedule($dayWeek, $openingHour, $closingHour, $businessId)
    {
        // Verificar si ya existe un horario para ese día
        $sql = "SELECT id_schedule FROM schedule WHERE day_week = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $dayWeek, $businessId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Ya existe un horario para ese día, devolver error
            return ['success' => false, 'message' => 'Ya existe un horario para ese día'];
        }

        // Insertar nuevo horario
        $sql = "INSERT INTO schedule (day_week, opening_hour, closing_hour, id_business) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issi", $dayWeek, $openingHour, $closingHour, $businessId);

        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->db->insert_id];
        } else {
            return ['success' => false, 'message' => 'Error al añadir el horario'];
        }
    }

    public function update_schedule($scheduleId, $dayWeek, $openingHour, $closingHour, $businessId)
    {
        // Verificar si ya existe otro horario para ese día (excluyendo el actual)
        $sql = "SELECT id_schedule FROM schedule WHERE day_week = ? AND id_business = ? AND id_schedule != ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $dayWeek, $businessId, $scheduleId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Ya existe otro horario para ese día, devolver error
            return ['success' => false, 'message' => 'Ya existe otro horario para ese día'];
        }

        // Actualizar horario
        $sql = "UPDATE schedule SET day_week = ?, opening_hour = ?, closing_hour = ? WHERE id_schedule = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issii", $dayWeek, $openingHour, $closingHour, $scheduleId, $businessId);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el horario'];
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
        // Verificar que el servicio pertenece al negocio antes de eliminarlo
        $sql = "SELECT id_service FROM service WHERE id_service = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $serviceId, $businessId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false; // El servicio no pertenece a este negocio
        }

        // Verificar si hay citas asociadas a este servicio
        $sql = "SELECT id_appointment FROM appointment WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'No se puede eliminar el servicio porque tiene citas asociadas'];
        }

        // Eliminar el servicio
        $sql = "DELETE FROM service WHERE id_service = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $serviceId);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar el servicio'];
        }
    }

    public function delete_schedule($scheduleId, $businessId)
    {
        // Verificar que el horario pertenece al negocio antes de eliminarlo
        $sql = "SELECT id_schedule FROM schedule WHERE id_schedule = ? AND id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $scheduleId, $businessId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false; // El horario no pertenece a este negocio
        }

        // Eliminar el horario
        $sql = "DELETE FROM schedule WHERE id_schedule = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $scheduleId);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar el horario'];
        }
    }
    public function get_business_info($businessId) {
        $sql = "SELECT * FROM business WHERE id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $businessId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function update_business_field($businessId, $field, $value) {
        // Lista de campos permitidos para actualizar
        $allowedFields = ['name', 'category', 'description', 'address', 'phone_number', 'postal_code'];
        
        if (!in_array($field, $allowedFields)) {
            return false;
        }
        
        $sql = "UPDATE business SET $field = ? WHERE id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $value, $businessId);
        
        return $stmt->execute();
    }
    
    public function update_business_image($businessId, $imagePath) {
        $sql = "UPDATE business SET business_image = ? WHERE id_business = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $imagePath, $businessId);
        
        return $stmt->execute();
    }
    
}
