<?php
	$notice_class = ( is_array( $notice_class ) ) ? implode( ' ', array_values( $notice_class ) ) : $notice_class;
	$icon         = ( isset( $icon ) && '' !== $icon ) ? 'icon-' . $icon : '';
	$dismissible  = ( ! empty( $dismissible ) ) ? 'data-closable' : '';
?>
<div class="courier-notices modal <?php echo esc_attr( $notice_class ); ?>" <?php esc_attr( $dismissible ); ?> data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>">
	<?php if ( $dismissible ) : ?>
		<a href="#" class="courier-close modal-close close">&times;</a>
	<?php endif; ?>
	<?php if ( 'hide' !== $show_hide_title ) : ?>
		<h6><?php echo wp_kses_post( $notice_title ); ?></h6>
	<?php endif; ?>
	<div class="courier-content"><?php echo wp_kses_post( $notice_content ); ?></div>
</div>
