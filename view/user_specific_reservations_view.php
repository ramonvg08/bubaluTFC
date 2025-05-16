<?php
// view/user_specific_reservations_view.php
require_once("view/menu.php"); 

// Se espera que $reservas sea un array de reservas para el usuario logueado.
// Cada reserva debe tener al menos: id_appointment, date_time, service_name, business_name, state, comments.
?>
<link rel="stylesheet" href="styles/tables.css"> <!-- Asumiendo que tienes un CSS para tablas -->

<div class="content-with-menu">
    <div class="container">
        <h2>Mis Reservas</h2>

        <?php if (empty($reservas)): ?>
            <p>No tienes ninguna reserva programada.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Servicio</th>
                            <th>Negocio</th>
                            <th>Estado</th>
                            <th>Comentarios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= htmlspecialchars(isset($reserva["date_time"]) ? date("d/m/Y H:i", strtotime($reserva["date_time"])) : "N/A") ?></td>
                                <td><?= htmlspecialchars($reserva["service_name"] ?? "N/A") ?></td>
                                <td><?= htmlspecialchars($reserva["business_name"] ?? "N/A") ?></td>
                                <td><?= htmlspecialchars(ucfirst($reserva["state"] ?? "N/A")) ?></td>
                                <td><?= htmlspecialchars($reserva["comments"] ?? "-") ?></td>
                                <td>
                                    <?php if (isset($reserva["id_appointment"]) && isset($reserva["state"]) && $reserva["state"] === "pending" || $reserva["state"] === "confirmed"): ?>
                                        <button class="btn btn-danger btn-sm" onclick="cancelUserReservation(<?= $reserva["id_appointment"] ?>)">Cancelar</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Asegúrate de que jQuery está cargado si lo usas para AJAX
function cancelUserReservation(appointmentId) {
    if (confirm("¿Estás seguro de que quieres cancelar esta reserva?")) {
        // Comprueba si jQuery está disponible
        if (typeof jQuery !== "undefined") {
            $.ajax({
                url: "index.php?controlador=users&action=cancelarReserva",
                type: "POST",
                data: { id_appointment: appointmentId },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        alert("Reserva cancelada con éxito.");
                        window.location.reload(); // Recargar para ver los cambios
                    } else {
                        alert("Error al cancelar la reserva: " + (response.message || "Inténtalo de nuevo."));
                    }
                },
                error: function() {
                    alert("Error de comunicación al intentar cancelar la reserva.");
                }
            });
        } else {
            // Fallback si jQuery no está (ej. usando Fetch API)
            fetch("index.php?controlador=users&action=cancelarReserva", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "id_appointment=" + appointmentId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Reserva cancelada con éxito.");
                    window.location.reload();
                } else {
                    alert("Error al cancelar la reserva: " + (data.message || "Inténtalo de nuevo."));
                }
            })
            .catch(() => {
                alert("Error de comunicación al intentar cancelar la reserva.");
            });
        }
    }
}
</script>

<?php render_site_footer();?>
