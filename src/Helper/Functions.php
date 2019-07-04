<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Utility method to add a new notice within the system.
 *
 * @param $notice
 * @param string|array $types
 * @param bool $global
 * @param bool $dismissible
 * @param string $user_id
 *
 * @return bool
 */
function courier_add_notice( $notice = '', $types = array( 'Info' ), $global = false, $dismissible = true, $user_id = '' ) {

	$notice_args = array(
		'post_type'    => 'courier_notice',
		'post_status'  => 'publish',
		'post_author'  => empty( $user_id ) ? get_current_user_id() : intval( $user_id ),
		'post_name'    => uniqid(),
		'post_title'   => empty( $notice['post_title'] ) ? '' : sanitize_text_field( $notice['post_title'] ),
		'post_excerpt' => empty( $notice['post_excerpt'] ) ? '' : wp_kses_post( $notice['post_excerpt'] ),
		'post_content' => empty( $notice['post_content'] ) ? '' : wp_kses_post( $notice['post_content'] ),
	);

	//If the notice won't have any content in it, just bail.
	if ( empty( $notice_args['post_content'] ) ) {
		if ( empty( $notice_args['post_title'] ) ) {
			return '';
		} else {
			$notice_args['post_content'] = $notice_args['post_title'];
		}
	}

	if ( $notice_id = wp_insert_post( $notice_args ) ) {
		if ( ! is_array( $types ) ) {
			$types = explode( ',', $types );
		}

		wp_set_object_terms( $notice_id, $types, 'courier_type', false );

		if ( $global ) {
			wp_set_object_terms( $notice_id, array( 'Global' ), 'courier_scope', false );

			//Clear the global notice cache
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
 * Query global notices. Cache appropriately
 *
 * @param array $args
 *
 * @return array|bool|mixed
 */
function courier_get_global_notices( $args = array() ) {

	$defaults = array(
		'ids_only'   => true,
		'number'     => 100,
		'placement'  => 'header',
		'query_args' => array(),
	);

	$defaults  = apply_filters( 'courier_get_global_notices_default_settings', $defaults );
	$args      = wp_parse_args( $args, $defaults );
	$cache_key = 'global-' . sanitize_title( $args['placement'] ) . '-notices';
	$cache     = wp_cache_get( $cache_key, 'courier' );

	if ( false !== $cache ) {
		if ( $args['ids_only'] ) {
			return wp_list_pluck( $cache, 'ID' );
		}

		return $cache;
	}

	$query_args = array(
		'post_type'      => 'courier_notice',
		'post_status'    => array(
			'publish',
		),
		'posts_per_page' => $args['number'],
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => array(
			array(
				'taxonomy' => 'courier_scope',
				'field'    => 'slug',
				'terms'    => array( 'global' ),
				'operator' => 'IN',
			),
		),
		'no_found_rows'  => true,
	);

	// Only include the notices for a specific placement
	if ( ! empty( $args['placement'] ) ) {
		$query_args['tax_query']['relation'] = 'AND';
		$query_args['tax_query'][] = array(
			'taxonomy' => 'courier_placement',
			'field'    => 'slug',
			'terms'    => is_array( $args['placement'] ) ? $args['placement'] : array( $args['placement'] ),
			'operator' => 'IN',
		);
	}

	$global_notices_query = new WP_Query( $query_args );

	wp_cache_set( $cache_key, $global_notices_query->posts, 'courier', 300 );

	if ( isset( $args['ids_only'] ) && false !== $args['ids_only'] ) {
		return wp_list_pluck( $global_notices_query->posts, 'ID' );
	} else {
		return $global_notices_query->posts;
	}
}

/**
 * Query dismissible global notices. Cache appropriately.
 *
 * @param bool $ids_only
 *
 * @return array|bool|mixed
 */
function courier_get_dismissible_global_notices( $ids_only = false ) {
	$cache = wp_cache_get( 'global-dismissible-notices', 'courier' );

	if ( false !== $cache ) {
		if ( $ids_only ) {
			return wp_list_pluck( $cache, 'ID' );
		}

		return $cache;
	}

	$global_notices = courier_get_global_notices(
		array(
			'ids_only' => true,
		)
	);

	if ( empty( $global_notices ) ) {
		return array();
	}

	$args = array(
		'post_type'      => 'courier_notice',
		'post_status'    => array(
			'publish',
		),
		'posts_per_page' => 100,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post__in'       => array_map( 'intval', $global_notices ),
		'meta_query'     => array(
			array(
				'key'     => '_courier_dismissible',
				'compare' => 'EXISTS',
			),
		),
		'no_found_rows'  => true,
	);

	$global_notices_query = new WP_Query( $args );

	wp_cache_set( 'global-dismissible-notices', $global_notices_query->posts, 'courier', 300 );

	if ( $ids_only ) {
		return wp_list_pluck( $global_notices_query->posts, 'ID' );
	} else {
		return $global_notices_query->posts;
	}
}

/**
 * Query not dismissible global notices. Cache appropriately.
 *
 * @param array $args
 *
 * @return array|bool|mixed
 */
function courier_get_persistent_global_notices( $args = array() ) {

	$defaults = array(
		'ids_only'   => false,
		'number'     => 100,
		'placement'  => 'header',
		'query_args' => array(),
	);

	$defaults  = apply_filters( 'courier_get_global_persistent_notices_default_settings', $defaults );
	$args      = wp_parse_args( $args, $defaults );
	$cache_key = 'global-persistent-' . sanitize_title( $args['placement'] ) . '-notices';
	$cache     = wp_cache_get( $cache_key, 'courier' );

	if ( false !== $cache ) {
		if ( true === $args['ids_only'] ) {
			return wp_list_pluck( $cache, 'ID' );
		}

		return $cache;
	}

	$global_args    = wp_parse_args( array( 'ids_only' => true ), $args );
	$global_notices = courier_get_global_notices( $global_args );

	if ( empty( $global_notices ) ) {
		return array();
	}

	$args = array(
		'post_type'      => 'courier_notice',
		'post_status'    => array(
			'publish',
		),
		'posts_per_page' => 100,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post__in'       => array_map( 'intval', $global_notices ),
		'meta_query'     => array(
			array(
				'key'     => '_courier_dismissible',
				'compare' => 'NOT EXISTS',
			),
		),
		'no_found_rows'  => true,
	);

	$global_persistent_notices_query = new WP_Query( $args );

	wp_cache_set( $cache_key, $global_persistent_notices_query->posts, 'courier', 300 );

	if ( isset( $args['ids_only'] ) && true === $args['ids_only'] ) {
		return wp_list_pluck( $global_persistent_notices_query->posts, 'ID' );
	} else {
		return $global_persistent_notices_query->posts;
	}
}

/**
 * Get Courier notices for a user.
 *
 * @since 1.0
 *
 * @param string $user_id
 * @param bool   $include_global
 * @param bool   $dismissed
 * @param bool   $prioritize_persistent_global
 * @param bool   $ids_only
 * @param int    $number
 * @param array  $wp_query_args
 *
 * @return array
 */

function courier_get_notices( $args = array() ) {

	$defaults = array(
		'user_id'                      => '',
		'include_global'               => true,
		'include_dismissed'            => false,
		'prioritize_persistent_global' => true,
		'ids_only'                     => true,
		'number'                       => 4,
		'placement'                    => 'header',
		'query_args'                   => array(),
	);

	$defaults = apply_filters( 'courier_get_notices_default_settings', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['user_id'] ) ) {
		if ( ! $args['user_id'] = get_current_user_id() ) {
			return array();
		}
	}

	$number = min( $args['number'], 100 ); // Catch if someone tries to pass more than 100 notices in one shot. Bad practice and should be filtered.
	$number = apply_filters( 'courier_override_notices_number', $number );

	$results = array();

	$query_args = array(
		'post_type'      => 'courier_notice',
		'post_status'    => array(
			'publish',
		),
		'posts_per_page' => $number,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => array(
			'relation' => 'AND',
		),
		'author'         => $args['user_id'],
		'fields'         => 'ids',
		'no_found_rows'  => true,
	);

	// Do not include dismissed notices
	if ( ! $args['include_dismissed'] ) {
		$query_args['tax_query'][] = array(
			'taxonomy' => 'courier_status',
			'field'    => 'name',
			'terms'    => array( 'Dismissed' ),
			'operator' => 'NOT IN',
		);
	}

	// Only include the notices for a specific placement
	if ( ! empty( $args['placement'] ) ) {
		$query_args['tax_query']['relation'] = 'AND';

		$query_args['tax_query'][] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'courier_placement',
				'field'    => 'slug',
				'terms'    => is_array( $args['placement'] ) ? $args['placement'] : array( $args['placement'] ),
				'operator' => 'IN',
			),
		);
	}

	/**
	 * Exclude global notices written by this user.
	 * This prevents dismissed global notices from persisting for the admin that created it.
	 */
	if ( current_user_can( 'edit_posts' ) ) {
		$query_args['tax_query'][] = array(
			'taxonomy' => 'courier_scope',
			'field'    => 'name',
			'terms'    => array( 'Global' ),
			'operator' => 'NOT IN',
		);
	}

	$notices_query = new WP_Query( $query_args );

	// Account for global notices
	$global_posts = array();

	if ( true === $args['include_global'] ) {
		$global_posts = courier_get_global_notices(
			array(
				'ids_only'  => false,
				'placement' => $args['placement'],
			)
		);

		// Exclude dismissed
		if ( ! $args['include_dismissed'] ) {
			$global_dismissed = courier_get_global_dismissed_notices( $args['user_id'] );

			foreach ( $global_posts as $key => $global_post ) {
				if ( ( is_object( $global_post ) && in_array( $global_post->ID, $global_dismissed, true ) ) || in_array( $global_post, $global_dismissed, true ) ) {
					unset( $global_posts[ $key ] );
				}
			}
		}
	}

	$post_list = array_merge( $notices_query->posts, $global_posts );

	// Prioritize Persistent Global Notes to the top by getting them separately and putting them at the front of the line.
	if ( true === $args['prioritize_persistent_global'] ) {
		$persistent_global = courier_get_persistent_global_notices(
			array(
				'ids_only'  => false,
				'placement' => $args['placement'],
			)
		);

		if ( ! empty( $persistent_global ) ) {

			$results = array_merge( $results, $persistent_global );

			if ( false === $args['ids_only'] ) {
				$difference = array_diff( $post_list, $persistent_global );
			} else {
				$difference = array_diff(
					wp_list_pluck( $post_list, 'ID' ),
					wp_list_pluck( $persistent_global, 'ID' )
				);
			}

			// If there is no difference, then the persistent global notices are the only ones left.
			if ( empty( $difference ) ) {
				return $results;
			} else {
				$post_list = $difference;
			}
		}
	}

	if ( empty( $post_list ) ) {
		return array();
	}

	$query_args = array(
		'post_type'      => 'courier_notice',
		'post_status'    => array(
			'publish',
		),
		'posts_per_page' => $number,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => array(),
		'post__in'       => $post_list,
	);

	if ( true === $args['ids_only'] ) {
		$query_args['fields'] = 'ids';
	}

	$query_args          = wp_parse_args( $args['query_args'], $query_args );
	$final_notices_query = new WP_Query( $query_args );

	return array_merge( $results, $final_notices_query->posts );
}

/**
 * Display Courier notices on the page on the front end
 *
 * @since 1.0
 *
 * @param array $args
 */
function courier_display_notices( $args = array() ) {

	$notices = courier_get_notices( $args );

	if ( empty( $notices ) ) {
		return;
	}

	ob_start();
	?>
	<div class="courier-notices alerts">
		<?php
		$feedback_notices = array();

		foreach ( $notices as $notice ) {
			setup_postdata( $notice );
			?>
			<div data-courier-notice-id="<?php echo esc_attr( get_the_ID() ); ?>" data-alert <?php post_class( 'courier-notice courier_notice callout alert alert-box' ); ?><?php if ( get_post_meta( get_the_ID(), '_courier_dismissible', true ) ) : ?>data-closable<?php endif; ?>>
				<?php the_content(); ?>
				<?php if ( get_post_meta( get_the_ID(), '_courier_dismissible', true ) ) : ?>
					<a href="#" class="courier-close close">&times;</a>
				<?php endif; ?>
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
 * @param string $user_id
 *
 * @return array|void
 */
function courier_get_dismissed_notices( $user_id = '' ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) {
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
 * @param string $user_id
 *
 * @return array|void
 */
function courier_get_global_dismissed_notices( $user_id = '' ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) {
			return array();
		}
	}

	if ( ! $dismissed_notices = get_user_option( 'courier_dismissals', $user_id ) ) {
		$dismissed_notices = array();
	}

	return array_map( 'intval', $dismissed_notices );
}

/**
 * Get all dismissed notices for a user
 *
 * @param string $user_id
 * @param bool|false $global_only
 *
 * @return array|bool|mixed
 */
function courier_get_all_dismissed_notices( $user_id = '' ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) {
			return false;
		}
	}

	return array_merge( courier_get_dismissed_notices( $user_id ), courier_get_global_dismissed_notices( $user_id ) );
}

/**
 * Dismiss a notice for a user
 *
 * @param        $notice_ids
 * @param string $user_id
 * @param bool   $force_dismiss
 * @param bool   $force_trash
 *
 * @return bool|WP_Error
 */
function courier_dismiss_notices( $notice_ids, $user_id = '', $force_dismiss = false, $force_trash = false ) {
	if ( empty( $user_id ) ) {
		if ( ! $user_id = get_current_user_id() ) {
			return false;
		}
	}

	wp_cache_delete( $user_id, 'user_meta' );
	$errors = new WP_Error();

	$notice_ids = (array) $notice_ids;

	foreach ( $notice_ids as $n_id ) {
		if ( ! $notice = get_post( $n_id ) ) {
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

