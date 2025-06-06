<?php
require_once("view/menu.php");
?>

<link rel="stylesheet" href="styles/bubbles.css">
<link rel="stylesheet" href="styles/modal.css">
<style>
    .search-input {
        color: var(--purple-dark);

    }

    .dark-theme .search-input {
        background-color: rgba(52, 53, 65, 0.99) !important;
    }

    .dark-theme .search-input::placeholder {
        color: white;
    }

    .dark-theme .search-button {
        background-color: rgba(52, 53, 65, 0.99) !important;
        color: white;
    }

    .dark-theme .fas.fa-search {
        color: white;
    }

    .dark-theme .search-form {
        background-color: rgba(52, 53, 65, 0.99);
    }

    .no-results {
        position: relative;
        z-index: 10;
        text-align: center;
        padding: 30px 20px 20px 20px;
        background-color: var(--white);
        opacity: 0.8;
        margin: 0px 40px 40px 40px;
        border-radius: 20px;
    }
</style>
<div class="home-container">
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
    <!-- Contenedor de video con buscador superpuesto, reemplazado por efecto de burbujas -->
    <div class="video-container">
        <div class="text-container">
            bubalu
        </div>

        <div class="search-container">
            <form action="index.php" method="GET" class="search-form">
                <input type="hidden" name="controlador" value="business">
                <input type="hidden" name="action" value="home">
                <input type="text" name="search" placeholder="Buscar por nombre o código postal" class="search-input" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>


    <?php
    // Configuración de paginación
    $items_per_page = 8;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $total_items = count($array);
    $total_pages = ceil($total_items / $items_per_page);

    if ($current_page < 1) {
        $current_page = 1;
    } elseif ($current_page > $total_pages && $total_pages > 0) {
        $current_page = $total_pages;
    }

    $start_index = ($current_page - 1) * $items_per_page;
    $businesses_page = array_slice($array, $start_index, $items_per_page);

    if (isset($array) && count($array) > 0) {
        echo '    <div class="divider"></div>';
        echo '<div class="businesses-container">';
        echo '<div class="businesses-grid">';

        foreach ($businesses_page as $negocio) {
            echo '<div class="business-card">';
            // Modificado para abrir modal en lugar de enlace directo
            echo '<a href="#" onclick="openBusinessModal(' . htmlspecialchars($negocio['id_business']) . '); return false;">';
            echo '<img src="' . htmlspecialchars($negocio['business_image']) . '" alt="' . htmlspecialchars($negocio['name']) . '" class="business-image">';
            echo '</a>';
            echo '<h3 class="business-name">' . htmlspecialchars($negocio['name']) . '</h3>';
            echo '<p class="business-address">' . htmlspecialchars($negocio['address']) . ', ' . htmlspecialchars($negocio['postal_code']) . '</p>';
            echo '</div>';
        }

        echo '</div>'; // Fin de la cuadrícula de negocios

        if ($total_pages > 1) {
            echo '<div class="pagination">';
            if ($current_page > 1) {
                echo '<a href="index.php?controlador=business&action=home&page=1' . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">«</a>';
                echo '<a href="index.php?controlador=business&action=home&page=' . ($current_page - 1) . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">‹</a>';
            }
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $current_page) {
                    echo '<span class="pagination-button active">' . $i . '</span>';
                } else {
                    echo '<a href="index.php?controlador=business&action=home&page=' . $i . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">' . $i . '</a>';
                }
            }
            if ($current_page < $total_pages) {
                echo '<a href="index.php?controlador=business&action=home&page=' . ($current_page + 1) . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">›</a>';
                echo '<a href="index.php?controlador=business&action=home&page=' . $total_pages . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">»</a>';
            }
            echo '</div>';
        }
        echo '</div>';
    } else if (isset($_GET['search'])) {
        echo '<div class="no-results">';
        echo '<h3>No se encontraron resultados para su búsqueda.</h3>';
        echo '<p>Intente con otros términos o <a href="index.php?controlador=business&action=home">vea todos los negocios</a>.</p>';
        echo '</div>';
    }
    ?>
</div> <!-- Cierre de home-container -->

<!-- Estructura de la Modal -->
<div id="businessModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" onclick="closeBusinessModal()">&times;</button>
        <div id="modalBodyContent">
            <!-- El contenido del detalle del negocio se cargará aquí -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const interactiveGradient = document.querySelector('.interactive');
        if (interactiveGradient) {
            document.addEventListener('mousemove', function(e) {
                const x = e.pageX;
                const y = e.pageY;
                const videoContainer = document.querySelector('.video-container');
                if (videoContainer) {
                    const rect = videoContainer.getBoundingClientRect();
                    if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                        interactiveGradient.style.left = `${x - rect.left}px`;
                        interactiveGradient.style.top = `${y - rect.top}px`;
                    }
                }
            });
        }

        // Funcionalidad de la Modal
        const modal = document.getElementById('businessModal');
        const modalBody = document.getElementById('modalBodyContent');
        const homeContainer = document.querySelector('.home-container'); // Elemento a desenfocar

        window.openBusinessModal = function(businessId) {
            // Mostrar spinner o mensaje de carga
            modalBody.innerHTML = '<p>Cargando detalles...</p>';
            modal.style.display = 'flex';
            if (homeContainer) homeContainer.classList.add('blur-background');
            document.body.classList.add('modal-open');

            // Petición AJAX para obtener el contenido del detalle del negocio
            // Asumimos que jQuery está disponible globalmente por el index.php o menu.php
            $.ajax({
                url: 'index.php?controlador=business&action=get_detail_content&id=' + businessId,
                type: 'GET',
                success: function(response) {
                    modalBody.innerHTML = response;
                    // Re-inicializar scripts si es necesario para el contenido cargado dinámicamente
                    // Por ejemplo, los event listeners para los botones de reserva dentro de la modal
                    // Esto es importante porque el script original de business_detail_view.php no se ejecutará aquí.
                    // Se necesitará replicar o adaptar la lógica de setServiceId y toggleExternalCustomerForm aquí o en el contenido devuelto.
                },
                error: function() {
                    modalBody.innerHTML = '<p>Error al cargar los detalles. Por favor, inténtelo de nuevo.</p>';
                }
            });
        }

        window.closeBusinessModal = function() {
            modal.style.display = 'none';
            modalBody.innerHTML = ''; // Limpiar contenido
            if (homeContainer) homeContainer.classList.remove('blur-background');
            document.body.classList.remove('modal-open');
        }

        // Cerrar modal al hacer clic fuera de ella
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeBusinessModal();
            }
        });

        // Cerrar modal con la tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                closeBusinessModal();
            }
        });
    });

    // Funciones que estaban en business_detail_view.php, adaptadas para el contexto de la modal
    // Estas funciones deben estar disponibles globalmente o ser re-adjuntadas después de cargar el contenido de la modal.
    function setServiceId(serviceId) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?controlador=business&action=guardar_sesion';
        var serviceIdInput = document.createElement('input');
        serviceIdInput.type = 'hidden';
        serviceIdInput.name = 'service_id';
        serviceIdInput.value = serviceId;
        form.appendChild(serviceIdInput);
        document.body.appendChild(form);
        form.submit();
    }

    function toggleExternalCustomerForm(serviceId) {
        var formId = 'external-form-' + serviceId;
        var form = document.getElementById(formId);
        var allForms = document.querySelectorAll('.modal-external-customer-form'); // Asegurarse de seleccionar dentro de la modal si es necesario

        // Primero, ocultar todos los otros formularios de cliente externo que puedan estar abiertos
        allForms.forEach(f => {
            if (f.id !== formId) {
                f.style.display = 'none';
            }
        });

        if (form) {
            if (form.style.display === 'block') {
                form.style.display = 'none';
            } else {
                form.style.display = 'block';
            }
        }
    }
</script>


<?php render_site_footer(); ?>