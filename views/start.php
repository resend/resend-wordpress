<div class="resend-plugin-container">
	<div class="resend-start-container">
		<?php Resend::view( 'logo' ); ?>

		<div id="resend_alerts"></div>

		<div>
			<h3 class="resend-h3"><?php esc_html_e( 'Connect your site to Resend', 'resend' ); ?></h3>
			<p class="resend-setup-steps-desc"><?php esc_html_e( 'Follow these steps to send emails from your site using Resend.', 'resend' ); ?></p>
		</div>

		<div class="resend-setup-steps-container">
			<div class="resend-setup-steps-gradient"></div>

			<div class="resend-setup-step-create-key resend-setup-steps-step-container">
				<div class="resend-setup-steps-spot">
					<div></div>
				</div>
				<div class="resend-setup-steps-content">
					<div>
						<div>
							<div style="display: flex; align-items: center; margin-bottom: 8px; gap: 8px;">
								<h3 class="resend-h3" style="margin-bottom: 0;">Create an API key</h3>
								<svg class="only-completed" xmlns="http://www.w3.org/2000/svg" fill="none" width="22" viewBox="0 0 24 24" stroke-width="2" stroke="#00713f">
									<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
								</svg>
							</div>
							<p class="resend-setup-steps-desc">Create an API key with <strong>"Sending access"</strong> permissions to use it in the next step.</p>
							<div class="resend-setup-steps-actions" style="display: flex; align-items: center; gap: 16px;">
								<a id="resend-create-key" class="resend-button is-primary" href="http://resend.com/api-keys" onclick="resendCreateKey()" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Create API key', 'resend' ); ?></a>
								<span>OR</span>
								<a id="resend-use-existing-key" href="#"><?php esc_html_e( 'Use existing API key', 'resend' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="resend-setup-step-enter-key resend-setup-steps-step-container is-disabled">
				<div class="resend-setup-steps-spot">
					<div></div>
				</div>
				<div class="resend-setup-steps-content">
					<div>
						<div>
							<h3 class="resend-h3"><?php esc_html_e( 'Enter your API key', 'resend' ); ?></h3>
							<p class="resend-setup-steps-desc"><?php esc_html_e( 'Copy your API key from the Resend dashboard, and paste it into the field below.', 'resend' ); ?></p>
							<form id="resend-api-key-form" autocomplete="off" method="post">
								<div>
									<input id="resend_api_key" class="resend-input" name="key" type="password" value="" placeholder="<?php esc_attr_e( 're_xxxxxxxxx', 'resend' ); ?>" style="width: 100%;" autocomplete="off" data-1p-ignore data-lpignore="true" data-protonpass-ignore="true">
								</div>
								<div style="margin-top: 16px;">
									<input type="submit" class="resend-button" value="<?php esc_attr_e( 'Connect with API key', 'resend' ); ?>">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
