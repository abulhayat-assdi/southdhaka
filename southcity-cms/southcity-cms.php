<?php
/**
 * Plugin Name:       South City CMS
 * Plugin URI:        https://southdhaka.com/
 * Description:       A custom dashboard interface for South City, so day-to-day work happens outside wp-admin.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            South Dhaka
 * Text Domain:       southcity-cms
 *
 * @package SouthCity_CMS
 */

defined( 'ABSPATH' ) || exit;

define( 'SOUTHCITY_CMS_VERSION', '1.0.0' );
define( 'SOUTHCITY_CMS_FILE', __FILE__ );
define( 'SOUTHCITY_CMS_DIR', plugin_dir_path( __FILE__ ) );
define( 'SOUTHCITY_CMS_URL', plugin_dir_url( __FILE__ ) );

require_once SOUTHCITY_CMS_DIR . 'inc/router.php';
require_once SOUTHCITY_CMS_DIR . 'inc/auth.php';
require_once SOUTHCITY_CMS_DIR . 'inc/app.php';

/**
 * Initialize ACF form head before any output
 */
function southcity_cms_init_acf() {
	if ( ! function_exists( 'acf_form_head' ) ) {
		return;
	}
	
	// We only need acf_form_head if we are on the /manage route
	if ( southcity_cms_is_request() && is_user_logged_in() ) {
		acf_form_head();
	}
}
add_action( 'init', 'southcity_cms_init_acf', 15 );
