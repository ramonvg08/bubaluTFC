<?php
// Archivo: view/appointment_detail_modal_content.php
// Este archivo genera el HTML para el contenido de la modal de detalle de reserva.
// Se espera que la variable $reserva_detalle (array) esté disponible con los datos de la reserva.

// Simulación de datos si no vienen del controlador (para pruebas aisladas)
if (!isset($reserva_detalle)) {
    // $reserva_detalle = [
    //     'id_appointment' => '123',
    //     'date_time' => '2025-05-15 10:00:00',
    //     'business_name' => 'Peluquería Estilo Único',
    //     'service_name' => 'Corte de Caballero',
    //     'customer_name' => 'Juan Pérez',
    //     'customer_phone' => '600123456',
    //     'customer_email' => 'juan.perez@example.com', // Asumiendo que podríamos tener el email
    //     'state' => 'confirmed',
    //     'comments' => 'Llegará 5 minutos tarde.',
    //     'service_price' => '20.00',
    //     'service_duration' => '30'
    // ];
    echo "<p>Error: Detalles de la reserva no disponibles.</p>";
    return; // Salir si no hay datos
}

?>

<h3>Detalles de la Reserva #<?= htmlspecialchars($reserva_detalle["id_appointment"]) ?></h3>

<div class="reservation-detail-item">
    <strong>Fecha y Hora:</strong>
    <span><?= htmlspecialchars(date("d/m/Y H:i", strtotime($reserva_detalle["date_time"]))) ?></span>
</div>

<div class="reservation-detail-item">
    <strong>Cliente:</strong>
    <span><?= htmlspecialchars($reserva_detalle["customer_name"] ?? "No especificado") ?></span>
</div>

<?php if (isset($reserva_detalle["customer_phone"]) && !empty($reserva_detalle["customer_phone"])): ?>
<div class="reservation-detail-item">
    <strong>Teléfono Cliente:</strong>
    <span><?= htmlspecialchars($reserva_detalle["customer_phone"]) ?></span>
</div>
<?php endif; ?>

<?php if (isset($reserva_detalle["customer_email"]) && !empty($reserva_detalle["customer_email"])): // Si tuvieras email del cliente ?>
<div class="reservation-detail-item">
    <strong>Email Cliente:</strong>
    <span><?= htmlspecialchars($reserva_detalle["customer_email"]) ?></span>
</div>
<?php endif; ?>

<div class="reservation-detail-item">
    <strong>Negocio:</strong>
    <span><?= htmlspecialchars($reserva_detalle["business_name"]) ?></span>
</div>

<div class="reservation-detail-item">
    <strong>Servicio:</strong>
    <span><?= htmlspecialchars($reserva_detalle["service_name"]) ?></span>
</div>

<?php if (isset($reserva_detalle["service_price"])): ?>
<div class="reservation-detail-item">
    <strong>Precio:</strong>
    <span><?= htmlspecialchars(number_format($reserva_detalle["service_price"], 2)) ?>€</span>
</div>
<?php endif; ?>

<?php if (isset($reserva_detalle["service_duration"])): ?>
<div class="reservation-detail-item">
    <strong>Duración:</strong>
    <span><?= htmlspecialchars($reserva_detalle["service_duration"]) ?> minutos</span>
</div>
<?php endif; ?>

<div class="reservation-detail-item">
    <strong>Estado:</strong>
    <span><?= htmlspecialchars(ucfirst($reserva_detalle["state"])) ?></span>
</div>

<div class="reservation-detail-item">
    <strong>Comentarios:</strong>
    <span><?= !empty($reserva_detalle["comments"]) ? htmlspecialchars($reserva_detalle["comments"]) : "Sin comentarios" ?></span>
</div>

<?php 
// Lógica para botones de acción (cancelar, confirmar, etc.) podría ir aquí si es necesario
// Por ejemplo, si el administrador puede cambiar el estado desde esta modal.
// Esto requeriría más AJAX o un formulario.
// Por ahora, solo mostramos la información.
?>

