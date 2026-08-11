<?php
/**
 * Notice Renderer
 *
 * @package CourierNotices\Helper
 * @since 2.0.0
 */

namespace CourierNotices\Helper;

use CourierNotices\Core\View;
use CourierNotices\Model\Courier_Notice\Data;

/**
 * Notice_Renderer Class
 *
 * Turns a notice post into its HTML fragment. Extracted from two near-identical
 * copies in Courier_REST_Controller (the `display` and `display/all` handlers)
 * because the courier/notices block's `server` mode needs a third.
 *
 * @since 2.0.0
 */
class Notice_Renderer {

	/**
	 * Render one notice.
	 *
	 * A courier/notice block IS the notice — it renders its own chrome from
	 * postId context, so the legacy template wrapper must not double-wrap it.
	 * Everything else goes through the legacy per-style template.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Post $notice    The notice to render.
	 * @param string   $placement Placement being rendered, which forces the
	 *                            modal template for the modal region.
	 * @param Data     $data      Optional data model, to avoid re-instantiating per notice.
	 *
	 * @return string
	 */
	public static function render( $notice, $placement = '', $data = null ) {
		if ( ! $notice instanceof \WP_Post ) {
			return '';
		}

		if ( has_block( 'courier/notice', $notice ) ) {
			return Utils::prepare_notice_content( $notice->post_content, $notice->ID );
		}

		if ( ! $data instanceof Data ) {
			$data = new Data();
		}

		$notice_data  = $data->get_notice_meta( $notice->ID );
		$post_classes = array( 'courier-notice courier_notice alert alert-box' );

		if ( is_array( $notice_data['type'] ) && array() !== $notice_data['type'] ) {
			$post_classes[] = 'courier_type-' . $notice_data['type'][0]->slug;
		}

		$post_classes[] = $notice_data['is_confirmation'] ? 'gform-confirmation' : '';

		$view = new View();
		$view->assign( 'notice_id', $notice->ID );
		$view->assign( 'show_hide_title', $notice_data['show_hide_title'] );
		$view->assign( 'notice_title', courier_notices_the_notice_title( $notice->post_title, '<h6 class="courier-notice-title">', '</h6>', false ) );
		$view->assign( 'notice_class', implode( ' ', get_post_class( $post_classes, $notice->ID ) ) );
		$view->assign( 'dismissible', get_post_meta( $notice->ID, '_courier_dismissible', true ) );
		$view->assign( 'icon', $notice_data['icon'] );
		$view->assign( 'notice_content', Utils::prepare_notice_content( $notice->post_content ) );

		return $view->get_text_view( self::template_for( $notice_data, $placement ) );
	}

	/**
	 * Render a list of notices, keyed by notice ID.
	 *
	 * @since 2.0.0
	 *
	 * @param array<int, \WP_Post> $notices   Notices to render.
	 * @param string               $placement Placement being rendered.
	 *
	 * @return array<int, string>
	 */
	public static function render_many( array $notices, $placement = '' ) {
		$data     = new Data();
		$rendered = array();

		foreach ( $notices as $notice ) {
			if ( ! $notice instanceof \WP_Post ) {
				continue;
			}

			$rendered[ $notice->ID ] = self::render( $notice, $placement, $data );
		}

		return $rendered;
	}

	/**
	 * Pick the template for a notice.
	 *
	 * Resolved per notice, on purpose. The `display` handler used to hoist its
	 * $style variable outside the loop and only reassign it when a notice
	 * carried a courier_style term — so a notice with no style term silently
	 * rendered through the PREVIOUS notice's template. Scoping it here fixes
	 * that by construction.
	 *
	 * @since 2.0.0
	 *
	 * @param array<string, mixed> $notice_data Meta from Data::get_notice_meta().
	 * @param string               $placement   Placement being rendered.
	 *
	 * @return string
	 */
	private static function template_for( array $notice_data, $placement ) {
		if ( 'popup-modal' === $placement ) {
			return 'notice-popup-modal';
		}

		if ( isset( $notice_data['style'] ) && is_array( $notice_data['style'] ) && array() !== $notice_data['style'] ) {
			return 'notice-' . $notice_data['style'][0]->slug;
		}

		return 'notice-informational';
	}
}
