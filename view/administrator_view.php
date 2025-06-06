<?php require_once("view/menu.php"); ?>
<link rel="stylesheet" href="styles/tables.css">
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
<div class="content-with-menu" style="z-index: 99;">
    <div class="admin-dashboard" style="margin: 20px;">
        <h1>Gestión de Negocio</h1>

        <!-- Nueva sección: Información del Negocio -->
        <section class="business-info">
            <h2>Información del Negocio</h2>
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-image-container">
                        <img id="business-image" src="<?= htmlspecialchars($negocio['business_image']) ?>" alt="Imagen del negocio">
                        <form id="upload-business-image-form" enctype="multipart/form-data">
                            <button type="button" id="change-business-image-btn" class="btn btn-primary">Cambiar imagen</button>
                            <input type="file" id="business-image-upload" name="business_image" style="display: none;" accept="image/*">
                        </form>
                    </div>

                    <div class="profile-info">
                        <div class="business-field profile-field">
                            <span class="field-label">Nombre:</span>
                            <span class="field-value" id="name-value"><?= htmlspecialchars($negocio['name']) ?></span>
                            <button class="edit-service-btn btn btn-secondary" data-field="name">Editar</button>
                        </div>

                        <div class="business-field profile-field">
                            <span class="field-label">Categoría:</span>
                            <span class="field-value" id="category-value"><?= htmlspecialchars($negocio['category']) ?></span>
                            <button class="edit-service-btn btn btn-secondary" data-field="category">Editar</button>
                        </div>

                        <div class="business-field profile-field">
                            <span class="field-label">Descripción:</span>
                            <span class="field-value" id="description-value"><?= htmlspecialchars($negocio['description']) ?></span>
                            <button class="edit-service-btn btn btn-secondary" data-field="description">Editar</button>
                        </div>

                        <div class="business-field profile-field">
                            <span class="field-label">Dirección:</span>
                            <span class="field-value" id="address-value"><?= htmlspecialchars($negocio['address']) ?></span>
                            <button class="edit-service-btn btn btn-secondary" data-field="address">Editar</button>
                        </div>

                        <div class="business-field profile-field">
                            <span class="field-label">Código Postal:</span>
                            <span class="field-value" id="postal_code-value"><?= htmlspecialchars($negocio['postal_code'] ?? 'No especificado') ?></span>
                            <button class="edit-service-btn btn btn-secondary" data-field="postal_code">Editar</button>
                        </div>

                        <div class="business-field profile-field">
                            <span class="field-label">Teléfono:</span>
                            <span class="field-value" id="phone_number-value"><?= htmlspecialchars($negocio['phone_number']) ?></span>
                            <button class="edit-service-btn btn btn-secondary" data-field="phone_number">Editar</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Sección de Servicios -->
        <section class="servicios">
            <h2>Servicios Ofrecidos</h2>
            <button id="add-service-btn" class="btn btn-primary">Añadir Nuevo Servicio</button>
            <div class="table-responsive">
                <table border="1" class="service-table data-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Duración (min)</th>
                            <th>Precio (€)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicios as $servicio): ?>
                            <tr>
                                <td><?= htmlspecialchars($servicio['name']) ?></td>
                                <td><?= htmlspecialchars($servicio['duration']) ?></td>
                                <td><?= htmlspecialchars($servicio['price']) ?></td>
                                <td>
                                    <button class="edit-service-btn btn btn-secondary"
                                        data-id="<?= $servicio['id_service'] ?>"
                                        data-name="<?= htmlspecialchars($servicio['name']) ?>"
                                        data-duration="<?= htmlspecialchars($servicio['duration']) ?>"
                                        data-price="<?= htmlspecialchars($servicio['price']) ?>">
                                        Editar
                                    </button>
                                    <button class="delete-service-btn btn btn-danger"
                                        data-id="<?= $servicio['id_service'] ?>"
                                        data-name="<?= htmlspecialchars($servicio['name']) ?>">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Sección de Citas -->
        <section class="citas">
            <h2>Citas Programadas</h2>
            <div class="date-selector">
                <form action="index.php" method="get" id="date-filter-form">
                    <input type="hidden" name="controlador" value="administrator">
                    <input type="hidden" name="action" value="home">
                    <div class="date-filter">
                        <label for="date-picker">Seleccionar fecha:</label>
                        <input type="date" id="date-picker" name="date" value="<?= $selected_date ?>" onchange="this.form.submit()">
                        <button type="button" class="btn btn-secondary" onclick="goToToday()">Hoy</button>
                        <button type="button" class="btn btn-secondary" onclick="changeDate(-1)">Día anterior</button>
                        <button type="button" class="btn btn-secondary" onclick="changeDate(1)">Día siguiente</button>
                    </div>
                </form>
            </div>
            <div id="modify_form"></div>
            <?php if (empty($citas)): ?>
                <p class="no-appointments">No hay citas programadas para esta fecha.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table border="1" class="data-table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Comentarios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($citas as $cita):
                                // Determinar si la cita es de un día pasado
                                $cita_date = date('Y-m-d', strtotime($cita['date_time']));
                                $is_past_date = $cita_date < $current_date;
                            ?>
                                <tr <?= $is_past_date ? 'class="past-appointment"' : '' ?>>
                                    <td>
                                        <?php if (!empty($cita['id_user'])): ?>
                                            <a href="index.php?controlador=users&action=verPerfil&id=<?= $cita['id_user'] ?>" class="client-profile-link">
                                                <?= htmlspecialchars($cita['customer_name'] ?? 'Cliente desconocido') ?>
                                            </a>
                                        <?php else: ?>
                                            <?= htmlspecialchars($cita['customer_name'] ?? 'Cliente desconocido') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($cita['phone_number'] ?? 'No disponible') ?></td>
                                    <td><?= date('d/m/Y', strtotime($cita['date_time'])) ?></td>
                                    <td><?= date('H:i', strtotime($cita['date_time'])) ?></td>
                                    <td><?= htmlspecialchars($cita['state']) ?></td>
                                    <td><?= !empty($cita['comments']) ? htmlspecialchars($cita['comments']) : 'Sin comentarios' ?></td>
                                    <td>
                                        <?php if ($is_past_date): ?>
                                            <span class="status-readonly"><?= htmlspecialchars($cita['state']) ?></span>
                                        <?php else: ?>
                                            <select onchange="modificarEstadoCita(<?= $cita['id_appointment'] ?>, this.value)">
                                                <option value="earring" <?= ($cita['state'] == 'earring') ? 'selected' : '' ?>>Pendiente</option>
                                                <option value="confirmed" <?= ($cita['state'] == 'confirmed') ? 'selected' : '' ?>>Confirmar</option>
                                                <option value="canceled" <?= ($cita['state'] == 'canceled') ? 'selected' : '' ?>>Cancelar</option>
                                            </select>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <style>
            .client-profile-link {
                color: #0066cc;
                text-decoration: none;
                font-weight: bold;
            }

            .client-profile-link:hover {
                text-decoration: underline;
            }

            .date-selector {
                margin-bottom: 20px;
                background-color: var(--light-gray);
                padding: 15px;
                border-radius: 8px;
            }

            .date-filter {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
            }

            .date-filter label {
                margin-right: 10px;
                font-weight: bold;
            }

            .date-filter input[type="date"] {
                padding: 8px;
                border: 1px solid #ced4da;
                border-radius: 4px;
            }

            .past-appointment {
                background-color: var(--white);
                color: #777;
            }

            .status-readonly {
                padding: 5px 10px;
                background-color: #e9ecef;
                border-radius: 4px;
                display: inline-block;
            }

            .no-appointments {
                padding: 20px;
                text-align: center;
                background-color: #f8f8f8;
                border-radius: 8px;
                margin-top: 20px;
                font-style: italic;
                color: #666;
            }

            table {
                font-size: smaller;
            }

            .table-responsive {
                width: 100%;
                overflow-x: auto;
            }

            .service-table.data-table {
                width: 100%;
                min-width: 600px;
                /* Ajusta según tus columnas */
                box-sizing: border-box;
                max-width: 100%;
            }
        </style>

        <script>
            function goToToday() {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('date-picker').value = today;
                document.getElementById('date-filter-form').submit();
            }

            function changeDate(days) {
                const currentDate = new Date(document.getElementById('date-picker').value);
                currentDate.setDate(currentDate.getDate() + days);
                document.getElementById('date-picker').value = currentDate.toISOString().split('T')[0];
                document.getElementById('date-filter-form').submit();
            }
        </script>

        <style>
            .client-profile-link {
                color: #0066cc;
                text-decoration: none;
                font-weight: bold;
            }

            .client-profile-link:hover {
                text-decoration: underline;
            }
        </style>

        <!-- Sección de Horario -->
        <section class="horario">
            <h2>Horario Comercial</h2>
            <button id="add-schedule-btn" class="btn btn-primary">Añadir Nuevo Horario</button>
            <?php if ($horario): ?>
                <div class="table-responsive">
                    <table border="1" class="schedule-table data-table">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Apertura</th>
                                <th>Cierre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $diasSemana = [
                                0 => 'Domingo',
                                1 => 'Lunes',
                                2 => 'Martes',
                                3 => 'Miércoles',
                                4 => 'Jueves',
                                5 => 'Viernes',
                                6 => 'Sábado'
                            ];
                            foreach ($horario as $dia): ?>
                                <tr>
                                    <td><?= $diasSemana[$dia['day_week']] ?></td>
                                    <td><?= htmlspecialchars($dia['opening_hour']) ?></td>
                                    <td><?= htmlspecialchars($dia['closing_hour']) ?></td>
                                    <td>
                                        <button class="edit-schedule-btn btn btn-secondary"
                                            data-id="<?= $dia['id_schedule'] ?>"
                                            data-day="<?= $dia['day_week'] ?>"
                                            data-opening="<?= htmlspecialchars($dia['opening_hour']) ?>"
                                            data-closing="<?= htmlspecialchars($dia['closing_hour']) ?>">
                                            Editar
                                        </button>
                                        <button class="delete-schedule-btn btn btn-danger"
                                            data-id="<?= $dia['id_schedule'] ?>"
                                            data-day="<?= $diasSemana[$dia['day_week']] ?>">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No se ha configurado un horario.</p>
            <?php endif; ?>
        </section>
        <!-- Botón para eliminar negocio -->
        <div class="delete-business-container" style="margin-top: 100px; text-align: center;">
            <button id="delete-business-btn" class="btn btn-danger" style="padding: 10px 20px; font-size: 25px;width: 90%;">Eliminar mi negocio</button>
        </div>

        <!-- Modal para añadir/editar servicios -->
        <div id="service-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3 id="service-modal-title">Añadir Nuevo Servicio</h3>
                <form id="service-form">
                    <input type="hidden" id="service-id" name="service_id" value="">
                    <div class="form-group">
                        <label for="service-name">Nombre del Servicio:</label>
                        <input type="text" id="service-name" name="name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="service-duration">Duración (minutos):</label>
                        <input type="number" id="service-duration" name="duration" min="1" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="service-price">Precio (€):</label>
                        <input type="number" id="service-price" name="price" min="0" step="0.01" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>

        <!-- Modal para añadir/editar horarios -->
        <div id="schedule-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3 id="schedule-modal-title">Añadir Nuevo Horario</h3>
                <form id="schedule-form">
                    <input type="hidden" id="schedule-id" name="schedule_id" value="">
                    <div class="form-group">
                        <label for="schedule-day">Día de la Semana:</label>
                        <select id="schedule-day" name="day_week" required class="form-control">
                            <option value="">Seleccione un día</option>
                            <option value="0">Domingo</option>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="schedule-opening">Hora de Apertura:</label>
                        <input type="time" id="schedule-opening" name="opening_hour" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="schedule-closing">Hora de Cierre:</label>
                        <input type="time" id="schedule-closing" name="closing_hour" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>

        <!-- Modal para editar campos del negocio -->
        <div id="edit-business-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Editar <span id="business-field-to-edit"></span></h3>
                <form id="edit-business-form">
                    <input type="hidden" id="business-field-name">
                    <div id="business-input-container">
                        <!-- El input se generará dinámicamente según el campo a editar -->
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>


        <!-- Modal para confirmar eliminación de negocio -->
        <div id="delete-business-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Confirmar Eliminación de Negocio</h3>
                <p>¿Seguro que desea eliminar su negocio de la página? <br>Esto no eliminará su perfil, sino el negocio.</p>
                <div class="form-actions">
                    <button id="confirm-delete-business" class="btn btn-danger">Sí, eliminar</button>
                    <button class="btn btn-secondary cancel-btn">Cancelar</button>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Estilos para los formularios */
    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 8px;
        font-size: 16px;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    label {
        display: inline-block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    /* Estilos para los modales */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 65%;
        max-width: 500px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }

    .business-info {
        background-color: var(--light-gray);
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
    }

    .profile-container {
        margin-top: 20px;
    }

    .profile-header {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .profile-image-container {
        flex: 0 0 200px;
        text-align: center;
    }

    #business-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid #f0f0f0;
        margin-bottom: 10px;
    }

    #change-business-image-btn {
        width: 100%;
        margin-top: 10px;
    }

    .profile-info {
        flex: 1;
        min-width: 300px;
    }

    .profile-field {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
    }

    .profile-field:last-child {
        border-bottom: none;
    }

    .field-label {
        font-weight: bold;
        width: 120px;
        color: #666;
    }

    .field-value {
        flex: 1;
        margin: 0 15px;
    }

    .edit-btn {
        background-color: #5ab2da;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .edit-btn:hover {
        background-color: #409dc4;
    }


    /* Fondo opaco para secciones */
    .admin-dashboard {
        background-color: rgba(245, 245, 245, 0.50);
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0px 10px 0px;
    }

    .dark-theme .admin-dashboard {
        background-color: rgba(52, 53, 65, 0.50);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        min-width: 600px;
        /* Ajusta según tus columnas */
        box-sizing: border-box;
        max-width: 100%;
    }
    .site-footer{
        z-index: 98 !important;
    }
</style>

<?php render_site_footer(); ?>