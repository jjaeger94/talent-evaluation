<?php if ($talent) : ?>
    <div class="container">
        <?php if (current_user_can('dienstleister')) : ?>
        <?php include TE_DIR.'talents/actions.php'; ?>
        <?php include TE_DIR.'talents/meta.php'; ?>
        <?php else: ?>
        <?php include TE_DIR.'talents/info.php'; ?>
        <?php endif; ?>
        <?php include TE_DIR.'talents/personal-data-start.php'; ?>
        <?php include TE_DIR.'talents/apprenticeship.php'; ?>
        <?php include TE_DIR.'talents/studies.php'; ?>
        <?php include TE_DIR.'talents/experience.php'; ?>
        <?php include TE_DIR.'talents/eq.php'; ?>
        <?php include TE_DIR.'talents/personal-data-end.php'; ?>
        <?php if (!empty($messages)) : ?>
        <h2>Chatverlauf</h2>
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
        <?php endif; ?>
    </div>
<?php else : ?>
    <p>Talent nicht gefunden.</p>
<?php endif; ?>
