<form id="application-form" class="bootstrap-form" method="post" enctype="multipart/form-data">
    <div class="form-group mb-3">
        <label for="salutation">Anrede</label>
        <select class="form-control" id="salutation" name="salutation" required>
            <option value="0">Nicht angegeben</option>
            <option value="1">Herr</option>
            <option value="2">Frau</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="prename">Vorname</label>
        <input type="text" class="form-control" id="prename" name="prename" required>
    </div>
    <div class="form-group mb-3">
        <label for="surname">Nachname</label>
        <input type="text" class="form-control" id="surname" name="surname" required>
    </div>
    <div class="form-group mb-3">
        <label for="email">E-Mail-Adresse</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group mb-3">
        <label for="job_id"><strong class="mr-2">Stelle</strong></label><?php echo info_button('application_form_job'); ?>
        <select class="form-control" id="job_id" name="job_id">
            <?php foreach ($jobs as $job) : ?>
                <option value="<?php echo esc_attr($job->ID); ?>"><?php echo esc_html($job->job_title); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="resume"><strong class="mr-2">Berwerbungsunterlagen</strong></label><?php echo info_button('application_form_docs'); ?>
        <input class="form-control" type="file" name="resumes[]" id="resume" multiple accept="application/pdf" required>
    </div>
    <button type="submit" class="btn btn-primary">Kandidat erstellen</button>
</form>

<div id="message"></div>