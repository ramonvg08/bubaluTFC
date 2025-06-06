<?php
// Vista para el contenido del detalle del negocio en la modal (AJAX)
// Este archivo espera que las variables $business y $services estén definidas
// por el controlador que lo incluye (get_detail_content en business_controller.php).
?>
<div class="modal-business-header">
    <img src="<?= htmlspecialchars(isset($business['business_image']) ? $business['business_image'] : 'images/default_business.png') ?>"
        alt="<?= htmlspecialchars(isset($business['name']) ? $business['name'] : 'Nombre no disponible') ?>"
        class="modal-business-image">
    <div>
        <h1>
            <a href="index.php?controlador=business&action=detail&id=<?= urlencode($business['id_business']) ?>" >
            <?= htmlspecialchars(isset($business['name']) ? $business['name'] : 'Detalles del Negocio') ?>
            </a>
        </h1>
        <p><?= htmlspecialchars(isset($business['address']) ? $business['address'] : 'Dirección no disponible') ?></p>
    </div>
</div>

<div class="modal-service-list">
    <h2>Servicios Disponibles</h2>
    <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): ?>
            <div class="modal-service-item">
                <div>
                    <h3><?= htmlspecialchars($service['name']) ?></h3>
                    <p>Duración: <?= htmlspecialchars($service['duration']) ?> minutos</p>
                </div>
                <div class="modal-service-details">
                    <p class="service-price">Precio: <?= number_format($service['price'], 2) ?>€</p>
                    <?php 
                    // Verificar si el usuario es administrador Y es el administrador de este negocio específico
                    $isBusinessAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'administrator' && 
                                      isset($_SESSION['id_user']) && isset($business['id_administrator']) && 
                                      $_SESSION['id_user'] == $business['id_administrator'];
                    
                    if ($isBusinessAdmin): 
                    ?>
                        <a href="#" class="reserve-button" onclick="toggleExternalCustomerForm(<?= htmlspecialchars($service['id_service']) ?>); return false;">Reservar para cliente</a>
                        <div id="external-form-<?= htmlspecialchars($service['id_service']) ?>" class="modal-external-customer-form">
                            <form method="POST" action="index.php?controlador=business&action=guardar_sesion" onsubmit="return handleModalFormSubmit(this);">
                                <input type="hidden" name="service_id" value="<?= htmlspecialchars($service['id_service']) ?>">
                                <input type="hidden" name="external_customer" value="1">

                                <label for="customer_name_<?= htmlspecialchars($service['id_service']) ?>">Nombre del cliente:</label>
                                <input type="text" id="customer_name_<?= htmlspecialchars($service['id_service']) ?>" name="customer_name" required>

                                <label for="customer_phone_<?= htmlspecialchars($service['id_service']) ?>">Teléfono del cliente:</label>
                                <input type="tel" id="customer_phone_<?= htmlspecialchars($service['id_service']) ?>" name="customer_phone" required>

                                <label for="customer_comments_<?= htmlspecialchars($service['id_service']) ?>">Comentarios:</label>
                                <textarea id="customer_comments_<?= htmlspecialchars($service['id_service']) ?>" name="customer_comments" rows="3"></textarea>

                                <button type="submit">Confirmar Reserva</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <a href="#" class="reserve-button" onclick="setServiceId(<?= htmlspecialchars($service['id_service']) ?>); return false;">Reservar</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay servicios disponibles para este negocio.</p>
    <?php endif; ?>
</div>

<script>
    // Las funciones setServiceId y toggleExternalCustomerForm ya están definidas globalmente en business_view.php
    // Si necesitas reinicializar algo específico para el contenido cargado dinámicamente, puedes hacerlo aquí.
    // Por ejemplo, si tuvieras date pickers o algo así.

    // Función para manejar el envío de formularios dentro de la modal vía AJAX (opcional, pero mejora UX)
    function handleModalFormSubmit(form) {
        // Esta es una implementación básica. Podrías querer usar jQuery.ajax si ya lo estás usando.
        const formData = new FormData(form);
        fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) // o response.json() si esperas JSON
            .then(data => {
                // Aquí puedes manejar la respuesta, por ejemplo, mostrar un mensaje de confirmación
                // y luego redirigir o cerrar la modal.
                // Por ahora, simplemente redirigimos como lo hacía el original.
                // Si la acción `guardar_sesion` redirige, esta parte puede no ser necesaria si el navegador sigue la redirección.
                // Sin embargo, para una mejor UX modal, se podría actualizar la UI sin recargar toda la página.
                // console.log('Reserva procesada:', data);
                // alert('Reserva procesada. Serás redirigido.');
                // window.location.href = 'index.php?controlador=booking&action=show'; // Redirección original
                // Para evitar la recarga de la página y mantener la modal, necesitarías que `guardar_sesion` devuelva una respuesta AJAX
                // y luego manejar esa respuesta aquí (ej. mostrar mensaje de éxito/error, cerrar modal, etc.)
                // Por simplicidad y para mantener la lógica original de redirección de `guardar_sesion`:
                // El form.submit() tradicional ocurrirá si no prevenimos el default y no hacemos fetch.
                // El onsubmit="return handleModalFormSubmit(this);" si devuelve false, previene el envío normal.
                // Si quieres que el form se envíe de forma tradicional (recargando la página), quita el onsubmit o haz que devuelva true.
                // Para AJAX real, la acción del controlador debería devolver JSON y no una redirección.
                // Dado que `guardar_sesion` hace un header_location, el submit normal es lo esperado.
                // Para que el `onclick` de los botones de reserva funcione correctamente con el `form.submit()` que ya tienes en `setServiceId`
                // y el submit del form de admin, no es necesario `handleModalFormSubmit` si la redirección es el comportamiento deseado.
                // Si se quiere mantener la modal abierta y mostrar un mensaje, `guardar_sesion` debe cambiar.
                // Por ahora, se asume que la redirección es aceptable.
            })
            .catch(error => {
                console.error('Error al enviar el formulario:', error);
                alert('Error al procesar la reserva.');
            });
        return true; // Permitir el envío tradicional del formulario que causa la redirección
    }
</script>
