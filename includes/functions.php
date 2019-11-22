<?php
@ob_start();
@session_start();//custom_registration_function
function uirg_registration() {
    wp_enqueue_script( 'password-strength-meter' );
    if (isset($_POST['submit'])) {
        if ((isset($_POST["micro_captcha"]) && $_POST["micro_captcha"] != "" && $_SESSION["code"] == $_POST["micro_captcha"])) {
            // sanitize user form input
            global $username, $repassword, $password, $email, $first_name, $last_name, $tel, $mobile;
            $username = sanitize_user($_POST['username']);
            $password = $_POST['password'];
            $repassword = $_POST['repassword'];
            $email = sanitize_email($_POST['email']);
            $website = esc_url($_POST['website']);
            $first_name = sanitize_text_field($_POST['fname']);
            $last_name = sanitize_text_field($_POST['lname']);
            $tel = sanitize_text_field($_POST['tel']);
            $mobile = sanitize_text_field($_POST['mobile']);
            $nonceField = $_POST['cosmetic_register_nonce_field'];
            $validity = sanitize_text_field($_POST['validity']);
            uirg_registration_validation($username, $repassword, $password, $email, $first_name, $last_name, $tel, $mobile, $validity, $website, $nonceField);
        } else {
            echo("<div class='alert alert-danger'><p>".__('Please Enter correct security code', 'uikar-registration')."</p></div>");
            uirg_registration_form();
        }
    } else {

        uirg_registration_form();
    }
}

function uirg_registration_form() {

    if (is_user_logged_in()) {
        wp_redirect(home_url());
    } else {
        require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/form.php');
    }
}

// $username, $password, $repassword, $email, $website, $first_name, $last_name, $tel, $mobile, $validity



function uirg_registration_validation($username, $repassword, $password, $email, $first_name, $last_name, $tel, $mobile, $validity, $website, $nonceField) {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if( ! isset($nonceField) || ! wp_verify_nonce( $_POST['cosmetic_register_nonce_field'], 'register_action' ))
    {
        $reg_errors->add('field', __('Sorry, Beacuse of the security reason, we cant let you in. please try again or contact the administrator', 'uikar-registration'));
    }
    if (empty($password)) {
        $reg_errors->add('field', __('لطفا پسورد خود را وارد نمایید', 'uikar-registration'));
    }
    if (empty($email)) {
        $reg_errors->add('field', __('لطفا ایمیل خود را وارد نمایید', 'uikar-registration'));
    }
    if (empty($username)) {
        $reg_errors->add('field', __('لطفا نام کاربری خود را وارد نمایید', 'uikar-registration'));
    }
    if (username_exists($username)) {
        $reg_errors->add('user_name', __('این نام کاربری قبلا ثبت شده لطفا با نام کاربری دیگری ثبت نام کنید!', 'uikar-registration'));
    }
    if (!validate_username($username)) {
        $reg_errors->add('username_invalid', __('نام کاربری شما نامعتبر است', 'uikar-registration'));
    }

    if (5 > strlen($password)) {
        $reg_errors->add('password', __('تعداد حروف پسورد باید بیش از 5 حرف باشد', 'uikar-registration'));
    }
    if ($password != $repassword) {
        $reg_errors->add('password', __('کلمه عبور شما یکسان نیستند', 'uikar-registration'));
    }

    if (!is_email($email)) {
        $reg_errors->add('email_invalid', __('ایمیل معتبر نیست', 'uikar-registration'));
    }
    if (email_exists($email)) {
        $reg_errors->add('email', __('این ایمیل قبلا ثبت شده است', 'uikar-registration'));
    }
    if (is_wp_error($reg_errors)) {
        $errorCount = 0;
        foreach ($reg_errors->get_error_messages() as $error) {    
            echo '<div class="alert alert-danger">';
            echo '<strong>' . __('خطا', 'uikar-registration') . '</strong>:';
            echo $error . '<br/>';
            echo '</div>';
            $errorCount++;
        }
        uirg_complete_registration($username, $password, $email, $first_name, $last_name, $tel, $mobile, $validity, $website);
        //registration_form();
    } 
}

function uirg_complete_registration($username, $password, $email, $first_name, $last_name, $tel, $mobile, $validity, $website) {
    global $reg_errors;
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
            'user_url' => $website,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'role' => 'pending'
        );
        $validity = sanitize_text_field($validity);
        $user = wp_insert_user($userdata);
        add_user_meta($user, 'validity_duration', sanitize_text_field($validity));
        add_user_meta($user, 'tel', $tel);
        add_user_meta($user, 'Mobile', $mobile);
        $nonce = wp_create_nonce( 'register-nonce' );
        $landingPageID = get_option('landing_page_id');
        if ($user && !is_wp_error($user)) {
            $code = sha1($user . time());
            $activation_link = add_query_arg(array('key' => $code, 'usrkey' => $user, '_wpnonce' => $nonce), get_permalink($landingPageID));
            add_user_meta($user, 'has_to_be_activated', $code, true);
            uirg_mailFunction($username, $email, $activation_link);
        }
        
        uirg_successRegistrationPrint($username, $email, $website);

    } else {
        uirg_registration_form();
    }
}

function uirg_mailFunction($username, $email, $activation_link) {
    $to = $email;
    $subject = __('ثبت نام در ', 'uikar-registration') . bloginfo('name');
    $mailContent = get_option('email_content'); 
    if($mailContent)
    {
        $mailer = $mailContent;
    }
    else
    {
        $mailer =  __('شما با موفقیت اکانت خود را تایید کردید و عضو سایت شدید.', 'uikar-registration');
    }
    $body = '<div style="font:12px tahoma;width:300px;margin:0 auto;border:1px solid #ebebeb;padding: 19px;border-radius: 5px;box-shadow: 0 1px 2px #cecece;">
                    <p style="margin-top: 15px;">' .esc_html($mailer). '</p>
                    <p>' . __('مشخصات اکانت شما به شرح زیر است', 'uikar-registration') . '</p>
                    <ul style="padding-left:0;padding-right:30px;list-style:none; margin-top:10px; margin-bottom:10px">
                        <li>' . __('نام کاربری', 'uikar-registration') . ' : ' . esc_html($username) . '</li>
                        <li>' . __('لینک فعال سازی', 'uikar-registration') . ' : <a href="'. esc_url($activation_link) . '">' . __('فعال سازی', 'uikar-registration') . '</a></li>
                    </ul>
                    <p>' . __('با تشکر', 'uikar-registration') . '</p>
             </div>';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);
}

function uirg_successRegistrationPrint($username, $email, $website) {
    require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/links.php');
    echo '<div class="alert alert-success">
            <h4 class="alert-heading">'.__('ازاینکه با حوصله اطلاعات را تکمیل کردید از شما سپاسگذاریم', 'uikar-registration').'</h4>
            </div>'
          . '<p>' . __(' لطفا برای فعال سازی نام کاربری روی لینکی که به ایمیلتان ارسال شده کلیک کنید', 'uikar-registration') . '</p>
            <p>'.__('در صورتی که ایمیل در هرز نامه رفته لطفا ابتدا آن را از هرز نامه خارج کرده سپس روی لیکن فعال سازی کلیک نمایید', 'uikar-registration').'</p>
            <p>'.__('نام کاربری', 'uikar-registration').': ' . esc_html($username) . '</p>
            <p>'.__('ایمیل', 'uikar-registration').' : ' . esc_html($email) . '</p>';
}

add_action( 'init', 'uirg_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function uirg_load_textdomain() {
  load_plugin_textdomain( 'uikar-registration', false, plugin_dir_path( __FILE__ ).'/languages' ); 
}


//* Add filter to the authenticate hook
add_filter( 'authenticate', 'uirg_authenticate', 20, 3 );
function uirg_authenticate( $user, $username, $password ) {
  //* Check if the user has the pending role
  if( ! is_wp_error( $user ) && in_array( 'pending', $user->roles ) ) {
    //* Throw an error
    $error = new WP_Error();
    $errorMessage = __( 'Please Activate Your Account.' );
    $error->add( 401, $errorMessage );
    return $error;
  }
  //* Or return the user
  return $user;
}


$result = add_role( 'pending', __('Pending' ),
        array(
            'read' => false, // true allows this capability
            'edit_posts' => false, // Allows user to edit their own posts
            'edit_pages' => false, // Allows user to edit pages
            'edit_others_posts' => false, // Allows user to edit others posts not just their own
            'create_posts' => false, // Allows user to create new posts
            'manage_categories' => false, // Allows user to manage post categories
            'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
            'edit_themes' => false, // false denies this capability. User can’t edit your theme
            'install_plugins' => false, // User cant add new plugins
            'update_plugin' => false, // User can’t update any plugins
            'update_core' => false // user cant perform core updates
        )
);
