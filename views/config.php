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
					<p><?php esc_html_e( 'Test that the connection to Resend is working by sending a test email from your site.', 'resend' ); ?></p>

					<form id="resend-api-key-form" autocomplete="off" method="post" style="max-width: 448px; width: 100%;">
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

						<div style="margin-top: 16px;">
							<input type="submit" class="resend-button is-primary" value="<?php esc_attr_e( 'Save changes', 'resend' ); ?>">
						</div>
					</form>
				</div>
			</section>

			<section class="resend-card">
				<div class="resend-card-header">
					<h2 class="resend-card-title"><?php esc_html_e( 'Send a test email', 'resend' ); ?></h2>
				</div>
				<div class="resend-card-content">
					<p><?php esc_html_e( 'Test that the connection to Resend is working by sending a test email from your site.', 'resend' ); ?></p>
					<form id="resend-test-email-form" autocomplete="off" method="post" style="max-width: 448px; width: 100%;">
						<div>
							<label for="test_email" class="resend-label"><?php esc_html_e( 'Email address', 'resend' ); ?></label>
							<input id="test_email" name="email" type="email" class="resend-input" value="<?php echo esc_attr( $current_user_email ); ?>" autocomplete="email" required>
						</div>
						<div style="margin-top: 16px;">
							<input type="submit" class="resend-button is-primary" value="<?php esc_attr_e( 'Send test email', 'resend' ); ?>">
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</div>
