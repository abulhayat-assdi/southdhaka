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
 * Best-effort client IP. Cloudflare sends the real visitor IP in CF-Connecting-IP.
 */
function southcity_cms_client_ip() {
	foreach ( [ 'HTTP_CF_CONNECTING_IP', 'REMOTE_ADDR' ] as $key ) {
		if ( empty( $_SERVER[ $key ] ) ) {
			continue;
		}

		$ip = filter_var( trim( wp_unslash( $_SERVER[ $key ] ) ), FILTER_VALIDATE_IP );

		if ( $ip ) {
			return $ip;
		}
	}

	return '0.0.0.0';
}

/**
 * Fixed-window rate limit per visitor IP. Returns true when the caller is over the limit.
 *
 * Limits are deliberately generous: mobile carriers in Bangladesh put many
 * subscribers behind one shared IP, so a strict limit would block real customers.
 */
function southcity_cms_rate_limited( $bucket, $max, $window ) {
	$key   = 'sc_rl_' . $bucket . '_' . md5( southcity_cms_client_ip() );
	$now   = time();
	$state = get_transient( $key );

	if ( ! is_array( $state ) || empty( $state['exp'] ) || $state['exp'] <= $now ) {
		$state = [ 'n' => 0, 'exp' => $now + $window ];
	}

	if ( $state['n'] >= $max ) {
		return true;
	}

	$state['n']++;
	set_transient( $key, $state, max( 1, $state['exp'] - $now ) );

	return false;
}

/**
 * Only accept submissions that claim to come from this site.
 */
function southcity_cms_source_is_local( $url ) {
	$host = wp_parse_url( (string) $url, PHP_URL_HOST );
	$home = wp_parse_url( home_url(), PHP_URL_HOST );

	if ( ! $host || ! $home ) {
		return false;
	}

	$strip = static function ( $value ) {
		return preg_replace( '/^www\./i', '', strtolower( $value ) );
	};

	return $strip( $host ) === $strip( $home );
}

/**
 * Convert Bangla digits to ASCII and strip spacing characters from a phone number.
 */
function southcity_cms_normalize_phone( $raw ) {
	$raw = strtr( (string) $raw, [
		'০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4',
		'৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9',
	] );

	return preg_replace( '/[\s\-().]+/', '', $raw );
}

/**
 * Bangladeshi mobile number: 01XXXXXXXXX, +8801XXXXXXXXX or 8801XXXXXXXXX.
 */
function southcity_cms_is_valid_bd_phone( $phone ) {
	return (bool) preg_match( '/^(?:\+?880|0)1[3-9]\d{8}$/', $phone );
}

/**
 * Turnstile keys always look like "0x4AAAAAAA..." (test keys "1x000..."). Anything
 * else, such as text a browser auto-filled by mistake, counts as "not configured".
 */
function southcity_cms_is_turnstile_key( $key ) {
	return 1 === preg_match( '/^[0-9]x[A-Za-z0-9_-]{16,}$/', trim( (string) $key ) );
}

/**
 * Cloudflare Turnstile is optional: it is enforced only when both keys are saved
 * in Site Settings and both are valid.
 */
function southcity_cms_turnstile_enabled() {
	return southcity_cms_is_turnstile_key( get_option( 'options_turnstile_site_key', '' ) )
		&& southcity_cms_is_turnstile_key( get_option( 'options_turnstile_secret_key', '' ) );
}

/**
 * Verify a Turnstile token with Cloudflare.
 *
 * Fails open if Cloudflare cannot be reached, so an outage never swallows a real
 * customer's enquiry (the IP rate limit still applies).
 */
function southcity_cms_verify_turnstile( $token ) {
	$token = is_string( $token ) ? substr( trim( $token ), 0, 2048 ) : '';

	if ( '' === $token ) {
		return false;
	}

	$response = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', [
		'timeout' => 8,
		'body'    => [
			'secret'   => trim( (string) get_option( 'options_turnstile_secret_key', '' ) ),
			'response' => $token,
			'remoteip' => southcity_cms_client_ip(),
		],
	] );

	if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
		return true;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	return ! empty( $body['success'] );
}

/**
 * Shared field sanitizer for both endpoints (with hard length caps).
 */
function southcity_cms_sanitize_lead_fields() {
	$cap = static function ( $value, $max ) {
		return mb_substr( $value, 0, $max );
	};

	return [
		'name'       => $cap( isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '', 100 ),
		'phone'      => $cap( isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '', 30 ),
		'plot_size'  => $cap( isset( $_POST['plot_size'] ) ? sanitize_text_field( wp_unslash( $_POST['plot_size'] ) ) : '', 50 ),
		'message'    => $cap( isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '', 1000 ),
		'source_url' => $cap( isset( $_POST['source'] ) ? esc_url_raw( wp_unslash( $_POST['source'] ) ) : '', 255 ),
	];
}

/**
 * Log a WhatsApp button click as a "lead".
 */
function southcity_cms_handle_log_lead() {
	// Non-blocking: page caching freezes the nonce in cached HTML/JS, so it cannot be
	// relied on. Abuse protection is the rate limit + same-site source check below.
	check_ajax_referer( 'southcity_cms_leads', 'nonce', false );

	if ( ! empty( $_POST['botcheck'] ) || southcity_cms_rate_limited( 'lead', 60, HOUR_IN_SECONDS ) ) {
		wp_send_json_success();
	}

	global $wpdb;
	$fields = southcity_cms_sanitize_lead_fields();

	if ( ! southcity_cms_source_is_local( $fields['source_url'] ) ) {
		wp_send_json_error( [ 'message' => 'Invalid request.' ], 400 );
	}

	$fields['type']       = 'lead';
	$fields['created_at'] = current_time( 'mysql' );

	$wpdb->insert( southcity_cms_leads_table(), $fields );

	wp_send_json_success();
}
add_action( 'wp_ajax_sc_log_lead', 'southcity_cms_handle_log_lead' );
add_action( 'wp_ajax_nopriv_sc_log_lead', 'southcity_cms_handle_log_lead' );

/**
 * Email the sales team about a new form enquiry.
 *
 * Recipients come from South City Settings → "Enquiry notification email"
 * (comma separated), falling back to the WordPress admin email.
 */
function southcity_cms_notify_new_lead( $fields ) {
	$recipients = array_filter(
		array_map( 'trim', explode( ',', (string) get_option( 'options_notify_email', '' ) ) ),
		'is_email'
	);

	if ( empty( $recipients ) ) {
		$admin_email = get_option( 'admin_email' );
		$recipients  = is_email( $admin_email ) ? [ $admin_email ] : [];
	}

	if ( empty( $recipients ) ) {
		return;
	}

	$site    = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$subject = sprintf( '[%s] New enquiry: %s (%s)', $site, $fields['name'], $fields['phone'] );

	$lines = [
		'A new enquiry was submitted on the website.',
		'',
		'Name:      ' . $fields['name'],
		'Phone:     ' . $fields['phone'],
		'Plot size: ' . ( '' !== $fields['plot_size'] ? $fields['plot_size'] : '-' ),
		'Message:   ' . ( '' !== $fields['message'] ? $fields['message'] : '-' ),
		'Page:      ' . $fields['source_url'],
		'Time:      ' . wp_date( 'j M Y, g:i a' ),
		'',
		'All enquiries: ' . add_query_arg( 'view', 'leads', southcity_cms_url() ),
	];

	wp_mail( $recipients, $subject, implode( "\n", $lines ) );
}

/**
 * Log a completed contact-form submission as a "purchase".
 */
function southcity_cms_handle_log_purchase() {
	// Non-blocking for the same reason as the lead endpoint (cached nonce).
	check_ajax_referer( 'southcity_cms_leads', 'nonce', false );

	if ( ! empty( $_POST['botcheck'] ) ) {
		wp_send_json_success();
	}

	if ( southcity_cms_rate_limited( 'purchase', 10, HOUR_IN_SECONDS ) ) {
		wp_send_json_error( [ 'message' => 'Too many requests. Please try again later.' ], 429 );
	}

	$fields = southcity_cms_sanitize_lead_fields();

	if ( ! southcity_cms_source_is_local( $fields['source_url'] ) ) {
		wp_send_json_error( [ 'message' => 'Invalid request.' ], 400 );
	}

	if ( '' === $fields['name'] || '' === $fields['phone'] ) {
		wp_send_json_error( [ 'message' => 'Name and phone are required.' ], 400 );
	}

	$fields['phone'] = southcity_cms_normalize_phone( $fields['phone'] );

	if ( ! southcity_cms_is_valid_bd_phone( $fields['phone'] ) ) {
		wp_send_json_error( [ 'message' => 'Please enter a valid Bangladeshi mobile number.' ], 400 );
	}

	if ( southcity_cms_turnstile_enabled() ) {
		$token = isset( $_POST['turnstile'] ) ? sanitize_text_field( wp_unslash( $_POST['turnstile'] ) ) : '';

		if ( ! southcity_cms_verify_turnstile( $token ) ) {
			wp_send_json_error( [ 'message' => 'Verification failed. Please try again.' ], 403 );
		}
	}

	global $wpdb;
	$table = southcity_cms_leads_table();

	// Same number submitted again within 10 minutes (double tap / resubmit): treat as done.
	$duplicate = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM {$table} WHERE type = %s AND phone = %s AND created_at >= %s LIMIT 1",
		'purchase',
		$fields['phone'],
		wp_date( 'Y-m-d H:i:s', time() - 10 * MINUTE_IN_SECONDS )
	) );

	if ( $duplicate ) {
		wp_send_json_success();
	}

	$fields['type']       = 'purchase';
	$fields['created_at'] = current_time( 'mysql' );

	if ( false === $wpdb->insert( $table, $fields ) ) {
		wp_send_json_error( [ 'message' => 'Could not save your enquiry.' ], 500 );
	}

	southcity_cms_notify_new_lead( $fields );

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
