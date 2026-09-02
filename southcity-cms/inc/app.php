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
	$view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : 'settings';
	$post_id = isset( $_GET['post_id'] ) ? intval( wp_unslash( $_GET['post_id'] ) ) : 0;
	$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';

	// Navigation structure based on South City content model
	$nav_items = [
		'settings' => [
			'label' => 'Global Settings',
			'icon'  => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
		],
		'homepage' => [
			'label' => 'Homepage Content',
			'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
		],
		'southcity_plot' => [
			'label' => 'Plots & Pricing',
			'icon'  => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'
		],
		'southcity_amenity' => [
			'label' => 'Amenities',
			'icon'  => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'
		],
		'southcity_landmark' => [
			'label' => 'Neighborhood',
			'icon'  => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'
		],
		'southcity_gallery' => [
			'label' => 'Gallery Images',
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
			.acf-input input[type="text"], .acf-input textarea, .acf-input select { border-radius: 0.375rem !important; border: 1px solid #cbd5e1 !important; padding: 0.5rem 0.75rem !important; width: 100% !important; max-width: 100% !important; }
			.acf-button { background-color: #14245C !important; border-color: #14245C !important; text-shadow: none !important; box-shadow: none !important; padding: 6px 14px !important; }
			.acf-button:hover { background-color: #0E1A44 !important; }
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
							<h1 class="text-2xl font-bold text-slate-900"><?php echo esc_html( $nav_items[$view]['label'] ); ?></h1>
						</div>
						<div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8 mt-6">
							
							<?php
							// RENDER THE VIEW
							if ( $view === 'settings' ) {
								southcity_cms_render_options_form();
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
		
		<?php wp_footer(); ?>
	</body>
	</html>
	<?php
}

/**
 * Render the Options Page ACF form.
 */
function southcity_cms_render_options_form() {
	echo '<div class="bg-white shadow rounded-lg p-6">';
	if ( function_exists( 'acf_form' ) ) {
		acf_form( [
			'id'           => 'sc-settings-form',
			'post_id'      => 'options',
			'post_title'   => false,
			'post_content' => false,
			'submit_value' => 'Save Settings',
			'return'       => add_query_arg( 'updated', 'true', southcity_cms_url() ),
			'html_updated_message'  => '<div class="bg-green-50 text-green-800 p-4 rounded-md mb-4 font-medium">Settings saved successfully.</div>',
		] );
	} else {
		echo '<p>ACF is not active.</p>';
	}
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
	}
	echo '</div>';
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
		$edit_url = add_query_arg( [ 'view' => $post_type, 'action' => 'edit', 'post_id' => $p->ID ], southcity_cms_url() );
		echo '<li class="px-6 py-4 flex items-center justify-between hover:bg-slate-50">';
		echo '<div class="font-medium text-slate-900">' . esc_html( $p->post_title ? $p->post_title : '(No Title)' ) . '</div>';
		echo '<div><a href="' . esc_url( $edit_url ) . '" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a></div>';
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
	}
	echo '</div>';
}
