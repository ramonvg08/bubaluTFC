<?php
require_once("view/menu.php");
?>
<div class="content-with-menu">
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
    <div class="data-container">
        <h4 style="margin-bottom: 10px;">Datos de la cita</h4>
        <p><strong>Negocio:</strong> <?= htmlspecialchars($service['business_name'] ?? '') ?></p>
        <p><strong>Servicio:</strong> <?= htmlspecialchars($service['name'] ?? '') ?></p>
        <p><strong>Precio:</strong> <?= isset($service['price']) ? number_format($service['price'], 2) . ' €' : '' ?></p>
    </div>
    <div class="container">
        <h3>Selecciona una fecha:</h3>
        <input type="date" id="calendar" min="<?php echo date('Y-m-d'); ?>">

        <h4>Horas disponibles:</h4>
        <div id="time-slots-container">
            <ul id="time-slots-list"></ul>
            <div id="selected-slot-container" style="display: none; margin-top: 20px;">
                <p>Has seleccionado: <strong id="selected-slot-text"></strong></p>
                <button id="continue-booking" class="btn btn-primary">Continuar con la reserva</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.userRole = '<?= htmlspecialchars($rol) ?>';
    window.bookingData = {
        serviceId: <?= json_encode($service['id_service']) ?>,
        businessId: <?= json_encode($service['id_business']) ?>,
        selectedSlot: null,
        selectedTime: null
    };

    // Inicializar el calendario y los eventos cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Inicializando sistema de reservas...");

        // Obtener referencia al calendario
        const calendar = document.getElementById('calendar');
        const continueButton = document.getElementById('continue-booking');

        // Establecer la fecha actual como valor predeterminado
        if (calendar) {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            calendar.value = `${year}-${month}-${day}`;

            // Cargar los slots disponibles para la fecha actual
            fetchAvailableSlots(calendar.value);

            // Añadir evento para cuando se cambia la fecha
            calendar.addEventListener('change', function() {
                console.log("Fecha seleccionada:", this.value);
                fetchAvailableSlots(this.value);
            });
        } else {
            console.error("Elemento calendario no encontrado");
        }

        // Añadir evento para el botón de continuar
        if (continueButton) {
            continueButton.addEventListener('click', function() {
                if (!window.bookingData.selectedTime) {
                    alert("Por favor, selecciona una hora disponible.");
                    return;
                }

                // Construir la fecha y hora completa en formato Y-m-d H:i:s
                const selectedDate = document.getElementById('calendar').value;
                const selectedTime = window.bookingData.selectedTime;

                // Formatear la fecha y hora correctamente
                const dateTime = `${selectedDate} ${selectedTime}:00`;
                console.log("Fecha y hora de la reserva:", dateTime);

                // Enviar los datos para crear la cita
                $.ajax({
                    type: 'POST',
                    url: 'index.php?controlador=booking&action=create',
                    data: {
                        service_id: window.bookingData.serviceId,
                        business_id: window.bookingData.businessId,
                        datetime: dateTime
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success) {
                            // Mostrar mensaje de éxito
                            showSuccessMessage();
                        } else {
                            alert('Error al crear la reserva: ' + (response && response.message ? response.message : 'Error desconocido'));
                        }
                    },
                    error: function(error) {
                        console.error("Error al crear la reserva:", error);
                        alert('Ha ocurrido un error al crear la reserva. Por favor, inténtalo de nuevo.');
                    }
                });
            });
        } else {
            console.error("Botón de continuar no encontrado");
        }
    });

    // Sobrescribir la función fetchAvailableSlots para manejar correctamente el formato de hora
    // y filtrar horas que ya han pasado
    function fetchAvailableSlots(selectedDate) {
        const dateStr = selectedDate;
        const serviceId = window.bookingData.serviceId;
        const businessId = window.bookingData.businessId;

        // Mostrar indicador de carga
        const list = document.getElementById('time-slots-list');
        list.innerHTML = '<li>Cargando horarios disponibles...</li>';

        // Ocultar el contenedor de slot seleccionado si estaba visible
        document.getElementById('selected-slot-container').style.display = 'none';

        // Resetear la selección
        window.bookingData.selectedSlot = null;
        window.bookingData.selectedTime = null;

        $.ajax({
            type: 'GET',
            url: 'index.php?controlador=booking&action=getAvailableSlots',
            data: {
                date: dateStr,
                service_id: serviceId,
                business_id: businessId
            },
            dataType: 'json',
            success: function(data) {
                list.innerHTML = '';

                if (data.length === 0) {
                    list.innerHTML = '<li>No hay horarios disponibles para esta fecha.</li>';
                } else {
                    // Obtener la fecha y hora actual
                    const now = new Date();
                    const selectedDateObj = new Date(selectedDate);
                    const isToday = selectedDateObj.toDateString() === now.toDateString();

                    // Filtrar slots que ya han pasado si es el día actual
                    const availableSlots = isToday ?
                        data.filter(slot => {
                            const slotParts = slot.split(' ');
                            const timeOnly = slotParts.length > 1 ? slotParts[1] : slot;
                            const [hours, minutes] = timeOnly.split(':').map(Number);

                            // Comparar con la hora actual
                            return (hours > now.getHours()) ||
                                (hours === now.getHours() && minutes > now.getMinutes());
                        }) : data;

                    if (availableSlots.length === 0) {
                        list.innerHTML = '<li style= "color: var(--dark-accent-primary);">No hay horarios disponibles para esta fecha o todas las horas ya han pasado.</li>';
                    } else {
                        availableSlots.forEach(function(slot) {
                            const li = document.createElement('li');

                            // Extraer solo la hora del formato completo (d/m/Y H:i)
                            const slotParts = slot.split(' ');
                            const timeOnly = slotParts.length > 1 ? slotParts[1] : slot;

                            li.textContent = timeOnly;
                            li.className = 'time-slot';
                            li.style.cursor = 'pointer';
                            li.style.padding = '8px';
                            li.style.margin = '5px 0';
                            li.style.borderRadius = '4px';
                            li.style.backgroundColor = 'var(--medium-gray)';
                            li.style.color = 'var(--dark-gray)';

                            // Añadir evento de clic para seleccionar la hora
                            li.addEventListener('click', function() {
                                // Quitar selección anterior
                                document.querySelectorAll('.time-slot').forEach(function(el) {
                                    el.style.backgroundColor = 'var(--medium-gray)';
                                    el.style.color = 'var(--dark-gray)';
                                });

                                // Marcar como seleccionado
                                this.style.backgroundColor = '#007bff';
                                this.style.color = '#fff';

                                // Guardar la hora seleccionada
                                window.bookingData.selectedSlot = slot;
                                window.bookingData.selectedTime = timeOnly;

                                // Mostrar el texto seleccionado y el botón de continuar
                                document.getElementById('selected-slot-text').textContent = timeOnly;
                                document.getElementById('selected-slot-container').style.display = 'block';
                            });

                            list.appendChild(li);
                        });
                    }
                }
            },
            error: function(error) {
                console.error("Error obteniendo slots:", error);
                list.innerHTML = '<li>Error al cargar los horarios. Por favor, inténtalo de nuevo.</li>';
            }
        });
    }
</script>

<style>
    .gradient-bg {
        height: 110%;
    }

    .content-with-menu {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .container {
        max-height: 600px;
        padding: 15px;
        margin: 25px 20px 40px 20px;
        min-width: min-content;
        max-width: -webkit-fill-available;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 20px;
        color: var(--purple-dark);
        z-index: 3;
    }

    .dark-theme .container,
    .dark-theme .data-container {
        background-color: rgba(0, 0, 0, 0.7);
        color: var(--purple-white);
    }

    .data-container {
        display: flex;
        justify-content: left;
        flex-direction: column;
        width: auto;
        margin: 50px;
        margin-top: 20px;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 20px;
        padding: 15px;
        margin-top: 25px;
        margin-bottom: 20px;
        z-index: 3;
    }

    .data-container h4 {
        margin-bottom: 10px;
        font-size: larger;
        font-weight: 600;
        text-align: center;
    }

    #time-slots-list {
        max-height: 275px;
        overflow-y: scroll;
        list-style: none;
    }

    .dark-theme p {
        color: var(--dark-accent-primary);
    }

    @media (max-width: 575px) {
        .container {
            width: max-content;
        }

        #time-slots-list {
            max-height: 200px;
        }
    }
</style>

<?php render_site_footer(); ?>