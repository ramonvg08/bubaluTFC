// ajax.js - Versión corregida para evitar múltiples alertas
function modificarEstadoCita(citaId, nuevoEstado) {
    $.ajax({
        type: 'POST',
        url: "controller/administrator_controller.php",
        data: {
            accion: "modificarEstadoCita",
            id_appointment: citaId,
            state: nuevoEstado
        },
        success: function (response) {
            // Recargar la página o actualizar la fila en la tabla
            location.reload(); // Recargar la página
        },
        error: function (error) {
            console.log(error);
        }
    });
}

// Función para que los usuarios cancelen sus reservas
function cancelarReserva(citaId) {
    if (confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
        $.ajax({
            type: 'POST',
            url: "index.php?controlador=users&action=cancelarReserva",
            data: {
                id_appointment: citaId
            },
            success: function (response) {
                // Recargar la página para mostrar los cambios
                location.reload();
            },
            error: function (error) {
                console.error("Error al cancelar la reserva:", error);
                alert('Ha ocurrido un error al cancelar la reserva. Por favor, inténtalo de nuevo.');
            }
        });
    }
}

// Función para obtener las horas disponibles para una fecha seleccionada
function fetchAvailableSlots(selectedDate) {
    const dateStr = selectedDate;
    const serviceId = window.bookingData.serviceId;
    const businessId = window.bookingData.businessId;

    // Mostrar indicador de carga
    const list = document.getElementById('time-slots-list');
    list.innerHTML = '<li>Cargando horarios disponibles...</li>';

    // Ocultar el contenedor de slot seleccionado si estaba visible
    document.getElementById('selected-slot-container').style.display = 'none';

    $.ajax({
        type: 'GET',
        url: 'index.php?controlador=booking&action=getAvailableSlots',
        data: {
            date: dateStr,
            service_id: serviceId,
            business_id: businessId
        },
        dataType: 'json',
        success: function (data) {
            list.innerHTML = '';

            if (data.length === 0) {
                list.innerHTML = '<li>No hay horarios disponibles para esta fecha.</li>';
            } else {
                data.forEach(function (slot) {
                    const li = document.createElement('li');
                    li.textContent = slot;
                    li.className = 'time-slot';
                    li.style.cursor = 'pointer';
                    li.style.padding = '8px';
                    li.style.margin = '5px 0';
                    li.style.borderRadius = '4px';
                    li.style.backgroundColor = '#f0f0f0';

                    // Añadir evento de clic para seleccionar la hora
                    li.addEventListener('click', function () {
                        // Quitar selección anterior
                        document.querySelectorAll('.time-slot').forEach(function (el) {
                            el.style.backgroundColor = '#f0f0f0';
                            el.style.color = '#000';
                        });

                        // Marcar como seleccionado
                        this.style.backgroundColor = '#007bff';
                        this.style.color = '#fff';

                        // Guardar la hora seleccionada
                        window.bookingData.selectedSlot = slot;

                        // Mostrar el texto seleccionado y el botón de continuar
                        document.getElementById('selected-slot-text').textContent = slot;
                        document.getElementById('selected-slot-container').style.display = 'block';
                    });

                    list.appendChild(li);
                });
            }
        },
        error: function (error) {
            console.error("Error obteniendo slots:", error);
            list.innerHTML = '<li>Error al cargar los horarios. Por favor, inténtalo de nuevo.</li>';
        }
    });
}

// Función para mostrar el mensaje de confirmación
function showSuccessMessage() {
    $.ajax({
        type: 'GET',
        url: 'index.php?controlador=booking&action=showConfirmation',
        success: function (response) {
            const messageContainer = document.createElement('div');
            messageContainer.innerHTML = response;
            document.body.appendChild(messageContainer);

            // Redirección según el rol
            let redirectUrl = 'index.php?controlador=users&action=reservas';
            if (window.userRole === 'administrator') {
                redirectUrl = 'index.php?controlador=administrator&action=view_appointments_calendar';
            }
            setTimeout(function () {
                window.location.href = redirectUrl;
            }, 2000);
        },
        error: function (error) {
            console.error("Error al mostrar confirmación:", error);
            let redirectUrl = 'index.php?controlador=users&action=reservas';
            if (window.userRole === 'administrator') {
                redirectUrl = 'index.php?controlador=administrator&action=view_appointments_calendar';
            }
            window.location.href = redirectUrl;
        }
    });
}

// Función para actualizar el perfil del usuario
function actualizarPerfilUsuario(field, value) {
    $.ajax({
        type: 'POST',
        url: 'index.php?controlador=users&action=actualizarPerfil',
        data: {
            field: field,
            value: value
        },
        dataType: 'json',
        success: function (response) {
            if (response && response.success) {
                // Actualizar el valor en la interfaz
                const fieldValueElement = document.getElementById(field + '-value');
                if (fieldValueElement) {
                    if (field === 'password') {
                        fieldValueElement.textContent = '••••••••';
                    } else {
                        fieldValueElement.textContent = value;
                    }
                }

                // Mostrar mensaje de éxito
                alert('¡Perfil actualizado correctamente!');

                // Recargar la página para mostrar los cambios
                location.reload();
            } else {
                alert('Error al actualizar el perfil: ' + (response && response.message ? response.message : 'Error desconocido'));
            }
        },
        error: function (error) {
            console.error("Error al actualizar el perfil:", error);
            alert('Ha ocurrido un error al actualizar el perfil. Por favor, inténtalo de nuevo.');
        }
    });
}



// Función para subir la imagen de perfil
function subirImagenPerfil(formData) {
    $.ajax({
        type: 'POST',
        url: 'index.php?controlador=users&action=subirImagenPerfil', // URL corregida
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            if (response && response.success) {
                // Actualizar la imagen en la interfaz
                document.getElementById('profile-image').src = response.image_path + '?t=' + new Date().getTime();
                alert('¡Imagen de perfil actualizada correctamente!');

                // Recargar la página para mostrar los cambios
                location.reload();
            } else {
                alert('Error al subir la imagen: ' + (response && response.message ? response.message : 'Error desconocido'));
            }
        },
        error: function (error) {
            console.error("Error al subir la imagen:", error);
            alert('Ha ocurrido un error al subir la imagen. Por favor, inténtalo de nuevo.');
        }
    });
}

// Funciones auxiliares
function getFieldLabel(field) {
    const labels = {
        'name': 'Nombre',
        'surnames': 'Apellidos',
        'email': 'Email',
        'password': 'Contraseña',
        'phone_number': 'Teléfono',
        'birthdate': 'Fecha de Nacimiento'
    };

    return labels[field] || field;
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function isValidPhone(phone) {
    const re = /^[0-9]{9}$/;
    return re.test(phone);
}

// Función para actualizar la información del negocio
function actualizarNegocio(field, value) {
    $.ajax({
        type: 'POST',
        url: 'controller/administrator_controller.php',
        data: {
            accion: 'actualizarNegocio',
            field: field,
            value: value
        },
        dataType: 'json',
        success: function (response) {
            if (response && response.success) {
                // Actualizar el valor en la interfaz
                const fieldValueElement = document.getElementById(field + '-value');
                if (fieldValueElement) {
                    fieldValueElement.textContent = value;
                }

                // Mostrar mensaje de éxito
                alert('¡Información actualizada correctamente!');

                // Recargar la página para mostrar los cambios
                location.reload();
            } else {
                alert('Error al actualizar la información: ' + (response && response.message ? response.message : 'Error desconocido'));
            }
        },
        error: function (error) {
            console.error("Error al actualizar la información:", error);
            alert('Ha ocurrido un error al actualizar la información. Por favor, inténtalo de nuevo.');
        }
    });
}

// Función para subir la imagen del negocio
function subirImagenNegocio(formData) {
    $.ajax({
        type: 'POST',
        url: 'controller/administrator_controller.php',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            if (response && response.success) {
                // Actualizar la imagen en la interfaz
                document.getElementById('business-image').src = response.image_path + '?t=' + new Date().getTime();
                alert('¡Imagen actualizada correctamente!');

                // Recargar la página para mostrar los cambios
                location.reload();
            } else {
                alert('Error al subir la imagen: ' + (response && response.message ? response.message : 'Error desconocido'));
            }
        },
        error: function (error) {
            console.error("Error al subir la imagen:", error);
            alert('Ha ocurrido un error al subir la imagen. Por favor, inténtalo de nuevo.');
        }
    });
}

// Funciones auxiliares para los campos del negocio
function getBusinessFieldLabel(field) {
    const labels = {
        'name': 'Nombre',
        'category': 'Categoría',
        'description': 'Descripción',
        'address': 'Dirección',
        'postal_code': 'Código Postal',
        'phone_number': 'Teléfono'
    };

    return labels[field] || field;
}

// Variable para controlar si ya se han inicializado las funciones
var adminFunctionsInitialized = false;
var userProfileFunctionsInitialized = false;

// Función para inicializar la gestión de servicios y horarios
function initAdminFunctions() {
    // Evitar inicialización múltiple
    if (adminFunctionsInitialized) {
        console.log("Las funciones de administración ya fueron inicializadas");
        return;
    }

    console.log("Inicializando funciones de administración");
    adminFunctionsInitialized = true;

    // Referencias a elementos del DOM para servicios
    const serviceModal = document.getElementById('service-modal');
    const serviceForm = document.getElementById('service-form');
    const serviceModalTitle = document.getElementById('service-modal-title');
    const serviceIdInput = document.getElementById('service-id');
    const serviceNameInput = document.getElementById('service-name');
    const serviceDurationInput = document.getElementById('service-duration');
    const servicePriceInput = document.getElementById('service-price');
    const addServiceBtn = document.getElementById('add-service-btn');
    const serviceModalCloseBtn = serviceModal ? serviceModal.querySelector('.close') : null;

    // Referencias a elementos del DOM para horarios
    const scheduleModal = document.getElementById('schedule-modal');
    const scheduleForm = document.getElementById('schedule-form');
    const scheduleModalTitle = document.getElementById('schedule-modal-title');
    const scheduleIdInput = document.getElementById('schedule-id');
    const scheduleDaySelect = document.getElementById('schedule-day');
    const scheduleOpeningInput = document.getElementById('schedule-opening');
    const scheduleClosingInput = document.getElementById('schedule-closing');
    const addScheduleBtn = document.getElementById('add-schedule-btn');
    const scheduleModalCloseBtn = scheduleModal ? scheduleModal.querySelector('.close') : null;

    // Referencias a elementos del DOM para editar información del negocio
    const businessModal = document.getElementById('edit-business-modal');
    const businessForm = document.getElementById('edit-business-form');
    const businessFieldToEdit = document.getElementById('business-field-to-edit');
    const businessFieldNameInput = document.getElementById('business-field-name');
    const businessInputContainer = document.getElementById('business-input-container');
    const businessModalCloseBtn = businessModal ? businessModal.querySelector('.close') : null;
    const changeBusinessImageBtn = document.getElementById('change-business-image-btn');
    const businessImageUpload = document.getElementById('business-image-upload');
    const uploadBusinessImageForm = document.getElementById('upload-business-image-form');

    // === GESTIÓN DE SERVICIOS ===

    // Abrir modal para añadir servicio
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function () {
            // Resetear el formulario
            serviceForm.reset();
            serviceIdInput.value = '';
            serviceModalTitle.textContent = 'Añadir Nuevo Servicio';

            // Mostrar el modal
            serviceModal.style.display = 'block';
        });
    }

    // Abrir modal para editar servicio - MODIFICADO para usar un selector más específico
    document.querySelectorAll('.service-table .edit-service-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const serviceId = this.getAttribute('data-id');
            const serviceName = this.getAttribute('data-name');
            const serviceDuration = this.getAttribute('data-duration');
            const servicePrice = this.getAttribute('data-price');

            // Llenar el formulario con los datos del servicio
            serviceIdInput.value = serviceId;
            serviceNameInput.value = serviceName;
            serviceDurationInput.value = serviceDuration;
            servicePriceInput.value = servicePrice;

            // Cambiar el título del modal
            serviceModalTitle.textContent = 'Editar Servicio';

            // Mostrar el modal
            serviceModal.style.display = 'block';
        });
    });

    // Cerrar modal de servicio
    if (serviceModalCloseBtn) {
        serviceModalCloseBtn.addEventListener('click', function () {
            serviceModal.style.display = 'none';
        });
    }

    // Manejar envío del formulario de servicio
    if (serviceForm) {
        serviceForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const serviceId = serviceIdInput.value;
            const name = serviceNameInput.value;
            const duration = serviceDurationInput.value;
            const price = servicePriceInput.value;

            // Determinar si es añadir o actualizar
            const action = serviceId ? 'updateService' : 'addService';

            // Datos para enviar
            const formData = {
                accion: action,
                name: name,
                duration: duration,
                price: price
            };

            // Añadir service_id si estamos actualizando
            if (serviceId) {
                formData.service_id = serviceId;
            }

            // Enviar solicitud AJAX
            $.ajax({
                type: 'POST',
                url: 'controller/administrator_controller.php',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        // Cerrar el modal
                        serviceModal.style.display = 'none';
                        // Recargar la página para mostrar los cambios
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Ha ocurrido un error al procesar la solicitud.');
                }
            });
        });
    }

    // === GESTIÓN DE HORARIOS ===

    // Abrir modal para añadir horario
    if (addScheduleBtn) {
        addScheduleBtn.addEventListener('click', function () {
            // Resetear el formulario
            scheduleForm.reset();
            scheduleIdInput.value = '';
            scheduleModalTitle.textContent = 'Añadir Nuevo Horario';

            // Mostrar el modal
            scheduleModal.style.display = 'block';
        });
    }

    // Abrir modal para editar horario
    document.querySelectorAll('.edit-schedule-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const scheduleId = this.getAttribute('data-id');
            const scheduleDay = this.getAttribute('data-day');
            const scheduleOpening = this.getAttribute('data-opening');
            const scheduleClosing = this.getAttribute('data-closing');

            // Llenar el formulario con los datos del horario
            scheduleIdInput.value = scheduleId;
            scheduleDaySelect.value = scheduleDay;
            scheduleOpeningInput.value = scheduleOpening;
            scheduleClosingInput.value = scheduleClosing;

            // Cambiar el título del modal
            scheduleModalTitle.textContent = 'Editar Horario';

            // Mostrar el modal
            scheduleModal.style.display = 'block';
        });
    });

    // Cerrar modal de horario
    if (scheduleModalCloseBtn) {
        scheduleModalCloseBtn.addEventListener('click', function () {
            scheduleModal.style.display = 'none';
        });
    }

    // === GESTIÓN DE INFORMACIÓN DEL NEGOCIO ===
    // AÑADIDO: Manejador para los botones de edición de campos del negocio
    document.querySelectorAll('.business-field .edit-service-btn[data-field]').forEach(function (button) {
        button.addEventListener('click', function () {
            const field = this.getAttribute('data-field');
            const currentValue = document.getElementById(field + '-value').textContent;

            // Configurar el modal de edición de negocio
            businessFieldToEdit.value = field;
            businessFieldNameInput.textContent = getBusinessFieldLabel(field);

            // Limpiar el contenedor de input
            businessInputContainer.innerHTML = '';

            // Crear el input adecuado según el tipo de campo
            let inputHTML = '';
            if (field === 'description') {
                inputHTML = `<textarea id="business-field-value" class="form-control" rows="4">${currentValue}</textarea>`;
            } else {
                inputHTML = `<input type="text" id="business-field-value" class="form-control" value="${currentValue}">`;
            }

            // Añadir el input al contenedor
            businessInputContainer.innerHTML = inputHTML;

            // Mostrar el modal
            businessModal.style.display = 'block';
        });
    });

    // Cerrar modal de edición de negocio
    if (businessModalCloseBtn) {
        businessModalCloseBtn.addEventListener('click', function () {
            businessModal.style.display = 'none';
        });
    }

    // Manejar envío del formulario de edición de negocio
    if (businessForm) {
        businessForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const field = businessFieldToEdit.value;
            const value = document.getElementById('business-field-value').value;

            // Llamar a la función para actualizar el negocio
            actualizarNegocio(field, value);

            // Cerrar el modal
            businessModal.style.display = 'none';
        });
    }

    // Cerrar modales al hacer clic fuera de ellos
    window.addEventListener('click', function (event) {
        if (event.target === serviceModal) {
            serviceModal.style.display = 'none';
        }
        if (event.target === scheduleModal) {
            scheduleModal.style.display = 'none';
        }
        if (event.target === businessModal) {
            businessModal.style.display = 'none';
        }
    });

    // Manejar envío del formulario de horario
    if (scheduleForm) {
        scheduleForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const scheduleId = scheduleIdInput.value;
            const dayWeek = scheduleDaySelect.value;
            const openingHour = scheduleOpeningInput.value;
            const closingHour = scheduleClosingInput.value;

            // Validar que la hora de cierre sea posterior a la de apertura
            if (openingHour >= closingHour) {
                alert('La hora de cierre debe ser posterior a la hora de apertura.');
                return;
            }

            // Determinar si es añadir o actualizar
            const action = scheduleId ? 'updateSchedule' : 'addSchedule';

            // Datos para enviar
            const formData = {
                accion: action,
                day_week: dayWeek,
                opening_hour: openingHour,
                closing_hour: closingHour
            };

            // Añadir schedule_id si estamos actualizando
            if (scheduleId) {
                formData.schedule_id = scheduleId;
            }

            // Enviar solicitud AJAX
            $.ajax({
                type: 'POST',
                url: 'controller/administrator_controller.php',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        // Cerrar el modal
                        scheduleModal.style.display = 'none';
                        // Recargar la página para mostrar los cambios
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Ha ocurrido un error al procesar la solicitud.');
                }
            });
        });
    }

    // === GESTIÓN DE ELIMINACIÓN ===

    // Eliminar servicio
    document.querySelectorAll('.delete-service-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const serviceId = this.getAttribute('data-id');
            const serviceName = this.getAttribute('data-name');

            if (confirm(`¿Estás seguro de que deseas eliminar el servicio "${serviceName}"?`)) {
                $.ajax({
                    type: 'POST',
                    url: 'controller/administrator_controller.php',
                    data: {
                        accion: 'deleteService',
                        service_id: serviceId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            // Recargar la página para mostrar los cambios
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function (error) {
                        console.error('Error en la solicitud AJAX:', error);
                        alert('Ha ocurrido un error al procesar la solicitud.');
                    }
                });
            }
        });
    });

    // Eliminar horario
    document.querySelectorAll('.delete-schedule-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const scheduleId = this.getAttribute('data-id');
            const scheduleDay = this.getAttribute('data-day');

            if (confirm(`¿Estás seguro de que deseas eliminar el horario del ${scheduleDay}?`)) {
                $.ajax({
                    type: 'POST',
                    url: 'controller/administrator_controller.php',
                    data: {
                        accion: 'deleteSchedule',
                        schedule_id: scheduleId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            // Recargar la página para mostrar los cambios
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function (error) {
                        console.error('Error en la solicitud AJAX:', error);
                        alert('Ha ocurrido un error al procesar la solicitud.');
                    }
                });
            }
        });
    });

    //Eliminar negocio
    // Manejo del botón y modal para eliminar negocio
    $("#delete-business-btn").click(function () {
        $("#delete-business-modal").css("display", "block");
    });

    // Confirmar eliminación de negocio
    $("#confirm-delete-business").click(function () {
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                controlador: 'administrator',
                accion: 'deleteBusiness'
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Redireccionar a la página principal
                    window.location.href = 'index.php?controlador=business&action=home';
                } else {
                    alert('Error al eliminar el negocio: ' + response.message);
                }
            },
            error: function () {
                alert('Error en la comunicación con el servidor');
            }
        });
    });

    // Cerrar el modal al hacer clic en el botón de cancelar o en la X
    $(".close, .cancel-btn").click(function () {
        $("#delete-business-modal").css("display", "none");
    });

    // Cerrar el modal al hacer clic fuera de él
    $(window).click(function (event) {
        if ($(event.target).hasClass('modal')) {
            $("#delete-business-modal").css("display", "none");
        }
    });


    // === GESTIÓN DE IMAGEN DEL NEGOCIO ===

    // Abrir selector de archivo al hacer clic en el botón
    if (changeBusinessImageBtn && businessImageUpload) {
        changeBusinessImageBtn.addEventListener('click', function () {
            businessImageUpload.click();
        });
    }

    // Subir imagen cuando se selecciona un archivo
    if (businessImageUpload && uploadBusinessImageForm) {
        businessImageUpload.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const formData = new FormData(uploadBusinessImageForm);
                formData.append('accion', 'subirImagenNegocio');

                // Subir la imagen
                subirImagenNegocio(formData);
            }
        });
    }

    // === GESTIÓN DE CITAS ===

    // Cambiar estado de cita
    document.querySelectorAll('.appointment-status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const citaId = this.getAttribute('data-appointment-id');
            const nuevoEstado = this.value;

            modificarEstadoCita(citaId, nuevoEstado);
        });
    });
}

// Funciones globales para eliminar perfil
function eliminarPerfil() {
    console.log("Función eliminarPerfil ejecutada");
    const userRole = window.userRole || "customer";
    console.log("Rol de usuario:", userRole);

    if (userRole === "administrator") {
        console.log("Eliminando negocio y usuario administrador");
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                controlador: 'administrator',
                accion: 'deleteBusinessAndUser'
            },
            dataType: 'json',
            success: function (response) {
                console.log("Respuesta eliminar negocio y usuario:", response);
                if (response.success) {
                    window.location.href = 'index.php?controlador=business&action=home';
                } else {
                    alert('Error al eliminar: ' + (response.message || "Error desconocido"));
                    $("#delete-profile-modal").css("display", "none");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error AJAX:", xhr, status, error);
                alert('Error en la comunicación con el servidor al intentar eliminar el negocio y usuario');
                $("#delete-profile-modal").css("display", "none");
            }
        });
    } else {
        console.log("Eliminando como cliente");
        eliminarPerfilUsuario();
    }
}

// Función auxiliar para eliminar el perfil de usuario
function eliminarPerfilUsuario() {
    console.log("Función eliminarPerfilUsuario ejecutada");
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: {
            controlador: 'users',
            action: 'deleteProfile'
        },
        dataType: 'json',
        success: function (response) {
            console.log("Respuesta eliminar perfil:", response);
            if (response.success) {
                // Redireccionar a la página principal
                window.location.href = 'index.php?controlador=business&action=home';
            } else {
                alert('Error al eliminar el perfil: ' + (response.message || "Error desconocido"));
                $("#delete-profile-modal").css("display", "none");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error AJAX:", xhr, status, error);
            alert('Error en la comunicación con el servidor al intentar eliminar el perfil');
            $("#delete-profile-modal").css("display", "none");
        }
    });
}

// Función para inicializar la edición del perfil de usuario
function initUserProfileFunctions() {
    // Evitar inicialización múltiple
    if (userProfileFunctionsInitialized) {
        console.log("Las funciones de perfil de usuario ya fueron inicializadas");
        return;
    }

    console.log("Inicializando funciones de perfil de usuario");
    userProfileFunctionsInitialized = true;

    // Obtener referencias a elementos del DOM
    const modal = document.getElementById('edit-modal');
    const closeBtn = modal ? modal.querySelector('.close') : null;
    const editForm = document.getElementById('edit-form');
    const fieldToEdit = document.getElementById('field-to-edit');
    const fieldNameInput = document.getElementById('field-name');
    const inputContainer = document.getElementById('input-container');
    const changeImageBtn = document.getElementById('change-image-btn');
    const avatarUpload = document.getElementById('avatar-upload');
    const uploadAvatarForm = document.getElementById('upload-avatar-form');

    // Configurar eventos para los botones de edición - MODIFICADO para usar la nueva clase
    document.querySelectorAll('.profile-field .edit-service-btn[data-field]').forEach(function (button) {
        button.addEventListener('click', function () {
            const field = this.getAttribute('data-field');
            const currentValue = document.getElementById(field + '-value').textContent;

            // Configurar el modal
            fieldToEdit.textContent = getFieldLabel(field);
            fieldNameInput.value = field;

            // Crear el input apropiado según el tipo de campo
            inputContainer.innerHTML = '';
            let input;

            switch (field) {
                case 'email':
                    input = document.createElement('input');
                    input.type = 'email';
                    input.id = 'edit-input';
                    input.value = currentValue;
                    input.required = true;
                    break;
                case 'password':
                    input = document.createElement('input');
                    input.type = 'password';
                    input.id = 'edit-input';
                    input.placeholder = 'Nueva contraseña';
                    input.required = true;

                    const confirmInput = document.createElement('input');
                    confirmInput.type = 'password';
                    confirmInput.id = 'confirm-input';
                    confirmInput.placeholder = 'Confirmar contraseña';
                    confirmInput.required = true;

                    inputContainer.appendChild(input);
                    inputContainer.appendChild(confirmInput);
                    break;
                case 'birthdate':
                    input = document.createElement('input');
                    input.type = 'date';
                    input.id = 'edit-input';
                    input.value = currentValue;
                    input.required = true;
                    break;
                case 'phone_number':
                    input = document.createElement('input');
                    input.type = 'tel';
                    input.id = 'edit-input';
                    input.value = currentValue === 'No especificado' ? '' : currentValue;
                    input.pattern = "[0-9]{9}";
                    input.placeholder = "Ej: 612345678";
                    break;
                default:
                    input = document.createElement('input');
                    input.type = 'text';
                    input.id = 'edit-input';
                    input.value = currentValue;
                    input.required = true;
            }

            // Si no hemos añadido ya los inputs (caso de password)
            if (inputContainer.children.length === 0) {
                inputContainer.appendChild(input);
            }

            // Mostrar el modal
            modal.style.display = 'block';
        });
    });

    // Cerrar el modal al hacer clic en la X
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }

    // Cerrar el modal al hacer clic fuera de él
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Manejar el envío del formulario
    if (editForm) {
        editForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const field = document.getElementById('field-name').value;
            const value = document.getElementById('edit-input').value;

            actualizarPerfilUsuario(field, value);
            modal.style.display = 'none';
        });
    }

    // Funcionalidad para subir la imagen de perfil
    if (changeImageBtn && avatarUpload && uploadAvatarForm) {
        changeImageBtn.addEventListener('click', function () {
            avatarUpload.click();
        });

        avatarUpload.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const formData = new FormData(uploadAvatarForm); // Usar el formulario para construir formData
                subirImagenPerfil(formData); // Enviar el formData a la función subirImagenPerfil
            }
        });
    }

    // Manejo del botón y modal para eliminar perfil
    $("#delete-profile-btn").click(function () {
        $("#delete-profile-modal").css("display", "block");
    });

    // Confirmar eliminación de perfil
    $("#confirm-delete-profile").click(function () {
        eliminarPerfil();
    });

    // Cerrar el modal al hacer clic en el botón de cancelar o en la X
    $("#delete-profile-modal .close, #delete-profile-modal .cancel-btn").click(function () {
        $("#delete-profile-modal").css("display", "none");
    });

    // Cerrar el modal al hacer clic fuera de él
    $(window).click(function (event) {
        if ($(event.target).hasClass('modal')) {
            $("#delete-profile-modal").css("display", "none");
        }
    });
}

// Inicializar funciones cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function () {
    console.log("DOM cargado, inicializando funciones...");

    // Inicializar funciones de administración si estamos en la página de administración
    if (document.querySelector('.admin-dashboard')) {
        initAdminFunctions();
    }

    // Inicializar funciones de perfil de usuario si estamos en la página de perfil
    if (document.querySelector('.user-profile')) {
        initUserProfileFunctions();
    }
});

$(document).ready(function () {
    initUserProfileFunctions();
});

