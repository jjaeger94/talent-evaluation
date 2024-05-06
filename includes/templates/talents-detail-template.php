<div class="talent-details">
    <?php if ($talent) : ?>
        <h2>Talentdetails</h2>
        <p><strong>ID:</strong> <?php echo $talent->ID; ?></p>
        <p><strong>Vorname:</strong> <?php echo $talent->prename; ?></p>
        <p><strong>Nachname:</strong> <?php echo $talent->surname; ?></p>
        <p><strong>E-Mail:</strong> <?php echo $talent->email; ?></p>
        <p><strong>Telefonnummer:</strong> <?php echo $talent->mobile; ?></p>
        <p><strong>OAI Test ID:</strong> <?php echo $talent->oai_test_id; ?></p>
        <!-- Chatverlauf anzeigen -->
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
    <?php else : ?>
        <p>Talent nicht gefunden.</p>
    <?php endif; ?>
</div>
