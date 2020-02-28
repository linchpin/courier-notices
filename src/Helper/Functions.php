<?php // phpcs:ignore phpcs: WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Courier Functions
 *
 * @package Courier/Helper
 */

use Courier\Model\Courier_Notice\Data as Courier_Notice_Data;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Utility method to add a new notice within the system.
 *
 * @since 1.0
 *
 * @param string       $notice      The notice text.
 * @param string|array $types       The type(s) of notice.
 * @param bool         $global      Whether this notice is global or not.
 * @param bool         $dismissible Whether this notice is dismissible or not.
 * @param int          $user_id     The ID of the user this notice is for.
 *
 * @return bool
 */
function courier_add_notice( $notice = '', $types = array( 'Info' ), $global = false, $dismissible = true, $user_id = 0 ) {

	$notice_args = array(
		'post_type'    => 'courier_notice',
		'post_status'  => 'publish',
		'post_author'  => empty( $user_id ) ? get_current_user_id() : intval( $user_id ),
		'post_name'    => uniqid(),
		'post_title'   => empty( $notice['post_title'] ) ? '' : sanitize_text_field( $notice['post_title'] ),
		'post_excerpt' => empty( $notice['post_excerpt'] ) ? '' : wp_kses_post( $notice['post_excerpt'] ),
		'post_content' => empty( $notice['post_content'] ) ? '' : wp_kses_post( $notice['post_content'] ),
	);

	// If the notice won't have any content in it, just bail.
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

		wp_set_object_terms( $notice_id, $types, 'courier_type', false );

		if ( $global ) {
			wp_set_object_terms( $notice_id, array( 'Global' ), 'courier_scope', false );

			// Clear the global notice cache.
			wp_cache_delete( 'global-notices', 'courier' );
			wp_cache_delete( 'global-dismissible-notices', 'courier' );
			wp_cache_delete( 'global-persistent-notices', 'courier' );
		}

		if ( $dismissible ) {
			update_post_meta( $notice_id, '_courier_dismissible', 1 );
		}

		return true;
	} else {
		return false;
	}
}

/**
 * Returns the user notices.
 *
 * @since 1.0
 *
 * @param array $args Array of arguments.
 *
 * @return array
 */
function courier_get_user_notices( $args = array() ) {

		$data = new Courier_Notice_Data();

		return $data->get_user_notices( $args );

}

/**
 * Query global notices. Cache appropriately
 *
 * @since 1.0
 *
 * @param array $args Array of arguments.
 *
 * @return array|bool|mixed
 */
function courier_get_global_notices( $args = array() ) {

	$data = new Courier_Notice_Data();

	return $data->get_global_notices( $args );
}

/**
 * Query dismissible global notices. Cache appropriately.
 *
 * @since 1.0
 *
 * @param array $args     Query Args
 * @param bool  $ids_only Whether to return only IDs.
 *
 * @return array|bool|mixed
 */
function courier_get_dismissible_global_notices( $args = array(), $ids_only = false ) {

	$data = new Courier_Notice_Data();

	return $data->get_dismissible_global_notices( $args, $ids_only );
}

/**
 * Query not dismissible global notices. Cache appropriately.
 *
 * @since 1.0
 *
 * @param array $args Array of arguments.
 *
 * @return array|bool|mixed
 */
function courier_get_persistent_global_notices( $args = array() ) {
	$data    = new Courier_Notice_Data();

	return $data->get_persistent_global_notices( $args );
}

/**
 * Get Courier all notices.
 *
 * @since 1.0
 *
 * @param array $args Array of arguments.
 *
 * @return array
 */
function courier_get_notices( $args = array() ) {

	error_log('courier_get_notices function:');

	$data = new Courier_Notice_Data();

	return $data->get_notices( $args );
}

/**
 * Display Courier notices on the page on the front end
 *
 * @since 1.0
 *
 * @param array $args Array of arguments.
 */
function courier_display_notices( $args = array() ) {

	$courier_placement = ( ! empty( $args['placement'] ) ) ? $args['placement'] : '';
	$courier_options   = get_option( 'courier_settings', array() );
	$courier_notices   = new \Courier\Core\View();

	$courier_notices->assign( 'courier_placement', $courier_placement );

	error_log( print_r( $courier_options, true ) );

	if ( isset( $courier_options['ajax_notices'] ) && 1 === intval( $courier_options['ajax_notices'] ) ) {
		$output = $courier_notices->get_text_view( 'notices-ajax' );
	} else {

		$notices = courier_get_notices( $args );

		if ( empty( $notices ) ) {
			return;
		}

		$courier_notices->assign( 'notices', $notices );

		$output = $courier_notices->get_text_view( 'notices' );
	}

	$output = apply_filters( 'courier_notices', $output );

	$allowed_html = array(
		'div'   => array(
			'class'                  => array(),
			'data-courier'           => array(),
			'data-courier-notice-id' => array(),
			'data-courier-ajax'      => array(),
			'data-courier-placement' => array(),
			'data-alert'             => array(),
			'data-closable'          => array(),
		),
		'span'  => array(
			'class' => array(),
		),
		'p'     => array(),
		'style' => array(
			'id' => array(),
		),
	);

	$allowed_html = apply_filters( 'courier_allowed_html', $allowed_html );

	echo wp_kses( $output, $allowed_html ); // @todo this should probably be sanitized more extensively.
}

/**
 * Display Courier modal(s) on the front end
 *
 * @since 1.0
 *
 * @todo this should be a template
 *
 * @param array $args Array of arguments.
 */
function courier_display_modals( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'placement' => 'popup-modal',
		)
	);

	$notices = courier_get_notices( $args );

	if ( empty( $notices ) ) {
		return;
	}

	ob_start();
	?>
	<div class="courier-modal-overlay" style="display:none;">
		<?php
		$feedback_notices = array();

		global $post;
		foreach ( $notices as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			setup_postdata( $post );
			?>
			<div class="courier-notices modal" data-courier-notice-id="<?php echo esc_attr( get_the_ID() ); ?>" <?php if ( get_post_meta( get_the_ID(), '_courier_dismissible', true ) ) : ?>data-closable<?php endif; ?>>
				<?php if ( get_post_meta( get_the_ID(), '_courier_dismissible', true ) ) : ?>
					<a href="#" class="courier-close close">&times;</a>
				<?php endif; ?>
				<?php the_content(); ?>
			</div>
			<?php

			if ( has_term( 'feedback', 'courier_type' ) ) {
				$feedback_notices[] = get_the_ID();
			}

			if ( ! empty( $feedback_notices ) ) {
				courier_dismiss_notices( $feedback_notices );
			}
		}
		wp_reset_postdata();
		?>
	</div>
	<?php

	$output = ob_get_contents();

	$output = apply_filters( 'courier_notices', $output );
	ob_end_clean();

	echo $output; // @todo this should probably be filtered more extensively.
}

/**
 * Get a user's owned dismissed notices
 *
 * @since 1.0
 *
 * @param int $user_id The ID of the user to get notices for.
 *
 * @return array|void
 */
function courier_get_dismissed_notices( $user_id = 0 ) {
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
 * @since 1.0
 *
 * @param int $user_id The ID of the user to get notices for.
 *
 * @return array|void
 */
function courier_get_global_dismissed_notices( $user_id = 0 ) {

	$data = new Courier_Notice_Data();

	return $data->get_global_dismissed_notices( $user_id );
}

/**
 * Get all dismissed notices for a user
 *
 * @since 1.0
 *
 * @param int $user_id The ID of the user to get notices for.
 *
 * @return array|bool|mixed
 */
function courier_get_all_dismissed_notices( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) { // phpcs:ignore
			return false;
		}
	}

	return array_merge( courier_get_dismissed_notices( $user_id ), courier_get_global_dismissed_notices( $user_id ) );
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
function courier_dismiss_notices( $notice_ids, $user_id = 0, $force_dismiss = false, $force_trash = false ) {
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
			$errors->add( 'courier_does_not_exist', esc_html__( 'The notice you tried to dismiss does not exist.', 'courier' ) );
			continue;
		}

		// Feedback notices should automatically be sent to the trash. They are meant for viewing once.
		if ( has_term( 'Feedback', 'courier_type', $n_id ) ) {
			wp_trash_post( $n_id );
			continue;
		}

		if ( false === $force_dismiss ) {
			if ( ! get_post_meta( $notice->ID, '_courier_dismissible', true ) ) {
				$errors->add( 'courier_not_dismissible', esc_html__( 'This notice is not dismissible.', 'courier' ) );
				continue;
			}
		}

		if ( true === $force_trash ) {
			wp_trash_post( $n_id );
			continue;
		}

		if ( has_term( 'global', 'courier_scope', $notice->ID ) ) {
			$dismissed_notices = courier_get_global_dismissed_notices( $user_id );

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

