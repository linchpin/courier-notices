<?php
/**
 * Server render for the courier/notice-icon block.
 *
 * The icon follows the notice's type by default — the same
 * _courier_type_icon term meta the legacy templates read — and an explicit
 * attribute overrides it per notice.
 *
 * @package CourierNotices
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Unused.
 * @var WP_Block $block      Block instance, carrying postId context.
 */

$courier_icon = isset( $attributes['icon'] ) ? $attributes['icon'] : '';

if ( '' === $courier_icon ) {
	$courier_notice_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
	$courier_types     = get_the_terms( $courier_notice_id, 'courier_type' );

	if ( is_array( $courier_types ) && array() !== $courier_types ) {
		$courier_icon = (string) get_term_meta( $courier_types[0]->term_id, '_courier_type_icon', true );
	}
}

if ( '' === $courier_icon ) {
	$courier_icon = 'info';
}

?>
<span class="courier-icon icon-<?php echo esc_attr( sanitize_html_class( $courier_icon ) ); ?>" aria-hidden="true"></span>
