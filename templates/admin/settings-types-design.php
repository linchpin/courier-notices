<?php
/**
 * Provide a meta box view for the settings page
 * Renders a single meta box.
 *
 * @since      1.0
 * @package    CourierNotices
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

?>
<form action="" class="settings-form" method="post">
	<div class="about hero negative-bg">
		<div class="hero-text">
			<h1><?php esc_html_e( 'Design Settings / Info/Types Settings', 'courier-notices' ); ?></h1>
		</div>
	</div>
	<?php
	settings_fields( 'courier_design' );
	$settings_section = new \CourierNotices\Controller\Admin\Fields\Sections();
	$settings_section->do_settings_sections( 'courier' );
	?>
</form>
<br class="clear" />
