<?php
// Detectar si es una petición AJAX antes de imprimir NADA
$isAjax = (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
);

// Solo imprimir HTML si NO es AJAX
if (!$isAjax):
?>
<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bubalu</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/layout.css">
    <link rel="stylesheet" href="styles/responsive.css">
    <link rel="stylesheet" href="styles/dark-theme.css">
    <link rel="stylesheet" href="styles/cookie-modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script type="text/javascript" src="js/theme-switcher.js"></script>
</head>
<body>
<?php endif; ?>

<?php
require_once("controller/front_controller.php");
?>

<?php if (!$isAjax): ?>
    <?php require_once("view/cookie_modal.php"); ?>
</body>
</html>
<?php endif; ?>
