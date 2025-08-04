<?php
$current_user       = wp_get_current_user();
$current_user_email = $current_user->user_email;

$notice_message    = isset( $notice ) && isset( $notice['message'] ) ? $notice['message'] : null;
$notice_is_success = isset( $notice ) && isset( $notice['success'] ) ? $notice['success'] : false;
?>
<div class="resend-plugin-container">
	<div class="resend-config-container">
		<?php Resend::view( 'logo', array( 'dashboard' => true ) ); ?>

		<?php if ( isset( $notice_message ) ) : ?>
			<div id="resend_alerts" data-message="<?php echo esc_attr( $notice_message ); ?>" data-success="<?php echo esc_attr( $notice_is_success ); ?>"></div>
		<?php else : ?>
			<div id="resend_alerts"></div>
		<?php endif; ?>

		<div class="resend-card-list">
			<section class="resend-card">
				<div class="resend-card-header">
					<h2 class="resend-card-title"><?php esc_html_e( 'Settings', 'resend' ); ?></h2>
				</div>
				<div class="resend-card-content">
					<p><?php esc_html_e( 'Manage the settings used to send emails from your site.', 'resend' ); ?></p>

					<form id="resend-settings-form" class="resend-form" autocomplete="off" method="post">
						<div style="display: grid; gap: 16px; grid-template-columns: repeat(2, minmax(0, 1fr));">
							<div>
								<label for="from_name" class="resend-label"><?php esc_html_e( 'Sender name', 'resend' ); ?></label>
								<input id="from_name" name="from_name" type="text" class="resend-input" value="<?php echo esc_attr( Resend::get_from_name() ); ?>" autocomplete="name" required>
							</div>
							<div>
								<label for="form_email" class="resend-label"><?php esc_html_e( 'Sender email address', 'resend' ); ?></label>
								<input id="form_email" name="from_email" type="email" class="resend-input" value="<?php echo esc_attr( Resend::get_from_address() ); ?>" autocomplete="email" required>
							</div>
						</div>
						<div>
							<input type="submit" class="resend-button is-primary" value="<?php esc_attr_e( 'Save changes', 'resend' ); ?>">
						</div>
					</form>
				</div>
			</section>

			<?php /** Send test email */ ?>
			<section class="resend-card">
				<div class="resend-card-header">
					<h2 class="resend-card-title"><?php esc_html_e( 'Send a test email', 'resend' ); ?></h2>
				</div>
				<div class="resend-card-content">
					<p><?php esc_html_e( 'Test that the connection to Resend is working by sending a test email from your site.', 'resend' ); ?></p>

					<form id="resend-test-email-form" class="resend-form" autocomplete="off" method="post">
						<div>
							<label for="test_email" class="resend-label"><?php esc_html_e( 'Email address', 'resend' ); ?></label>
							<input id="test_email" name="email" type="email" class="resend-input" value="<?php echo esc_attr( $current_user_email ); ?>" autocomplete="email" required>
						</div>
						<div>
							<input type="submit" class="resend-button is-primary" value="<?php esc_attr_e( 'Send test email', 'resend' ); ?>">
						</div>
					</form>
				</div>
			</section>

			<?php /** API key settings */ ?>
			<section class="resend-card">
				<div class="resend-accordion-toggle resend-card-header">
					<h2 class="resend-card-title"><?php esc_html_e( 'API key settings', 'resend' ); ?></h2>
					<div class="resend-accordion-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
							<path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
						</svg>
					</div>
				</div>
				<div class="resend-card-content" style="display: none">
					<p><?php esc_html_e( 'Manage the API key used to connect your site to Resend.', 'resend' ); ?></p>

					<form id="resend-api-key-form" class="resend-form" autocomplete="off" method="post">
						<div>
							<label for="resend_api_key" class="resend-label"><?php esc_html_e( 'API Key', 'resend' ); ?></label>
							<div style="display: flex; align-items: center; gap: 6px;">
								<input id="resend_api_key" name="key" type="password" class="resend-input" value="<?php echo esc_attr( get_option( 'resend_api_key' ) ); ?>" autocomplete="off" data-1p-ignore data-lpignore="true" data-protonpass-ignore="true">
								<button type="button" class="resend-button" onclick="resendTogglePassword(this, 'resend_api_key')" style="padding-left: 7px; padding-right: 7px;">
									<span id="show-password" style="display: inline-flex;">
										<?php Resend::view( 'icon', array( 'type' => 'eye' ) ); ?>
									</span>
									<span id="hide-password" style="display: none;">
										<?php Resend::view( 'icon', array( 'type' => 'eye-slash' ) ); ?>
									</span>
								</button>
							</div>
						</div>

						<div>
							<input type="submit" class="resend-button is-primary" value="<?php esc_attr_e( 'Save changes', 'resend' ); ?>">
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</div>
