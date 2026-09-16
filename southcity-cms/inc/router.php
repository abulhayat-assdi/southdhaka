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

	southcity_cms_handle_cpt_delete();
	southcity_cms_handle_lead_delete();
	southcity_cms_handle_lead_save();
	southcity_cms_render_app();
	exit;
}
add_action( 'init', 'southcity_cms_route', 20 );

/**
 * Handle a request to delete a lead (form submission) row from the CMS list view.
 */
function southcity_cms_handle_lead_delete() {
	if ( ! isset( $_GET['sc_action'] ) || 'delete_lead' !== $_GET['sc_action'] ) {
		return;
	}

	$lead_id = isset( $_GET['lead_id'] ) ? intval( wp_unslash( $_GET['lead_id'] ) ) : 0;

	if ( ! $lead_id ) {
		return;
	}

	$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'sc_delete_lead_' . $lead_id ) ) {
		wp_die( 'Security check failed. Please go back and try again.' );
	}

	southcity_cms_delete_purchase( $lead_id );

	wp_safe_redirect( add_query_arg( [ 'view' => 'leads', 'deleted' => 'true' ], southcity_cms_url() ) );
	exit;
}

/**
 * Handle saving edits to a lead (form submission) row from the CMS edit form.
 */
function southcity_cms_handle_lead_save() {
	if ( ! isset( $_POST['sc_lead_save'] ) ) {
		return;
	}

	$lead_id = isset( $_POST['lead_id'] ) ? intval( wp_unslash( $_POST['lead_id'] ) ) : 0;

	if ( ! $lead_id ) {
		return;
	}

	check_admin_referer( 'sc_save_lead_' . $lead_id, 'sc_lead_nonce' );

	southcity_cms_update_purchase( $lead_id, [
		'name'      => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ),
		'phone'     => sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) ),
		'plot_size' => sanitize_text_field( wp_unslash( $_POST['plot_size'] ?? '' ) ),
		'message'   => sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) ),
	] );

	wp_safe_redirect( add_query_arg( [ 'view' => 'leads', 'updated' => 'true' ], southcity_cms_url() ) );
	exit;
}

/**
 * Handle a request to delete (trash) a South City CPT item from the CMS list view.
 */
function southcity_cms_handle_cpt_delete() {
	if ( ! isset( $_GET['sc_action'] ) || 'delete_post' !== $_GET['sc_action'] ) {
		return;
	}

	$post_id = isset( $_GET['post_id'] ) ? intval( wp_unslash( $_GET['post_id'] ) ) : 0;

	if ( ! $post_id ) {
		return;
	}

	$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'sc_delete_post_' . $post_id ) ) {
		wp_die( 'Security check failed. Please go back and try again.' );
	}

	if ( ! current_user_can( 'delete_post', $post_id ) ) {
		wp_die( 'You do not have permission to delete this item.' );
	}

	$post_type = get_post_type( $post_id );

	// Only allow deleting South City content types from this screen.
	if ( ! $post_type || 0 !== strpos( $post_type, 'southcity_' ) ) {
		wp_die( 'This item cannot be deleted from here.' );
	}

	wp_trash_post( $post_id );

	wp_safe_redirect( add_query_arg( [ 'view' => $post_type, 'deleted' => 'true' ], southcity_cms_url() ) );
	exit;
}
