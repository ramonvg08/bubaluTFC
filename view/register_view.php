<?php
// Vista para el formulario de registro adaptativo
require_once("view/footer_loader.php"); // Cargar el footer y sus estilos
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Bubalu</title>
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="styles/bubbles.css">
    <style>
        /* Estilos originales para el contenedor del formulario y sus elementos internos */
        .form-container {
            width: 100%;
            margin: 20px auto;
            /* Añadido margen superior e inferior para mejor espaciado dentro del .container */
            padding: 20px;
            background-color: var(--white);
            /* Asegurar un fondo, con fallback */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            position: relative;
            /* Para que z-index funcione si es necesario */
            z-index: 10;
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--dark-gray);
            /* Asegurar color de texto legible */
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group.required label:after {
            content: " *";
            color: red;
        }

        .role-selector {
            margin-bottom: 20px;
            text-align: center;
        }

        .role-selector label {
            margin: 0 10px;
            color: var(--dark-gray);
        }

        .admin-fields,
        .common-fields {
            display: none;
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border: solid 2px;
            border-radius: 15px;

        }

        .common-fields h3,
        .admin-fields h3 {
            color: var(--dark-gray);
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .submit-btn {
            width: 100%;
            padding: var(--spacing-md, 10px 15px);
            background-color: var(--purple-regular, #6a0dad);
            color: var(--light-text-primary, #fff);
            border: none;
            border-radius: var(--border-radius-sm, 4px);
            font-family: 'Impact', Arial, sans-serif;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color var(--transition-fast, 0.3s);
        }

        .submit-btn:hover {
            background-color: var(--purple-dark, #4c0080);
        }

        .error-message {
            color: red;
            margin-top: 5px;
            font-size: 14px;
            text-align: center;
            /* Centrar mensaje de error */
        }

        /* Ajustes para asegurar que el texto dentro del form-container sea legible */
        .form-container p {
            color: var(--dark-gray);
            /* Heredado o especificado */
        }

        .role-selector {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        #registerForm {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .gradient-bg{
            position: relative;
        }
        .gradients-container {
            position: absolute;
        }
    </style>
</head>

<body>
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
            <a href="index.php?controlador=business&action=iniciar" class="boton-atras">&#8592;</a>


            <div class="container">
                <div class="form-container">
                    <div class="form-header">
                        <h1>Registro de Usuario</h1>
                        <p>Por favor, completa el formulario para crear tu cuenta</p>
                        <label>Tipo de usuario:</label>
                        <div class="role-selector">
                            <label>
                                <input type="radio" name="role" value="customer" checked> Cliente
                            </label>
                            <label>
                                <input type="radio" name="role" value="administrator"> Administrador
                            </label>
                        </div>
                    </div>

                    <?php if (!empty($message)): ?>
                        <div class="error-message"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form id="registerForm" action="index.php?controlador=users&action=procesarRegistro" method="post">



                        <div id="commonFields" class="common-fields" style="display: block;">
                            <h3>Información Personal</h3>
                            <div class="form-group required">
                                <label for="name">Nombre</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group required">
                                <label for="surnames">Apellidos</label>
                                <input type="text" id="surnames" name="surnames" required>
                            </div>
                            <div class="form-group required">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group required">
                                <label for="password">Contraseña</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <div class="form-group required">
                                <label for="confirm_password">Confirmar Contraseña</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="form-group">
                                <label for="birthdate">Fecha de Nacimiento</label>
                                <input type="date" id="birthdate" name="birthdate">
                            </div>
                        </div>

                        <div id="adminFields" class="admin-fields">
                            <h3>Información del Negocio</h3>
                            <div class="form-group required">
                                <label for="business_name">Nombre del Negocio</label>
                                <input type="text" id="business_name" name="business_name">
                            </div>
                            <div class="form-group required">
                                <label for="business_category">Categoría</label>
                                <select id="business_category" name="business_category">
                                    <option value="">Selecciona una categoría</option>
                                    <option value="Peluqueria">Peluquería</option>
                                    <option value="Taller de Automóviles">Taller Automovilístico</option>
                                </select>
                            </div>
                            <div class="form-group required">
                                <label for="business_description">Descripción</label>
                                <input type="text" id="business_description" name="business_description">
                            </div>
                            <div class="form-group required">
                                <label for="business_address">Dirección</label>
                                <input type="text" id="business_address" name="business_address">
                            </div>
                            <div class="form-group required">
                                <label for="business_postal_code">Código Postal</label>
                                <input type="text" id="business_postal_code" name="business_postal_code" pattern="[0-9]{5}" title="Debe contener 5 dígitos">
                            </div>
                            <div class="form-group required">
                                <label for="business_phone">Teléfono</label>
                                <input type="number" id="business_phone" name="business_phone">
                            </div>
                        </div>
                    </form>
                    <button type="submit" class="submit-btn">Registrarse</button>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const adminFields = document.getElementById('adminFields');
            const commonFields = document.getElementById('commonFields'); // Assuming you might want to hide this if admin is selected, or just specific fields

            function updateFieldsVisibility() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;

                if (selectedRole === 'administrator') {
                    adminFields.style.display = 'block';
                    document.querySelectorAll('#adminFields input, #adminFields select').forEach(input => {
                        input.required = true;
                    });
                } else {
                    adminFields.style.display = 'none';
                    document.querySelectorAll('#adminFields input, #adminFields select').forEach(input => {
                        input.required = false;
                    });
                }
            }

            updateFieldsVisibility();

            roleRadios.forEach(radio => {
                radio.addEventListener('change', updateFieldsVisibility);
            });

            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(event) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

                if (password !== confirmPassword) {
                    event.preventDefault();
                    alert('Las contraseñas no coinciden');
                }
            });
        });
    </script>
    <?php render_site_footer(); ?>
</body>

</html>