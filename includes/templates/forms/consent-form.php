<div class="container mt-5">
    <form id="consent-form">
        <p id="consent-text">Hiermit gebe Ich, <?php echo $application->prename . ' ' . $application->surname; ?>, der Firma Commit IQ mein Einverständnis dazu (bitte ankreuzen):</p>
        <?php if ($job->screening & 1): ?>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="linkedIn_check" name="linkedIn_check">
            <label class="form-check-label" for="linkedIn_check">Mein LinkedIn-Profil zu überprüfen</label>
        </div>
        <?php endif; ?>
        <?php if ($job->screening & 4): ?>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="work_reference_check" name="work_reference_check">
            <label class="form-check-label" for="work_reference_check">Meine alten Arbeitgeber zu kontaktieren</label>
        </div>
        <?php endif; ?>
        <div class="mb-3">
            <p>Hier unterschreiben:</p>
            <canvas id="signature-pad" class="border" width="500" height="200"></canvas>
        </div>
    </form>
    <div class="d-flex justify-content-between">
        <button type="button" id="clear-signature" class="btn btn-danger mt-2">Unterschrift zurücksetzen</button>
        <button type="button" id="save-consent" class="btn btn-primary">PDF speichern</button>
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