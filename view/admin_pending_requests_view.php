<?php
// view/admin_pending_requests_view.php
// Esta vista mostrará la lista de solicitudes de reserva pendientes para el administrador.

// Incluir el menú (que a su vez carga footer_loader.php)
require_once("view/menu.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Pendientes - <?php echo isset($nombre_negocio) ? htmlspecialchars($nombre_negocio) : "Administración"; ?></title>
    <!-- Aquí se podrían añadir estilos específicos para esta página si es necesario -->
    <link rel="stylesheet" href="styles/pending_requests.css">
    <style>
        /* Estilos básicos para la tabla de solicitudes pendientes */
        .pending-requests-container {
            padding: 20px;
            margin: 20px auto;
            max-width: 900px;
            background-color: var(--light-bg-secondary, #f9f9f9);
            border-radius: var(--border-radius-md, 8px);
            box-shadow: var(--shadow-md, 0 4px 6px rgba(0,0,0,0.1));
        }
        .pending-requests-container h1 {
            color: var(--dark-text-primary, #333);
            text-align: center;
            margin-bottom: 20px;
        }
        .requests-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .requests-table th, .requests-table td {
            border: 1px solid var(--medium-gray, #ddd);
            padding: 10px;
            text-align: left;
            font-size: 0.9em;
        }
        .requests-table th {
            background-color: var(--purple-light, #e9d8f2);
            color: var(--purple-dark, #4c0080);
        }
        .requests-table tr:nth-child(even) {
            background-color: var(--light-bg-tertiary, #f2f2f2);
        }
        .requests-table .actions a {
            margin-right: 8px;
            padding: 5px 10px;
            border-radius: var(--border-radius-sm, 4px);
            text-decoration: none;
            font-size: 0.85em;
            transition: background-color 0.3s ease;
        }
        .requests-table .actions .approve-btn {
            background-color: var(--success-bg, #28a745);
            color: white;
        }
        .requests-table .actions .approve-btn:hover {
            background-color: var(--success-bg-hover, #218838);
        }
        .requests-table .actions .reject-btn {
            background-color: var(--danger-bg, #dc3545);
            color: white;
        }
        .requests-table .actions .reject-btn:hover {
            background-color: var(--danger-bg-hover, #c82333);
        }
        .no-requests {
            text-align: center;
            padding: 20px;
            color: var(--dark-text-secondary, #555);
        }

        /* Estilos para modo oscuro */
        .dark-theme .pending-requests-container {
            background-color: var(--dark-bg-secondary, #2a2a2a);
        }
        .dark-theme .pending-requests-container h1 {
            color: var(--dark-text-primary, #f1f1f1);
        }
        .dark-theme .requests-table th, .dark-theme .requests-table td {
            border-color: var(--dark-border-color, #444);
        }
        .dark-theme .requests-table th {
            background-color: var(--dark-purple-light, #583772); /* Un púrpura más oscuro para el header en modo oscuro */
            color: var(--dark-text-primary, #f1f1f1);
        }
        .dark-theme .requests-table tr:nth-child(even) {
            background-color: var(--dark-bg-tertiary, #333333);
        }
        .dark-theme .no-requests {
            color: var(--dark-text-secondary, #aaa);
        }

    </style>
</head>
<body>
    <div class="content-with-menu" style="z-index: 99;">
        <div class="pending-requests-container">
            <h1>Solicitudes de Reserva Pendientes</h1>
            <?php if (!empty($pending_appointments)): ?>
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Fecha y Hora</th>
                            <th>Comentarios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_appointments as $appointment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment["customer_name"]); ?></td>
                                <td><?php echo htmlspecialchars($appointment["service_name"]); ?></td>
                                <td><?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($appointment["date_time"]))); ?></td>
                                <td><?php echo htmlspecialchars(!empty($appointment["comments"]) ? $appointment["comments"] : "-"); ?></td>
                                <td class="actions">
                                    <a href="index.php?controlador=administrator&action=process_pending_appointment&id_appointment=<?php echo $appointment["id_appointment"]; ?>&status=confirmed" class="approve-btn">Aceptar</a>
                                    <a href="index.php?controlador=administrator&action=process_pending_appointment&id_appointment=<?php echo $appointment["id_appointment"]; ?>&status=cancelled" class="reject-btn">Rechazar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-requests">No hay solicitudes de reserva pendientes en este momento.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php 
    // Renderizar el footer al final de la página
    if (function_exists("render_site_footer")) {
        render_site_footer();
    }
    ?>
</body>
</html>

