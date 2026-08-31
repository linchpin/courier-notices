<?php
/**
 * Container for ajax loaded notices.
 *
 * Exposed as an ARIA landmark (role="region" plus an accessible name) so that
 * the notices inside of it are not flagged as content outside of a landmark.
 *
 * @package CourierNotices
 */

$courier_region_label = ( ! empty( $courier_region_label ) ) ? $courier_region_label : courier_notices_get_placement_region_label( $courier_placement );
?>
<div class="courier-notices alerts <?php echo esc_attr( 'courier-location-' . $courier_placement ); ?>" role="region" aria-label="<?php echo esc_attr( $courier_region_label ); ?>" data-courier-placement="<?php echo esc_attr( $courier_placement ); ?>" data-courier data-courier-ajax="true">
</div>
