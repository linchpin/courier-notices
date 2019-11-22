<div data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>" data-alert class="<?php echo esc_attr( $post_class ); ?>" <?php if ( $dismissable ) : ?>data-closable<?php endif; ?>>
	<div class="courier-content-wrapper">
		<?php echo $post_content; ?>
		<?php if ( $dismissable ) : ?>
			<a href="#" class="courier-close close">&times;</a>
		<?php endif; ?>
	</div>
</div>
