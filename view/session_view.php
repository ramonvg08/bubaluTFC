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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* Estilos para el botón de mostrar/ocultar contraseña */
        .input-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 10;
        }
    </style>

    <a href="index.php?controlador=business&action=home" class="boton-atras">&#8592;</a>

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
    </div>
    <div class="container">
        <form id="login_form" action="" method="post">
            <div id="form_header">
                <h1>Iniciar Sesión</h1>
            </div>
            <div id="inputs">
                <div class="input-box">
                    <label for="uname"><b>Correo Electrónico</b></label>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Tu e-mail..." name="nombre" required>
                    </div>
                </div>
                <div class="input-box">
                    <label for="psw"><b>Contraseña</b></label>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" placeholder="Contraseña..." name="pswd" required>
                        <i class="password-toggle fas fa-eye" id="togglePassword"></i>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            // Función para mostrar/ocultar contraseña
            togglePassword.addEventListener('click', function() {
                // Cambiar el tipo de input entre password y text
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    togglePassword.classList.remove('fa-eye');
                    togglePassword.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    togglePassword.classList.remove('fa-eye-slash');
                    togglePassword.classList.add('fa-eye');
                }
            });
        });
    </script>

    <?php render_site_footer(); ?>

    </html>
<?php
}
?>