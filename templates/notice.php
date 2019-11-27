<div data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>" data-alert class="<?php echo esc_attr( $post_class ); ?>" <?php if ( $dismissable ) : ?>data-closable<?php endif; ?>>
	<div class="courier-content-wrapper">
		<span class="courier-icon icon-<?php echo esc_attr( $icon ); ?>"></span>
		<span class="courier-content"><?php echo $post_content; ?></span>
		<?php if ( $dismissable ) : ?>
			<a href="#" class="courier-close close">&times;</a>
		<?php endif; ?>
	</div>
</div>
