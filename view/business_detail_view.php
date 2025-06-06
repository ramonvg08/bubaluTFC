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
         <title><?= htmlspecialchars($business['name']) ?></title>
         <style>
             .business-header {
                 display: flex;
                 align-items: center;
                 margin-bottom: 2rem;
             }

             .business-image {
                 max-width: 200px;
                 margin-right: 2rem;
             }

             .map-container {
                 width: 100%;
                 height: 400px;
                 margin: 2rem 0;
                 border-radius: 10px;
                 overflow: hidden;
                 box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
             }

             #map {
                 width: 100%;
                 height: 100%;
             }

             .map-info {
                 background-color: rgba(255, 255, 255, 0.9);
                 padding: 10px;
                 border-radius: 5px;
                 margin-top: 10px;
                 font-size: 14px;
             }

             .external-customer-form {
                 display: none;
                 margin-top: 1rem;
                 padding: 1rem;
                 border: 1px solid #ddd;
                 border-radius: 5px;
                 background-color: var(--light-gray);
             }

             .external-customer-form input,
             .external-customer-form textarea {
                 width: 100%;
                 padding: 0.5rem;
                 margin-bottom: 0.5rem;
                 border: 1px solid #ddd;
                 border-radius: 3px;
             }

             .external-customer-form label {
                 display: block;
                 margin-bottom: 0.3rem;
                 font-weight: bold;
             }

             .external-customer-form button {
                 padding: 0.5rem 1rem;
                 background-color: var(--accent-yellow);
                 color: white;
                 border: none;
                 border-radius: 3px;
                 cursor: pointer;
             }

             .external-customer-form button:hover {
                 background-color: rgb(229, 180, 0);
             }

             .details-container {
                 position: relative;
                 z-index: 1;
                 margin: 20px;
                 padding: 20px;
                 background-color: rgba(245, 245, 245, 0.85);
                 border-radius: 15px;
             }

             .dark-theme .details-container {
                 background-color: rgba(52, 53, 65, 0.85);
             }

             .location-info,
             .contact-info,
             .business-description,
             .business-schedule {
                 display: flex;
                 align-items: center;
                 margin-top: 10px;
                 gap: 10px;
             }

             .location-info i,
             .contact-info i,
             .business-description i,
             .business-schedule i {
                 margin: 10px;
             }

             .location-info p,
             .contact-info p,
             .business-description p,
             .business-schedule p {
                 margin: 0;
                 font-size: 22px;
             }

             .service-item {
                 display: flex;
                 justify-content: space-between;
                 padding: 1rem;
                 border-bottom: 1px solid #ddd;
             }

             /* Estilos para modo oscuro */
             .dark-theme .map-info {
                 background-color: rgba(40, 40, 40, 0.9);
                 color: #f1f1f1;
             }

             .loading-indicator {
                 text-align: center;
                 padding: 20px;
                 font-style: italic;
                 color: #666;
             }

             .map-error {
                 color: #ff5252;
                 margin-top: 10px;
                 padding: 8px;
                 background-color: rgba(255, 82, 82, 0.1);
                 border-radius: 4px;
                 text-align: center;
             }

             @media (max-width: 768px) {
                 .business-header {
                     flex-direction: column;
                     align-items: flex-start;
                 }

                 .business-image {
                     max-width: 100%;
                     margin-bottom: 1rem;
                 }

                 .map-container {
                     height: 300px;
                 }
                
             }
         </style>
         <!-- Incluir Leaflet CSS y JS -->
         <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
         <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
         <!-- Font Awesome para iconos -->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
         <?php
            require_once("view/menu.php");
            require_once("./model/administrator_model.php");


            ?>

         <body>
             <div class="details-container">

                 <div class="business-header">
                     <img src="<?= htmlspecialchars($business['business_image']) ?>"
                         alt="<?= htmlspecialchars($business['name']) ?>"
                         class="business-image">
                     <div>
                         <h1><?= htmlspecialchars($business['name']) ?></h1>
                         <div class="location-info">
                             <i class="fas fa-map-marker-alt"></i>
                             <p id="business-address"><?= htmlspecialchars($business['address']) ?>, <?= htmlspecialchars($business['postal_code']) ?></p>
                         </div>
                         <?php if (!empty($business['phone_number'])): ?>
                             <div class="contact-info">
                                 <i class="fas fa-phone"></i>
                                 <p><?= htmlspecialchars($business['phone_number']) ?></p>
                             </div>
                         <?php endif; ?>
                         <?php if (!empty($business['description'])): ?>
                             <div class="business-description">
                                 <i class="fas fa-comment"></i>
                                 <p><?= htmlspecialchars($business['description']) ?></p>
                             </div>
                         <?php endif; ?>
                         <div class="business-schedule">
                             <i class="fas fa-clock"></i>
                             <?= scheduleFormat($business['id_business']); ?>
                         </div>

                     </div>
                 </div>

                 <!-- Contenedor del mapa -->
                 <div class="map-container">
                     <div id="map"></div>
                     <div id="map-loading" class="loading-indicator">
                         <i class="fas fa-spinner fa-spin"></i> Cargando mapa...
                     </div>
                     <div id="map-error" class="map-error" style="display: none;"></div>
                     <div class="map-info">
                         <p><strong>Dirección:</strong> <?= htmlspecialchars($business['address']) ?>, <?= htmlspecialchars($business['postal_code']) ?></p>
                         <?php if (!empty($business['phone'])): ?>
                             <p><strong>Teléfono:</strong> <?= htmlspecialchars($business['phone']) ?></p>
                         <?php endif; ?>
                     </div>
                 </div>

                 <!-- Servicios disponibles (ahora ocultos por defecto) -->
                 <div id="services-section" style="display: none;">
                     <h2>Servicios Disponibles</h2>
                     <?php foreach ($services as $service): ?>
                         <div class="service-item">
                             <div>
                                 <h3><?= htmlspecialchars($service['name']) ?></h3>
                                 <p>Duración: <?= $service['duration'] ?> minutos</p>
                             </div>
                             <div>
                                 <p>Precio: <?= number_format($service['price'], 2) ?>€</p>
                                 <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'administrator'): ?>
                                     <a href="#" onclick="toggleExternalCustomerForm(<?= $service['id_service'] ?>)">Reservar</a>
                                     <div id="external-form-<?= $service['id_service'] ?>" class="external-customer-form">
                                         <form method="POST" action="index.php?controlador=business&action=guardar_sesion">
                                             <input type="hidden" name="service_id" value="<?= $service['id_service'] ?>">
                                             <input type="hidden" name="external_customer" value="1">

                                             <label for="customer_name">Nombre del cliente:</label>
                                             <input type="text" id="customer_name" name="customer_name" required>

                                             <label for="customer_phone">Teléfono del cliente:</label>
                                             <input type="tel" id="customer_phone" name="customer_phone" required>

                                             <label for="customer_comments">Comentarios:</label>
                                             <textarea id="customer_comments" name="customer_comments" rows="3"></textarea>

                                             <button type="submit">Confirmar Reserva</button>
                                         </form>
                                     </div>
                                 <?php else: ?>
                                     <a href="#" onclick="setServiceId(<?= $service['id_service'] ?>)">Reservar</a>
                                 <?php endif; ?>
                             </div>
                         </div>
                     <?php endforeach; ?>
                 </div>
             </div>

     </div>
     <script>
         // Función para inicializar el mapa
         function initMap() {
             // Obtener la dirección del negocio
             const businessAddress = document.getElementById('business-address').textContent;
             const businessName = "<?= htmlspecialchars($business['name']) ?>";
             const mapLoading = document.getElementById('map-loading');
             const mapError = document.getElementById('map-error');

             // Crear el mapa centrado inicialmente en España
             const map = L.map('map').setView([40.416775, -3.703790], 6);

             // Añadir capa de OpenStreetMap
             L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                 attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
             }).addTo(map);

             // Geocodificar la dirección usando Nominatim con más precisión
             // Añadimos "España" para mejorar la precisión de la búsqueda
             const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(businessAddress)}&countrycodes=es&limit=1`;

             fetch(geocodeUrl)
                 .then(response => {
                     if (!response.ok) {
                         throw new Error('Error en la respuesta del servidor de geocodificación');
                     }
                     return response.json();
                 })
                 .then(data => {
                     // Ocultar indicador de carga
                     mapLoading.style.display = 'none';

                     if (data && data.length > 0) {
                         // Obtener las coordenadas
                         const lat = parseFloat(data[0].lat);
                         const lon = parseFloat(data[0].lon);

                         // Centrar el mapa en la ubicación con un zoom más cercano (18 es muy cercano)
                         map.setView([lat, lon], 17);

                         // Añadir marcador
                         const marker = L.marker([lat, lon]).addTo(map);

                         // Añadir popup con información del negocio
                         marker.bindPopup(`<b>${businessName}</b><br>${businessAddress}`).openPopup();

                         // Forzar una actualización del mapa para asegurar que se renderiza correctamente
                         setTimeout(() => {
                             map.invalidateSize();
                         }, 100);
                     } else {
                         // Si no se encuentra la dirección, intentar con una búsqueda más general
                         const cityPostalCode = businessAddress.split(',').pop().trim();
                         const fallbackUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(cityPostalCode)}&countrycodes=es&limit=1`;

                         mapError.textContent = 'No se pudo localizar la dirección exacta. Mostrando ubicación aproximada.';
                         mapError.style.display = 'block';

                         return fetch(fallbackUrl)
                             .then(response => response.json())
                             .then(fallbackData => {
                                 if (fallbackData && fallbackData.length > 0) {
                                     const lat = parseFloat(fallbackData[0].lat);
                                     const lon = parseFloat(fallbackData[0].lon);

                                     map.setView([lat, lon], 15);

                                     const marker = L.marker([lat, lon]).addTo(map);
                                     marker.bindPopup(`<b>${businessName}</b><br>Ubicación aproximada`).openPopup();
                                 } else {
                                     throw new Error('No se pudo localizar ni siquiera la ubicación aproximada');
                                 }
                             });
                     }
                 })
                 .catch(error => {
                     console.error('Error al geocodificar la dirección:', error);
                     mapLoading.style.display = 'none';
                     mapError.textContent = 'Error al cargar el mapa. Por favor, inténtelo de nuevo más tarde.';
                     mapError.style.display = 'block';
                 });

             // Detectar tema oscuro y ajustar estilo del mapa
             if (document.body.classList.contains('dark-theme')) {
                 document.querySelector('.leaflet-container').style.filter = 'invert(90%) hue-rotate(180deg)';
             }
         }

         // Inicializar el mapa cuando se carga la página
         document.addEventListener('DOMContentLoaded', initMap);

         function setServiceId(serviceId) {
             // Crear un formulario dinámicamente
             var form = document.createElement('form');
             form.method = 'POST';
             form.action = 'index.php?controlador=business&action=guardar_sesion'; // Controlador y acción para guardar en sesión

             // Crear un campo oculto para el service_id
             var serviceIdInput = document.createElement('input');
             serviceIdInput.type = 'hidden';
             serviceIdInput.name = 'service_id';
             serviceIdInput.value = serviceId;

             // Agregar el campo oculto al formulario
             form.appendChild(serviceIdInput);

             // Agregar el formulario al documento y enviarlo
             document.body.appendChild(form);
             form.submit();
         }

         function toggleExternalCustomerForm(serviceId) {
             var formId = 'external-form-' + serviceId;
             var form = document.getElementById(formId);

             // Ocultar todos los formularios primero
             var allForms = document.getElementsByClassName('external-customer-form');
             for (var i = 0; i < allForms.length; i++) {
                 allForms[i].style.display = 'none';
             }

             // Mostrar u ocultar el formulario seleccionado
             if (form.style.display === 'block') {
                 form.style.display = 'none';
             } else {
                 form.style.display = 'block';
             }
         }

         // Función para mostrar/ocultar la sección de servicios
         function toggleServices() {
             const servicesSection = document.getElementById('services-section');
             if (servicesSection.style.display === 'none') {
                 servicesSection.style.display = 'block';
             } else {
                 servicesSection.style.display = 'none';
             }
         }
     </script>

     <?php
        function scheduleFormat($id_business): string
        {
            $datos = new Administrator_model();
            $horario = $datos->get_schedule($id_business);
            $horario = $horario ? $horario : null; // Asegurarse de que $horario sea null si no se encuentra


            // Si no hay horario, devolver mensaje
            if (!$horario) {
                return '<p>Horario no disponible</p>';
            }

            $diasSemana = [
                0 => 'Domingo',
                1 => 'Lunes',
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado'
            ];

            $horariosFormateados = [];

            foreach ($horario as $dia) {
                $texto = '';
                if (!empty($dia['opening_hour']) && !empty($dia['closing_hour'])) {
                    $texto = 'de ' . $dia['opening_hour'] . ' a ' . $dia['closing_hour'];
                    if (!empty($dia['opening_hour_2']) && !empty($dia['closing_hour_2'])) {
                        $texto .= ' y de ' . $dia['opening_hour_2'] . ' a ' . $dia['closing_hour_2'];
                    }
                } else {
                    $texto = 'cerrado';
                }
                $horariosFormateados[$texto][] = $dia['day_week'];
            }

            // Agrupar días consecutivos
            $bloques = [];
            foreach ($horariosFormateados as $horarioTexto => $dias) {
                sort($dias);
                $rangos = [];
                $inicio = $dias[0];
                $fin = $dias[0];

                for ($i = 1; $i < count($dias); $i++) {
                    if ($dias[$i] == $fin + 1) {
                        $fin = $dias[$i];
                    } else {
                        $rangos[] = ($inicio == $fin)
                            ? $diasSemana[$inicio]
                            : $diasSemana[$inicio] . ' a ' . $diasSemana[$fin];
                        $inicio = $dias[$i];
                        $fin = $dias[$i];
                    }
                }

                $rangos[] = ($inicio == $fin)
                    ? $diasSemana[$inicio]
                    : $diasSemana[$inicio] . ' a ' . $diasSemana[$fin];

                $bloques[] = implode(', ', $rangos) . ' ' . $horarioTexto;
            }

            return '<p>' . implode('.<br>', $bloques) . '.</p>';
        }
        ?>


     <?php render_site_footer(); ?>