<?php
// Vista para el formulario de registro adaptativo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
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
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        }
        .admin-fields {
            display: none;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-top: 5px;
            font-size: 14px;
        }
        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Registro de Usuario</h1>
            <p>Por favor, completa el formulario para crear tu cuenta</p>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="error-message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form id="registerForm" action="index.php?controlador=users&action=procesarRegistro" method="post">
            <div class="role-selector">
                <label>Tipo de usuario:</label>
                <label>
                    <input type="radio" name="role" value="customer" checked> Cliente
                </label>
                <label>
                    <input type="radio" name="role" value="administrator"> Administrador
                </label>
            </div>
            
            <!-- Campos comunes para ambos tipos de usuario -->
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
            
            <!-- Campos específicos para administradores -->
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
            
            <button type="submit" class="submit-btn">Registrarse</button>
        </form>
        
        <a href="index.php?controlador=business&action=iniciar" class="back-link">Volver al inicio de sesión</a>
    </div>
    
    <script>
        // Script para mostrar/ocultar campos según el tipo de usuario seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const adminFields = document.getElementById('adminFields');
            
            // Función para actualizar la visibilidad de los campos
            function updateFieldsVisibility() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                
                if (selectedRole === 'administrator') {
                    adminFields.style.display = 'block';
                    // Hacer los campos de negocio requeridos
                    document.querySelectorAll('#adminFields input').forEach(input => {
                        input.required = true;
                    });
                } else {
                    adminFields.style.display = 'none';
                    // Quitar el requerido de los campos de negocio
                    document.querySelectorAll('#adminFields input').forEach(input => {
                        input.required = false;
                    });
                }
            }
            
            // Inicializar la visibilidad
            updateFieldsVisibility();
            
            // Actualizar cuando cambie la selección
            roleRadios.forEach(radio => {
                radio.addEventListener('change', updateFieldsVisibility);
            });
            
            // Validación de contraseñas
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
</body>
</html>
