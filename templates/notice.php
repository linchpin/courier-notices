<?php
	$post_class  = ( is_array( $post_class ) ) ? implode( ' ', array_values( $post_class ) ) : $post_class;
	$icon        = ( isset( $icon ) && '' !== $icon ) ? 'icon-' . $icon : '';
	$dismissible = ( isset( $dismissible ) ) ? 'data-closable' : '';
?>
<?php if ( ! empty( $notice_id ) ) : ?>
<div data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>" data-alert class="<?php echo esc_attr( $post_class ); ?>" <?php echo esc_attr( $dismissible ); ?>>
	<div class="courier-content-wrapper">
		<span class="courier-icon <?php echo esc_attr( $icon ); ?>"></span>
		<span class="courier-content"><?php echo $post_content; ?></span>
		<?php if ( $dismissible ) : ?>
			<a href="#" class="courier-close close">&times;</a>
		<?php endif; ?>
	</div>
</div>
<?php endif;
