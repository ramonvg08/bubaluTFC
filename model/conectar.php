<?php
class Conectar{
    public static function conexion(){
        try {
            $conexion = new mysqli("bbdd.bubalues.com", "ddb254416", "g135Jpkl)62%bj", "ddb254416");
            $conexion->set_charset("utf8mb4");
        } catch (Exception $e) {
            die('Error: '.$e->getMessage());
            }
        return $conexion;
    }
}

?>