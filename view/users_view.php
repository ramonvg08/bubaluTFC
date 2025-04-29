<?php
require_once("view/menu.php");
?>
<div class="content-with-menu">
    <div class="user-profile-container">
        <div class="user-profile">
            <h2>Mi Perfil</h2>
            
            <div class="profile-content">
                <div class="profile-image-container">
                    <img id="profile-image" src="<?= htmlspecialchars($usuario['avatar_image'] ?? 'avatar_images/default_avatar.png') ?>" alt="Imagen de perfil">
                    <form id="upload-avatar-form" enctype="multipart/form-data">
                    <button type="button" id="change-image-btn" class="btn btn-primary">Cambiar imagen</button>
                    <input type="file" id="avatar-upload" name="avatar_image" style="display: none;" accept="image/*">
                    </form>
                </div>
                
                <div class="profile-info">
                    <div class="profile-field">
                        <span class="field-label">Nombre:</span>
                        <span class="field-value" id="name-value"><?= htmlspecialchars($usuario['name']) ?></span>
                        <button class="edit-service-btn btn btn-secondary" data-field="name">Editar</button>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Apellidos:</span>
                        <span class="field-value" id="surnames-value"><?= htmlspecialchars($usuario['surnames']) ?></span>
                        <button class="edit-service-btn btn btn-secondary" data-field="surnames">Editar</button>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Email:</span>
                        <span class="field-value" id="email-value"><?= htmlspecialchars($usuario['email']) ?></span>
                        <button class="edit-service-btn btn btn-secondary" data-field="email">Editar</button>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Contraseña:</span>
                        <span class="field-value" id="password-value">••••••••</span>
                        <button class="edit-service-btn btn btn-secondary" data-field="password">Editar</button>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Teléfono:</span>
                        <span class="field-value" id="phone_number-value"><?= htmlspecialchars($usuario['phone_number'] ?? 'No especificado') ?></span>
                        <button class="edit-service-btn btn btn-secondary" data-field="phone_number">Editar</button>
                    </div>
                    
                    <div class="profile-field">
                        <span class="field-label">Fecha de Nacimiento:</span>
                        <span class="field-value" id="birthdate-value"><?= htmlspecialchars($usuario['birthdate']) ?></span>
                        <button class="edit-service-btn btn btn-secondary" data-field="birthdate">Editar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal para editar campos -->
        <div id="edit-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Editar <span id="field-to-edit"></span></h3>
                <form id="edit-form">
                    <input type="hidden" id="field-name">
                    <div id="input-container">
                        <!-- El input se generará dinámicamente según el campo a editar -->
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
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
    
    #change-image-btn {
        width: 100%;
        margin-top: 10px;
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
    
    .edit-btn {
        background-color: var(--purple-regular);
        color: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
        margin-left: 10px;
    }
    
    .edit-btn:hover {
        background-color:  var(--purple-dark);;
    }
    
    /* Estilos para el modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 50%;
        max-width: 500px;
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover {
        color: #000;
    }
    
    #edit-form input[type="text"],
    #edit-form input[type="email"],
    #edit-form input[type="password"],
    #edit-form input[type="date"],
    #edit-form input[type="tel"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    #edit-form button {
        margin-top: 10px;
    }
    

</style>
