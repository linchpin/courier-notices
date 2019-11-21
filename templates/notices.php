<div class="courier-notices alerts <?php echo esc_attr( $courier_placement ); ?>" data-courier>
	<?php

	$feedback_notices = array();

	global $post;

	$prev_post = $post;

	foreach ( $notices as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );
		?>
		<div data-courier-notice-id="<?php echo esc_attr( get_the_ID() ); ?>" data-alert <?php post_class( 'courier-notice courier_notice callout alert alert-box' ); ?><?php if ( get_post_meta( get_the_ID(), '_courier_dismissible', true ) ) : ?>data-closable<?php endif; ?>>
			<div class="courier-content-wrapper">
				<?php the_content(); ?>
				<?php if ( get_post_meta( get_the_ID(), '_courier_dismissible', true ) ) : ?>
					<a href="#" class="courier-close close">&times;</a>
				<?php endif; ?>
			</div>
		</div>
		<?php

		if ( has_term( 'feedback', 'courier_type' ) ) {
			$feedback_notices[] = get_the_ID();
		}

		if ( ! empty( $feedback_notices ) ) {
			courier_dismiss_notices( $feedback_notices );
		}
	}
	wp_reset_postdata();

	$post = $prev_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	?>
</div>
