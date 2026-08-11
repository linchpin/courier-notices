<?php
/**
 * Server render for the courier/notice block.
 *
 * Emits the same markup contract the legacy templates emit —
 * .courier-notice with the type class, data-courier-notice-id and the
 * dismiss affordance — so the existing frontend JS, type-color CSS and
 * dismissal pipeline keep working while the editor shows the same thing.
 *
 * @package CourierNotices
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks markup.
 * @var WP_Block $block      Block instance, carrying postId context.
 */

$courier_notice_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$courier_layout    = isset( $attributes['layout'] ) ? $attributes['layout'] : 'informational';
$courier_dismiss   = (bool) get_post_meta( $courier_notice_id, '_courier_dismissible', true );

$courier_classes = array(
	'courier-notice',
	'courier_notice',
	'alert',
	'alert-box',
	'courier-layout-' . sanitize_html_class( $courier_layout ),
);

$courier_types = get_the_terms( $courier_notice_id, 'courier_type' );

if ( is_array( $courier_types ) && array() !== $courier_types ) {
	$courier_classes[] = 'courier_type-' . $courier_types[0]->slug;
}

$courier_wrapper = get_block_wrapper_attributes(
	array(
		'class'                  => implode( ' ', $courier_classes ),
		'data-courier-notice-id' => (string) $courier_notice_id,
		'data-alert'             => '',
	)
);

?>
<div <?php echo $courier_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Linchpin.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() output is pre-escaped. ?> <?php echo $courier_dismiss ? 'data-closable' : ''; ?>>
	<div class="courier-content-wrapper">
		<?php if ( ! empty( $attributes['showTitle'] ) ) : ?>
			<h6 class="courier-notice-title"><?php echo esc_html( get_the_title( $courier_notice_id ) ); ?></h6>
		<?php endif; ?>
		<div class="courier-content">
			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Linchpin.Security.EscapeOutput.OutputNotEscaped -- Inner blocks, already rendered and escaped by their own renderers. ?>
		</div>
		<?php if ( $courier_dismiss ) : ?>
			<a href="#" class="courier-close close" aria-label="<?php esc_attr_e( 'Dismiss this notice', 'courier-notices' ); ?>">&times;</a>
		<?php endif; ?>
	</div>
</div>
