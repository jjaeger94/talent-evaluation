<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="my-chatbot-page">
                <!-- Hier kommt der Inhalt Ihrer Chatbot-Seite -->
                <h2 class="text-center mb-4">Willkommen zum Chatbot-Spiel!</h2>
                <?php if (!empty($messages)) : ?>
                    <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
                    <?php foreach (array_reverse($messages) as $message) : ?>
                        <?php include 'message.php'; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="message-container">
                        <div class="message-info">
                            <p>Beginnen Sie das Spiel, indem Sie unten eine Nachricht eingeben.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Texteingabefeld und Senden-Button -->
                <form id="chat-form" class="mt-4">
                    <div class="input-group">
                        <input type="text" id="user-message-input" class="form-control" placeholder="Geben Sie Ihre Nachricht ein...">
                        <button id="button-send-message" type="submit" class="btn btn-primary">Senden</button>
                        <button id="button-delete-thread" class="btn btn-danger ms-2">Chat löschen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        // Event Listener für den Senden-Button hinzufügen
        $('#button-send-message').click(function(e) {
            e.preventDefault(); 
            // Nachricht aus dem Eingabefeld abrufen
            var userMessage = $('#user-message-input').val();

            // AJAX-Anfrage senden, um die Nachricht zu speichern
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'send_message',
                    message: userMessage
                },
                success: function(response) {
                    $('.message-container').last().append('<div class="message user">' + userMessage + '</div>');
                    // Nach dem Senden die Eingabe löschen
                    $('#user-message-input').val('');
                    console.log(response);
                    // Erfolgreich: Neue Nachricht anzeigen
                    $('.message-container').last().append('<div class="message assistant">' + response.data + '</div>');
                },
                error: function(xhr, status, error) {
                    // Fehler: Fehlermeldung anzeigen oder Fehlerbehandlung durchführen
                    console.error(error);
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
