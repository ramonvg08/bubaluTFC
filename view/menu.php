<link rel="stylesheet" href="styles/menu_bar.css">
<link rel="stylesheet" href="styles/home.css">
<link rel="stylesheet" href="styles/bubbles.css">

<?php
// Detectar si estamos en la página de inicio o no
$is_home_page = true; // Por defecto, asumimos que estamos en la página de inicio

// Solo si hay parámetros específicos que indiquen otra página, cambiamos a false
if (isset($_GET['controlador']) && isset($_GET['action'])) {
    // Estas combinaciones se consideran como página de inicio o páginas que no deben tener fondo animado
    if (($_GET['controlador'] == 'business' && $_GET['action'] == 'home') || 
        ($_GET['controlador'] == 'business' && $_GET['action'] == 'iniciar') ||
        (!isset($_GET['controlador']) && !isset($_GET['action']))) {
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
        <a href="index.php?controlador=users&action=reservas" class="icon-menu-item">
            <i class="fas fa-calendar-alt"></i>
        </a>
        <a href="index.php?controlador=users&action=home" class="icon-menu-item">
            <i class="fas fa-user"></i>
        </a>
        <?php if ($_SESSION["role"] === "administrator"): ?>
            <a href="index.php?controlador=administrator&action=home" class="icon-menu-item">
                <i class="fas fa-cogs"></i>
            </a>
        <?php else: ?>
            <a href="index.php?controlador=contacto&action=contactar" class="icon-menu-item">
                <i class="fas fa-envelope"></i>
            </a>
        <?php endif; ?>
        <a href="#" class="icon-menu-item">
            <i class="fas fa-info-circle"></i>
        </a>
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
document.addEventListener('DOMContentLoaded', function() {
    const interactiveGradient = document.querySelector('.menu-bubbles .interactive');
    
    if (interactiveGradient) {
        document.addEventListener('mousemove', function(e) {
            const x = e.pageX;
            const y = e.pageY;
            
            const menuContainer = document.querySelector('.menu-container.with-bubbles');
            if (menuContainer) {
                const rect = menuContainer.getBoundingClientRect();
                
                // Solo aplicar si el mouse está dentro del contenedor del menú
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
