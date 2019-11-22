<?php 

if(!is_user_logged_in())
{
    require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/links.php');
    wp_enqueue_script( 'password-strength-meter' );?>


<div class="register-home clearfix">

    <form action="<?php echo ($_SERVER['REQUEST_URI']); ?>" method="post">
        <div class="register-form-holder clearfix">
            <div class="form-group">
                <label for="firstname"><?php _e('نام', 'uikar-registration'); ?></label>
                <input type="text"  class="form-control" name="fname" value="<?php echo( isset($_POST['fname']) ? $first_name : null ); ?>">
            </div>
            <div class="form-group">
                <label for="website"><?php _e('نام خانوادگی', 'uikar-registration'); ?></label>
                <input type="text"  class="form-control" name="lname" value="<?php echo( isset($_POST['lname']) ? $last_name : null ) ?>">
            </div>
            <div class="form-group">
                <label for="nickname"><?php _e('شماره تلفن', 'uikar-registration'); ?></label>
                <input class="form-control ltr" type="text" placeholder="<?php _e('لطفا شماره اصلیتان را وارد کنید', 'uikar-registration'); ?>" name="tel" value="<?php echo ( isset($_POST['tel']) ? $tel : null ); ?>">
            </div>
            <div class="form-group">
                <label for="nickname"><?php _e('تلفن همراه', 'uikar-registration'); ?></label>
                <input class="form-control ltr" type="text" placeholder="<?php _e('لطفا شماره همراه اصلیتان را وارد کنید', 'uikar-registration'); ?>" name="mobile" value="<?php echo( isset($_POST['mobile']) ? $mobile : null ); ?>">
            </div>
            <div class="form-group">
                <label for="validity"><?php _e('تخصص', 'uikar-registration'); ?></label>
                <input type="text" class="form-control" name="validity" value="<?php echo ( isset($_POST['validity']) ? $validity : null ); ?>">
            </div>
            <div class="form-group">
                <label for="email"><?php _e('ایمیل', 'uikar-registration'); ?> <strong>*</strong></label>
                <input class="form-control ltr" type="text" placeholder="<?php _e('برای دریافت لینک فعال سازی لطفا ایمیل اصلی خود را وارد کنید', 'uikar-registration'); ?>" name="email" value="<?php echo ( isset($_POST['email']) ? $email : null ); ?>">
            </div>
            <div class="form-group">
                <label for="username"><?php _e('نام کاربری', 'uikar-registration'); ?><strong>*</strong></label>
                <input class="form-control ltr" type="text" name="username" value="<?php echo ( isset($_POST['username']) ? $username : null ); ?>">
            </div>
            <div class="form-group">
                <label for="password"><?php _e('کلمه عبور', 'uikar-registration'); ?><strong>*</strong></label>
                <input type="password" class="form-control" name="password" value="<?php echo( isset($_POST['password']) ? $password : null ); ?>">
            </div>
            <div class="form-group">
                <label for="repassword"><?php _e('تکرار کلمه عبور', 'uikar-registration'); ?><strong>*</strong></label>
                <input type="password" class="form-control" name="repassword" value="<?php echo ( isset($_POST['repassword']) ? $repassword : null ) ?>">
                <div class="strength-pass clearfix">
                    <span id="password-strength" class="stpass"><?php _e('امنیت گذرواژه', 'uikar-registration');?></span>
                    <span class="progress-meter"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1">
                    <label for="exampleInputEmail1"><?php _e('کد امنیتی', 'uikar-registration'); ?></label>
                    <img src="<?php echo((plugin_dir_url(__FILE__)) . '/captcha.php'); ?>"/>
                </div>
                <div class="col-sm-6">
                    <label for="exampleInputEmail1"></label>
                    <input type="text" class="form-control" name="micro_captcha" required class="wpcf7-form-control form-control" placeholder="<?php _e('کد امنیتی را وارد نمایید', 'uikar-registration'); ?>" />
                </div>
            </div>
            <div class="field-box clearfix inline-elements button-container">
                <ul>
                    <li>
                        <?php wp_nonce_field( 'register_action', 'cosmetic_register_nonce_field' );?>
                        <button type="submit" class="field-btn" name="submit" value="<?php _e('ثبت نام', 'uikar-registration'); ?>"><?php _e('ثبت نام', 'uikar-registration'); ?></button>
                    </li>
                    <li>
                        <button type="reset" class="field-btn clear" name="reset" value="<?php _e('پاک کردن', 'uikar-registration'); ?>"><?php _e('پاک کردن', 'uikar-registration'); ?></button>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>
<?php }?>