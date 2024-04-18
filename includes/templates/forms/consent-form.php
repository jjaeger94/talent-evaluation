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

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.0/purify.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<script>
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);
</script>