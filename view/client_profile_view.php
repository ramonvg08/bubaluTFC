<?php
require_once("view/menu.php");
?>
<div class="content-with-menu">
    <div class="user-profile-container">
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
        background-color: var(--light-gray);
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
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
    
    .back-button-container {
        margin-top: 20px;
        text-align: center;
    }
</style>
