<?php
/**
 * Admin Notification / Help Template
 */

if ( ! $type ) {
	$type = 'info';
}
?>
<?php if ( empty( $courier_notifications[ $type ] ) ) : ?>
	<div class="courier-admin-notice notice notice-<?php echo esc_attr( $type ); ?> is-dismissible inline" data-type="<?php echo esc_attr( $type ); ?>">
		<p><span class="dashicons dashicons-welcome-learn-more"></span> <?php echo wp_kses_post( $message ); ?></p>
	</div>
<?php endif; ?>
