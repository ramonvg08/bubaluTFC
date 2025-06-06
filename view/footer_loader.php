<?php
// view/footer_loader.php

if (!function_exists('render_site_footer')) {
    function render_site_footer() {
        // Enlaces a los archivos CSS necesarios para el footer
        // Se asume que global.css ya está enlazado globalmente o se gestiona de otra manera
        // Si no, descomentar la siguiente línea o asegurarse de que se carga antes:
        // echo '<link rel="stylesheet" href="styles/global.css">';
        echo '<link rel="stylesheet" href="styles/footer.css">';

        // Estructura HTML del footer
        echo <<<HTML
        <footer class="site-footer">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>Información</h4>
                    <ul>
                        <li><a href="#">Sobre Nosotros</a></li>
                        <li><a href="#">Política de Privacidad</a></li>
                        <li><a href="#">Términos y Condiciones</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Ayuda</h4>
                    <ul>
                        <li><a href="#">Contacto</a></li>
                        <li><a href="#">Preguntas Frecuentes</a></li>
                        <li><a href="#">Soporte Técnico</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Mi Cuenta</h4>
                    <ul>
                        <li><a href="index.php?controlador=users&action=home">Mi Perfil</a></li>
                        <li><a href="index.php?controlador=users&action=reservas">Mis Reservas</a></li>
                        <li><a href="#">Preferencias</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Síguenos</h4>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 BubaluTFC. Todos los derechos reservados.</p>
            </div>
        </footer>
HTML;
    }
}
?>
