<?php
/**
 * Container for server rendered notices.
 *
 * Exposed as an ARIA landmark (role="region" plus an accessible name) so that
 * the notices inside of it are not flagged as content outside of a landmark.
 *
 * @package CourierNotices
 */

$courier_region_label = ( ! empty( $courier_region_label ) ) ? $courier_region_label : courier_notices_get_placement_region_label( $courier_placement );
?>
<div class="courier-notices alerts <?php echo esc_attr( 'courier-location-' . $courier_placement ); ?>" role="region" aria-label="<?php echo esc_attr( $courier_region_label ); ?>" data-courier>
	<?php

	$feedback_notices = array();

	if ( ! empty( $notices ) ) {
		foreach ( $notices as $notice ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			$post_meta    = get_post_meta( $notice->ID );
			$notice_type  = get_the_terms( $notice->ID, 'courier_type' );
			$post_classes = 'courier-notice courier_notice alert alert-box courier_type-' . $notice_type[0]->slug;
			$dismissible  = get_post_meta( $notice->ID, '_courier_dismissible', true );

			if ( $dismissible ) {
				$post_classes .= ' courier-notice-dismissible';
			}

			$notice_view = new \CourierNotices\Core\View();
			$notice_view->assign( 'notice_id', $notice->ID );
			$notice_view->assign( 'notice_class', get_post_class( $post_classes, $notice->ID ) );
			$notice_view->assign( 'dismissible', get_post_meta( $notice->ID, '_courier_dismissible', true ) );
			$notice_view->assign( 'notice_content', apply_filters( 'the_content', $notice->post_content ) );
			$notice_view->assign( 'icon', get_term_meta( $notice_type[0]->term_id, '_courier_type_icon', true ) );
			$notice_view->render( 'notice' );

			if ( has_term( 'feedback', 'courier_type' ) ) {
				$feedback_notices[] = get_the_ID();
			}

			if ( ! empty( $feedback_notices ) ) {
				courier_notices_dismiss_notices( $feedback_notices );
			}
		}
	}
	?>
</div>
