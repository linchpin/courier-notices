<div class="courier-notices alerts <?php echo esc_attr( 'courier-location-' . $courier_placement ); ?>" data-courier>
	<?php

	$feedback_notices = array();

	global $post;

	$prev_post = $post;

	foreach ( $notices as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );

		$notice = new \Courier\Core\View();
		$notice->assign( 'notice_id', get_the_ID() );
		$notice->assign( 'post_class', get_post_class( 'courier-notice courier_notice callout alert alert-box' ) );
		$notice->assign( 'dismissable', get_post_meta( get_the_ID(), '_courier_dismissible', true ) );
		$notice->assign( 'post_content', apply_filters( 'the_content', get_the_content() ) );
		$notice->render( 'notice' );

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
