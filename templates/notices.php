<?php
$courier_css = get_transient( 'courier_notice_css' );
$courier_settings = get_option( 'courier_design', array() );

// If CSS is disabled there is no need to
if ( isset( $courier_settings['disable_css'] ) && 1 === (int) $courier_settings['disable_css'] ) {
	$courier_css = false;
}
?>

<?php if ( false !== $courier_css ) : ?>
	<style id="courier_notice_css">
		<?php echo $courier_css; ?>
	</style>
<?php endif; ?>

<div class="courier-notices alerts <?php echo esc_attr( 'courier-location-' . $courier_placement ); ?>" data-courier>
	<?php

	$feedback_notices = array();

	global $post;

	$prev_post = $post;

	foreach ( $notices as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );

		$post_meta = get_post_meta( get_the_ID() );
		$notice_type = get_the_terms( get_the_ID(), 'courier_type' );

		$post_classes = 'courier-notice courier_notice callout alert alert-box courier_type-' . $notice_type[0]->slug;

		$dismissable = get_post_meta( get_the_ID(), '_courier_dismissible', true );

		if ( $dismissable ) {
			$post_classes .= ' courier-notice-dismissable';
		}

		$notice = new \Courier\Core\View();
		$notice->assign( 'notice_id', get_the_ID() );
		$notice->assign( 'post_class', get_post_class( $post_classes, get_the_ID() ) );
		$notice->assign( 'dismissable', get_post_meta( get_the_ID(), '_courier_dismissible', true ) );
		$notice->assign( 'post_content', apply_filters( 'the_content', get_the_content() ) );
		$notice->assign( 'icon', get_term_meta( $notice_type[0]->term_id, '_courier_type_icon', true ) );
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
