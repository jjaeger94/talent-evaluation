<div class="container-fluid">
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="my-chatbot-page">
            <div class="message-container">
            <div class="alert alert-info w-100">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <!-- Text mit Informationen -->
                            <p>Du arbeitest in einem Brillengeschäft. Eine Kundin betritt den Laden und sucht nach einer neuen Brille.</p>
                            <p>Versuche, der Kundin eine Brille zu verkaufen. Du kannst dir die verfügbaren Brillen anzeigen lassen, indem du auf 'Brillen anzeigen' klickst.</p>
                            <p>Das Spiel beginnt mit deiner ersten Nachricht. Viel Erfolg!</p>
                            <!-- Brillen anzeigen Button -->
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#glassesModal">Brillen anzeigen</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Bild der Kundin -->
                            <img src="https://commitiq.de/wp-content/uploads/2024/05/sahra-bild.jpeg" class="img-fluid rounded-circle p-3" alt="Kundin">
                            <div class="text-center">
                                <small>Deine Kundin</small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php if (!empty($messages)) : ?>
                <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
                <?php foreach (array_reverse($messages) as $message) : ?>
                    <?php include 'message.php'; ?>
                <?php endforeach; ?>                
            <?php endif; ?>
            </div>
            <div id="loading-indicator" class="message loading" style="display: none;">
                <div class="dot-typing"></div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Eingabefeld zum Senden einer Nachricht -->
<?php if ($state == 'in_progress') : ?>
<div id="chat-input-container" class="fixed-bottom">
    <form id="chat-form" class="mt-4">
        <div class="input-group">
            <input type="text" id="user-message-input" class="form-control" placeholder="Geben Sie Ihre Nachricht ein...">
            <button id="button-send-message" type="submit" class="btn btn-primary">Senden</button>
        </div>
    </form>
</div>
<?php endif; ?>

    <!-- Modal für positives Testergebnis -->
    <?php include 'save-test-form.php'; ?>
    <!-- Modal für negatives Testergebnis -->
<?php include 'test-result-modal.php'; ?>
<!-- Modal für Produkt -->
<?php include 'product-view-modal.php'; ?>
<script>
    jQuery(document).ready(function($) {
        // Event Listener für den Senden-Button hinzufügen
        $('#button-send-message').click(function(e) {
            e.preventDefault(); 
            // Nachricht aus dem Eingabefeld abrufen
            var userMessage = $('#user-message-input').val();
            $('#button-send-message').prop('disabled', true);
            $('.message-container').last().append('<div class="message user">' + userMessage + '</div>');
            // Nach dem Senden die Eingabe löschen
            $('#user-message-input').val('');
            $('#loading-indicator').show();

            // AJAX-Anfrage senden, um die Nachricht zu speichern
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'send_message',
                    message: userMessage
                },
                success: function(response) {
                    if(response.success){
                        let data = JSON.parse(response.data);
                        console.log(data);
                        $('#loading-indicator').hide();
                        if(data.state === 'success') {
                            $('#chat-form').hide();
                            $('.message-container').last().append('<div class="alert alert-info w-100">Danke fürs mitmachen! Du kannst das Fenster jetzt schließen</div>')
                            $('#talentFormModal').show();
                        } else if(data.state === 'failed') {
                            $('#testResultMessage').text('Schade! Der Test wurde nicht bestanden.');
                            $('#testResultModal').modal('show');
                            $('#chat-form').hide();
                            $('.message-container').last().append('<div class="alert alert-info w-100">Danke fürs mitmachen! Du kannst das Fenster jetzt schließen</div>');
                        }else{
                            // Erfolgreich: Neue Nachricht anzeigen
                            $('.message-container').last().append('<div class="message assistant">' + data.message + '</div>');
                        }
                    }else{
                        $('#loading-indicator').hide();
                    }
                },
                error: function(xhr, status, error) {
                    // Fehler: Fehlermeldung anzeigen oder Fehlerbehandlung durchführen
                    console.error(error);
                },
                complete: function(){
                    $('#button-send-message').prop('disabled', false);
                }
            });
        });
        
         // Event Listener für den Löschen-Button hinzufügen
         $('#button-delete-thread').click(function(e) {
            e.preventDefault();
            // AJAX-Anfrage senden, um den Thread zu löschen
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'delete_chat'
                },
                success: function(response) {
                    if(response.success){
                        // Erfolgreich: Seite neu laden
                        location.reload();
                    }else{
                        console.error(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    // Fehler: Fehlermeldung anzeigen oder Fehlerbehandlung durchführen
                    console.error(error);
                }
            });
        });
    });
</script>
