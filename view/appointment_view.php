<?php
require_once("view/menu.php");
?>
<link rel="stylesheet" href="styles/tables.css">
<div class="content-with-menu">
    <h1>Mis Reservas</h1>

        <?php if (empty($reservas)): ?>
            <p>No tienes reservas programadas.</p>
        <?php else: ?>
            <table border="1" class="data-table">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Negocio</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Comentarios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($reserva['date_time'])) ?></td>
                            <td><?= htmlspecialchars($reserva['business_name']) ?></td>
                            <td><?= htmlspecialchars($reserva['service_name']) ?></td>
                            <td><?= htmlspecialchars($reserva['state']) ?></td>
                            <td><?= !empty($reserva['comments']) ? htmlspecialchars($reserva['comments']) : 'Sin comentarios' ?></td>
                            <td>
                                <?php if ($reserva['state'] != 'canceled'): ?>
                                    <button class="btn btn-danger" onclick="cancelarReserva(<?= $reserva['id_appointment'] ?>)">
                                        Cancelar Reserva
                                    </button>
                                <?php else: ?>
                                    <span>Cancelada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
</div>