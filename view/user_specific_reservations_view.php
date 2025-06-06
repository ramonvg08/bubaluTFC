<?php
// view/user_specific_reservations_view.php
require_once("view/menu.php");

// Se espera que $reservas sea un array de reservas para el usuario logueado.
// Cada reserva debe tener al menos: id_appointment, date_time, service_name, business_name, state, comments.
?>
<link rel="stylesheet" href="styles/tables.css">
<style>
    .container {
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .dark-theme .container {
        background-color: rgba(52, 53, 65, 0.8);
    }

    .reservations-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .reservation-card {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .reservation-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .dark-theme .reservation-card {
        background-color: #2a2a2a;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    .reservation-section {
        padding: 0 15px;
        flex: 1;
        min-width: 150px;
        margin: 5px 0;
    }

    .reservation-section.business {
        flex: 2;
        min-width: 150px;
        border-left: 4px solid var(--accent-yellow);
        padding-left: 15px;
    }

    .reservation-section.service {
        flex: 2;
        min-width: 150px;
    }

    .reservation-section.status {
        flex: 1;
        min-width: 120px;
        text-align: center;
    }

    .reservation-section.date {
        flex: 1;
        min-width: 100px;
    }

    .reservation-section.actions {
        flex: 1;
        min-width: 120px;
        text-align: center;
    }

    .section-title {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .dark-theme .section-title {
        color: #aaa;
    }

    .section-content {
        font-size: 1rem;
        font-weight: 500;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-canceled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .dark-theme .status-pending {
        background-color: #665e20;
        color: #ffe97d;
    }

    .dark-theme .status-confirmed {
        background-color: #1e5a2f;
        color: #8eeaa8;
    }

    .dark-theme .status-canceled {
        background-color: #5a2828;
        color: #ffb3b9;
    }

    .dark-theme .status-completed {
        background-color: #1a4a54;
        color: #a8e4f0;
    }

    .btn {
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 15px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .date-time {
        display: flex;
        flex-direction: column;
    }

    .date-time .date,
    .time {
        display: flex;
        justify-content: center;
    }

    .date {
        font-weight: 500;
    }

    .time {
        font-size: 0.9rem;
        color: #666;
    }


    .dark-theme .time {
        color: #aaa;
    }

    .comments-text {
        font-style: italic;
        color: #666;
        font-size: 0.9rem;
        margin-top: 5px;
    }

    .dark-theme .comments-text {
        color: #aaa;
    }

    .empty-message {
        text-align: center;
        padding: 30px;
        font-style: italic;
        color: #666;
    }

    .dark-theme .empty-message {
        color: #aaa;
    }

    /* ...existing code... */
    @media (max-width: 768px) {
        .reservation-card {
            flex-direction: row;
            /* Mantiene el formato horizontal */
            align-items: center;
            max-width: 100%;
            overflow-x: auto;
        }

        .reservation-section {
            width: auto;
            padding: 8px 5px;
            border-bottom: none;
            min-width: 0px;
            word-break: break-word;
            box-sizing: border-box;
        }

        .reservation-section.business {
            border-left: 4px solid var(--accent-yellow);
            border-bottom: none;
            padding-left: 10px;
        }

        .reservation-section.actions {
            margin-top: 0;
            text-align: center;
        }

        .section-title {
            display: block;
            width: auto;
            font-weight: bold;
            font-size: 0.95rem;
        }

        .section-content {
            display: block;
            font-size: 0.95rem;
        }
    }

    /* ...existing code... */
</style>
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
<div class="content-with-menu">
    <div class="container">
        <h2>Mis Reservas</h2>

        <?php if (empty($reservas)): ?>
            <div class="empty-message">
                <p>No tienes ninguna reserva programada.</p>
            </div>
        <?php else: ?>
            <div class="reservations-container">
                <?php foreach ($reservas as $reserva):
                    // Determinar la clase de estado para el badge
                    $statusClass = '';
                    switch ($reserva["state"] ?? "N/A") {
                        case 'pending':
                        case 'earring': // Por si acaso usa "earring" en lugar de "pending"
                            $statusClass = 'status-pending';
                            $statusText = 'Pendiente';
                            break;
                        case 'confirmed':
                            $statusClass = 'status-confirmed';
                            $statusText = 'Confirmada';
                            break;
                        case 'canceled':
                            $statusClass = 'status-canceled';
                            $statusText = 'Cancelada';
                            break;
                        case 'completed':
                            $statusClass = 'status-completed';
                            $statusText = 'Completada';
                            break;
                        default:
                            $statusClass = '';
                            $statusText = ucfirst($reserva["state"] ?? "N/A");
                    }

                    // Formatear fecha y hora
                    $dateTime = isset($reserva["date_time"]) ? strtotime($reserva["date_time"]) : null;
                    $formattedDate = $dateTime ? date("d/m/Y", $dateTime) : "N/A";
                    $formattedTime = $dateTime ? date("H:i", $dateTime) : "";
                ?>
                    <div class="reservation-card">
                        <div class="reservation-section business">
                            <div class="section-title">Negocio</div>
                            <div class="section-content"><?= htmlspecialchars($reserva["business_name"] ?? "N/A") ?></div>
                        </div>

                        <div class="reservation-section service">
                            <div class="section-title">Servicio</div>
                            <div class="section-content">
                                <?= htmlspecialchars($reserva["service_name"] ?? "N/A") ?>
                                <?php if (!empty($reserva["comments"])): ?>
                                    <div class="comments-text"><?= htmlspecialchars($reserva["comments"]) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="reservation-section status">
                            <div class="section-content">
                                <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                            </div>
                        </div>

                        <div class="reservation-section date">
                            <div class="section-content">
                                <div class="date-time">
                                    <span class="date"><?= $formattedDate ?></span>
                                    <span class="time"><?= $formattedTime ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="reservation-section actions">
                            <div class="section-content">
                                <?php if (isset($reserva["id_appointment"]) && isset($reserva["state"]) && ($reserva["state"] === "pending" || $reserva["state"] === "earring" || $reserva["state"] === "confirmed")): ?>
                                    <button class="btn btn-danger" onclick="cancelUserReservation(<?= $reserva['id_appointment'] ?>)">Cancelar</button>
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
                    data: {
                        id_appointment: appointmentId
                    },
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

<?php render_site_footer(); ?>