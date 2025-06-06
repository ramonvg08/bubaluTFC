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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .form-container {
            width: 100%;
            margin: 20px auto;
            margin-top: 50px;
            padding: 20px;
            background-color: var(--white);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            position: relative;
            z-index: 10;
            opacity: 0.9;
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
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
            transition: border-color 0.3s ease;
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
            font-size: 1rem;
            cursor: pointer;
            transition: background-color var(--transition-fast, 0.3s);
        }

        .submit-btn:hover {
            background-color: var(--purple-dark, #4c0080);
        }

        .error-message {
            color: red;
            margin: 10px 0;
            font-size: 18px;
            text-align: center;
            padding: 8px;
            background-color: rgba(255, 0, 0, 0.05);
            border-radius: 4px;
            border-left: 3px solid red;
        }

        /* Estilos para mensajes de error específicos bajo los campos */
        .field-error {
            color: red;
            font-size: 16px;
            margin-top: 5px;
            display: none;
            transition: all 0.3s ease;
        }

        /* Estilos para campos con error */
        .input-error {
            border: 1px solid red !important;
            background-color: rgba(255, 0, 0, 0.03);
        }

        /* Estilos para campos válidos */
        .input-valid {
            border: 1px solid green !important;
        }

        /* Icono de validación */
        .validation-icon {
            position: absolute;
            padding-top: 8px;
            right: 30px;
            top: 33px;
            display: none;
        }

        .validation-icon.valid {
            color: green;
            display: block;
        }

        .validation-icon.invalid {
            color: red;
            display: block;
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
            align-items: flex-start;
            justify-content: space-between;
            gap: 25px;
        }

        @media (max-width: 768px) {
            #registerForm {
                flex-direction: column;
                gap: 10px;
            }

            .common-fields,
            .admin-fields {
                width: 100%;
                box-sizing: border-box;
            }
        }

        .gradients-container {
            position: absolute;
        }

        /* Estilos para el botón de mostrar/ocultar contraseña */
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 33px;
            padding: 9px 15px 0px 0px;
            cursor: pointer;
            color: #666;
        }

        /* Tooltip para sugerencias */
        .tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
            color: #666;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #555;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            line-height: 1.4;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* Animación para campos con error */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.6s;
        }

        /* Estilos para modo oscuro */
        .dark-theme .form-container {
            background-color: var(--dark-bg-secondary, #2a2a2a);
        }

        .dark-theme .form-group label,
        .dark-theme .form-header h1,
        .dark-theme .form-header p,
        .dark-theme .common-fields h3,
        .dark-theme .admin-fields h3,
        .dark-theme .role-selector label {
            color: var(--dark-text-primary, #f1f1f1);
        }

        .dark-theme .form-group input,
        .dark-theme .form-group select {
            background-color: var(--dark-bg-primary, #333);
            color: var(--dark-text-primary, #f1f1f1);
            border-color: #444;
        }

        .dark-theme .field-error {
            color: #ff6b6b;
        }

        .dark-theme .input-error {
            border-color: #ff6b6b !important;
            background-color: rgba(255, 107, 107, 0.1);
        }

        .dark-theme .input-valid {
            border-color: #6bff6b !important;
        }

        .dark-theme .validation-icon.valid {
            color: #6bff6b;
        }

        .dark-theme .validation-icon.invalid {
            color: #ff6b6b;
        }

        .dark-theme .error-message {
            background-color: rgba(255, 107, 107, 0.1);
            border-left-color: #ff6b6b;
        }

        .dark-theme .tooltip .tooltiptext {
            background-color: #222;
        }

        #submitBtn {
            font-family: 'Dongle', sans-serif;
            font-size: 25px;
            font-weight: 900;
        }
    </style>
</head>

<body>
    <a href="index.php?controlador=business&action=iniciar" class="boton-atras">&#8592;</a>

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
        <div class="form-container">
            <div class="form-header">
                <h1>Registro de Usuario</h1>
                <p>Por favor, completa el formulario para crear tu cuenta</p>
                <label>Tipo de usuario:</label>
                <div class="role-selector">
                    <label>
                        <input type="radio" name="role" value="customer" <?php echo (isset($formData['role']) && $formData['role'] == 'customer') || !isset($formData['role']) ? 'checked' : ''; ?>> Cliente
                    </label>
                    <label>
                        <input type="radio" name="role" value="administrator" <?php echo (isset($formData['role']) && $formData['role'] == 'administrator') ? 'checked' : ''; ?>> Administrador
                    </label>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php endif; ?>

            <form id="registerForm" action="index.php?controlador=users&action=procesarRegistro" method="post">
                <input type="hidden" name="role" id="role_input" value="<?php echo isset($formData['role']) ? $formData['role'] : 'customer'; ?>">

                <div id="commonFields" class="common-fields" style="display: block;">
                    <h3>Información Personal</h3>
                    <div class="form-group required">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" required value="<?php echo isset($formData['name']) ? htmlspecialchars($formData['name']) : ''; ?>" class="<?php echo isset($errors['name']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="name-error" class="field-error" <?php echo isset($errors['name']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="surnames">Apellidos</label>
                        <input type="text" id="surnames" name="surnames" required value="<?php echo isset($formData['surnames']) ? htmlspecialchars($formData['surnames']) : ''; ?>" class="<?php echo isset($errors['surnames']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="surnames-error" class="field-error" <?php echo isset($errors['surnames']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['surnames']) ? $errors['surnames'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>" class="<?php echo isset($errors['email']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="email-error" class="field-error" <?php echo isset($errors['email']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="password">Contraseña
                            <span class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltiptext">La contraseña debe tener al menos 8 caracteres y contener letras y números.</span>
                            </span>
                        </label>
                        <input type="password" id="password" name="password" required class="<?php echo isset($errors['password']) ? 'input-error' : ''; ?>">
                        <i class="password-toggle fas fa-eye" onclick="togglePasswordVisibility('password')"></i>
                        <div id="password-error" class="field-error" <?php echo isset($errors['password']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="confirm_password">Confirmar Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" required class="<?php echo isset($errors['confirm_password']) ? 'input-error' : ''; ?>">
                        <i class="password-toggle fas fa-eye" onclick="togglePasswordVisibility('confirm_password')"></i>
                        <div id="confirm-password-error" class="field-error" <?php echo isset($errors['confirm_password']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="phone_number">Teléfono</label>
                        <input type="number" id="phone_number" name="phone_number" value="<?php echo isset($formData['phone_number']) ? htmlspecialchars($formData['phone_number']) : ''; ?>" class="<?php echo isset($errors['phone_number']) ? 'input-error' : ''; ?>">
                        <div id="phone-number-error" class="field-error" <?php echo isset($errors['phone_number']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['phone_number']) ? $errors['phone_number'] : ''; ?></div>
                    </div>
                </div>

                <div id="adminFields" class="admin-fields">
                    <h3>Información del Negocio</h3>
                    <div class="form-group required">
                        <label for="business_name">Nombre del Negocio</label>
                        <input type="text" id="business_name" name="business_name" value="<?php echo isset($formData['business_name']) ? htmlspecialchars($formData['business_name']) : ''; ?>" class="<?php echo isset($errors['business_name']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="business-name-error" class="field-error" <?php echo isset($errors['business_name']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['business_name']) ? $errors['business_name'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="business_category">Categoría</label>
                        <select id="business_category" name="business_category" class="<?php echo isset($errors['business_category']) ? 'input-error' : ''; ?>">
                            <option value="">Selecciona una categoría</option>
                            <option value="Peluqueria" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Peluqueria') ? 'selected' : ''; ?>>Peluquería</option>
                            <option value="Taller de Automóviles" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Taller de Automóviles') ? 'selected' : ''; ?>>Taller Automovilístico</option>
                            <option value="Dentista" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Dentista') ? 'selected' : ''; ?>>Dentista</option>
                            <option value="Gimnasio" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Gimnasio') ? 'selected' : ''; ?>>Gimnasio</option>
                            <option value="Veterinario" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Veterinario') ? 'selected' : ''; ?>>Veterinario</option>
                            <option value="Clínica" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Clínica') ? 'selected' : ''; ?>>Clínica</option>
                            <option value="Centro de Bienestar" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Centro de Bienestar') ? 'selected' : ''; ?>>Centro de Bienestar</option>
                            <option value="Centro de Estética" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Centro de Estética') ? 'selected' : ''; ?>>Centro de Estética</option>
                            <option value="Otros" <?php echo (isset($formData['business_category']) && $formData['business_category'] == 'Otros') ? 'selected' : ''; ?>>Otros</option>
                        </select>
                        <i class="validation-icon fas fa-check"></i>
                        <div id="business-category-error" class="field-error" <?php echo isset($errors['business_category']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['business_category']) ? $errors['business_category'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="business_description">Descripción</label>
                        <input type="text" id="business_description" name="business_description" value="<?php echo isset($formData['business_description']) ? htmlspecialchars($formData['business_description']) : ''; ?>" class="<?php echo isset($errors['business_description']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="business-description-error" class="field-error" <?php echo isset($errors['business_description']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['business_description']) ? $errors['business_description'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="business_address">Dirección</label>
                        <input type="text" id="business_address" name="business_address" value="<?php echo isset($formData['business_address']) ? htmlspecialchars($formData['business_address']) : ''; ?>" class="<?php echo isset($errors['business_address']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="business-address-error" class="field-error" <?php echo isset($errors['business_address']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['business_address']) ? $errors['business_address'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="business_postal_code">Código Postal</label>
                        <input type="text" id="business_postal_code" name="business_postal_code" pattern="[0-9]{5}" title="Debe contener 5 dígitos" value="<?php echo isset($formData['business_postal_code']) ? htmlspecialchars($formData['business_postal_code']) : ''; ?>" class="<?php echo isset($errors['business_postal_code']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="business-postal-code-error" class="field-error" <?php echo isset($errors['business_postal_code']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['business_postal_code']) ? $errors['business_postal_code'] : ''; ?></div>
                    </div>
                    <div class="form-group required">
                        <label for="business_phone">Teléfono del negocio</label>
                        <input type="number" id="business_phone" name="business_phone" value="<?php echo isset($formData['business_phone']) ? htmlspecialchars($formData['business_phone']) : ''; ?>" class="<?php echo isset($errors['business_phone']) ? 'input-error' : ''; ?>">
                        <i class="validation-icon fas fa-check"></i>
                        <div id="business-phone-error" class="field-error" <?php echo isset($errors['business_phone']) ? 'style="display:block"' : ''; ?>><?php echo isset($errors['business_phone']) ? $errors['business_phone'] : ''; ?></div>
                    </div>
                </div>
            </form>
            <button type="button" id="submitBtn" class="submit-btn">Registrarse</button>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const roleInput = document.getElementById('role_input');
            const adminFields = document.getElementById('adminFields');
            const commonFields = document.getElementById('commonFields');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('registerForm');

            // Objeto para almacenar todos los campos y sus validaciones
            const formFields = {
                name: {
                    element: document.getElementById('name'),
                    error: document.getElementById('name-error'),
                    icon: document.getElementById('name').nextElementSibling,
                    validate: function() {
                        const value = this.element.value.trim();
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'El nombre es obligatorio.'
                            };
                        } else if (value.length < 2) {
                            return {
                                valid: false,
                                message: 'El nombre debe tener al menos 2 caracteres.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                surnames: {
                    element: document.getElementById('surnames'),
                    error: document.getElementById('surnames-error'),
                    icon: document.getElementById('surnames').nextElementSibling,
                    validate: function() {
                        const value = this.element.value.trim();
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'Los apellidos son obligatorios.'
                            };
                        } else if (value.length < 2) {
                            return {
                                valid: false,
                                message: 'Los apellidos deben tener al menos 2 caracteres.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                email: {
                    element: document.getElementById('email'),
                    error: document.getElementById('email-error'),
                    icon: document.getElementById('email').nextElementSibling,
                    validate: function() {
                        const value = this.element.value.trim();
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'El correo electrónico es obligatorio.'
                            };
                        } else if (!emailRegex.test(value)) {
                            return {
                                valid: false,
                                message: 'Introduce un correo electrónico válido.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                password: {
                    element: passwordInput,
                    error: passwordError,
                    validate: function() {
                        const password = this.element.value;

                        if (password === '') {
                            return {
                                valid: false,
                                message: 'La contraseña es obligatoria.'
                            };
                        } else if (password.length < 8) {
                            return {
                                valid: false,
                                message: 'La contraseña debe tener al menos 8 caracteres.'
                            };
                        } else if (!/[a-zA-Z]/.test(password) || !/[0-9]/.test(password)) {
                            return {
                                valid: false,
                                message: 'La contraseña debe contener letras y números.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                confirm_password: {
                    element: confirmPasswordInput,
                    error: confirmPasswordError,
                    validate: function() {
                        const confirmPassword = this.element.value;
                        const password = passwordInput.value;

                        if (confirmPassword === '') {
                            return {
                                valid: false,
                                message: 'Debes confirmar la contraseña.'
                            };
                        } else if (password !== confirmPassword) {
                            return {
                                valid: false,
                                message: 'Las contraseñas no coinciden.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                phone_number: {
                    element: document.getElementById('phone_number'),
                    error: document.getElementById('phone-number-error'),
                    icon: document.getElementById('phone_number').nextElementSibling,
                    validate: function() {

                        const value = this.element.value.trim();
                        const phoneRegex = /^[0-9]{9}$/;
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'El número de teléfono es obligatorio.'
                            };
                        } else if (!phoneRegex.test(value)) {
                            return {
                                valid: false,
                                message: 'El teléfono debe contener 9 dígitos.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                business_name: {
                    element: document.getElementById('business_name'),
                    error: document.getElementById('business-name-error'),
                    icon: document.getElementById('business_name').nextElementSibling,
                    validate: function() {
                        if (document.querySelector('input[name="role"]:checked').value !== 'administrator') {
                            return {
                                valid: true
                            };
                        }

                        const value = this.element.value.trim();
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'El nombre del negocio es obligatorio.'
                            };
                        } else if (value.length < 3) {
                            return {
                                valid: false,
                                message: 'El nombre del negocio debe tener al menos 3 caracteres.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                business_category: {
                    element: document.getElementById('business_category'),
                    error: document.getElementById('business-category-error'),
                    icon: document.getElementById('business_category').nextElementSibling,
                    validate: function() {
                        if (document.querySelector('input[name="role"]:checked').value !== 'administrator') {
                            return {
                                valid: true
                            };
                        }

                        const value = this.element.value;
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'Selecciona una categoría para el negocio.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                business_description: {
                    element: document.getElementById('business_description'),
                    error: document.getElementById('business-description-error'),
                    icon: document.getElementById('business_description').nextElementSibling,
                    validate: function() {
                        if (document.querySelector('input[name="role"]:checked').value !== 'administrator') {
                            return {
                                valid: true
                            };
                        }

                        const value = this.element.value.trim();
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'La descripción del negocio es obligatoria.'
                            };
                        } else if (value.length < 10) {
                            return {
                                valid: false,
                                message: 'La descripción debe tener al menos 10 caracteres.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                business_address: {
                    element: document.getElementById('business_address'),
                    error: document.getElementById('business-address-error'),
                    icon: document.getElementById('business_address').nextElementSibling,
                    validate: function() {
                        if (document.querySelector('input[name="role"]:checked').value !== 'administrator') {
                            return {
                                valid: true
                            };
                        }

                        const value = this.element.value.trim();
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'La dirección del negocio es obligatoria.'
                            };
                        } else if (value.length < 5) {
                            return {
                                valid: false,
                                message: 'La dirección debe ser más detallada.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                business_postal_code: {
                    element: document.getElementById('business_postal_code'),
                    error: document.getElementById('business-postal-code-error'),
                    icon: document.getElementById('business_postal_code').nextElementSibling,
                    validate: function() {
                        if (document.querySelector('input[name="role"]:checked').value !== 'administrator') {
                            return {
                                valid: true
                            };
                        }

                        const value = this.element.value.trim();
                        const postalCodeRegex = /^[0-9]{5}$/;
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'El código postal es obligatorio.'
                            };
                        } else if (!postalCodeRegex.test(value)) {
                            return {
                                valid: false,
                                message: 'El código postal debe contener exactamente 5 dígitos.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                },
                business_phone: {
                    element: document.getElementById('business_phone'),
                    error: document.getElementById('business-phone-error'),
                    icon: document.getElementById('business_phone').nextElementSibling,
                    validate: function() {
                        if (document.querySelector('input[name="role"]:checked').value !== 'administrator') {
                            return {
                                valid: true
                            };
                        }

                        const value = this.element.value.trim();
                        const phoneRegex = /^[0-9]{9}$/;
                        if (value === '') {
                            return {
                                valid: false,
                                message: 'El teléfono del negocio es obligatorio.'
                            };
                        } else if (!phoneRegex.test(value)) {
                            return {
                                valid: false,
                                message: 'El teléfono debe contener 9 dígitos.'
                            };
                        } else {
                            return {
                                valid: true
                            };
                        }
                    }
                }
            };

            // Función para mostrar error en un campo
            function showError(input, errorElement, message) {
                input.classList.add('input-error');
                input.classList.remove('input-valid');
                errorElement.textContent = message;
                errorElement.style.display = 'block';

                // Añadir animación de shake
                input.classList.remove('shake');
                setTimeout(() => {
                    input.classList.add('shake');
                }, 10);

                // Si hay un icono de validación y no es un campo de contraseña, actualizarlo
                const icon = input.nextElementSibling;
                if (icon && icon.classList.contains('validation-icon') &&
                    input.id !== 'password' && input.id !== 'confirm_password') {
                    icon.classList.remove('fa-check', 'valid');
                    icon.classList.add('fa-times', 'invalid');
                    icon.style.display = 'block';
                }
            }

            // Función para mostrar éxito en un campo
            function showSuccess(input, errorElement, icon) {
                input.classList.remove('input-error', 'shake');
                input.classList.add('input-valid');
                errorElement.style.display = 'none';

                // Actualizar icono si existe y no es un campo de contraseña
                if (icon && input.id !== 'password' && input.id !== 'confirm_password') {
                    icon.classList.remove('fa-times', 'invalid');
                    icon.classList.add('fa-check', 'valid');
                    icon.style.display = 'block';
                }
            }

            // Función para actualizar la visibilidad de los campos según el rol
            function updateFieldsVisibility() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                roleInput.value = selectedRole;

                if (selectedRole === 'administrator') {
                    adminFields.style.display = 'block';
                    document.querySelectorAll('#adminFields input, #adminFields select').forEach(input => {
                        input.required = true;
                    });
                } else {
                    adminFields.style.display = 'none';
                    document.querySelectorAll('#adminFields input, #adminFields select').forEach(input => {
                        input.required = false;
                        // Limpiar errores en campos de administrador
                        input.classList.remove('input-error', 'input-valid', 'shake');
                        const errorElement = document.getElementById(input.id.replace('_', '-') + '-error');
                        if (errorElement) errorElement.style.display = 'none';

                        // Ocultar iconos de validación
                        const icon = input.nextElementSibling;
                        if (icon && icon.classList.contains('validation-icon')) {
                            icon.style.display = 'none';
                        }
                    });
                }
            }

            // Validar todo el formulario
            function validateForm() {
                let isValid = true;
                let firstInvalidField = null;

                // Validar cada campo
                for (const fieldName in formFields) {
                    const field = formFields[fieldName];
                    const result = field.validate();

                    if (!result.valid) {
                        showError(field.element, field.error, result.message);

                        if (!firstInvalidField) {
                            firstInvalidField = field.element;
                        }

                        isValid = false;
                    } else {
                        showSuccess(field.element, field.error, field.icon);
                    }
                }

                // Hacer scroll al primer campo con error
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstInvalidField.focus();
                }

                return isValid;
            }

            // Inicializar la visibilidad de los campos
            updateFieldsVisibility();

            // Actualizar campos al cambiar el rol
            roleRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateFieldsVisibility();
                });
            });

            // Validar al enviar el formulario
            submitBtn.addEventListener('click', function(event) {
                if (validateForm()) {
                    form.submit();
                }
            });

            // Hacer scroll al primer campo con error si hay errores del servidor
            const errorFields = document.querySelectorAll('.input-error');
            if (errorFields.length > 0) {
                errorFields[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                errorFields[0].focus();

                // Añadir animación de shake a los campos con error
                errorFields.forEach(field => {
                    field.classList.add('shake');
                });
            }
        });

        // Función para mostrar/ocultar contraseña
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    <?php render_site_footer(); ?>
</body>

</html>