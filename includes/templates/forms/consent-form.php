<div class="container mt-5">
    <form id="consent-form">
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="option1" name="option1">
            <label class="form-check-label" for="option1">Option 1</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="option2" name="option2">
            <label class="form-check-label" for="option2">Option 2</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="option3" name="option3">
            <label class="form-check-label" for="option3">Option 3</label>
        </div>
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