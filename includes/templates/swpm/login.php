<?php
// Get the settings and auth objects
$auth = SwpmAuth::get_instance();
$setting = SwpmSettings::get_instance();
$password_reset_url = $setting->get_value('reset-page-url');
$join_url = $setting->get_value('join-us-page-url');

// Filter that allows changing of the default value of the username label on login form.
$label_username_or_email = __('Username or Email', 'simple-membership');
$swpm_username_label = apply_filters('swpm_login_form_set_username_label', $label_username_or_email);

// Check if password toggle is enabled
$display_password_toggle = $setting->get_value('password-visibility-login-form');
$display_password_toggle = !empty($display_password_toggle);

// CSS class for the login submit button
$login_submit_class = 'swpm-login-form-submit';
if ($setting->get_value('use-new-form-ui')) {
    $login_submit_class .= ' swpm-submit-btn-default-style';
}
?>
<div class="swpm-login-widget-form">
<form id="swpm-login-form" name="swpm-login-form" method="post" action="" class="container mt-5">
    <div class="swpm-login-form-inner row">
        <div class="mb-3 col-12">
            <label for="swpm_user_name" class="form-label"><?php echo $swpm_username_label; ?></label>
            <input type="text" class="form-control" id="swpm_user_name" name="swpm_user_name" value="" size="25" />
        </div>
        <div class="mb-3 col-12">
            <label for="swpm_password" class="form-label"><?php _e('Password', 'simple-membership'); ?></label>
            <input type="password" class="form-control" id="swpm_password" name="swpm_password" value="" size="25" />
        </div>
        <?php if ($display_password_toggle) { ?>
            <div class="mb-3 col-12 form-check">
                <input type="checkbox" class="form-check-input" id="swpm-password-toggle-checkbox" name="swpm-password-toggle-checkbox" data-state="password-hidden">
                <label class="form-check-label" for="swpm-password-toggle-checkbox"><?php _e('Show password', 'simple-membership'); ?></label>
            </div>
        <?php } ?>
        <div class="mb-3 col-12 form-check">
            <input type="checkbox" class="form-check-input" id="swpm-rememberme" name="rememberme">
            <label class="form-check-label" for="swpm-rememberme"><?php _e('Remember Me', 'simple-membership'); ?></label>
        </div>
        <div class="mb-3 col-12">
            <?php echo apply_filters('swpm_before_login_form_submit_button', ''); ?>
        </div>
        <div class="mb-3 col-12">
            <input type="submit" class="btn btn-primary <?php echo esc_attr($login_submit_class); ?>" name="swpm-login" value="<?php _e('Log In', 'simple-membership'); ?>" />
        </div>
        <div class="mb-3 col-12">
            <a id="forgot_pass" class="link-secondary" href="<?php echo esc_url($password_reset_url); ?>"><?php _e('Forgot Password?', 'simple-membership'); ?></a>
        </div>
        <div class="mb-3 col-12">
            <span class="text-danger"><?php echo apply_filters('swpm_login_form_action_msg', $auth->get_message()); ?></span>
        </div>
    </div>
</form>
</div>