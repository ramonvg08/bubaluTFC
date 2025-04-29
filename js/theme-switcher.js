/*
 * theme-switcher.js - Script para cambiar entre tema claro y oscuro
 */

document.addEventListener('DOMContentLoaded', function() {
    // Crear el botón de cambio de tema
    createThemeToggle();

    // Verificar si hay una preferencia guardada
    const savedTheme = localStorage.getItem('theme');

    // Si no hay preferencia guardada, establece el tema claro por defecto
    if (!savedTheme) {
        document.body.classList.remove('dark-theme'); // Asegura que no haya clase 'dark-theme'
        updateThemeIcon(false); // Establece el icono para el tema claro (luna)
        localStorage.setItem('theme', 'light'); // Guarda la preferencia como 'light'
    } else if (savedTheme === 'dark') {
        // Si la preferencia es 'dark', aplica el tema oscuro
        document.body.classList.add('dark-theme');
        updateThemeIcon(true);
    } else {
        // Si la preferencia es 'light', asegura que el tema claro esté aplicado
        document.body.classList.remove('dark-theme');
        updateThemeIcon(false);
    }

    // Función para crear el botón de cambio de tema
    function createThemeToggle() {
        const button = document.createElement('button');
        button.className = 'theme-toggle';
        button.innerHTML = '';
        button.setAttribute('aria-label', 'Cambiar tema');
        button.setAttribute('title', 'Cambiar tema');

        const icon = document.createElement('i');
        button.appendChild(icon);

        button.addEventListener('click', function() {
            toggleTheme();
        });

        document.body.appendChild(button);
        updateThemeIcon(localStorage.getItem('theme') === 'dark');
    }

    // Función para cambiar entre temas
    function toggleTheme() {
        const isDarkTheme = document.body.classList.toggle('dark-theme');
        updateThemeIcon(isDarkTheme);
        // Guardar preferencia
        localStorage.setItem('theme', isDarkTheme ? 'dark' : 'light');
    }

    // Función para actualizar el icono del botón
    function updateThemeIcon(isDarkTheme) {
        const themeToggle = document.querySelector('.theme-toggle i');
        if (themeToggle) {
            themeToggle.className = isDarkTheme ? 'fas fa-sun' : 'fas fa-moon';
        }
    }
});
