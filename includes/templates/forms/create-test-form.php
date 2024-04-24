<form id="create-test-form">
    <div class="mb-3">
        <label for="test-title" class="form-label">Titel:</label>
        <input type="text" class="form-control" id="test-title" name="test_title" required>
    </div>
    <div class="mb-3">
        <label for="affiliate-link" class="form-label">Affiliate-Link:</label>
        <input type="url" class="form-control" id="affiliate-link" name="affiliate_link" required>
    </div>
    <div class="mb-3">
        <label for="image-link" class="form-label">Bild-Link:</label>
        <input type="url" class="form-control" id="image-link" name="image_link" required>
    </div>
    <button type="submit" class="btn btn-primary">Test erstellen</button>
</form>
<!-- Container für Fehler- oder Erfolgsmeldungen -->
<div id="form-message" class="mt-3"></div>