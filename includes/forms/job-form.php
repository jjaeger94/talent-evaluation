<form id="add-job-form" class="bootstrap-form" method="post">
    <div class="form-group">
        <label for="job-title">Stellenbezeichnung:</label>
        <input type="text" class="form-control" id="job-title" name="job_title" required>
    </div>
    <input type="submit" value="Stelle hinzufügen">
</form>

<!-- Fehlermeldung, falls vorhanden -->
<?php if ( isset( $_POST['job_title_error'] ) ) : ?>
    <p><?php echo $_POST['job_title_error']; ?></p>
<?php endif; ?>