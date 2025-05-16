<div class="content-with-menu">
    <title><?= htmlspecialchars($business['name']) ?></title>
    <style>
        .business-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .business-image {
            max-width: 200px;
            margin-right: 2rem;
        }

        .service-list {
            margin: 2rem 0;
        }

        .service-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }

        .external-customer-form {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: var(--light-gray);
        }

        .external-customer-form input,
        .external-customer-form textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .external-customer-form label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: bold;
        }

        .external-customer-form button {
            padding: 0.5rem 1rem;
            background-color: var(--accent-yellow);
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .external-customer-form button:hover {
            background-color: rgb(229, 180, 0);
        }
    </style>
    <?php
    require_once("view/menu.php");
    ?>

    <body>
        <div class="business-header">
            <img src="<?= htmlspecialchars($business['business_image']) ?>"
                alt="<?= htmlspecialchars($business['name']) ?>"
                class="business-image">
            <div>
                <h1><?= htmlspecialchars($business['name']) ?></h1>
                <p><?= htmlspecialchars($business['address']) ?></p>
            </div>
        </div>

        <div class="service-list">
            <h2>Servicios Disponibles</h2>
            <?php foreach ($services as $service): ?>
                <div class="service-item">
                    <div>
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p>Duración: <?= $service['duration'] ?> minutos</p>
                    </div>
                    <div>
                        <p>Precio: <?= number_format($service['price'], 2) ?>€</p>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'administrator'): ?>
                            <a href="#" onclick="toggleExternalCustomerForm(<?= $service['id_service'] ?>)">Reservar</a>
                            <div id="external-form-<?= $service['id_service'] ?>" class="external-customer-form">
                                <form method="POST" action="index.php?controlador=business&action=guardar_sesion">
                                    <input type="hidden" name="service_id" value="<?= $service['id_service'] ?>">
                                    <input type="hidden" name="external_customer" value="1">

                                    <label for="customer_name">Nombre del cliente:</label>
                                    <input type="text" id="customer_name" name="customer_name" required>

                                    <label for="customer_phone">Teléfono del cliente:</label>
                                    <input type="tel" id="customer_phone" name="customer_phone" required>

                                    <label for="customer_comments">Comentarios:</label>
                                    <textarea id="customer_comments" name="customer_comments" rows="3"></textarea>

                                    <button type="submit">Confirmar Reserva</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <a href="#" onclick="setServiceId(<?= $service['id_service'] ?>)">Reservar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
</div>
<script>
    function setServiceId(serviceId) {
        // Crear un formulario dinámicamente
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?controlador=business&action=guardar_sesion'; // Controlador y acción para guardar en sesión

        // Crear un campo oculto para el service_id
        var serviceIdInput = document.createElement('input');
        serviceIdInput.type = 'hidden';
        serviceIdInput.name = 'service_id';
        serviceIdInput.value = serviceId;

        // Agregar el campo oculto al formulario
        form.appendChild(serviceIdInput);

        // Agregar el formulario al documento y enviarlo
        document.body.appendChild(form);
        form.submit();
    }

    function toggleExternalCustomerForm(serviceId) {
        var formId = 'external-form-' + serviceId;
        var form = document.getElementById(formId);

        // Ocultar todos los formularios primero
        var allForms = document.getElementsByClassName('external-customer-form');
        for (var i = 0; i < allForms.length; i++) {
            allForms[i].style.display = 'none';
        }

        // Mostrar u ocultar el formulario seleccionado
        if (form.style.display === 'block') {
            form.style.display = 'none';
        } else {
            form.style.display = 'block';
        }
    }
</script>


<?php render_site_footer(); ?>