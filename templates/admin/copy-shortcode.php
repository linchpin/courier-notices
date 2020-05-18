<?php
/**
 * Copy Shortcode
 *
 * @package Courier
 * @since 1.0
 */
?>
<div id="courier-shortcode-container" class="misc-pub-section">
	<div class="copy-text" data-copy="courier-shortcode">
		<label for="courier-shortcode" aria-hidden="true" class="screen-reader-text"><?php esc_html_e( 'Courier Shortcode', 'courier-notices' ); ?></label>
		<textarea readonly id="courier-shortcode" class="widefat">[courier_notice id="<?php echo esc_attr( $post_id ); ?>"]</textarea>
		<span class="copy-text dashicons dashicons-clipboard courier-help" data-copy="courier-shortcode" title="<?php esc_attr_e( 'Copy Courier Shortcode', 'courier-notices' ); ?>"></span>
	</div>
	<span class="copy-link-indicator" style="display: none;"></span>
</div>
