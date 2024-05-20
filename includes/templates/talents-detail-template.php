<?php if ($talent) : ?>
    <div class="container">
        <?php include 'talents/meta.php'; ?>
        <?php include 'talents/email.php'; ?>
        <?php include 'talents/personal-data.php'; ?>
        <?php include 'talents/school.php'; ?>
        <?php include 'talents/apprenticeship.php'; ?>
        <?php include 'talents/studies.php'; ?>
        <?php include 'talents/experience.php'; ?>
        <?php include 'talents/eq.php'; ?>
        <h2>Chatverlauf</h2>
        <div class="message-container">
            <?php if (!empty($messages)) : ?>
                <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
                <?php foreach (array_reverse($messages) as $message) : ?>
                    <?php include 'chatbot/message.php'; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Fehler beim Abrufen der Nachrichten</p>
            <?php endif; ?>
        </div>
    </div>
<?php else : ?>
    <p>Talent nicht gefunden.</p>
<?php endif; ?>
