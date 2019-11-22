
<?php
require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/links.php');
?>
<div class="login-wrapper clearfix">

    <form name="loginform" id="loginform" action="<?php echo(home_url()); ?>/wp-login.php" method="post">

        <div class="form-group">
            <label for="user_login"><?php _e('نام کاربری'); ?></label>
            <input name="log" id="user_login" class="form-control" value="" size="20" type="text">
        </div>
        <div class="form-group">
            <label for="user_pass"><?php _e('رمز عبور') ?></label>
            <input name="pwd" id="user_pass" class="form-control" value="" size="20" type="password">
        </div>
        <div class="form-group"><label><input name="rememberme" id="rememberme" value="forever" type="checkbox"> <?php _e('مرا به خاطر بسپار', 'uikar-registration'); ?></label></div>

        <div class="form-group">
            <input name="wp-submit" id="wp-submit" class="btn btn-success" value="<?php _e('ورود'); ?>" type="submit">
            <input name="redirect_to" value="<?php echo(home_url()); ?>" type="hidden">
        </div>

    </form>
</div>