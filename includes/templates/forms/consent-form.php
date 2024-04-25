<div id="consent-container" class="container">
    <form id="consent-form" enctype="multipart/form-data">
        <p id="pre-consent-text">Hiermit gebe Ich, <?php echo $application->prename . ' ' . $application->surname; ?>, Commit IQ mein Einverständnis dazu (bitte ankreuzen):</p>
        <?php if ($job->screening & 1): ?>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="linkedIn_check" name="linkedIn_check">
            <label class="form-check-label" for="linkedIn_check">Mein öffentliches LinkedIn-Profil einzusehen</label>
        </div>
        <?php endif; ?>
        <?php if ($job->screening & 4): ?>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="old_work_reference_check" name="old_work_reference_check">
            <label class="form-check-label" for="old_work_reference_check">Meine ehemaligen Arbeitgeber zu kontaktieren</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="new_work_reference_check" name="new_work_reference_check">
            <label class="form-check-label" for="new_work_reference_check">Meinen aktuellen Arbeitgeber zu kontaktieren</label>
        </div>
        <p id="work-consent-text">Ich verstehe, dass die Kontaktaufnahme mit meinen Arbeitgebern ausschließlich dazu dient, den im Lebenslauf angegebenen Arbeitszeitraum und die angegebene Stelle zu überprüfen.</p>
        <?php endif; ?>
        <p id="post-consent-text">Ich verstehe auch, dass diese Einwilligung freiwillig ist und dass ich das Recht habe, sie jederzeit zu widerrufen, indem ich eine schriftliche Benachrichtigung an <?php echo get_option('admin_email'); ?> sende.</p>
        <p id="date-consent-text">Datum: <?php echo date("d.m.Y")?></p>
        <div class="mb-3">
            <p>Hier unterschreiben:</p>
            <canvas id="signature-pad" class="border" width="500" height="200"></canvas>
        </div>
    </form>
    <div class="d-flex justify-content-between">
        <button type="button" id="clear-signature" class="btn btn-danger mt-2">Unterschrift zurücksetzen</button>
        <button type="button" id="save-consent" class="btn btn-primary">Einverständnis speichern</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<script>
jQuery(document).ready(function($) {
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    $('#clear-signature').click(function() {
        var canvas = $('#signature-pad')[0];
        var signaturePad = new SignaturePad(canvas);
        signaturePad.clear();
    });

    $('#save-consent').click(function(e) {
        e.preventDefault();

        var pdf = generatePDF();
        var formData = new FormData($('#consent-form')[0]); // FormData-Objekt erstellen und das Formular übergeben
        formData.append('action', 'save_consent');
        formData.append('application_id', <?php echo $application_id ?>);
        formData.append('key', <?php echo $review_path ?>);
        var pdf = generatePDF(); // Erstelle das PDF-Dokument
        formData.append('file', pdf, 'consent_' + Date.now() + '.pdf'); // Füge das Blob-Objekt als Datei hinzu

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            method: 'POST',
            data: formData,
            contentType: false, // Wichtig für das Senden von Dateien
            processData: false, // Wichtig für das Senden von Dateien
            success: function(response) {
                console.log(response);
                $('#consent-container').html('Das wars schon, du kannst die Seite jetzt schließen.');
                // Hier können Sie weitere Aktionen nach dem Speichern auf dem Server durchführen
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    function generatePDF(){
        var doc = new jspdf.jsPDF();
        var yOffset = 20; // Startposition für das erste Formularfeld

        doc.text("Einverständniserklärung", 10, yOffset); // Überschrift einfügen

        yOffset += 20;

        var preConsentText = $('#pre-consent-text').text();
        var preConsentTextLines = doc.splitTextToSize(preConsentText, 180); // Begrenzung der Breite auf 180 (angepasst nach Bedarf)
        doc.text(preConsentTextLines, 10, yOffset); // Text aus dem HTML-Element "consent-text" einfügen
        yOffset += 20;


    
        // Iteriere durch die Formularfelder
        $('#consent-form :input').each(function(index, element) {
            var elementType = $(element).attr('type');
            var elementValue = '';
    
            if (elementType === 'checkbox') {
                // Position und Größe der Checkbox festlegen
                var checkboxX = 10;
                var checkboxY = yOffset;
                var checkboxWidth = 5;
                var checkboxHeight = 5;
    
                // Überprüfen, ob die Checkbox angehakt ist
                var isChecked = $(element).is(':checked');
    
                // Checkbox zeichnen und Zustand basierend auf dem isChecked-Wert festlegen
                doc.rect(checkboxX, checkboxY, checkboxWidth, checkboxHeight);
                if (isChecked) {
                    // Kreuz in die Checkbox einfügen, wenn sie nicht angehakt ist
                    doc.line(checkboxX, checkboxY, checkboxX + checkboxWidth, checkboxY + checkboxHeight);
                    doc.line(checkboxX, checkboxY + checkboxHeight, checkboxX + checkboxWidth, checkboxY);
                }
                
                doc.text($(element).next('label').text(), checkboxX + checkboxWidth + 2, checkboxY + 4); // Label für die Checkbox hinzufügen
                yOffset += 10; // Anpassung der Y-Position für das nächste Formularfeld
            } else {
                // Übernehme den Wert für andere Feldtypen
                elementValue = $(element).val();
                doc.text($(element).prev('label').text(), 10, yOffset + 4); // Label für das Eingabefeld hinzufügen
                doc.text(elementValue, 50, yOffset + 4); // Wert des Eingabefelds hinzufügen
                yOffset += 10; // Anpassung der Y-Position für das nächste Formularfeld
            }
        });

        yOffset += 10;

        var postConsentText = $('#post-consent-text').text();
        var postConsentTextLines = doc.splitTextToSize(postConsentText, 180); // Begrenzung der Breite auf 180 (angepasst nach Bedarf)
        doc.text(postConsentTextLines, 10, yOffset);
        yOffset += 30;

        if($('#work-consent-text')[0]){
            var workConsentText = $('#work-consent-text').text();
            var workConsentTextLines = doc.splitTextToSize(workConsentText, 180); // Begrenzung der Breite auf 180 (angepasst nach Bedarf)
            doc.text(workConsentTextLines, 10, yOffset);
            yOffset += 30;
        }


        doc.text($('#date-consent-text').text(), 10, yOffset);
        yOffset += 10;	
    
        // Unterschrift hinzufügen
        var imgData = signaturePad.toDataURL();
        doc.addImage(imgData, 'PNG', 10, yOffset, 100, 40); // Position und Größe der Unterschrift anpassen
    
        // PDF speichern
        return doc.output('blob');
    }
});
        
</script>