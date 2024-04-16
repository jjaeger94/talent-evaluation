<form id="edit-user-data-form" method="post">
    <div class="form-group mb-3">
        <label for="first-name"><strong>Vorname</strong></label>
        <input type="text" class="form-control" id="first-name" name="first_name" value="<?php echo esc_attr(get_user_meta($user_id,'first_name', true)); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="last-name"><strong>Nachname</strong></label>
        <input type="text" class="form-control" id="last-name" name="last_name" value="<?php echo esc_attr(get_user_meta($user_id,'last_name', true)); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="company"><strong>Firma</strong></label>
        <input type="text" class="form-control" id="company" name="company" value="<?php echo esc_attr(get_user_meta($user_id,'company', true)); ?>" required>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Änderungen speichern</button>
</form>
<div id="message"></div>