<?php
/**
 * Container for ajax loaded modal notices.
 *
 * The overlay is exposed as a modal dialog: role="dialog", aria-modal and an
 * accessible name. It stays hidden until a notice is displayed, so it is never
 * content sitting outside of a landmark. Focus handling, focus trapping and
 * Escape to close live in assets/js/frontend/modal.js.
 *
 * @package CourierNotices
 */

$courier_region_label = ( ! empty( $courier_region_label ) ) ? $courier_region_label : courier_notices_get_placement_region_label( $courier_placement );
?>
<div class="courier-notices alerts <?php echo esc_attr( 'courier-location-' . $courier_placement ); ?>" data-courier-placement="<?php echo esc_attr( $courier_placement ); ?>" data-courier data-courier-ajax="true">
	<div class="courier-modal-overlay hide" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( $courier_region_label ); ?>" tabindex="-1">
	</div>
</div>
