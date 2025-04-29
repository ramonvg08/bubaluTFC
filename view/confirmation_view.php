<div id="confirmation-message" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); display: flex; justify-content: center; align-items: center; z-index: 9999;">
    <div style="background-color: white; padding: 30px; border-radius: 10px; text-align: center; max-width: 400px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <div style="font-size: 80px; color: #28a745; margin-bottom: 20px;">✓</div>
        <h2 style="color: #333; margin-bottom: 20px;"><?php echo isset($message_title) ? $message_title : '¡Operación completada con éxito!'; ?></h2>
    </div>
</div>
