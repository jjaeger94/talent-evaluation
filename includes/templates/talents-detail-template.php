<?php if ($talent) : ?>
    <div class="container">
        <?php if (current_user_can('dienstleister')) : ?>
        <?php include 'talents/actions.php'; ?>
        <?php include 'talents/meta.php'; ?>
        <?php else: ?>
        <?php include 'talents/info.php'; ?>
        <?php endif; ?>
        <?php include 'talents/personal-data-start.php'; ?>
        <?php include 'talents/school.php'; ?>
        <?php include 'talents/apprenticeship.php'; ?>
        <?php include 'talents/studies.php'; ?>
        <?php include 'talents/experience.php'; ?>
        <?php include 'talents/eq.php'; ?>
        <?php include 'talents/personal-data-end.php'; ?>
        <?php if (!empty($messages)) : ?>
        <h2>Chatverlauf</h2>
        <div class="message-container">
            <?php if (current_user_can('dienstleister')) : ?>
                <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
                <?php foreach (array_reverse($messages) as $message) : ?>
                    <?php include 'chatbot/message.php'; ?>
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
