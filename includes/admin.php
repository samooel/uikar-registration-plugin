<?php

/**
 * Register a custom menu page.
 */
function uikar_add_custom_page() {
    add_submenu_page(
            'options-general.php',__('تنظیمات ثبت نام', 'uikar-registration'), __('ویرایش اطلاعات ثبت نام', 'uikar-registration'), 'manage_options', 'uikar', 'uikar_custom_menu', plugins_url('uikar-form-builder/assets/img/icon.png'), 70
    );
}

add_action('admin_menu', 'uikar_add_custom_page');


function uirg_addRegisterPage() {
    // Gather post data.
    $my_page = array(
        'post_title' => __('ثبت نام', 'uikar-registration'),
        'post_content' => '[cr_custom_registration]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );
    // Insert the post into the database.
    wp_insert_post($my_page);
    // Gather post data.
    $landingPage = array(
        'post_title' => __('خوش امدید', 'uikar-registration'),
        'post_content' => '[land_page]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );

    // Insert the post into the database.
    $landing = wp_insert_post($landingPage);
    add_option( 'landing_page_id', $landing, '', 'yes' );
    
    $loginPage = array(
        'post_title' => __('ورود', 'uikar-registration'),
        'post_content' => '[login_page]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );

    // Insert the post into the database.
    $landing = wp_insert_post($loginPage);
}
/**
 * Display a custom menu page
 */
function uikar_custom_menu() {
    ?>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('محتوای ایمیل ارسالی', 'uikar-registration'); ?></th>
                <td><input type="text" name="email_content" value="<?php echo get_option('email_content'); ?>" size="50" /></td>
            </tr>

        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="email_content" />
        <div class="submit">
            <input type="submit" class="button-primary" value="<?php _e('ذخیره تغییرات', 'uikar-registration') ?>" />
        </div>
    </form>
    <?php
}
