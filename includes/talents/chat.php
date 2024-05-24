<?php if (!empty($messages)) : ?>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#chatCollapse" aria-expanded="false" aria-controls="chatCollapse">
Chatverlauf anzeigen
</button>
<div class="collapse" id="chatCollapse">
    <div class="card card-body">
        <div class="message-container">
        <?php if (current_user_can('dienstleister')) : ?>
            <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
            <?php foreach (array_reverse($messages) as $message) : ?>
                <?php include TE_DIR.'chatbot/message.php'; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Fehler beim Abrufen der Nachrichten</p>
        <?php endif; ?>
    </div>
    </div>
</div>
<?php endif; ?>