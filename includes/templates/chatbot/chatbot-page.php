<div class="container-fluid">
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="my-chatbot-page">
            <div class="message-container">
            <?php if (!empty($messages)) : ?>
                <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
                <?php foreach (array_reverse($messages) as $message) : ?>
                    <?php include 'message.php'; ?>
                <?php endforeach; ?>                
            <?php endif; ?>
            </div>
            <?php if ($state != 'in_progress') : ?>
                <div class="alert alert-info w-100">Danke fürs mitmachen! Du kannst das Fenster jetzt schließen</div>
            <?php endif; ?>
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
            <input type="text" id="user-message-input" class="form-control" placeholder="Deine Nachricht..." required>
            <button id="button-send-message" type="submit" class="btn btn-primary">Senden</button>
        </div>
    </form>
</div>
<?php endif; ?>

    <!-- Modal für positives Testergebnis -->
    <?php include 'save-test-form.php'; ?>
    <!-- Modal für negatives Testergebnis -->
<?php include 'test-result-modal.php'; ?>
<script>
    jQuery(document).ready(function($) {

        $("#user-message-input").focus(function(){
            window.scrollTo(0, $('.message-container').offset().top + $('.message-container').height() - $(window).height() +50);
        });
        // Event Listener für den Senden-Button hinzufügen
        $('#button-send-message').click(function(e) {
            e.preventDefault(); 
            // Nachricht aus dem Eingabefeld abrufen
            var userMessage = $('#user-message-input').val();
            var trimmed = userMessage.trim();
            if(trimmed != ''){
                $('#button-send-message').prop('disabled', true);
                $('.message-container').last().append('<div class="message user">' + userMessage + '</div>');
                // Nach dem Senden die Eingabe löschen
                $('#user-message-input').val('');
                $('#loading-indicator').show();
                window.scrollTo(0, $('#loading-indicator').offset().top + $('#loading-indicator').height() - $(window).height() + 80);

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
                                window.scrollTo(0, $('.message-container').offset().top + $('.message-container').height() - $(window).height() +50);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Fehler: Fehlermeldung anzeigen oder Fehlerbehandlung durchführen
                        console.error(error);
                    },
                    complete: function(){
                        $('#loading-indicator').hide();
                        $('#button-send-message').prop('disabled', false);
                    }
                });
            }else{
                // Eingabefeld hervorheben, wenn keine Nachricht eingegeben wurde
                $('#user-message-input').addClass('input-error');
                setTimeout(function() {
                    $('#user-message-input').removeClass('input-error');
                }, 1000);
            }
        });
    });
</script>
