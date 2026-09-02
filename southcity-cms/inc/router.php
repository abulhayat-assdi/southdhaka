<?php
/**
 * Routing logic for the /manage endpoint.
 *
 * @package SouthCity_CMS
 */

defined( 'ABSPATH' ) || exit;

/**
 * The base path segment for the CMS.
 */
function southcity_cms_base() {
	return 'manage';
}

/**
 * Get a URL inside the CMS.
 */
function southcity_cms_url( $path = '' ) {
	return home_url( '/' . southcity_cms_base() . '/' . ltrim( $path, '/' ) );
}

/**
 * Get the current request path.
 */
function southcity_cms_current_path() {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	$home = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );

	if ( $home && '/' !== $home && 0 === strpos( $path, $home ) ) {
		$path = substr( $path, strlen( $home ) );
	}

	return trim( rawurldecode( $path ), '/' );
}

/**
 * Check if the current request is for the CMS.
 */
function southcity_cms_is_request() {
	$base = southcity_cms_base();
	$path = southcity_cms_current_path();

	return $path === $base || 0 === strpos( $path, $base . '/' );
}

/**
 * Route the request.
 */
function southcity_cms_route() {
	if ( ! southcity_cms_is_request() ) {
		return;
	}

	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow', true );

	southcity_cms_handle_logout();
	$login_error = southcity_cms_handle_login();

	if ( ! is_user_logged_in() ) {
		southcity_cms_render_login( $login_error );
		exit;
	}

	// Make sure only admins can access this dashboard.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to access this page.' );
	}

	southcity_cms_render_app();
	exit;
}
add_action( 'init', 'southcity_cms_route', 20 );
