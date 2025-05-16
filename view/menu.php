<link rel="stylesheet" href="styles/menu_bar.css">
<link rel="stylesheet" href="styles/home.css">
<link rel="stylesheet" href="styles/bubbles.css">

<?php
// Incluir el cargador del footer que define la función y carga los estilos necesarios (global.css y footer.css)
require_once("view/footer_loader.php");

// Detectar si estamos en la página de inicio o no
$is_home_page = true; // Por defecto, asumimos que estamos en la página de inicio

// Solo si hay parámetros específicos que indiquen otra página, cambiamos a false
if (isset($_GET["controlador"]) && isset($_GET["action"])) {
    if (($_GET["controlador"] == "business" && $_GET["action"] == "home") || 
        ($_GET["controlador"] == "business" && $_GET["action"] == "iniciar") ||
        (!isset($_GET["controlador"]) && !isset($_GET["action"]))) {
        $is_home_page = true; // Explícitamente la página de inicio o login
    } else {
        $is_home_page = false; // Otra página diferente a la de inicio
    }
}
?>

<?php if (!$is_home_page): ?>
<!-- Contenedor del menú con fondo animado cuando no estamos en home -->
<div class="menu-container with-bubbles">
    <!-- Fondo animado de burbujas para el menú -->
    <div class="menu-bubbles gradient-bg">
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
<?php endif; ?>

<?php if (isset($_SESSION["nombre"])): ?>
    <!-- Menú con 6 iconos distribuidos para usuarios logueados -->
    <div class="icon-menu-logged">
        <a href="index.php?controlador=business&action=home" class="icon-menu-item">
            <i class="fas fa-home"></i>
        </a>
        <?php if ($_SESSION["role"] === "administrator"): ?>
            <a href="index.php?controlador=administrator&action=view_appointments_calendar" class="icon-menu-item">
                <i class="fas fa-calendar-alt"></i>
            </a>
        <?php else: ?>
            <a href="index.php?controlador=users&action=reservas" class="icon-menu-item">
                <i class="fas fa-calendar-alt"></i>
            </a>
        <?php endif; ?>
        <a href="index.php?controlador=users&action=home" class="icon-menu-item">
            <i class="fas fa-user"></i>
        </a>
        <?php if ($_SESSION["role"] === "administrator"): ?>
            <a href="index.php?controlador=administrator&action=home" class="icon-menu-item">
                <i class="fas fa-cogs"></i>
            </a>
            <a href="index.php?controlador=administrator&action=view_pending_requests" class="icon-menu-item notification-icon-container">
                <i class="fas fa-info-circle"></i>
                <span class="notification-bubble" id="pending-requests-bubble" style="display: none;">0</span>
            </a>
        <?php else: ?>
            <a href="index.php?controlador=contacto&action=contactar" class="icon-menu-item">
                <i class="fas fa-envelope"></i>
            </a>
             <!-- El icono de info-circle genérico para no administradores, si se quisiera mantener -->
             <!-- <a href="#" class="icon-menu-item">
                <i class="fas fa-info-circle"></i>
            </a> -->
        <?php endif; ?>
        <a href="index.php?controlador=business&action=desconectar" class="icon-menu-item">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
<?php else: ?>
    <!-- Menú simplificado para usuarios no logueados -->
    <div class="icon-menu">
        <div class="icon-menu-left">
            <a href="index.php?controlador=business&action=home" class="icon-menu-item">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <div class="icon-menu-right">
            <a href="index.php?controlador=business&action=iniciar" class="icon-menu-item">
                <i class="fas fa-sign-in-alt"></i>
            </a>
        </div>
    </div>
<?php endif; ?>

<?php if (!$is_home_page): ?>
</div> <!-- Cierre del contenedor del menú con fondo animado -->

<!-- Script para manejar la interactividad del efecto de burbujas en el menú -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const interactiveGradient = document.querySelector(".menu-bubbles .interactive");
    
    if (interactiveGradient) {
        document.addEventListener("mousemove", function(e) {
            const x = e.pageX;
            const y = e.pageY;
            
            const menuContainer = document.querySelector(".menu-container.with-bubbles");
            if (menuContainer) {
                const rect = menuContainer.getBoundingClientRect();
                
                if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                    interactiveGradient.style.left = `${x - rect.left}px`;
                    interactiveGradient.style.top = `${y - rect.top}px`;
                }
            }
        });
    }
});
</script>
<?php endif; ?>

<?php
// Script para la burbuja de notificación de solicitudes pendientes (solo para administradores)
if (isset($_SESSION["role"]) && $_SESSION["role"] === "administrator"):
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const bubble = document.getElementById("pending-requests-bubble");

    function fetchPendingCount() {
        if (!bubble) return; // Salir si el elemento no existe

        fetch("index.php?controlador=administrator&action=get_pending_requests_count_ajax", {
            method: "GET", // Es una solicitud GET
            headers: {
                "X-Requested-With": "XMLHttpRequest" // Cabecera para que index.php la reconozca como AJAX
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.pending_count > 0) {
                    bubble.textContent = data.pending_count;
                    bubble.style.display = "flex"; // Usar flex para centrar el número
                } else {
                    bubble.style.display = "none";
                }
            })
            .catch(error => {
                console.error("Error fetching pending requests count:", error);
                // Considerar mostrar un mensaje de error más amigable o reintentar
                bubble.style.display = "none";
            });
    }

    fetchPendingCount(); // Cargar al inicio

    // Para actualizar la burbuja después de aceptar/rechazar una solicitud en la página de pendientes:
    // Si la página de pendientes se recarga completamente después de una acción, fetchPendingCount() se llamará automáticamente.
    // Si la actualización en la página de pendientes es vía AJAX, esa lógica AJAX debería llamar a fetchPendingCount() al completarse.
    // Ejemplo de cómo se podría llamar desde otra parte del código si fuera necesario:
    // document.addEventListener("someCustomEventAfterRequestProcessed", fetchPendingCount);

    // Opcional: Actualizar periódicamente (considerar el impacto en el servidor)
    // setInterval(fetchPendingCount, 30000); // Actualizar cada 30 segundos
});
</script>
<?php
endif;
?>

<?php
// La función render_site_footer() y los estilos del footer ahora se cargan desde view/footer_loader.php
// NO llamar a render_site_footer(); aquí. Se llamará al final de cada vista individual si es necesario.
?>

