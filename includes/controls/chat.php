<?php if ($talent->oai_test_id) : ?>
    <?php $messages = list_messages_by_thread($talent->oai_test_id); ?>
    <div class="card card-body">
        <div class="message-container">
            <?php if (has_service_permission()) : ?>
                <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
                <?php foreach (array_reverse($messages) as $message) : ?>
                    <?php include TE_DIR . 'chatbot/message.php'; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Fehler beim Abrufen der Nachrichten</p>
            <?php endif; ?>
        </div>
    </div>
<?php else : ?>
    <?php $games = get_games(); ?>
    <p>Kein Chat hinterlegt</p>
    <!-- Select-Feld für die Auswahl der Spiele -->
    <div class="form-group">
        <label for="game-select">Wähle ein Spiel:</label>
        <select name="game_key" id="game-select" class="form-control">
            <option value="">-- Bitte auswählen --</option>
            <?php foreach ($games as $game) : ?>
                <option value="<?php echo $game->gamekey; ?>">
                    <?php echo $game->title; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Button zum Generieren des Links -->
    <button id="generate-link-btn" class="btn btn-primary mt-3" disabled>
        Generiere Link
    </button>
    <p id="game-link-container" style="margin-top: 10px;"></p>

    <script>
        jQuery(document).ready(function($) {
            const generateLinkBtn = $('#generate-link-btn');

            $('#game-select').on('change', function() {
                const selectedGameKey = $(this).val();

                if (selectedGameKey) {
                    // Aktiviert den Button, wenn ein Spiel ausgewählt ist
                    generateLinkBtn.prop('disabled', false);
                } else {
                    // Deaktiviert den Button, wenn keine Auswahl getroffen wurde
                    generateLinkBtn.prop('disabled', true);
                    $('#game-link-container').html(''); // Entfernt den alten Link
                }
            });

            generateLinkBtn.on('click', function() {
                const selectedGameKey = $('#game-select').val();

                if (selectedGameKey) {
                    // Generiere den Link
                    const link = `<?php echo home_url('game-instructions/'); ?>?game=${selectedGameKey}&id=<?php echo $talent->ID; ?>`;
                    // Zeige den Link im Container an
                    $('#game-link-container').html(`<p>${link}</p>`);
                }
            });
        });
    </script>
<?php endif; ?>