<form id="edit-user-data-form" method="post">
    <div class="form-group mb-3">
        <label for="email"><strong>Email</strong></label>
        <input type="text" class="form-control" id="email" name="email" value="<?php echo esc_attr($user_info->user_email); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="subscribe_notifications" style="display: inline-block;"><strong>Informiere mich bei abgeschlossenen Prüfungen</strong></label>
        <input type="checkbox" id="subscribe_notifications" name="subscribe_notifications" value="1" <?php checked(get_user_meta($user_id, 'subscribe_notifications', true), '1'); ?> style="display: inline-block; vertical-align: middle; margin-left: 10px;">
    </div>
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
    <div class="form-group mb-3">
        <label for="subscription"><strong>Dein ausgewähltes Paket:</strong></label>
        <input type="text" class="form-control" id="subscription" name="subscription" value="<?php echo esc_attr(get_user_meta($user_id,'subscription', true)); ?>" readonly>
    </div>
    <button type="submit" class="btn btn-primary">Änderungen speichern</button>
</form>
<div id="message"></div>
