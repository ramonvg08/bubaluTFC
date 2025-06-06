<?php
// Componente para el modal de aviso de cookies
// Este archivo debe ser incluido en index.php antes de cerrar el body
?>

<!-- Modal de aviso de cookies -->
<div id="cookieModalOverlay" class="cookie-modal-overlay">
    <div id="cookieModal" class="cookie-modal">
        <div class="cookie-modal-header">
            <h3>Aviso de Cookies</h3>
        </div>
        <div class="cookie-modal-content">
            <i class="fas fa-cookie-bite"></i>
            <p>Este sitio web utiliza cookies propias y de terceros para mejorar tu experiencia de navegación. <br>
            Al hacer clic en "Aceptar cookies", consientes el uso de todas las cookies. <br>
            Si prefieres, puedes configurar tus preferencias o rechazar su uso haciendo clic en "No, gracias".</p>
        </div>
        <div class="cookie-modal-actions">
            <button id="cookieAcceptBtn" class="cookie-accept-btn">Aceptar cookies</button>
            <button id="cookieDeclineBtn" class="cookie-decline-btn">No, gracias</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const cookieModalOverlay = document.getElementById('cookieModalOverlay');
    const cookieModal = document.getElementById('cookieModal');
    const cookieAcceptBtn = document.getElementById('cookieAcceptBtn');
    const cookieDeclineBtn = document.getElementById('cookieDeclineBtn');
    
    // Comprobar si el usuario ya ha tomado una decisión sobre las cookies
    const cookieConsent = localStorage.getItem('cookieConsent');
    
    // Si no hay decisión previa, mostrar el modal
    if (cookieConsent === null) {
        // Pequeño retraso para asegurar que la página se ha cargado completamente
        setTimeout(() => {
            cookieModalOverlay.classList.add('active');
            cookieModal.classList.add('active');
        }, 500);
    }
    
    // Función para guardar la decisión y ocultar el modal
    function saveCookieDecision(accepted) {
        localStorage.setItem('cookieConsent', accepted ? 'accepted' : 'declined');
        
        // Ocultar el modal con animación
        cookieModal.classList.remove('active');
        setTimeout(() => {
            cookieModalOverlay.classList.remove('active');
        }, 300);
        
        // Si se aceptan las cookies, aquí se podrían inicializar servicios de terceros
        if (accepted) {
            // Ejemplo: inicializar Google Analytics u otros servicios
            console.log('Cookies aceptadas: inicializando servicios...');
        }
    }
    
    // Event listeners para los botones
    cookieAcceptBtn.addEventListener('click', () => {
        saveCookieDecision(true);
    });
    
    cookieDeclineBtn.addEventListener('click', () => {
        saveCookieDecision(false);
    });
});
</script>
