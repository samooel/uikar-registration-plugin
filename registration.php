<?php 
/*
 * Plugin Name:  wp user registration
 * Plugin URI: http://uikar.com
 * Description: Wordpress User Registration and login form
 * Version: 2.0
 * Author: Saman Tohidian
 * Author URI: http://uikar.com
 * Text Domain: uikar-registration
 * Domain Path: /languages/
 *
 */
define('UIKAR_REGISTER_BUILDER_DIR', plugin_dir_path(__FILE__));
define('UIKAR_REGISTER_BUILDER_URL', plugin_dir_url(__FILE__));

require_once(UIKAR_REGISTER_BUILDER_DIR.'includes/functions.php');

register_activation_hook(__FILE__, 'uirg_builder_activation');
//register_deactivation_hook(__FILE__, 'uikar_form_builder_deactivation');
 
function uirg_Builder_load() {

    if (is_admin()) { //load admin files only in admin
        require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/admin.php');
    }
}

uirg_Builder_load();

add_action('plugins_loaded', 'uikar_load_textdomain');
function uikar_load_textdomain() {
	load_plugin_textdomain( 'uikar-registration', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'cr_custom_registration', 'uirg_shortcode' );
// Register a new shortcode: [land-page]
add_shortcode( 'land_page', 'uirg_landing_page' );
// Register a new shortcode: [login-page]
add_shortcode( 'login_page', 'uirg_login_page' );

// The callback function that will replace [book]
function uirg_shortcode() {
    ob_start();
    uirg_registration();
    return ob_get_clean();
}
function uirg_landing_page()
{
    require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/landing.php');
}
function uirg_login_page()
{
    require_once(UIKAR_REGISTER_BUILDER_DIR . 'includes/login.php');
}
function uirg_builder_activation() {
    uirg_addRegisterPage();
}



?>