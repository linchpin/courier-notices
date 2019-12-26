<?php
	$post_class = ( is_array( $post_class ) ) ? implode( ' ', array_values( $post_class ) ) : $post_class;
?>

<div data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>" data-alert class="<?php echo esc_attr( $post_class ); ?>" <?php if ( $dismissable ) : ?>data-closable<?php endif; ?>>
	<div class="courier-content-wrapper">
		<div class="courier-icon icon-<?php echo esc_attr( $icon ); ?>"></div>
		<div class="courier-content"><?php echo $post_content; ?></div>
		<?php if ( $dismissable ) : ?>
			<a href="#" class="courier-close close">&times;</a>
		<?php endif; ?>
	</div>
</div>
