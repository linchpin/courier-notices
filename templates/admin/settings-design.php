<?php
/**
 * Provide a meta box view for the settings page
 * Renders a single meta box.
 *
 * @since      1.0.0
 * @package    Courier
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}
?>
<form action="options.php" class="settings-form" method="post">
	<div class="about hero negative-bg">
		<div class="hero-text">
			<h1><?php esc_html_e( 'Design Settings', 'courier' ); ?></h1>
		</div>
	</div>
	<?php settings_fields( 'courier_design' ); ?>
	<?php do_settings_sections( 'courier_design' ); ?>
	<?php submit_button(); ?>
</form>
<br class="clear" />
