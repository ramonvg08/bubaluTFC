<?php
require_once("view/menu.php"); // menu.php already links styles/bubbles.css
?>
<div class="content-with-menu" style="position: relative;"> <!-- Make this the positioning context -->
    <!-- Animated Background -->
    <div class="gradient-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;">
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

    <!-- Original Content -->
    <div class="user-profile-container" style="position: relative; z-index: 1;"> <!-- Content on top -->
        <div class="user-profile">
            <h2>Perfil de Cliente</h2>
            
            <div class="profile-content">
                <div class="profile-image-container">
                    <img id="profile-image" src="<?= htmlspecialchars($usuario['avatar_image'] ?? 'avatar_images/default_avatar.png') ?>" alt="Imagen de perfil">
                </div>
                
                <div class="profile-info">
                    <div class="profile-field">
                        <span class="field-label">Nombre:</span>
                        <span class="field-value"><?= htmlspecialchars($usuario['name']) ?></span>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Apellidos:</span>
                        <span class="field-value"><?= htmlspecialchars($usuario['surnames']) ?></span>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Email:</span>
                        <span class="field-value"><?= htmlspecialchars($usuario['email']) ?></span>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Teléfono:</span>
                        <span class="field-value"><?= htmlspecialchars($usuario['phone_number'] ?? 'No especificado') ?></span>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Fecha de Nacimiento:</span>
                        <span class="field-value"><?= htmlspecialchars($usuario['birthdate']) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="back-button-container">
                <a href="index.php?controlador=administrator&action=home" class="btn btn-primary">Volver al Panel de Administración</a>
            </div>
        </div>
    </div>
</div>
<style>
    .user-profile-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .user-profile {
        /* background-color: var(--light-gray); Original */
        background-color: rgba(245, 245, 245, 0.85); /* Light gray with opacity for light mode */
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .dark-theme .user-profile {
        background-color: rgba(52, 53, 65, 0.85); /* Dark gray with opacity for dark mode (var(--dark-bg-secondary)) */
    }

    .profile-content {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    
    .profile-image-container {
        flex: 0 0 200px;
        text-align: center;
        margin-right: 30px;
        margin-bottom: 20px;
    }
    
    #profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #f0f0f0;
    }
    
    .profile-info {
        flex: 1;
        min-width: 300px;
    }
    
    .profile-field {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
    }
    
    .field-label {
        font-weight: bold;
        width: 150px;
    }
    
    .field-value {
        flex: 1;
    }
    #email-value{
        word-break: break-all;
    }
    
    .back-button-container {
        margin-top: 20px;
        text-align: center;
    }
</style>

<?php render_site_footer();?>
