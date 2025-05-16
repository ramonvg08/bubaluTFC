<?php
require_once("view/footer_loader.php");
//Aqui le decimos que si se ha logeado correctamente en el formulario de abajo, que llame a la vista del menu y de los datos
if (isset($_SESSION["nombre"])) {

    //Y que se vaya a la pagina de datos
    require_once("view/business_view.php");
} else {
?>
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="styles/bubbles.css">

    <div class="video-container">
        <div class="gradient-bg">
            <svg xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <filter id="goo">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -8" result="goo" />
                        <feBlend in="SourceGraphic" in2="goo" />
                    </filter>
                </defs>
            </svg>
            <div class="gradients-container">
                <div class="g1"></div>
                <div class="g2"></div>
                <div class="g3"></div>
                <div class="g4"></div>
                <div class="g5"></div>
                <div class="interactive"></div>
            </div>
            <a href="index.php" class="boton-atras">&#8592;</a>
        </div>
        <div class="container">
            <form id="login_form" action="" method="post">
                <div id="form_header">
                    <h1>Iniciar Sesión</h1>
                </div>
                <div id="inputs">
                    <div class="input-box">
                        <label for="uname"><b>Usuario</b></label>
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Usuario..." name="nombre" required>
                        </div>
                    </div>
                    <div class="input-box">
                        <label for="psw"><b>Contraseña</b></label>
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Contraseña..." name="pswd" required>
                        </div>
                    </div>
                </div>
                <button id="login_button" type="submit" name="submit">Iniciar</button>
                <div class="register-link">
                    <p>¿No tienes cuenta? <a href="index.php?controlador=users&action=registro">Regístrate</a></p>
                </div>
            </form>
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php render_site_footer(); ?>

    </html>
<?php
}
?>