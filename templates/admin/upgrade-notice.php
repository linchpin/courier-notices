<?php
/**
 * Upgrade Notification Template
 * Display useful upgrade information within an admin notification
 *
 * @since      1.0.0
 * @package    Courier
 * @subpackage Admin
 */

$courier_version = get_option( 'courier_version', '0.0' );

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

?>
<div class="courier-notice courier-update-notice notice notice-info is-dismissible" data-type="update-notice">
	<div class="table">
		<div class="table-cell">
			<img src="<?php echo esc_attr( COURIER_PLUGIN_URL . 'assets/images/courier-full-logo-full-color@2x.png' ); ?>" alt="">
		</div>
		<div class="table-cell">
			<p class="no-margin">
				<?php
				printf(
					wp_kses_post(
						// translators: %1$s: Version Number %2$s: Link to what's new tab.
						__( 'Thanks for updating Courier to v. (%1$s). <strong>Major release</strong>. <a href="%2$s">what\'s new</a>', 'courier-notices' )
					),
					esc_html( $courier_version ),
					esc_url( admin_url( 'options-general.php?page=courier&tab=new' ) )
				);
				?>
			</p>
			<p class="no-margin">
				<?php echo wp_kses_post( __( 'Initial Release', 'courier-notices' ) ); ?>
			</p>
		</div>
	</div>
</div>
