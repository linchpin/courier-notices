<?php
/**
 * Addon to display gravity forms confirmation messages.
 *
 * @since      1.0
 * @package    Courier
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

?>

<div id="courier-gravityforms">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="hero negative-bg">
				<div class="hero-text">
					<h1><?php echo esc_html__( 'Gravity Forms Addon', 'courier-notices' ); ?></h1>
				</div>
			</div>
			<div class="wrapper">

				<?php
				/**
				 * Provide a dashboard view for the plugin
				 *
				 * This file is used to markup the plugin settings and faq pages
				 * Renders the settings page contents.
				 *
				 * @since 1.0.0
				 */
				?>
				<form action="options.php" class="settings-form" method="POST">
					<div class="about hero negative-bg">
						<div class="hero-text">
							<h1><?php esc_html_e( 'GravityForms Settings', 'courier-notices' ); ?></h1>
						</div>
					</div>
					<?php settings_fields( 'courier_gravityforms' ); ?>
					<?php do_settings_sections( 'courier_gravityforms' ); ?>
					<?php submit_button(); ?>
				</form>
				<br class="clear" />
			</div>
		</div>
	</div>
</div>
