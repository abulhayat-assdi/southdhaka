<?php
/**
 * Authentication logic for the CMS.
 *
 * @package SouthCity_CMS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handle logout request.
 */
function southcity_cms_handle_logout() {
	if ( ! isset( $_GET['sc_action'] ) || 'logout' !== $_GET['sc_action'] ) {
		return;
	}

	$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'sc_logout' ) ) {
		return;
	}

	wp_logout();
	wp_safe_redirect( southcity_cms_url() );
	exit;
}

/**
 * Handle login request.
 */
function southcity_cms_handle_login() {
	if ( ! isset( $_POST['sc_action'] ) || 'login' !== $_POST['sc_action'] ) {
		return null;
	}

	$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'sc_login' ) ) {
		return new WP_Error( 'expired', 'That form had been open too long. Please try again.' );
	}

	$user = wp_signon(
		array(
			'user_login'    => isset( $_POST['log'] ) ? sanitize_user( wp_unslash( $_POST['log'] ) ) : '',
			'user_password' => isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : '',
			'remember'      => ! empty( $_POST['rememberme'] ),
		),
		is_ssl()
	);

	if ( is_wp_error( $user ) ) {
		return new WP_Error( 'failed', 'Invalid username or password.' );
	}

	wp_set_current_user( $user->ID );
	wp_safe_redirect( southcity_cms_url() );
	exit;
}

/**
 * Render the login page.
 */
function southcity_cms_render_login( $error = null ) {
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Log In &lsaquo; South City CMS</title>
		<script src="https://cdn.tailwindcss.com"></script>
	</head>
	<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
		<div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg border border-slate-100">
			<div>
				<h2 class="mt-2 text-center text-3xl font-extrabold text-slate-900">
					South City
				</h2>
				<p class="mt-2 text-center text-sm text-slate-600">
					Sign in to manage the website
				</p>
			</div>
			
			<form class="mt-8 space-y-6" action="<?php echo esc_url( southcity_cms_url() ); ?>" method="POST">
				<input type="hidden" name="sc_action" value="login">
				<?php wp_nonce_field( 'sc_login' ); ?>

				<?php if ( is_wp_error( $error ) ) : ?>
					<div class="rounded-md bg-red-50 p-4">
						<div class="flex">
							<div class="ml-3">
								<h3 class="text-sm font-medium text-red-800">
									<?php echo esc_html( $error->get_error_message() ); ?>
								</h3>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<div class="rounded-md shadow-sm -space-y-px">
					<div>
						<label for="log" class="sr-only">Email or Username</label>
						<input id="log" name="log" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Username or Email">
					</div>
					<div>
						<label for="pwd" class="sr-only">Password</label>
						<input id="pwd" name="pwd" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Password">
					</div>
				</div>

				<div class="flex items-center justify-between">
					<div class="flex items-center">
						<input id="rememberme" name="rememberme" type="checkbox" value="forever" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
						<label for="rememberme" class="ml-2 block text-sm text-slate-900">
							Remember me
						</label>
					</div>
				</div>

				<div>
					<button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#14245C] hover:bg-[#0E1A44] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#14245C]">
						Sign in
					</button>
				</div>
			</form>
		</div>
	</body>
	</html>
	<?php
}
