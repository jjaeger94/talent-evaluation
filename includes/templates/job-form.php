<form id="add-job-form" class="bootstrap-form" method="post">
    <div class="form-group mb-3">
        <label for="job-title">Stellenbezeichnung:</label>
        <input type="text" class="form-control" id="job-title" name="job_title" required>
    </div>
    <div class="form-group mb-3">
        <label for="job-title">Standort:</label>
        <input type="text" class="form-control" id="location" name="location">
    </div>
    <div class="form-group mb-3">
        <label for="criteria1">Kriterien zur Vorauswahl:</label>
        <input type="text" class="form-control" id="criteria1" name="criteria1">
        <input type="text" class="form-control mt-2" id="criteria2" name="criteria2">
        <input type="text" class="form-control mt-2" id="criteria3" name="criteria3">
    </div>
    <div class="form-group mb-3">
        <label>Vollständigkeits Check</label>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="completeness1" name="completeness1" value="0">
            <label class="form-check-label" for="completeness1">Zeugnisse auf Vollständigkeit prüfen</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="completeness2" name="completeness2" value="0">
            <label class="form-check-label" for="completeness2">Arbeitszeugnisse auf Vollständigkeit prüfen</label>
        </div>
    </div>
    <div class="form-group mb-3">
        <label>Referenz Check</label>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="reference1" name="reference1" value="0">
            <label class="form-check-label" for="reference1">LinkedIn checken</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="reference2" name="reference2" value="0">
            <label class="form-check-label" for="reference2">Höchstes Bildungszeugnis prüfen</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="reference3" name="reference3" value="0">
            <label class="form-check-label" for="reference3">Arbeitszeugnis prüfen</label>
        </div>
    </div>
    <input type="submit" value="Stelle hinzufügen" class="btn btn-primary">
</form>

<!-- Container für Fehler- oder Erfolgsmeldungen -->
<div id="form-message" class="mt-3"></div>
