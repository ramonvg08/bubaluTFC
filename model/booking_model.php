<?php
class Booking_Model {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/conectar.php';
        $this->db = Conectar::conexion();
    }

    // Obtener detalles del servicio y negocio
    public function get_service_details($service_id) {
        $stmt = $this->db->prepare("
            SELECT s.*, b.id_business, b.name AS business_name 
            FROM service s
            INNER JOIN business b ON s.id_business = b.id_business
            WHERE s.id_service = ?
        ");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Generar slots disponibles para 7 días
    public function get_available_slots($business_id, $service_duration) {
        $slots = [];
        
        // 1. Obtener horario del negocio
        $schedule = $this->db->query("
            SELECT day_week, opening_hour, closing_hour 
            FROM schedule 
            WHERE id_business = $business_id
        ")->fetch_all(MYSQLI_ASSOC);

        // 2. Generar fechas para los próximos 7 días
        $startDate = new DateTime();
        $endDate = (new DateTime())->modify('+7 days');
        
        for($date = $startDate; $date <= $endDate; $date->modify('+1 day')){
            $dayOfWeek = $date->format('N'); // 1=Lunes, 7=Domingo
            
            // Buscar horario para este día
            foreach($schedule as $day){
                if($day['day_week'] == $dayOfWeek){
                    $slots = array_merge($slots, 
                        $this->generate_time_slots(
                            $date->format('Y-m-d'),
                            $day['opening_hour'],
                            $day['closing_hour'],
                            $service_duration
                        ));
                }
            }
        }
        
        // 3. Filtrar slots ocupados
        return $this->filter_booked_slots($business_id, $slots);
    }

    private function generate_time_slots($date, $start, $end, $duration) {
        $slots = [];
        $startTime = strtotime("$date $start");
        $endTime = strtotime("$date $end");
        
        while($startTime < $endTime){
            $slotEnd = $startTime + ($duration * 60);
            if($slotEnd > $endTime) break;
            
            $slots[] = [
                'datetime' => date('Y-m-d H:i:s', $startTime),
                'formatted' => date('d/m/Y H:i', $startTime)
            ];
            
            $startTime = $slotEnd;
        }
        return $slots;
    }

    private function filter_booked_slots($business_id, $slots) {
        // Modificado para excluir citas canceladas
        $booked = $this->db->query("
            SELECT date_time 
            FROM appointment 
            WHERE id_business = $business_id AND state != 'canceled'
        ")->fetch_all(MYSQLI_ASSOC);
        
        $bookedTimes = array_column($booked, 'date_time');
        
        return array_filter($slots, function($slot) use ($bookedTimes) {
            return !in_array($slot['datetime'], $bookedTimes);
        });
    }

    // Crear nueva cita
    public function create_appointment($data) {
        // Verificar si hay comentarios para la cita
        if (isset($data['comments'])) {
            $stmt = $this->db->prepare("
                INSERT INTO appointment 
                (date_time, state, id_customer, id_business, id_service, comments)
                VALUES (?, 'earring', ?, ?, ?, ?)
            ");
            $stmt->bind_param("siiis", 
                $data['datetime'],
                $data['user_id'],
                $data['business_id'],
                $data['service_id'],
                $data['comments']
            );
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO appointment 
                (date_time, state, id_customer, id_business, id_service)
                VALUES (?, 'earring', ?, ?, ?)
            ");
            $stmt->bind_param("siii", 
                $data['datetime'],
                $data['user_id'],
                $data['business_id'],
                $data['service_id']
            );
        }
        return $stmt->execute();
    }

    public function get_available_slots_for_date($business_id, $service_duration, $date) {
        $slots = [];
    
        // 1. Obtener horario del negocio
        $schedule = $this->db->query("
            SELECT day_week, opening_hour, closing_hour 
            FROM schedule 
            WHERE id_business = $business_id
        ")->fetch_all(MYSQLI_ASSOC);
    
        // Convertir la fecha a un objeto DateTime
        $dateTime = new DateTime($date);
        $dayOfWeek = $dateTime->format('N'); // 1=Lunes, 7=Domingo
    
        // Buscar horario para este día
        foreach ($schedule as $day) {
            if ($day['day_week'] == $dayOfWeek) {
                $slots = array_merge($slots,
                    $this->generate_time_slots(
                        $date,
                        $day['opening_hour'],
                        $day['closing_hour'],
                        $service_duration
                    ));
            }
        }
    
        // 2. Filtrar slots ocupados
        return $this->filter_booked_slots_for_date($business_id, $slots, $date);
    }    
    
    private function filter_booked_slots_for_date($business_id, $slots, $date) {
        $dateTimeStart = new DateTime($date);
        $dateTimeEnd = new DateTime($date);
        $dateTimeEnd->modify('+1 day');
    
        $dateStart = $dateTimeStart->format('Y-m-d H:i:s');
        $dateEnd = $dateTimeEnd->format('Y-m-d H:i:s');
    
        // Modificado para excluir citas canceladas
        $stmt = $this->db->prepare("
            SELECT date_time 
            FROM appointment 
            WHERE id_business = ? AND date_time BETWEEN ? AND ? AND state != 'canceled'
        ");
        $stmt->bind_param("iss", $business_id, $dateStart, $dateEnd);
        $stmt->execute();
        $booked = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
        $bookedTimes = array_column($booked, 'date_time');
    
        return array_filter($slots, function($slot) use ($bookedTimes) {
            return !in_array($slot['datetime'], $bookedTimes);
        });
    }    
}
?>
