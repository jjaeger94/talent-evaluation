<form id="candidate-form" class="bootstrap-form" method="post">
    <div class="form-group">
        <label for="job_id">Stelle</label>
        <select class="form-control" id="job_id" name="job_id">
            <?php foreach ($jobs as $job) : ?>
                <option value="<?php echo esc_attr($job->ID); ?>"><?php echo esc_html($job->job_title); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="prename">Vorname</label>
        <input type="text" class="form-control" id="prename" name="prename" required>
    </div>
    <div class="form-group">
        <label for="surname">Nachname</label>
        <input type="text" class="form-control" id="surname" name="surname" required>
    </div>
    <div class="form-group">
        <label for="email">E-Mail-Adresse</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="salutation">Anrede</label>
        <select class="form-control" id="salutation" name="salutation" required>
            <option value="1">Herr</option>
            <option value="2">Frau</option>
        </select>
    </div>
    <div class="form-group">
        <label for="classification">Klassifizierung</label>
        <input type="text" class="form-control" id="classification" name="classification" required>
    </div>
    <button type="submit" class="btn btn-primary">Kandidat erstellen</button>
</form>

<div id="message"></div>