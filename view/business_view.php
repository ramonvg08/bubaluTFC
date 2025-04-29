<?php
require_once("view/menu.php");
?>

<link rel="stylesheet" href="styles/bubbles.css">

<div class="home-container">
    <!-- Contenedor de video con buscador superpuesto, reemplazado por efecto de burbujas -->
    <div class="video-container">
        <!-- Efecto de burbujas en lugar del video -->
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

        <!-- Texto "Bubbles" -->
        <div class="text-container">
            bubalu
        </div>
        
        <!-- Buscador sobre el fondo de burbujas -->
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

    <!-- Línea divisoria -->
    <div class="divider"></div>

<?php
// Configuración de paginación
$items_per_page = 8; // 4 negocios por fila, 2 filas
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_items = count($array);
$total_pages = ceil($total_items / $items_per_page);

// Asegurar que la página actual es válida
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

// Calcular el índice de inicio para la página actual
$start_index = ($current_page - 1) * $items_per_page;

// Obtener los negocios para la página actual
$businesses_page = array_slice($array, $start_index, $items_per_page);

// Mostrar resultados o mensaje si no hay resultados
if (isset($array) && count($array) > 0) {
    echo '<div class="businesses-container">';
    
    // Mostrar los negocios en formato de cuadrícula 4x2
    echo '<div class="businesses-grid">';
    
    foreach ($businesses_page as $negocio) {
        echo '<div class="business-card">';
        
        // Imagen del negocio
        echo '<a href="index.php?controlador=business&action=detail&id=' . htmlspecialchars($negocio['id_business']) . '">';
        echo '<img src="' . htmlspecialchars($negocio['business_image']) . '" alt="' . htmlspecialchars($negocio['name']) . '" class="business-image">';
        echo '</a>';
        
        // Nombre del negocio (en negrita)
        echo '<h3 class="business-name">' . htmlspecialchars($negocio['name']) . '</h3>';
        
        // Dirección y código postal (en azul)
        echo '<p class="business-address">' . htmlspecialchars($negocio['address']) . ', ' . htmlspecialchars($negocio['postal_code']) . '</p>';
        
        echo '</div>'; // Fin de la tarjeta de negocio
    }
    
    echo '</div>'; // Fin de la cuadrícula de negocios
    
    // Paginación
    if ($total_pages > 1) {
        echo '<div class="pagination">';
        
        // Botón para la primera página
        if ($current_page > 1) {
            echo '<a href="index.php?controlador=business&action=home&page=1' . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">«</a>';
        }
        
        // Botón para la página anterior
        if ($current_page > 1) {
            echo '<a href="index.php?controlador=business&action=home&page=' . ($current_page - 1) . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">‹</a>';
        }
        
        // Mostrar enlaces a páginas cercanas
        $start_page = max(1, $current_page - 2);
        $end_page = min($total_pages, $current_page + 2);
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $current_page) {
                echo '<span class="pagination-button active">' . $i . '</span>';
            } else {
                echo '<a href="index.php?controlador=business&action=home&page=' . $i . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">' . $i . '</a>';
            }
        }
        
        // Botón para la página siguiente
        if ($current_page < $total_pages) {
            echo '<a href="index.php?controlador=business&action=home&page=' . ($current_page + 1) . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">›</a>';
        }
        
        // Botón para la última página
        if ($current_page < $total_pages) {
            echo '<a href="index.php?controlador=business&action=home&page=' . $total_pages . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . '" class="pagination-button">»</a>';
        }
        
        echo '</div>'; // Fin de la paginación
    }
    
    echo '</div>'; // Fin del contenedor de negocios
} else if (isset($_GET['search'])) {
    // Mensaje cuando no hay resultados de búsqueda
    echo '<div class="no-results">';
    echo '<h3>No se encontraron resultados para su búsqueda.</h3>';
    echo '<p>Intente con otros términos o <a href="index.php?controlador=business&action=home">vea todos los negocios</a>.</p>';
    echo '</div>';
}
?>
</div>

<!-- Script para manejar la interactividad del efecto de burbujas -->
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
                
                // Solo aplicar si el mouse está dentro del contenedor de video
                if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                    interactiveGradient.style.left = `${x - rect.left}px`;
                    interactiveGradient.style.top = `${y - rect.top}px`;
                }
            }
        });
    }
});
</script>
