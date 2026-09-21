<?php
/**
 * Small, low-risk security hardening for the public site.
 *
 * Kept in the plugin (not the theme) so it survives a theme change.
 *
 * @package SouthCity_CMS
 */

defined( 'ABSPATH' ) || exit;

// XML-RPC is not used by this site and is a common brute-force target.
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Hide the user list from anonymous visitors (it reveals admin usernames).
 * Logged-in users (the block editor, wp-admin) keep full access.
 */
function southcity_cms_hide_users_endpoint( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}

	return $endpoints;
}
add_filter( 'rest_endpoints', 'southcity_cms_hide_users_endpoint' );

/**
 * Turn "/?author=1" and /author/username/ into a harmless redirect to the homepage.
 */
function southcity_cms_block_author_enumeration() {
	if ( is_admin() ) {
		return;
	}

	if ( isset( $_GET['author'] ) || is_author() ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'southcity_cms_block_author_enumeration', 1 );

// Never list users in the XML sitemap.
add_filter( 'wp_sitemaps_add_provider', static function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );

// Do not advertise the WordPress version or legacy endpoints.
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Baseline security headers. HSTS is intentionally left to Cloudflare/the host,
 * because enabling it wrongly can lock visitors out.
 */
function southcity_cms_security_headers() {
	if ( headers_sent() ) {
		return;
	}

	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), payment=()' );
	header_remove( 'X-Powered-By' );
}
add_action( 'send_headers', 'southcity_cms_security_headers' );
