<div class="container-fluid pt-5 pb-5">
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="my-chatbot-page">
            <div class="message-container">
            <?php if (!empty($messages)) : ?>
                <!-- Wenn Nachrichten vorhanden sind, zeige sie an -->
                <?php foreach (array_reverse($messages) as $message) : ?>
                    <?php include 'message.php'; ?>
                <?php endforeach; ?>     
            <?php else : ?>          
                <div class="alert alert-warning w-100"><?php echo isset($game->start_msg) ? $game->start_msg : 'Beginne das Spiel, indem du unten die erste Nachricht sendest. Du kannst entweder tippen oder gedrückt halten, um eine Sprachnachricht aufzunehmen.';?></div>
            <?php endif; ?>
            </div>
            <?php if ($state != 'in_progress') : ?>
                <div class="alert alert-info w-100">Danke fürs mitmachen! Du kannst das Fenster jetzt schließen</div>
            <?php endif; ?>
            <div class="indication-container">
                <div id="loading-indicator" class="message loading" style="display: none;">
                    <div class="dot-typing"></div>
                </div>
                <div id="speaking-indicator" class="message speaking" style="display: none;">
                    <div class="playing">
                        <span class="playing__bar playing__bar1"></span>
                        <span class="playing__bar playing__bar2"></span>
                        <span class="playing__bar playing__bar3"></span>
                    </div>
                </div>
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
            <input type="text" id="user-message-input" class="form-control" placeholder="Tippen oder gedrückt halten zum aufnehmen...">
            <button id="button-send-message" type="submit" class="btn btn-primary">
                <i id="send-icon" class="fa fa-paper-plane"></i>
                <i id="mic-icon" class="fa fa-microphone" style="display: none;"></i>
            </button>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Modal für positives Testergebnis -->
<?php include_once 'save-test-form.php'; ?>
<!-- Modal für negatives Testergebnis -->
<?php include_once 'test-result-modal.php'; ?>
<script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
<script>
    jQuery(document).ready(function($) {
        let state = '<?php echo $state; ?>';
        let recorder;
        let audioStream;
        let isRecording = false;
        var urlParams = new URLSearchParams(window.location.search);
        var hasId;
        if(urlParams && urlParams.has('id')){
            hasId = urlParams.get('id');
        }

        $("#user-message-input").focus(function() {
            window.scrollTo(0, $('.message-container').offset().top + $('.message-container').height() - $(window).height() + 50);
        });

        function render_send_btn(){
            if ($('#user-message-input').val() && $('#user-message-input').val().trim() === '') {
                $('#send-icon').hide();
                $('#mic-icon').show();
            } else {
                $('#send-icon').show();
                $('#mic-icon').hide();
            }
        };

        // Toggle microphone icon based on input field content
        $('#user-message-input').on('input', function() {
            render_send_btn();
        });

        $('#user-message-input').keypress(function (e) {
            var key = e.which;
            if(key == 13 && !$('#button-send-message').prop('disabled'))  // the enter key code
            {
                sendMessage();
                return false;  
            }
        });   

        $('#button-send-message').click((e)=>{
            e.preventDefault();
        });
        // Event Listener für den Senden-Button hinzufügen
        $('#button-send-message').on('mousedown touchstart', function(e) {
            e.preventDefault();
            if ($('#user-message-input').val().trim() !== '') {
                // Handle text message sending
                sendMessage();
            } else {
                // Start recording audio
                startRecording();
            }
        });

        $('#button-send-message').on('mouseup touchend', function(e) {
            e.preventDefault();
            // Stop recording audio
            if ($('#user-message-input').val().trim() === '') {
                stopRecording();
            }
        });

        function sendMessage() {
            var userMessage = $('#user-message-input').val();
            var trimmed = userMessage.trim();
            if (trimmed != '') {
                $('#button-send-message').prop('disabled', true);
                $('.message-container').last().append('<div class="message user">' + userMessage + '</div>');
                $('#user-message-input').val('');
                $('#loading-indicator').show();
                window.scrollTo(0, $('#loading-indicator').offset().top + $('#loading-indicator').height() - $(window).height() + 80);

                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {
                        action: 'send_message',
                        message: userMessage
                    },
                    success: function(response) {
                        handleResponse(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    },
                    complete: function() {
                        $('#loading-indicator').hide();
                        $('#button-send-message').prop('disabled', false);
                    }
                });
            } else {
                $('#user-message-input').addClass('input-error');
                setTimeout(function() {
                    $('#user-message-input').removeClass('input-error');
                }, 1000);
            }
        }

        async function startRecording() {
            if (!isRecording) {
                const result = await navigator.permissions.query({ name: "microphone" });
                if(result && result.state == 'granted'){
                    $('#speaking-indicator').show();
                        try {
                            audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                            recorder = RecordRTC(audioStream, {
                                type: 'audio',
                                mimeType: 'audio/webm',
                                recorderType: StereoAudioRecorder,
                                desiredSampRate: 16000
                            });
                            recorder.startRecording();
                            isRecording = true;
                        } catch (err) {
                            console.error('Error accessing media devices.', err);
                            $('#speaking-indicator').hide();
                        }
                }else if(result && result.state == 'prompt'){
                    try{
                        await navigator.mediaDevices.getUserMedia({ audio: true });
                    } catch (err) {
                        console.error('Microphone access denied.'); 
                        alert('Um die Spracheingabe nutzen zu können, erlaube der Seite bitte den Zugriff auf dein Mikrofon. Möchtest du eine Textnachricht senden, tippe diese bitte links unten ein.');  
                    };
                } else {
                    console.error('Microphone access denied.');   
                    alert('Um die Spracheingabe nutzen zu können, erlaube der Seite bitte den Zugriff auf dein Mikrofon. Möchtest du eine Textnachricht senden, tippe diese bitte links unten ein.');  
                }   
            }
        }
        
        async function stopRecording() {
        if (isRecording) {
            $('#speaking-indicator').hide();
            return new Promise((resolve, reject) => {
                recorder.stopRecording(async function() {
                    try {
                        const audioBlob = recorder.getBlob();
                        audioStream.getTracks().forEach(track => track.stop());
                        isRecording = false;
                        let audioUrl = URL.createObjectURL(audioBlob);
                        $('#button-send-message').prop('disabled', true);
                        let formData = new FormData();
                        formData.append('audio', audioBlob, 'audio.webm'); // Datei hinzufügen
                        formData.append('audioCodec', 'webm');
                        formData.append('action', 'send_audio');

                        // AJAX-Anfrage zum Server senden
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    $('#user-message-input').val(response.data);
                                    sendMessage();
                                    resolve();
                                } else {
                                    console.error('Fehler bei der Transkription:', response.data);
                                    $('#button-send-message').prop('disabled', false);
                                    reject(new Error('Fehler bei der Transkription'));
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('error', error);
                                $('#button-send-message').prop('disabled', false);
                                reject(new Error(error));
                            }
                        });
                    } catch (err) {
                        console.error('Fehler beim Stoppen der Aufnahme:', err);
                        $('#button-send-message').prop('disabled', false);
                        reject(err);
                    }
                });
            });
        }
    }

        function handleResponse(response) {
            let data = JSON.parse(response.data);
            $('.message-container').last().append('<div class="message assistant">' + data.message + '</div>');
            window.scrollTo(0, $('.message-container').offset().top + $('.message-container').height() - $(window).height() + 50);
            if (response.success) {
                if (data.state === 'success') {
                    $('#chat-form').hide();
                    if(hasId){
                        let formData = new FormData();
                        formData.append('action', 'add_chat_to_talent');
                        formData.append('talent_id', hasId);
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            data: formData,
                            processData: false, // Wichtig: Verhindert, dass jQuery die Daten verarbeitet
                            contentType: false,
                            success: function(response) {
                                // Erfolgreiche Verarbeitung
                                if(response.success){
                                    $('.countdown').text('Danke fürs mitmachen! Du kannst das Fenster jetzt schließen');
                                }
                                // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                            },
                            error: function(xhr, status, error) {
                                // Fehlerbehandlung
                                console.error(error);
                                $('.countdown').text('Ein Fehler ist aufgetreten, bitte wende dich an Kontakt@convii.de');
                            }
                        });
                        
                    }else{
                        $('.message-container').last().append('<div class="alert alert-info w-100 countdown">Super du hast bestanden! Es geht weiter in 5 Sekunden</div>');

                        // Initialize the countdown value
                        var countdownValue = 5;

                        // Function to update the countdown text
                        function updateCountdown() {
                            $('.countdown').text('Super du hast bestanden! Es geht weiter in ' + countdownValue + ' Sekunden');
                        }

                        // Call the updateCountdown function immediately to set the initial text
                        updateCountdown();

                        // Set an interval to update the countdown every second
                        var countdownInterval = setInterval(function() {
                            countdownValue--; // Decrease the countdown value
                            if (countdownValue > 0) {
                                updateCountdown(); // Update the countdown text
                            } else {
                                clearInterval(countdownInterval); // Clear the interval when countdown reaches 0
                                $('.countdown').text('Danke fürs mitmachen! Du kannst das Fenster jetzt schließen'); // Set the final message
                                $('#talentFormModal').modal('show'); // Show the modal
                            }
                        }, 1000);
                    }
                } else if (data.state === 'failed') {
                    $('#chat-form').hide();
                    $('.message-container').last().append('<div class="alert alert-info w-100">Danke fürs mitmachen! Der Test wurde leider nicht bestanden. Du kannst das Fenster jetzt schließen</div>');
                }
            }
        }

        setTimeout(function() {
            render_send_btn();
        }, 1000);
        

        if (state == 'success') {
            if(hasId){
                let formData = new FormData();
                formData.append('action', 'add_chat_to_talent');
                formData.append('talent_id', hasId);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: formData,
                    processData: false, // Wichtig: Verhindert, dass jQuery die Daten verarbeitet
                    contentType: false,
                    success: function(response) {
                        // Erfolgreiche Verarbeitung
                        if(response.success){
                            $('.countdown').text('Danke fürs mitmachen! Du kannst das Fenster jetzt schließen');
                        }
                        // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                    },
                    error: function(xhr, status, error) {
                        // Fehlerbehandlung
                        console.error(error);
                        $('.countdown').text('Ein Fehler ist aufgetreten, bitte wende dich an Kontakt@convii.de');
                    }
                });
            }else{
                $('#talentFormModal').modal('show');
            }
        }
    });
</script>