<p>Bitte vergebe ein Passwort um deinen Account zu aktivieren. </p>
<p>Anschließend kannst du dich einloggen und dein Profil vervollständigen.</p>
<form id="swpm-registration-form" class="swpm-form" name="swpm-registration-form" method="post" action="">
    <input type="hidden" name="level_identifier" value="<?php echo esc_attr($level); ?>" />
    <input type="hidden" name="user_name" id="user_name" value="<?php echo esc_attr($email); ?>" />
    <input type="hidden" id="email" class="form-control" value="<?php echo esc_attr($email); ?>" name="email" required/>

    <div class="mb-3">
        <label for="email_copy" class="form-label"><?php _e('Email', "simple-membership") ?></label>
        <input disabled type="email" id="email_copy" class="form-control" value="<?php echo esc_attr($email); ?>" name="email_copy" required/>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label"><?php _e('Password', "simple-membership") ?></label>
        <input type="password" id="password" class="form-control" name="password" required />
    </div>

    <div class="mb-3">
        <label for="password_re" class="form-label"><?php _e('Repeat Password', "simple-membership") ?></label>
        <input type="password" id="password_re" class="form-control" name="password_re" required />
    </div>

    <input type="hidden" name="first_name" value="<?php echo esc_attr($first_name); ?>" />
    <input type="hidden" name="last_name" value="<?php echo esc_attr($last_name); ?>" />
    <input type="hidden" name="swpm_membership_level" value="<?php echo esc_attr($membership_level); ?>" />
    <input type="hidden" name="swpm_level_hash" value="<?php echo esc_attr(md5(get_option('swpm_private_key_one') . '|' . $membership_level)); ?>" />

    <div class="text-center">
        <button type="submit" class="btn btn-primary"><?php _e('Register', "simple-membership") ?></button>
        <input type="hidden" name="swpm_registration_submit" value="Register">
    </div>
</form>