# Bubalu

**Bubalu** es una plataforma web para la **gestión y reserva de citas** en negocios locales. El sistema está diseñado para conectar a clientes con empresas, permitiendo encontrar, visualizar y reservar servicios fácilmente, al mismo tiempo que proporciona herramientas completas de gestión para los administradores de negocio.

---

## 🎯 Objetivo

Digitalizar y optimizar el proceso de reserva de citas, brindando a clientes una experiencia de búsqueda fluida y a negocios locales una solución centralizada para la gestión de servicios, horarios y citas.

---

## 🔑 Características principales

- 🔍 Búsqueda de negocios por nombre o código postal
- 📅 Reservas online a través de calendario interactivo
- 👥 Gestión de usuarios con roles: Cliente y Administrador
- 📊 Panel de control completo para administradores
- 🕒 Gestión de servicios, horarios y citas
- 📌 Notificaciones de nuevas reservas
- 🎨 Interfaz moderna, responsive y accesible (modo oscuro incluido)
- 📍 Mapa interactivo con OpenStreetMap (Leaflet.js)
- 📡 Interacciones dinámicas con AJAX

---

## 🛠️ Tecnologías utilizadas

### Backend
- PHP (v7.4)
- MySQL
- Patrón MVC con Front Controller

### Frontend
- HTML5
- CSS3 (Flexbox, Grid, animaciones)
- JavaScript (AJAX)
- Leaflet.js (mapas)
- Font Awesome & Google Fonts

### Herramientas
- Visual Studio Code
- XAMPP (Apache, MySQL)
- Git + GitHub
- Google Chrome DevTools

---

## 🧩 Arquitectura

El proyecto se estructura bajo el patrón **Modelo-Vista-Controlador (MVC)**:
- **Modelo**: Acceso y manipulación de datos (CRUD).
- **Vista**: Vistas PHP/HTML modulares.
- **Controlador**: Manejo de lógica y rutas a través de `index.php` (Front Controller).

---

## 🗄️ Base de Datos

Modelo relacional con las siguientes entidades:
- `USERS`: Clientes y administradores
- `BUSINESS`: Información del negocio
- `SERVICE`: Servicios ofrecidos
- `APPOINTMENT`: Citas realizadas
- `SCHEDULE`: Horarios configurados

Incluye relaciones 1:1 y 1:N con control de integridad referencial.

---

## ✅ Pruebas realizadas

- **Funcionales**: Registro, login, búsqueda, reservas, edición de perfil, gestión de negocio, citas y horarios.
- **Usabilidad**: Flujo intuitivo con retroalimentación visual.
- **Seguridad**: Control de roles, validación en cliente y servidor.
- **Carga y rendimiento**: Pruebas con Lighthouse y ajustes en queries.

---


🌐 Enlaces

- Sitio web: https://www.bubalues.com

- Video demostrativo: https://www.youtube.com/watch?v=eoOarVgOx3E

---


📄 Licencia

Este proyecto ha sido desarrollado como parte de un Trabajo de Fin de Ciclo y no cuenta con una licencia abierta explícita.
