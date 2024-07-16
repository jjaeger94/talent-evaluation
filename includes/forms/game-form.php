<form id="edit-game-form" method="post">
<?php if(isset($game->ID)): ?>
<input type="hidden" name="game_id" value="<?php echo $game->ID; ?>">
<?php endif; ?>
<div class="form-group mb-3">
    <label for="key"><strong>Game-Key</strong></label>
    <input type="text" class="form-control" id="gamekey" name="gamekey" value="<?php echo isset($game->gamekey) ? esc_attr($game->gamekey) : ''; ?>" required>
</div>
<div class="form-group mb-3">
    <label for="assistant_id"><strong>Assistant ID</strong></label>
    <input type="text" class="form-control" id="assistant_id" name="assistant_id" value="<?php echo isset($game->assistant_id) ? esc_attr($game->assistant_id) : ''; ?>" required>
</div>
<div class="form-group mb-3">
    <label for="title"><strong>Titel</strong></label>
    <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($game->title) ? esc_attr($game->title) : ''; ?>" required>
</div>
<div class="form-group mb-3">
    <label for="image_url"><strong>Bild URL</strong></label>
    <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo isset($game->image_url) ? esc_attr($game->image_url) : ''; ?>">
</div>
<div class="form-group mb-3">
    <label for="info_text"><strong>Info Text</strong></label>
    <textarea rows="3" class="form-control" id="info_text" name="info_text"><?php echo isset($game->info_text) ? esc_attr($game->info_text) : ''; ?></textarea>
</div>
<div class="form-group mb-3">
    <label for="start_msg"><strong>Start Nachricht</strong></label>
    <textarea rows="3" class="form-control" id="start_msg" name="start_msg"><?php echo isset($game->start_msg) ? esc_attr($game->start_msg) : ''; ?></textarea>
</div>
<div class="form-group mb-3">
    <label for="info_title"><strong>Info Popup Titel</strong></label>
    <input type="text" class="form-control" id="info_title" name="info_title" value="<?php echo isset($game->info_title) ? esc_attr($game->info_title) : ''; ?>">
</div>
<div class="form-group mb-3">
    <label for="info_msg"><strong>Info Popup Text</strong></label>
    <textarea rows="3" class="form-control" id="info_msg" name="info_msg"><?php echo isset($game->info_msg) ? esc_attr($game->info_msg) : ''; ?></textarea>
</div>
<div class="form-group mb-3">
<label for="type"><strong>Typ</strong></label>
<select class="form-select" id="type" name="type">
    <?php for ($i = 0; $i <= 0; $i++) : ?>
        <option value="<?php echo $i; ?>" <?php echo (isset($game->type) && $game->type == $i) ? 'selected' : ''; ?>>
            <?php echo get_game_type($i); ?>
        </option>
    <?php endfor; ?>
</select>
</div>
    <button type="submit" class="btn btn-primary"><?php echo isset($game->ID) ? 'Änderungen speichern' : 'Neues Spiel anlegen'; ?></button>
</form>
<?php if(isset($game->ID)): ?>
<div class="row d-flex justify-content-end">
    <div class="col-auto">
        <button id="removeGame" class="btn btn-danger">Spiel entfernen</button>
    </div>
</div>
<?php endif; ?>
<div id="form-message"></div>
<script>
jQuery(document).ready(function($) {
    $('#edit-game-form').submit(function(e) {
            e.preventDefault(); // Verhindert das Standard-Formular-Verhalten
            
            var formData = $(this).serialize(); // Serialisiert die Formulardaten
            
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData + '&action=edit_game', // Fügt die Aktion hinzu
                success: function(response) {
                    // Erfolgsfall: Weiterleitung oder Anzeige einer Erfolgsmeldung
                    console.log(response);
					$('#form-message').html(response.data);
                },
                error: function(xhr, status, error) {
                    // Fehlerfall: Anzeige einer Fehlermeldung
                    console.error('Fehler beim Speichern der Spieldaten:', error);
                }
            });
        });
        <?php if(isset($game->ID)): ?>
        $('#removeGame').click(()=>{
            // AJAX-Anfrage senden
            if (confirm('Eintrag wirklich entfernen?')) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: 'game_id=<?php echo $game->ID; ?>&action=remove_game',
                    success: function(response) {
                        // Erfolgreiche Verarbeitung
                        console.log(response);
                        // Seite neu laden, um die aktualisierten Daten anzuzeigen
                        if(response.success){
                            window.location.href = '<?php echo home_url('/games/');?>';
                        }
                    },
                    error: function(xhr, status, error) {
                        // Fehlerbehandlung
                        console.error(error);
                    }
                });
            }
        });
        <?php endif; ?>
});
</script>
