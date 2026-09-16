<?php
/**
 * Lead & purchase tracking: DB table, AJAX endpoints, and stat helpers.
 *
 * "Lead" = a WhatsApp button click anywhere on the site.
 * "Purchase" = a completed contact-form submission.
 *
 * @package SouthCity_CMS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Full table name for the leads/purchases log.
 */
function southcity_cms_leads_table() {
	global $wpdb;
	return $wpdb->prefix . 'southcity_leads';
}

/**
 * Create the leads table on plugin activation.
 */
function southcity_cms_install_leads_table() {
	global $wpdb;

	$table           = southcity_cms_leads_table();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		type VARCHAR(20) NOT NULL DEFAULT 'lead',
		name VARCHAR(191) NULL,
		phone VARCHAR(50) NULL,
		plot_size VARCHAR(100) NULL,
		message TEXT NULL,
		source_url VARCHAR(255) NULL,
		created_at DATETIME NOT NULL,
		PRIMARY KEY (id),
		KEY type (type),
		KEY created_at (created_at)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

/**
 * Hand the theme's front-end script the AJAX URL + nonce it needs to log events.
 */
function southcity_cms_localize_leads_config() {
	if ( ! wp_script_is( 'south-city-main', 'registered' ) && ! wp_script_is( 'south-city-main', 'enqueued' ) ) {
		return;
	}

	wp_localize_script( 'south-city-main', 'southCityLeads', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'southcity_cms_leads' ),
	] );
}
add_action( 'wp_enqueue_scripts', 'southcity_cms_localize_leads_config', 20 );

/**
 * Shared field sanitizer for both endpoints.
 */
function southcity_cms_sanitize_lead_fields() {
	return [
		'name'       => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
		'phone'      => isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '',
		'plot_size'  => isset( $_POST['plot_size'] ) ? sanitize_text_field( wp_unslash( $_POST['plot_size'] ) ) : '',
		'message'    => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
		'source_url' => isset( $_POST['source'] ) ? esc_url_raw( wp_unslash( $_POST['source'] ) ) : '',
	];
}

/**
 * Log a WhatsApp button click as a "lead".
 */
function southcity_cms_handle_log_lead() {
	// Non-blocking: a stale nonce (e.g. a cached page) should never swallow a real lead.
	check_ajax_referer( 'southcity_cms_leads', 'nonce', false );

	if ( ! empty( $_POST['botcheck'] ) ) {
		wp_send_json_success();
	}

	global $wpdb;
	$fields              = southcity_cms_sanitize_lead_fields();
	$fields['type']       = 'lead';
	$fields['created_at'] = current_time( 'mysql' );

	$wpdb->insert( southcity_cms_leads_table(), $fields );

	wp_send_json_success();
}
add_action( 'wp_ajax_sc_log_lead', 'southcity_cms_handle_log_lead' );
add_action( 'wp_ajax_nopriv_sc_log_lead', 'southcity_cms_handle_log_lead' );

/**
 * Log a completed contact-form submission as a "purchase".
 */
function southcity_cms_handle_log_purchase() {
	// Non-blocking: a stale nonce (e.g. a cached page) should never swallow a real lead.
	check_ajax_referer( 'southcity_cms_leads', 'nonce', false );

	if ( ! empty( $_POST['botcheck'] ) ) {
		wp_send_json_success();
		return;
	}

	$fields = southcity_cms_sanitize_lead_fields();

	if ( '' === $fields['name'] || '' === $fields['phone'] ) {
		wp_send_json_error( [ 'message' => 'Name and phone are required.' ], 400 );
		return;
	}

	global $wpdb;
	$fields['type']       = 'purchase';
	$fields['created_at'] = current_time( 'mysql' );

	$wpdb->insert( southcity_cms_leads_table(), $fields );

	wp_send_json_success();
}
add_action( 'wp_ajax_sc_log_purchase', 'southcity_cms_handle_log_purchase' );
add_action( 'wp_ajax_nopriv_sc_log_purchase', 'southcity_cms_handle_log_purchase' );

/**
 * Total lead (WhatsApp click) and purchase (form submission) counts.
 */
function southcity_cms_lead_counts() {
	global $wpdb;
	$table = southcity_cms_leads_table();

	return [
		'leads'     => (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE type = %s", 'lead' ) ),
		'purchases' => (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE type = %s", 'purchase' ) ),
	];
}

/**
 * Paginated list of purchase (form fill-up) rows, newest first.
 */
function southcity_cms_get_purchases( $per_page = 50, $paged = 1 ) {
	global $wpdb;
	$table  = southcity_cms_leads_table();
	$offset = max( 0, ( $paged - 1 ) * $per_page );

	return $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM {$table} WHERE type = %s ORDER BY created_at DESC LIMIT %d OFFSET %d",
		'purchase',
		$per_page,
		$offset
	) );
}

/**
 * Total number of purchase rows, for pagination.
 */
function southcity_cms_count_purchases() {
	global $wpdb;
	$table = southcity_cms_leads_table();

	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE type = %s", 'purchase' ) );
}

/**
 * Fetch a single purchase (lead) row by id.
 */
function southcity_cms_get_purchase( $id ) {
	global $wpdb;
	$table = southcity_cms_leads_table();

	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d AND type = %s", $id, 'purchase' ) );
}

/**
 * Update a purchase (lead) row.
 */
function southcity_cms_update_purchase( $id, $fields ) {
	global $wpdb;

	return $wpdb->update( southcity_cms_leads_table(), $fields, [ 'id' => $id, 'type' => 'purchase' ] );
}

/**
 * Delete a purchase (lead) row.
 */
function southcity_cms_delete_purchase( $id ) {
	global $wpdb;

	return $wpdb->delete( southcity_cms_leads_table(), [ 'id' => $id, 'type' => 'purchase' ] );
}
