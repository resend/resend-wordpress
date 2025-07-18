<?php
if ( ! isset( $dashboard ) ) {
	$dashboard = false;
}
?>
<div class="resend-logo-container">
	<div style="display: flex;">
		<img class="resend-logo" src="<?php echo esc_url( plugins_url( '../public/img/resend-wordmark-black.png', __FILE__ ) ); ?>" srcset="<?php echo esc_url( plugins_url( '../public/img/resend-wordmark-black.svg', __FILE__ ) ); ?>" alt="Resend logo" />
	</div>
	<?php if ( $dashboard ) : ?>
		<a href="http://resend.com/emails" target="_blank" rel="noopener noreferrer" class="resend-button">
			<span>Resend Dashboard</span>
			<?php Resend::view( 'icon', array( 'type' => 'arrow-top-right-on-square' ) ); ?>
		</a>
	<?php endif; ?>
</div>
