<?php
/**
 * Server render for the courier/notices outlet block.
 *
 * The region a template declares for notices, as opposed to the hook-injected
 * placements the legacy `Placement` controller provides. Both paths claim
 * through Helper\Render_Registry, so a page carrying a block AND the legacy
 * hooks renders each region once.
 *
 * @package CourierNotices
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks markup (unused; this block has none).
 * @var WP_Block $block      Block instance.
 */

use CourierNotices\Helper\Notice_Renderer;
use CourierNotices\Helper\Render_Registry;
use CourierNotices\Model\Courier_Notice\Data;

$courier_loading   = isset( $attributes['loading'] ) ? (string) $attributes['loading'] : 'lazy';
$courier_placement = isset( $attributes['placement'] ) ? (string) $attributes['placement'] : 'header';
$courier_number    = isset( $attributes['number'] ) ? (int) $attributes['number'] : 10;

// Whoever gets here first owns the region. The legacy hooks fire on
// wp_body_open and wp_footer, which is before and after template rendering
// respectively, so on a page with both the hook usually wins and this block
// becomes a no-op rather than a duplicate.
if ( ! Render_Registry::claim( $courier_placement ) ) {
	return;
}

$courier_wrapper = get_block_wrapper_attributes(
	array(
		// The legacy region classes, so the existing frontend CSS applies and
		// assets/js/frontend/core.js recognises the region: it selects
		// `.courier-notices[data-courier-ajax="true"]` and reads the placement
		// off the data attribute.
		'class'                  => 'courier-notices alerts courier-location-' . sanitize_html_class( $courier_placement ),
		'data-courier-placement' => $courier_placement,
		'data-courier'           => '',
	)
);

if ( 'server' !== $courier_loading ) {
	/*
	 * Lazy is the default because it is the only mode that is safe behind a
	 * full-page cache: PHP emits an empty region and the frontend fetches the
	 * notices per visitor. Reserve height with the block's minHeight support
	 * if the region sits above the fold, or the arriving notices shift the
	 * page.
	 */
	printf(
		'<div %s data-courier-ajax="true"></div>',
		$courier_wrapper // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Linchpin.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() output is pre-escaped.
	);

	return;
}

/*
 * Server mode renders at request time. Data::get_notices() synthesizes the
 * request context itself (COURIER-1028), so the
 * courier_notices_display_notices_query filter fires with a real page and user
 * without this block having to hand-build $ajax_post_data.
 *
 * Not cache-safe yet, deliberately: get_notices() excludes notices the visitor
 * has dismissed, so this output is per-visitor. Behind a full-page cache one
 * visitor's dismissals would be served to everyone. The Interactivity frontend
 * moves dismissal-hiding to the client for both modes, at which point server
 * mode can stop filtering and become cacheable. Until then this mode is for
 * uncached or personalized sites, which is what the block's help text says.
 */
$courier_notices = ( new Data() )->get_notices(
	array(
		'placement' => $courier_placement,
		'number'    => $courier_number,
		'ids_only'  => false,
	)
);

$courier_fragments = Notice_Renderer::render_many( (array) $courier_notices, $courier_placement );

printf(
	'<div %1$s>%2$s</div>',
	$courier_wrapper, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Linchpin.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() output is pre-escaped.
	implode( '', $courier_fragments ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Linchpin.Security.EscapeOutput.OutputNotEscaped -- Fragments are rendered and escaped by Notice_Renderer and the notice templates.
);
