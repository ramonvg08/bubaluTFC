<?php
function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}
//La carpeta donde buscaremos los controladores
define('CONTROLLERS_FOLDER',"controller/"); 
//Si no se indica un controlador, este es el controlador que se usará por defecto
define('DEFAULT_CONTROLLER',"business");
//Si no se indica una acción, esta acción es la que se usará por defecto
define('DEFAULT_ACTION', "home");
// Obtenemos el controlador por defecto. 
$controller = DEFAULT_CONTROLLER;
// Si el usuario lo indica, seleccionamos el controlador indicado.
if (!empty($_GET['controlador'])) {
    $controller = $_GET['controlador'];
} elseif (!empty($_POST['controlador'])) {
    $controller = $_POST['controlador'];
}
// Obtenemos la acción por defecto.
$action = DEFAULT_ACTION;
// Si el usuario la indica, seleccionamos la indicada.
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
} elseif (!empty($_POST['action'])) {
    $action = $_POST['action'];
}
//Ya tenemos el controlador y la accion
//Formamos el nombre del fichero que contiene nuestro controlador 
$controller = CONTROLLERS_FOLDER . $controller . '_controller.php';
//Si la variable $controller es un fichero lo requerimos
try {
    if (is_file($controller)) require_once($controller);
    else
        throw new Exception('El controlador no existe- 404 not found');
    //Si la variable $action es una funcion la ejecutamos o detenemos el script
    if (is_callable($action)) $action();
    else
        throw new Exception('La accion no existe- 404 not found');
    }catch(Exception $e) {
    console_log($e->getMessage());
}
?>
