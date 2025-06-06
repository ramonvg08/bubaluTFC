<?php
// appointment_view.php

require_once("view/menu.php"); // menu.php already links styles/bubbles.css

$dias_semana_nombres = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];

$min_hour = 24;
$max_hour = 0;
if (!empty($horario_procesado)) {
    foreach ($horario_procesado as $turnos_dia) {
        foreach ($turnos_dia as $turno) {
            if (isset($turno["open"])) $min_hour = min($min_hour, (int)$turno["open"]);
            if (isset($turno["close"])) $max_hour = max($max_hour, (int)$turno["close"]);
        }
    }
} else {
    $min_hour = 8; 
    $max_hour = 19;
}
if ($min_hour >= $max_hour) { 
    $min_hour = 8;
    $max_hour = 19;
}

$time_slots = [];
for ($h = $min_hour; $h < $max_hour; $h++) {
    $time_slots[] = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";
}

$reservas_por_dia_hora = [];
if (!empty($reservas_semana)) {
    foreach ($reservas_semana as $reserva) {
        $fecha_reserva = new DateTime($reserva["date_time"]);
        $dia_key = $fecha_reserva->format("N"); 
        $hora_key = $fecha_reserva->format("H:00");
        $reservas_por_dia_hora[$dia_key][$hora_key][] = $reserva;
    }
}

?>
<link rel="stylesheet" href="styles/calendar.css">
<link rel="stylesheet" href="styles/modal.css">

<div class="content-with-menu" id="mainContentArea" style="position: relative;"> <!-- Positioning context -->
    <!-- Animated Background -->
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

    <!-- Original Calendar Content -->
    <div class="calendar-container" style="position: relative; z-index: 1;"> <!-- Content on top -->
        <div class="calendar-header">
            <h2>Calendario de Reservas - <?= htmlspecialchars($nombre_negocio ?? "Mi Negocio") ?></h2>
            <div class="calendar-nav">
                <a href="index.php?controlador=administrator&action=view_appointments_calendar&week_offset=<?= $week_offset - 1 ?>" class="btn">‹ Sem. Anterior</a>
                <a href="index.php?controlador=administrator&action=view_appointments_calendar&week_offset=0" class="btn">Sem. Actual</a>
                <a href="index.php?controlador=administrator&action=view_appointments_calendar&week_offset=<?= $week_offset + 1 ?>" class="btn">Sem. Siguiente ›</a>
            </div>
        </div>
        <p style="text-align: center; font-weight: bold;">
            Semana del <?= isset($current_week_start) ? $current_week_start->format("d/m/Y") : "Fecha no definida" ?> al <?= isset($current_week_start) ? (clone $current_week_start)->modify("+6 days")->format("d/m/Y") : "Fecha no definida" ?>
        </p>

        <div class="calendar-grid">
            <div class="grid-header">Hora</div>
            <?php 
            if (isset($current_week_start)):
                $temp_date_header = clone $current_week_start;
                for ($i = 0; $i < 7; $i++): ?>
                    <div class="grid-header">
                        <?= $dias_semana_nombres[$i] ?><br>
                        <small><?= $temp_date_header->format("d/m") ?></small>
                    </div>
                <?php 
                    $temp_date_header->modify("+1 day");
                endfor;
            else:
                for ($i = 0; $i < 7; $i++): 
                    echo "<div class='grid-header'>" . $dias_semana_nombres[$i] . "</div>";
                endfor;
            endif;
            ?>

            <?php foreach ($time_slots as $time_slot_display): ?>
                <?php $current_hour_int = (int)substr($time_slot_display, 0, 2); ?>
                <div class="time-slot"><?= $time_slot_display ?></div>

                <?php for ($day_of_week_N = 1; $day_of_week_N <= 7; $day_of_week_N++): ?>
                    <?php
                    $is_working_hour = false;
                    $cell_title = "Fuera de horario";
                    if (!empty($horario_procesado) && isset($horario_procesado[$day_of_week_N])) {
                        // Ahora $horario_procesado[$day_of_week_N] es un array de turnos
                        foreach ($horario_procesado[$day_of_week_N] as $turno) {
                            if ($current_hour_int >= (int)$turno["open"] && $current_hour_int < (int)$turno["close"]) {
                                $is_working_hour = true;
                                $cell_title = "Disponible";
                                break; // Si está en algún turno, ya es disponible
                            }
                        }
                    }
                    $cell_class = $is_working_hour ? "" : "non-working-hour";
                    
                    $cell_datetime_str = "";
                    if (isset($current_week_start)) {
                        $cell_date_obj = clone $current_week_start;
                        $cell_date_obj->modify("+" . ($day_of_week_N - 1) . " days");
                        $cell_datetime_str = $cell_date_obj->format("Y-m-d") . " " . $time_slot_display . ":00";
                    }
                    ?>
                    <div class="hour-cell <?= $cell_class ?>" data-datetime="<?= $cell_datetime_str ?>" title="<?= $cell_title ?>">
                        <?php
                        if ($is_working_hour && !empty($reservas_por_dia_hora) && isset($reservas_por_dia_hora[$day_of_week_N][$time_slot_display])) {
                            foreach ($reservas_por_dia_hora[$day_of_week_N][$time_slot_display] as $reserva) {
                                echo "<div class='appointment-block' onclick='openReservationModal(" . htmlspecialchars($reserva["id_appointment"]) . ")'>";
                                echo "<span class='appointment-time'>" . htmlspecialchars(date("H:i", strtotime($reserva["date_time"]))) . "</span>";
                                echo "<span class='appointment-service'>" . htmlspecialchars($reserva["service_name"] ?? "Servicio") . "</span>";
                                echo "<span class='appointment-customer'>" . htmlspecialchars($reserva["customer_name"] ?? "Cliente") . "</span>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                <?php endfor; ?>
            <?php endforeach; ?>
        </div>
        <?php if (empty($horario_procesado) || empty($time_slots)): ?>
            <p style="text-align:center; padding: 20px;">No hay horario comercial definido para mostrar el calendario o no hay slots de tiempo. Por favor, configure el horario del negocio.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Detalles de Reserva -->
<div id="reservationDetailModal" class="reservation-modal-overlay" style="display: none;">
    <div class="reservation-modal-content">
        <button type="button" class="reservation-modal-close" id="closeReservationModalButton">&times;</button>
        <div id="reservationModalBodyContent">
            <!-- Contenido dinámico de la reserva -->
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Interactive gradient for the main animated background
    const mainAnimatedBgInteractive = document.querySelector(".content-with-menu > .gradient-bg .interactive");
    if (mainAnimatedBgInteractive) {
        const mainContentContainer = document.querySelector(".content-with-menu"); // The container for the whole page content below menu
        if (mainContentContainer) {
            document.addEventListener("mousemove", function(e) {
                const rect = mainContentContainer.getBoundingClientRect();
                const x = e.pageX - rect.left - window.scrollX;
                const y = e.pageY - rect.top - window.scrollY;
                mainAnimatedBgInteractive.style.left = `${x}px`;
                mainAnimatedBgInteractive.style.top = `${y}px`;
            });
        }
    }

    const reservationModal = document.getElementById("reservationDetailModal");
    const reservationModalBody = document.getElementById("reservationModalBodyContent");
    const mainContentAreaForModalBlur = document.getElementById("mainContentArea"); // This is the .content-with-menu
    const closeButton = document.getElementById("closeReservationModalButton");

    window.openReservationModal = function(appointmentId) {
        reservationModalBody.innerHTML = "<p>Cargando detalles de la reserva...</p>";
        reservationModal.style.display = "flex";
        if(mainContentAreaForModalBlur) mainContentAreaForModalBlur.classList.add("blur-background-calendar");
        document.body.classList.add("reservation-modal-open");

        if (typeof jQuery == "undefined") {
            console.error("jQuery no está cargado. La modal de detalles no funcionará.");
            reservationModalBody.innerHTML = "<p>Error: jQuery no está disponible.</p>";
            return;
        }

        $.ajax({
            url: "index.php?controlador=administrator&action=get_appointment_detail_ajax&id=" + appointmentId,
            type: "GET",
            success: function(response) {
                reservationModalBody.innerHTML = response;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                reservationModalBody.innerHTML = "<p>Error al cargar los detalles de la reserva. (" + textStatus + ": " + errorThrown + "). Por favor, inténtelo de nuevo.</p>";
                console.error("Error AJAX: ", textStatus, jqXHR.responseText, errorThrown);
            }
        });
    };

    window.closeReservationModal = function() {
        reservationModal.style.display = "none";
        reservationModalBody.innerHTML = "";
        if(mainContentAreaForModalBlur) mainContentAreaForModalBlur.classList.remove("blur-background-calendar");
        document.body.classList.remove("reservation-modal-open");
    };

    if (closeButton) {
        closeButton.addEventListener("click", closeReservationModal);
    }

    reservationModal.addEventListener("click", function(event) {
        if (event.target === reservationModal) {
            closeReservationModal();
        }
    });

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && reservationModal.style.display === "flex") {
            closeReservationModal();
        }
    });
});
</script>

<style>
/* Add this to your existing styles or calendar.css if preferred */
.calendar-container {
    /* Make calendar container background semi-transparent */
    background-color: rgba(255, 255, 255, 0.85); /* var(--white) with opacity */
}

.dark-theme .calendar-container {
    background-color: rgba(52, 53, 65, 0.85); /* var(--dark-bg-secondary) with opacity */
}
</style>

<?php render_site_footer();?>
