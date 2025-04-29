<?php
class Business_Model{
    private $db; //Para conectar a labase de datos
    private $datos; //Para alamcenar los datos de la base de datos en el array de datos
    //La funcion para conectar a mi base de dastos
    public function __construct(){
        //Es para llamar al codigo que tenemos en la carpeta conectar, asique con ese codigo enm nuestra funcion, lo conectamos a nuestra variable
        require_once("model/conectar.php");
        $this->db = Conectar::conexion();
        $this->datos = array();
    }

    public function get_business(){
        $sql = "SELECT * FROM business";
        $consulta = $this->db->query($sql);
        //Este while se hara mientras haya filas dentro de mi base de datos, a la variable registro se le va introduciendo el array asociativo de nombre que es la clave mas sus siguientes datos
        while ($registro=$consulta->fetch_assoc()) {
            //Aqui iremos añadiendo atras de datos, arrays de las filas de datos que va encontrando
            $this->datos[] =$registro; 
        }
        //Devuelve el objeto, en el que dentro estara todos los datos recogidos de la tabla usuario
       return $this->datos;
   }
   
   public function search_business($search_term) {
    $this->datos = array();
    // Escapar el término de búsqueda para prevenir inyección SQL
    $search_term = $this->db->real_escape_string($search_term);
    
    // Buscar por id_business, name o postal_code
    $sql = "SELECT * FROM business WHERE 
            id_business LIKE '%$search_term%' OR 
            name LIKE '%$search_term%' OR 
            postal_code LIKE '%$search_term%'";
            
    $consulta = $this->db->query($sql);
    
    while ($registro = $consulta->fetch_assoc()) {
        $this->datos[] = $registro;
    }
    
    return $this->datos;
}
   public function login($user, $contra){
    $sql = "SELECT role, id_user, name FROM users WHERE email = '$user' AND password = '$contra'";
    $consulta = $this->db->query($sql);

    if (mysqli_num_rows($consulta) > 0) {
        return mysqli_fetch_assoc($consulta); // Devolver el array con el rol
    }
    return false;
    }
    public function get_business_by_id($business_id) {
        $stmt = $this->db->prepare("SELECT * FROM business WHERE id_business = ?");
        $stmt->bind_param("i", $business_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_services_by_business($business_id) {
        $stmt = $this->db->prepare("SELECT * FROM service WHERE id_business = ?");
        $stmt->bind_param("i", $business_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_available_slots($business_id, $startDate, $endDate) {
        // 1. Obtener horario del negocio
        $stmt = $this->db->prepare("SELECT * FROM schedule WHERE id_business = ?");
        $stmt->bind_param("i", $business_id);
        $stmt->execute();
        $schedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // 2. Obtener citas existentes
        $stmt = $this->db->prepare("SELECT date_time FROM appointment 
                                  WHERE id_business = ? AND date_time BETWEEN ? AND ?");
        $stmt->bind_param("iss", $business_id, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $stmt->execute();
        $bookedSlots = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // 3. Generar slots disponibles (lógica compleja)
        $availableSlots = [];
        // ... (implementar algoritmo para calcular huecos libres)
        
        return $availableSlots;
    }
}

?>