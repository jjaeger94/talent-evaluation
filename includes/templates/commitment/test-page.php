<div class="container" id="test-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="test-start-container">
                <?php if (isset($application)) : ?>
                    <div class="alert alert-success" role="alert">
                        Willkommen, <?php echo esc_html($application->prename) . ' ' . esc_html($application->surname); ?>!
                    </div>
                <?php endif; ?>
                <div class="progress mb-3">
                    <div class="progress-bar overflow-visible text-dark p-2" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <form id="test-answers-form">
                    <input type="hidden" name="jid" value="<?php echo esc_attr($jid); ?>">
                    <input type="hidden" name="key" value="<?php echo esc_attr($key); ?>">
                    <?php if (isset($application)) : ?>
                        <input type="hidden" name="aid" value="<?php echo esc_attr($application->ID); ?>">
                    <?php endif; ?>
                    <?php if (!isset($application)) : ?>
                    <div id="name-container">
                        <div class="mb-3">
                            <label for="prename" class="form-label">Vorname:</label>
                            <input type="text" class="form-control" id="prename" name="prename" required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Nachname:</label>
                            <input type="text" class="form-control" id="surname" name="surname" required>
                        </div>
                        <button type="button" id="confirm-name" class="btn btn-primary">Name bestätigen</button>
                    </div>
                    <?php endif; ?>
                    <div id="all-questions-container" class="<?php echo isset($application) ? '' : 'd-none'; ?>" >
                    <?php 
                    $totalQuestions = count($questions);
                    // Zeige nur eine Frage gleichzeitig an
                    foreach ($questions as $index => $question) : ?>
                        <div class="question-container <?php echo ($index === 0) ? '' : 'd-none'; ?>">
                            <div class="mb-3">
                                <label for="answer_<?php echo $question->ID; ?>" class="form-label"><?php echo stripslashes($question->question_text); ?></label>
                                <textarea class="form-control" id="answer_<?php echo $question->ID; ?>" name="answers[<?php echo $question->ID; ?>]" rows="4" required></textarea>
                            </div>
                            <!-- <?php if ($index > 0) : ?>
                                <button type="button" class="btn btn-primary prev-question">Zurück</button>
                            <?php endif; ?> -->
                            <?php if ($index < count($questions) - 1) : ?>
                                <button type="button" class="btn btn-primary next-question">Weiter</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary">Antworten abschicken</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    var totalQuestions = <?php echo $totalQuestions; ?>;
    var currentQuestionIndex = 0;
    var progressBar = $('.progress-bar');
    var timerInterval;

    function submitform() {
        // Formulardaten direkt aus dem Formular extrahieren
        var formData = $('#test-answers-form').serialize();

        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=process_test_answers', // Übergeben Sie die direkt aus dem Formular extrahierten Daten
            success: function(response) {
                // Erfolgsfall: Verarbeite die Antwort
                console.log(response);
                if(response.success){
                    $('#test-container').html('Vielen Dank, du kannst die Seite jetzt schließen.');
                }  
            },
            error: function(xhr, status, error) {
                // Fehlerfall: Behandele den Fehler
                console.error(error);
            }
        });
    }

    $('#confirm-name').click(function() {
        var prename = $('#prename').val().trim();
        var surname = $('#surname').val().trim();

        // Überprüfe, ob das Vorname-Feld ausgefüllt ist
        if (prename === '') {
            $('#prename').addClass('is-invalid');
        } else {
            $('#prename').removeClass('is-invalid');
        }

        // Überprüfe, ob das Nachname-Feld ausgefüllt ist
        if (surname === '') {
            $('#surname').addClass('is-invalid');
        } else {
            $('#surname').removeClass('is-invalid');
        }

        // Überprüfe, ob beide Felder ausgefüllt sind
        if (prename !== '' && surname !== '') {
            // Entferne die d-none-Klasse vom Container mit der ID all-questions-container
            $('#all-questions-container').removeClass('d-none');
            // Füge die d-none-Klasse zum Container mit der ID name-container hinzu
            $('#name-container').addClass('d-none');
            // Initialisiere den Timer beim Laden der Seite
            startTimer();
        }
    });

    $('.next-question').click(function() {
        // Überprüfe, ob das Textarea-Feld der aktuellen Frage ausgefüllt ist
        var currentTextarea = $(this).closest('.question-container').find('textarea');
        if (currentTextarea.val().trim() === '') {
            // Markiere das Textarea-Feld als ungültig, wenn es leer ist
            currentTextarea.addClass('is-invalid');
            return; // Beende die Funktion vorzeitig, wenn das Feld leer ist
        }

        // Verstecke die aktuelle Frage und zeige die nächste Frage an
        var currentQuestion = $(this).closest('.question-container');
        currentQuestion.addClass('d-none');
        currentQuestion.next('.question-container').removeClass('d-none');
        clearInterval(timerInterval); // Stoppe den Timer beim manuellen Wechsel zur nächsten Frage
        showNextQuestion(); // Wechsle zur nächsten Frage
    });

     // Funktion zum Starten des Timers
     function startTimer() {
        var timeLeft = 120; // Anzahl der Sekunden für den Timer
        clearInterval(timerInterval); // Sicherstellen, dass kein anderer Timer läuft
       
        // Funktion, die bei jedem Timer-Schritt aufgerufen wird
        timerInterval = setInterval(function() {
            timeLeft--; // Verringere die verbleibende Zeit um 1 Sekunde
            // Aktualisiere den Fortschrittsbalken basierend auf der verbleibenden Zeit
            progressBar.css('width', (100 - (timeLeft / 120 * 100)) + '%');
            progressBar.text(timeLeft + ' Sekunden bis zur nächsten Frage');

            // Überprüfe, ob die Zeit abgelaufen ist
            if (timeLeft <= 0) {
                clearInterval(timerInterval); // Stoppe den Timer
                // Wechsel zur nächsten Frage, wenn der Timer abgelaufen ist
                showNextQuestion();
            }
        }, 1000); // Timer wird alle 1 Sekunde aktualisiert
    }

  // Funktion zum Anzeigen der nächsten Frage
    function showNextQuestion() {
        var currentQuestionContainer = $('.question-container').eq(currentQuestionIndex);
        var currentTextarea = currentQuestionContainer.find('textarea');
        
        // Überprüfe, ob das Textarea-Feld der aktuellen Frage ausgefüllt ist
        if (currentTextarea.val().trim() === '') {
            // Füge "Zeit abgelaufen" in das Textarea-Feld ein, wenn es nicht ausgefüllt ist
            currentTextarea.val('Zeit abgelaufen');
        }

        currentQuestionContainer.addClass('d-none'); // Verstecke die aktuelle Frage
        currentQuestionIndex++; // Inkrementiere den Index der aktuellen Frage
        
        // Überprüfe, ob die nächste Frage existiert
        if (currentQuestionIndex < totalQuestions) {
            $('.question-container').eq(currentQuestionIndex).removeClass('d-none'); // Zeige die nächste Frage an
            startTimer(); // Starte den Timer für die neue Frage
        } else {
            // Wenn keine weitere Frage vorhanden ist, sende das Formular ab
            submitform();
        }
}


    <?php if (isset($application)) : ?>
        startTimer();
    <?php endif; ?>

    $('#test-answers-form').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardverhalten des Formulars (Neuladen der Seite)
        submitform();
    });
});
</script>
