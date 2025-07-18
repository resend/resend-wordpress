<?php
if ( ! isset( $type ) ) {
	$type = false;
}
?>
<?php if ( 'new-key-valid' === $type ) : ?>
	<div class="resend-alert is-success">
		<span class="resend-alert-icon">
			<?php Resend::view( 'icon', array( 'type' => 'check-circle' ) ); ?>
		</span>
		<p class="resend-alert-text"><?php esc_html_e( 'Resend is now connected to your site.', 'resend' ); ?></p>
	</div>

<?php elseif ( 'new-key-empty' === $type ) : ?>
	<div class="resend-alert is-danger">
		<span class="resend-alert-icon">
			<?php Resend::view( 'icon', array( 'type' => 'x-circle' ) ); ?>
		</span>
		<p class="resend-alert-text"><?php esc_html_e( 'You did not enter API key. Please try again.', 'resend' ); ?></p>
	</div>

<?php elseif ( 'new-key-invalid' === $type ) : ?>
	<div class="resend-alert is-danger">
		<span class="resend-alert-icon">
			<?php Resend::view( 'icon', array( 'type' => 'x-circle' ) ); ?>
		</span>
		<p class="resend-alert-text"><?php esc_html_e( 'The API key you entered is invalid. Please double-check it.', 'resend' ); ?></p>
	</div>

<?php elseif ( 'test-email-sent' === $type ) : ?>
	<div class="resend-alert is-success">
		<span class="resend-alert-icon">
			<?php Resend::view( 'icon', array( 'type' => 'check-circle' ) ); ?>
		</span>
		<p class="resend-alert-text"><?php esc_html_e( 'Test email sent!', 'resend' ); ?></p>
	</div>

<?php elseif ( 'test-email-not-set' === $type ) : ?>
	<div class="resend-alert is-danger">
		<span class="resend-alert-icon">
			<?php Resend::view( 'icon', array( 'type' => 'x-circle' ) ); ?>
		</span>
		<p class="resend-alert-text"><?php esc_html_e( 'Please provide an email address to send a test email.', 'resend' ); ?></p>
	</div>

<?php elseif ( 'test-email-failed' === $type ) : ?>
	<div class="resend-alert is-danger">
		<span class="resend-alert-icon">
			<?php Resend::view( 'icon', array( 'type' => 'x-circle' ) ); ?>
		</span>
		<p class="resend-alert-text"><?php esc_html_e( 'Failed to send a test email.', 'resend' ); ?></p>
	</div>

<?php elseif ( 'resend-error' === $type ) : ?>
	<div class="resend-alert is-danger">
		<span class="resend-alert-icon">
			<?php Resend::view( 'icon', array( 'type' => 'x-circle' ) ); ?>
		</span>
		<p class="resend-alert-text"><?php esc_html( $message ); ?></p>
	</div>

<?php endif; ?>
