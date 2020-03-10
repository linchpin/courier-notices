<?php
	$notice_class = ( is_array( $notice_class ) ) ? implode( ' ', array_values( $notice_class ) ) : $notice_class;
	$icon         = ( isset( $icon ) && '' !== $icon ) ? 'icon-' . $icon : '';
	$dismissible  = ( ! empty( $dismissible ) ) ? 'data-closable' : '';
?>
<div class="courier-modal-overlay" style="display:none;">
	<div class="courier-notices modal <?php echo esc_attr( $notice_class ); ?>" data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>" <?php esc_attr( $dismissible ); ?>>
		<?php if ( $dismissible ) : ?>
			<a href="#" class="courier-close close">&times;</a>
		<?php endif; ?>
		<div class="courier-content"><?php echo wp_kses_post( $notice_content ); ?></div>
	</div>
</div>
