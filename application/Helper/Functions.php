<?php // phpcs:ignore phpcs: WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Courier Functions
 *
 * @package CourierNotices/Helper
 */

use CourierNotices\Model\Courier_Notice\Data as Courier_Notice_Data;
use CourierNotices\Controller\Courier_Types as Courier_Types;
use CourierNotices\Helper\Utils;
use CourierNotices\Core\View;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Utility method to add a new notice within the system.
 *
 * @param string       $notice      The notice text.
 * @param string|array $types       The type(s) of notice.
 * @param bool         $global      Whether this notice is global or not.
 * @param bool         $dismissible Whether this notice is dismissible or not.
 * @param int          $user_id     The ID of the user this notice is for.
 *
 * @since 1.2.0
 *
 * @return bool|int Return either false or the Post ID for the created courier_notice.
 */
function courier_notices_add_notice( $notice = '', $types = array( 'Informational' ), $global = false, $dismissible = true, $user_id = 0, $style = 'Informational', $placement = array( 'header' ) ) {
	$user_id = empty( $user_id ) ? get_current_user_id() : intval( $user_id );

	if ( 0 !== $user_id ) {
		$slug = 'courier-' . sanitize_title( $notice ) . '-' . $user_id;
	} else {
		$slug = uniqid( 'courier-' );
	}

	$notice_args = [
		'post_type'    => 'courier_notice',
		'post_status'  => 'publish',
		'post_author'  => $user_id,
		'post_name'    => $slug,
		'post_title'   => empty( $notice['post_title'] ) ? '' : sanitize_text_field( $notice['post_title'] ),
		'post_excerpt' => empty( $notice['post_excerpt'] ) ? '' : wp_kses_post( $notice['post_excerpt'] ),
		'post_content' => empty( $notice['post_content'] ) ? '' : wp_kses_post( $notice['post_content'] ),
	];

	// If the notice doesn't have any content in it, just bail.
	if ( empty( $notice_args['post_content'] ) ) {
		if ( empty( $notice_args['post_title'] ) ) {
			return '';
		} else {
			$notice_args['post_content'] = $notice_args['post_title'];
		}
	}

	if ( $notice_id = wp_insert_post( $notice_args ) ) { // phpcs:ignore

		if ( ! is_array( $types ) ) {
			$types = explode( ',', $types );
		}

		if ( ! is_array( $placement ) ) {
			$placement = explode( ',', $placement );
		}

		wp_set_object_terms( $notice_id, $style, 'courier_style', true );
		wp_set_object_terms( $notice_id, $types, 'courier_type', true );
		wp_set_object_terms( $notice_id, $placement, 'courier_placement', true );

		if ( $global ) {
			wp_set_object_terms( $notice_id, array( 'Global' ), 'courier_scope', false );

			// Clear the global notice cache.
			wp_cache_delete( 'global-notices', 'courier-notices' );
			wp_cache_delete( 'global-dismissible-notices', 'courier-notices' );
			wp_cache_delete( 'global-persistent-notices', 'courier-notices' );
		}

		if ( ! empty( $user_id ) ) {
			wp_set_object_terms( $notice_id, array( 'User' ), 'courier_scope', false );
		}

		if ( $dismissible ) {
			update_post_meta( $notice_id, '_courier_dismissible', 1 );
		}

		return $notice_id;
	} else {
		return false;
	}

}


/**
 * Returns the user notices.
 *
 * @since 1.2.0
 *
 * @param array $args Array of arguments.
 *
 * @return array
 */
function courier_notices_get_user_notices( $args = array() ) {
	$data = new Courier_Notice_Data();

	return $data->get_user_notices( $args );

}


/**
 * Query global notices. Cache appropriately
 *
 * @since 1.2.0
 *
 * @param array $args Array of arguments.
 *
 * @return array|bool|mixed
 */
function courier_notices_get_global_notices( $args = array() ) {
	$data = new Courier_Notice_Data();

	return $data->get_global_notices( $args );

}


/**
 * Query dismissible global notices. Cache appropriately.
 *
 * @since 1.2.0
 *
 * @param array $args     Query Args
 * @param bool  $ids_only Whether to return only IDs.
 *
 * @return array|bool|mixed
 */
function courier_notices_get_dismissible_global_notices( $args = array(), $ids_only = false ) {
	$data = new Courier_Notice_Data();

	return $data->get_dismissible_global_notices( $args, $ids_only );

}


/**
 * Query not dismissible global notices. Cache appropriately.
 *
 * @since 1.2.0
 *
 * @param array $args Array of arguments.
 *
 * @return array|bool|mixed
 */
function courier_notices_get_persistent_global_notices( $args = array() ) {
	$data = new Courier_Notice_Data();

	return $data->get_persistent_global_notices( $args );

}


/**
 * Get Courier all notices.
 *
 * @since 1.2.0
 *
 * @param array $args Array of arguments.
 *
 * @return array
 */
function courier_notices_get_notices( $args = array() ) {
	$data = new Courier_Notice_Data();

	return $data->get_notices( $args );

}


/**
 * Display Courier notices on the page on the front end
 *
 * @since 1.2.0
 *
 * @param array $args Array of arguments.
 */
function courier_notices_display_notices( $args = array() ) {
	$courier_placement    = ( ! empty( $args['placement'] ) ) ? $args['placement'] : '';

	if ( empty( $courier_placement ) ) {
		return false;
	}

	$courier_style        = ( ! empty( $args['style'] ) ) ? $args['style'] : '';
	$courier_options      = get_option( 'courier_settings', array() );
	$courier_notices_view = new View();
	$courier_notices_view->assign( 'courier_placement', $courier_placement );
	$courier_notices_view->assign( 'courier_style', $courier_style );

	$output = $courier_notices_view->get_text_view( 'notices-ajax' );

	$output       = apply_filters( 'courier_notices', $output );
	$allowed_html = Utils::get_safe_markup();

	echo wp_kses( $output, $allowed_html );

}


/**
 * Display Courier modal(s) on the front end
 *
 * @since 1.2.0
 *
 * @todo this should be a template
 *
 * @param array $args Array of arguments.
 */
function courier_notices_display_modals( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'placement' => 'popup-modal',
		)
	);

	$courier_placement = ( ! empty( $args['placement'] ) ) ? $args['placement'] : '';
	$courier_style     = ( ! empty( $args['style'] ) ) ? $args['style'] : '';
	$courier_options   = get_option( 'courier_settings', array() );
	$courier_notices   = new \CourierNotices\Core\View();
	$courier_notices->assign( 'courier_placement', $courier_placement );
	$courier_notices->assign( 'courier_style', $courier_style );

	$output = $courier_notices->get_text_view( 'notices-ajax-modal' );

	$output       = apply_filters( 'courier_notices_modal', $output );
	$allowed_html = Utils::get_safe_markup();
	echo wp_kses( $output, $allowed_html );

}


/**
 * Get a user's owned dismissed notices
 *
 * @since 1.2.0
 *
 * @param int $user_id The ID of the user to get notices for.
 *
 * @return array|void
 */
function courier_notices_get_dismissed_notices( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) { // phpcs:ignore
			return array();
		}
	}

	$dismissed_notices = array();

	$args = array(
		'post_type'      => 'courier_notice',
		'posts_per_page' => 100,
		'offset'         => 0,
		'tax_query'      => array(
			array(
				'taxonomy' => 'courier_status',
				'field'    => 'slug',
				'terms'    => 'dismissed',
			),
		),
		'author'         => $user_id,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	);

	$dismissed_query = new WP_Query( $args );

	while ( $dismissed_query->have_posts() ) {
		$dismissed_notices = array_merge( $dismissed_notices, $dismissed_query->posts );
		$args['offset']    = $args['offset'] + $args['posts_per_page'];
		$dismissed_query   = new WP_Query( $args );
	}

	return $dismissed_notices;

}


/**
 * Get a user's dismissed global notices
 *
 * @since 1.2.0
 *
 * @param int $user_id The ID of the user to get notices for.
 *
 * @return array|void
 */
function courier_notices_get_global_dismissed_notices( $user_id = 0 ) {
	$data = new Courier_Notice_Data();

	return $data->get_global_dismissed_notices( $user_id );

}


/**
 * Get all dismissed notices for a user
 *
 * @since 1.2.0
 *
 * @param int $user_id The ID of the user to get notices for.
 *
 * @return array|bool|mixed
 */
function courier_notices_get_all_dismissed_notices( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) { // phpcs:ignore
			return false;
		}
	}

	return array_merge( courier_notices_get_dismissed_notices( $user_id ), courier_notices_get_global_dismissed_notices( $user_id ) );

}


/**
 * Dismiss a notice for a user
 *
 * @param array $notice_ids    Array of notice IDs.
 * @param int   $user_id       The ID of the user to get notices for.
 * @param bool  $force_dismiss Whether to force dismiss.
 * @param bool  $force_trash   Whether to force trash.
 *
 * @return bool|WP_Error
 */
function courier_notices_dismiss_notices( $notice_ids, $user_id = 0, $force_dismiss = false, $force_trash = false ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) { // phpcs:ignore
			return false;
		}
	}

	wp_cache_delete( $user_id, 'user_meta' );
	$errors = new WP_Error();

	$notice_ids = (array) $notice_ids;

	foreach ( $notice_ids as $n_id ) {
		if ( ! $notice = get_post( $n_id ) ) { // phpcs:ignore
			$errors->add( 'courier_does_not_exist', esc_html__( 'The notice you tried to dismiss does not exist.', 'courier-notices' ) );
			continue;
		}

		// Feedback notices should automatically be sent to the trash. They are meant for viewing once.
		if ( has_term( 'Feedback', 'courier_type', $n_id ) ) {
			wp_trash_post( $n_id );
			continue;
		}

		if ( false === $force_dismiss ) {
			if ( ! get_post_meta( $notice->ID, '_courier_dismissible', true ) ) {
				$errors->add( 'courier_not_dismissible', esc_html__( 'This notice is not dismissible.', 'courier-notices' ) );
				continue;
			}
		}

		if ( true === $force_trash ) {
			wp_trash_post( $n_id );
			continue;
		}

		if ( has_term( 'global', 'courier_scope', $notice->ID ) ) {
			$dismissed_notices = courier_notices_get_global_dismissed_notices( $user_id );

			if ( ! in_array( $notice->ID, $dismissed_notices, true ) ) {
				$dismissed_notices[] = $notice->ID;
				update_user_option( $user_id, 'courier_dismissals', $dismissed_notices );
			}
		} elseif ( (int) $user_id === (int) $notice->post_author ) {
			wp_set_object_terms( $notice->ID, 'dismissed', 'courier_status', false );
		}
	}

	if ( empty( $errors->get_error_codes() ) ) {
		return true;
	} else {
		return $errors;
	}

}


/**
 * Get Courier types CSS to be used for frontend display
 *
 * @since 1.2.0
 *
 * @return string|void
 */
function courier_notices_get_css() {
	$courier_css = get_transient( 'courier_notice_css' );

	if ( false === $courier_css ) {
		$courier_css = Courier_Types::save_css_transient();
	}

	return wp_strip_all_tags( $courier_css );

}


/**
 * Display the title for a Courier Notice
 *
 * @since 1.3.0
 *
 * @param        $title
 * @param string $before
 * @param string $after
 * @param bool   $echo
 *
 * @return mixed|string|void
 */
function courier_notices_the_notice_title( $title, $before = '', $after = '', $echo = true ) {
	if ( 0 === strlen( $title ) ) {
		return '';
	}

	$title = $before . $title . $after;
	$title = apply_filters( 'courier_notices_the_notice_title', $title );

	if ( $echo ) {
		echo wp_kses_post( $title );
	} else {
		return $title;
	}

}


/**
 * Utility method to check if the block editor is enabled
 * We use this to show/hide different elements in the admin, meta boxes
 * vs slotfills (in the block editor) for example
 *
 * @since 2.0.0
 *
 * @return bool
 */
function courier_notices_is_block_editor(): bool {
	$current_screen = get_current_screen();
	return ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() );
}