<?php
/**
 * Render the main CMS dashboard layout and ACF forms.
 *
 * @package SouthCity_CMS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the main app dashboard.
 */
function southcity_cms_render_app() {
	$current_user = wp_get_current_user();
	$logout_url   = wp_nonce_url( add_query_arg( 'sc_action', 'logout', southcity_cms_url() ), 'sc_logout' );
	
	// Determine the current view
	$view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : 'dashboard';
	$post_id = isset( $_GET['post_id'] ) ? intval( wp_unslash( $_GET['post_id'] ) ) : 0;
	$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';

	// Navigation structure based on South City content model
	$nav_items = [
		'dashboard' => [
			'label' => 'Dashboard',
			'icon'  => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2'
		],
		'leads' => [
			'label' => 'Leads',
			'icon'  => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-3-6.65'
		],
		'homepage' => [
			'label' => 'Homepage Content',
			'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
		],
		'settings' => [
			'label' => 'Site Settings',
			'icon'  => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'
		],
		'brochure' => [
			'label' => 'Brochure',
			'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
		],
		'southcity_plot' => [
			'label' => 'Plots & Pricing',
			'icon'  => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'
		],
		'southcity_amenity' => [
			'label' => 'Amenities & Facilities',
			'icon'  => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'
		],
		'southcity_landmark' => [
			'label' => 'Neighborhood',
			'icon'  => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'
		],
		'southcity_location' => [
			'label' => 'Location Manager',
			'icon'  => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'
		],
		'southcity_gallery' => [
			'label' => 'Project Gallery',
			'icon'  => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
		],
		'southcity_fact' => [
			'label' => 'Project Facts',
			'icon'  => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
		],
		'southcity_badge' => [
			'label' => 'Trust Badges',
			'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
		],
	];

	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>South City Management</title>
		<script src="https://cdn.tailwindcss.com"></script>
		<?php wp_head(); ?>
		<style>
			/* Minor tweaks to make ACF forms look native in this layout */
			.acf-field { border: none !important; padding: 15px 0 !important; }
			.acf-label label { font-weight: 600 !important; color: #1e293b !important; font-family: ui-sans-serif, system-ui, sans-serif !important; }
			.acf-input input[type="text"], .acf-input input[type="url"], .acf-input input[type="email"], .acf-input input[type="password"], .acf-input textarea, .acf-input select { border-radius: 0.375rem !important; border: 1px solid #cbd5e1 !important; padding: 0.5rem 0.75rem !important; width: 100% !important; max-width: 100% !important; }
			.acf-button, .acf-field .button { background-color: #14245C !important; border-color: #14245C !important; color: #ffffff !important; text-shadow: none !important; box-shadow: none !important; padding: 6px 14px !important; }
			.acf-button:hover, .acf-field .button:hover { background-color: #0E1A44 !important; color: #ffffff !important; }
			.acf-field .button:visited { color: #ffffff !important; }
			#wpadminbar { display: none !important; }
			html { margin-top: 0 !important; }
		</style>
	</head>
	<body class="bg-slate-50 min-h-screen text-slate-900 font-sans">
		
		<div class="flex h-screen overflow-hidden">
			
			<!-- Sidebar -->
			<div class="hidden md:flex md:flex-shrink-0">
				<div class="flex flex-col w-64 bg-white border-r border-slate-200">
					<div class="flex flex-col h-0 flex-1">
						<div class="flex items-center h-16 px-4 border-b border-slate-100 bg-slate-50">
							<h1 class="text-xl font-bold text-[#14245C]">South City</h1>
						</div>
						<div class="flex-1 flex flex-col overflow-y-auto">
							<nav class="flex-1 px-3 py-4 space-y-1">
								<?php foreach ( $nav_items as $key => $item ) : ?>
									<?php $is_active = $view === $key; ?>
									<a href="<?php echo esc_url( add_query_arg( 'view', $key, southcity_cms_url() ) ); ?>" class="<?php echo $is_active ? 'bg-blue-50 text-[#14245C]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'; ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
										<svg class="<?php echo $is_active ? 'text-[#14245C]' : 'text-slate-400 group-hover:text-slate-500'; ?> mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( $item['icon'] ); ?>" />
										</svg>
										<?php echo esc_html( $item['label'] ); ?>
									</a>
								<?php endforeach; ?>
							</nav>
						</div>
						<div class="flex-shrink-0 flex border-t border-slate-200 p-4">
							<div class="flex-shrink-0 w-full group block">
								<div class="flex items-center">
									<div class="ml-3">
										<p class="text-sm font-medium text-slate-700"><?php echo esc_html( $current_user->display_name ); ?></p>
										<a href="<?php echo esc_url( $logout_url ); ?>" class="text-xs font-medium text-red-600 hover:text-red-500">Sign out</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Main Content -->
			<div class="flex flex-col w-0 flex-1 overflow-hidden">
				
				<!-- Mobile header -->
				<div class="md:hidden flex items-center justify-between bg-white border-b border-slate-200 px-4 py-3">
					<h1 class="text-lg font-bold text-[#14245C]">South City</h1>
					<a href="<?php echo esc_url( $logout_url ); ?>" class="text-sm text-red-600">Sign out</a>
				</div>
				<!-- Mobile nav (simplified for demo) -->
				<div class="md:hidden overflow-x-auto bg-white border-b border-slate-200">
					<nav class="flex px-2 py-2 space-x-2">
						<?php foreach ( $nav_items as $key => $item ) : ?>
							<?php $is_active = $view === $key; ?>
							<a href="<?php echo esc_url( add_query_arg( 'view', $key, southcity_cms_url() ) ); ?>" class="<?php echo $is_active ? 'bg-blue-50 text-[#14245C]' : 'text-slate-600'; ?> whitespace-nowrap px-3 py-1.5 text-xs font-medium rounded-md">
								<?php echo esc_html( $item['label'] ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
				</div>

				<main class="flex-1 relative z-0 overflow-y-auto focus:outline-none bg-slate-50">
					<div class="py-6">
						<div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8">
							<h1 class="text-2xl font-bold text-slate-900"><?php echo esc_html( $nav_items[ $view ]['label'] ?? 'South City' ); ?></h1>
						</div>
						<div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8 mt-6">
							
							<?php
							// RENDER THE VIEW
							if ( $view === 'dashboard' ) {
								southcity_cms_render_dashboard();
							} elseif ( $view === 'leads' ) {
								if ( $action === 'edit' ) {
									$lead_id = isset( $_GET['lead_id'] ) ? intval( wp_unslash( $_GET['lead_id'] ) ) : 0;
									southcity_cms_render_lead_form( $lead_id );
								} else {
									southcity_cms_render_leads_list();
								}
							} elseif ( $view === 'settings' ) {
								southcity_cms_render_settings_form();
							} elseif ( $view === 'brochure' ) {
								southcity_cms_render_brochure_form();
							} elseif ( $view === 'homepage' ) {
								southcity_cms_render_homepage_form();
							} else {
								// It's a CPT view
								if ( $action === 'edit' || $action === 'new' ) {
									southcity_cms_render_cpt_form( $view, $post_id );
								} else {
									southcity_cms_render_cpt_list( $view );
								}
							}
							?>

						</div>
					</div>
				</main>
			</div>
		</div>
		
		<script>
			// Browsers may auto-fill saved logins into unrelated settings fields; switch that off.
			document.querySelectorAll('.acf-form input[type="text"], .acf-form input[type="url"], .acf-form input[type="email"]').forEach(function (input) {
				input.setAttribute('autocomplete', 'off');
			});
		</script>
		<?php wp_footer(); ?>
	</body>
	</html>
	<?php
}

/**
 * Render a helpful notice when ACF is not active.
 */
function southcity_cms_render_acf_missing_notice() {
	echo '<div class="bg-amber-50 border-l-4 border-amber-500 p-5 rounded-r-md">';
	echo '<div class="flex items-start">';
	echo '<div class="flex-shrink-0"><svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>';
	echo '<div class="ml-3">';
	echo '<h3 class="text-base font-bold text-amber-800">ACF (Advanced Custom Fields) is not active</h3>';
	echo '<p class="mt-2 text-sm text-amber-700">South City Dashboard needs the <strong>Advanced Custom Fields (ACF)</strong> plugin to edit website content. Please follow these steps:</p>';
	echo '<ol class="mt-2 ml-4 list-decimal text-sm text-amber-700 space-y-1">';
	echo '<li>Go to WordPress Dashboard (<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '" class="underline font-semibold" target="_blank">wp-admin &rarr; Plugins</a>)</li>';
	echo '<li>Search for <strong>Advanced Custom Fields</strong></li>';
	echo '<li>Click <strong>Install Now</strong> and then <strong>Activate</strong></li>';
	echo '</ol>';
	echo '</div></div></div>';
}

/**
 * Global site settings (phone, WhatsApp, notification email, SEO, analytics...).
 *
 * Rendered with acf_form() so it works with the free ACF plugin. The ACF Pro
 * "options page" is not required; both write to the same saved options.
 */
function southcity_cms_render_settings_form() {
	echo '<div class="bg-white shadow rounded-lg p-6">';

	if ( function_exists( 'acf_form' ) ) {
		acf_form( [
			'id'                   => 'sc-settings-form',
			'post_id'              => 'options',
			'field_groups'         => [ 'group_south_city_global_settings' ],
			'post_title'           => false,
			'post_content'         => false,
			'submit_value'         => 'Save Settings',
			'return'               => add_query_arg( [ 'view' => 'settings', 'updated' => 'true' ], southcity_cms_url() ),
			'html_updated_message' => '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Settings saved successfully.</div>',
		] );
	} else {
		southcity_cms_render_acf_missing_notice();
	}

	echo '</div>';
}

/**
 * Without ACF Pro there is no "South City Settings" page in wp-admin, so add a
 * menu entry that opens the same settings inside the /manage dashboard.
 */
function southcity_cms_register_settings_menu() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	$hook = add_menu_page( 'South City Settings', 'South City Settings', 'manage_options', 'south-city-settings', '__return_null', 'dashicons-admin-home', 59 );

	add_action( 'load-' . $hook, static function () {
		wp_safe_redirect( add_query_arg( 'view', 'settings', southcity_cms_url() ) );
		exit;
	} );
}
add_action( 'admin_menu', 'southcity_cms_register_settings_menu' );

/**
 * Render just the Brochure PDF field from the ACF options page, so it can be
 * updated here without exposing every other global setting.
 */
function southcity_cms_render_brochure_form() {
	echo '<div class="bg-white shadow rounded-lg p-6">';

	if ( function_exists( 'acf_form' ) ) {
		$current_url = south_city_cms_current_brochure_url();

		if ( $current_url ) {
			echo '<p class="mb-4 text-sm text-slate-600">Current file: <a href="' . esc_url( $current_url ) . '" class="text-blue-600 hover:text-blue-900 font-medium" target="_blank" rel="noopener">' . esc_html( basename( wp_parse_url( $current_url, PHP_URL_PATH ) ) ) . '</a></p>';
		} else {
			echo '<p class="mb-4 text-sm text-amber-700">No brochure uploaded yet. Visitors will not see a "Download Brochure" button until one is added here.</p>';
		}

		acf_form( [
			'id'                   => 'sc-brochure-form',
			'post_id'              => 'options',
			'post_title'           => false,
			'post_content'         => false,
			'fields'               => [ 'field_south_city_brochure_pdf' ],
			'submit_value'         => 'Save Brochure',
			'return'               => add_query_arg( [ 'view' => 'brochure', 'updated' => 'true' ], southcity_cms_url() ),
			'html_updated_message' => '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Brochure updated successfully.</div>',
		] );
	} else {
		southcity_cms_render_acf_missing_notice();
	}

	echo '</div>';
}

/**
 * Read the currently saved brochure file URL, if any.
 */
function south_city_cms_current_brochure_url() {
	$value = function_exists( 'get_field' ) ? get_field( 'brochure_pdf', 'option' ) : null;

	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		return (string) $value['url'];
	}

	if ( is_string( $value ) && $value !== '' ) {
		return $value;
	}

	return '';
}

/**
 * Render the click/submission counters.
 */
function southcity_cms_render_dashboard() {
	$counts = southcity_cms_lead_counts();
	?>
	<div class="grid gap-4 sm:grid-cols-2">
		<div class="bg-white shadow rounded-lg p-6">
			<p class="text-sm font-medium text-slate-500">WhatsApp button clicks (Leads)</p>
			<p class="mt-2 text-4xl font-bold text-[#14245C]"><?php echo esc_html( number_format_i18n( $counts['leads'] ) ); ?></p>
		</div>
		<div class="bg-white shadow rounded-lg p-6">
			<p class="text-sm font-medium text-slate-500">Form fill-ups (Purchases)</p>
			<p class="mt-2 text-4xl font-bold text-[#14245C]"><?php echo esc_html( number_format_i18n( $counts['purchases'] ) ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Render the list of contact-form submissions.
 */
function southcity_cms_render_leads_list() {
	$per_page = 50;
	$paged    = isset( $_GET['paged'] ) ? max( 1, intval( wp_unslash( $_GET['paged'] ) ) ) : 1;
	$rows     = southcity_cms_get_purchases( $per_page, $paged );
	$total    = southcity_cms_count_purchases();
	$pages    = (int) ceil( $total / $per_page );

	if ( isset( $_GET['updated'] ) && 'true' === $_GET['updated'] ) {
		echo '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Lead updated successfully.</div>';
	}

	if ( isset( $_GET['deleted'] ) && 'true' === $_GET['deleted'] ) {
		echo '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Lead deleted successfully.</div>';
	}

	if ( empty( $rows ) ) {
		echo '<div class="bg-white shadow rounded-lg p-10 text-center text-slate-500">No form submissions yet.</div>';
		return;
	}

	echo '<div class="bg-white shadow rounded-lg overflow-hidden overflow-x-auto">';
	echo '<table class="min-w-full divide-y divide-slate-200">';
	echo '<thead class="bg-slate-50"><tr>';
	foreach ( [ 'Name', 'Phone', 'Plot Size', 'Message', 'Submitted', 'Actions' ] as $head ) {
		echo '<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">' . esc_html( $head ) . '</th>';
	}
	echo '</tr></thead>';
	echo '<tbody class="divide-y divide-slate-100">';
	foreach ( $rows as $row ) {
		$edit_url   = add_query_arg( [ 'view' => 'leads', 'action' => 'edit', 'lead_id' => $row->id ], southcity_cms_url() );
		$delete_url = wp_nonce_url( add_query_arg( [ 'sc_action' => 'delete_lead', 'lead_id' => $row->id ], southcity_cms_url() ), 'sc_delete_lead_' . $row->id );

		echo '<tr>';
		echo '<td class="px-4 py-3 text-sm font-medium text-slate-900">' . esc_html( $row->name ) . '</td>';
		echo '<td class="px-4 py-3 text-sm text-slate-600">' . esc_html( $row->phone ) . '</td>';
		echo '<td class="px-4 py-3 text-sm text-slate-600">' . esc_html( $row->plot_size ) . '</td>';
		echo '<td class="px-4 py-3 text-sm text-slate-600 max-w-xs truncate">' . esc_html( $row->message ) . '</td>';
		echo '<td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">' . esc_html( mysql2date( 'M j, Y g:i a', $row->created_at ) ) . '</td>';
		echo '<td class="px-4 py-3 text-sm whitespace-nowrap">';
		echo '<a href="' . esc_url( $edit_url ) . '" class="text-blue-600 hover:text-blue-900 font-medium mr-4">Edit</a>';
		echo '<a href="' . esc_url( $delete_url ) . '" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm(\'Delete this lead? This cannot be undone.\');">Delete</a>';
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody></table></div>';

	if ( $pages > 1 ) {
		echo '<div class="mt-4 flex gap-2">';
		for ( $i = 1; $i <= $pages; $i++ ) {
			$page_url = add_query_arg( [ 'view' => 'leads', 'paged' => $i ], southcity_cms_url() );
			$is_current = $i === $paged;
			echo '<a href="' . esc_url( $page_url ) . '" class="' . ( $is_current ? 'bg-[#14245C] text-white' : 'bg-white text-slate-600 border border-slate-200' ) . ' px-3 py-1.5 text-sm font-medium rounded-md">' . esc_html( (string) $i ) . '</a>';
		}
		echo '</div>';
	}
}

/**
 * Render the edit form for a single lead (form submission) row.
 */
function southcity_cms_render_lead_form( $lead_id ) {
	$back_url = add_query_arg( [ 'view' => 'leads' ], southcity_cms_url() );
	$lead     = $lead_id ? southcity_cms_get_purchase( $lead_id ) : null;

	echo '<div class="mb-6">';
	echo '<a href="' . esc_url( $back_url ) . '" class="text-sm font-medium text-slate-500 hover:text-slate-700">&larr; Back to list</a>';
	echo '</div>';

	if ( ! $lead ) {
		echo '<div class="bg-white shadow rounded-lg p-10 text-center text-slate-500">Lead not found.</div>';
		return;
	}

	echo '<div class="bg-white shadow rounded-lg p-6">';
	echo '<form method="post" action="' . esc_url( southcity_cms_url() ) . '">';
	wp_nonce_field( 'sc_save_lead_' . $lead->id, 'sc_lead_nonce' );
	echo '<input type="hidden" name="lead_id" value="' . esc_attr( $lead->id ) . '">';
	echo '<input type="hidden" name="sc_lead_save" value="1">';

	$fields = [
		'name'      => [ 'label' => 'Name', 'type' => 'text' ],
		'phone'     => [ 'label' => 'Phone', 'type' => 'text' ],
		'plot_size' => [ 'label' => 'Plot Size', 'type' => 'text' ],
		'message'   => [ 'label' => 'Message', 'type' => 'textarea' ],
	];

	foreach ( $fields as $key => $field ) {
		echo '<div class="mb-4">';
		echo '<label class="block text-sm font-semibold text-slate-800 mb-1" for="sc-lead-' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label>';
		if ( 'textarea' === $field['type'] ) {
			echo '<textarea id="sc-lead-' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2">' . esc_textarea( $lead->$key ) . '</textarea>';
		} else {
			echo '<input type="text" id="sc-lead-' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $lead->$key ) . '" class="w-full rounded-md border border-slate-300 px-3 py-2">';
		}
		echo '</div>';
	}

	echo '<button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#14245C] hover:bg-[#0E1A44]">Update Lead</button>';
	echo '</form>';
	echo '</div>';
}

/**
 * Render the Homepage ACF form.
 */
function southcity_cms_render_homepage_form() {
	// Find the front page ID
	$front_page_id = get_option( 'page_on_front' );
	
	if ( ! $front_page_id ) {
		echo '<div class="bg-yellow-50 text-yellow-800 p-4 rounded-md">Please set a Static Front Page in WordPress Settings -> Reading first.</div>';
		return;
	}

	echo '<div class="bg-white shadow rounded-lg p-6">';
	if ( function_exists( 'acf_form' ) ) {
		acf_form( [
			'id'           => 'sc-homepage-form',
			'post_id'      => $front_page_id,
			'post_title'   => false,
			'post_content' => false,
			'submit_value' => 'Update Homepage',
			'return'       => add_query_arg( [ 'view' => 'homepage', 'updated' => 'true' ], southcity_cms_url() ),
			'html_updated_message'  => '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Homepage updated successfully.</div>',
		] );
	} else {
		southcity_cms_render_acf_missing_notice();
	}
	echo '</div>';
}

/**
 * Get a small preview image URL for a post's ACF "image" field, if any.
 */
function southcity_cms_get_thumb_url( $post_id ) {
	$value = function_exists( 'get_field' ) ? get_field( 'image', $post_id ) : null;

	if ( is_array( $value ) ) {
		if ( ! empty( $value['sizes']['thumbnail'] ) ) {
			return (string) $value['sizes']['thumbnail'];
		}
		if ( ! empty( $value['url'] ) ) {
			return (string) $value['url'];
		}
	} elseif ( is_numeric( $value ) ) {
		$src = wp_get_attachment_image_src( (int) $value, 'thumbnail' );
		if ( $src ) {
			return (string) $src[0];
		}
	} elseif ( is_string( $value ) && $value !== '' ) {
		return $value;
	}

	return '';
}

/**
 * Render list of items for a Custom Post Type.
 */
function southcity_cms_render_cpt_list( $post_type ) {
	$posts = get_posts( [
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'orderby'        => 'menu_order title',
		'order'          => 'ASC'
	] );

	$new_url = add_query_arg( [ 'view' => $post_type, 'action' => 'new' ], southcity_cms_url() );

	if ( isset( $_GET['deleted'] ) && 'true' === $_GET['deleted'] ) {
		echo '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Item deleted successfully.</div>';
	}

	echo '<div class="mb-6 flex justify-end">';
	echo '<a href="' . esc_url( $new_url ) . '" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#14245C] hover:bg-[#0E1A44]">+ Add New</a>';
	echo '</div>';

	if ( empty( $posts ) ) {
		echo '<div class="bg-white shadow rounded-lg p-10 text-center text-slate-500">No items found. Click "Add New" to create one.</div>';
		return;
	}

	echo '<div class="bg-white shadow rounded-lg overflow-hidden">';
	echo '<ul class="divide-y divide-slate-200">';
	foreach ( $posts as $p ) {
		$edit_url   = add_query_arg( [ 'view' => $post_type, 'action' => 'edit', 'post_id' => $p->ID ], southcity_cms_url() );
		$delete_url = wp_nonce_url( add_query_arg( [ 'sc_action' => 'delete_post', 'post_id' => $p->ID ], southcity_cms_url() ), 'sc_delete_post_' . $p->ID );
		$thumb_url  = southcity_cms_get_thumb_url( $p->ID );

		echo '<li class="px-6 py-4 flex items-center justify-between hover:bg-slate-50">';
		echo '<div class="flex items-center gap-4 min-w-0">';
		if ( $thumb_url ) {
			echo '<img src="' . esc_url( $thumb_url ) . '" alt="" class="h-12 w-12 rounded-md object-cover flex-shrink-0 border border-slate-200">';
		}
		echo '<div class="font-medium text-slate-900 truncate">' . esc_html( $p->post_title ? $p->post_title : '(No Title)' ) . '</div>';
		echo '</div>';
		echo '<div class="flex items-center gap-4 flex-shrink-0">';
		echo '<a href="' . esc_url( $edit_url ) . '" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a>';
		echo '<a href="' . esc_url( $delete_url ) . '" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm(\'Delete this item? It will be moved to Trash.\');">Delete</a>';
		echo '</div>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
}

/**
 * Render Add/Edit form for a Custom Post Type.
 */
function southcity_cms_render_cpt_form( $post_type, $post_id ) {
	$back_url = add_query_arg( [ 'view' => $post_type ], southcity_cms_url() );
	
	echo '<div class="mb-6">';
	echo '<a href="' . esc_url( $back_url ) . '" class="text-sm font-medium text-slate-500 hover:text-slate-700">&larr; Back to list</a>';
	echo '</div>';

	echo '<div class="bg-white shadow rounded-lg p-6">';
	if ( function_exists( 'acf_form' ) ) {
		$options = [
			'id'           => 'sc-cpt-form',
			'post_id'      => $post_id ? $post_id : 'new_post',
			'new_post'     => [
				'post_type'   => $post_type,
				'post_status' => 'publish'
			],
			'post_title'   => true,
			'post_content' => false,
			'submit_value' => $post_id ? 'Update Item' : 'Publish Item',
			'return'       => add_query_arg( [ 'view' => $post_type, 'updated' => 'true' ], southcity_cms_url() ),
			'html_updated_message'  => '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Item saved successfully.</div>',
		];
		acf_form( $options );
	} else {
		southcity_cms_render_acf_missing_notice();
	}
	echo '</div>';
}
