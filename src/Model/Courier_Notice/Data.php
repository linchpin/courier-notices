<?php // phpcs:ignore phpcs: WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Courier Notice Data Model
 */
namespace Courier\Model\Courier_Notice;

/**
 * Courier_Notice Class
 */
class Data {

	/**
	 * Courier_Notice constructor
	 *
	 * @since 1.0.5
	 */
	public function __construct() {
	}

	/**
	 * Query global notices. Cache appropriately
	 *
	 * @since 1.0.5
	 *
	 * @param array $args array of arguments.
	 *
	 * @return array|bool|mixed
	 */
	public function get_global_notices( $args = array() ) {

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

		error_log( 'get_global_notices function' );
		error_log( $cache_key );

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

		// Only include the notices for a specific placement.
		if ( ! empty( $args['placement'] ) ) {
			$query_args['tax_query']['relation'] = 'AND';
			$query_args['tax_query'][]           = array(
				'taxonomy' => 'courier_placement',
				'field'    => 'slug',
				'terms'    => is_array( $args['placement'] ) ? $args['placement'] : array( $args['placement'] ),
				'operator' => 'IN',
			);
		}

		error_log( print_r( $query_args, true ) );

		$global_notices_query = new \WP_Query( $query_args );

		wp_cache_set( $cache_key, $global_notices_query->posts, 'courier', 300 );

		if ( isset( $args['ids_only'] ) && false !== $args['ids_only'] ) {
			return wp_list_pluck( $global_notices_query->posts, 'ID' );
		} else {
			return $global_notices_query->posts;
		}
	}

	/**
	 * Get our dismissible global notices
	 * @param array $args     Query Args
	 * @param bool  $ids_only Whether to return only IDs.
	 *
	 * @since 1.0.5
	 *
	 * @return array|bool|mixed
	 */
	public function get_dismissible_global_notices( $args = array(), $ajax_post_data = array(), $ids_only = false ) {

		error_log( 'get_dismissible_global_notices' );

		$cache_key = 'global-dismissible-' . sanitize_title( $args['placement'] ) . '-notices';
		$cache     = wp_cache_get( $cache_key, 'courier' );

		if ( false !== $cache ) {
			if ( $ids_only ) {
				return wp_list_pluck( $cache, 'ID' );
			}

			return $cache;
		}

		$global_notices = $this->get_global_notices(
			array(
				'ids_only' => true,
			)
		);

		if ( empty( $global_notices ) ) {
			return array();
		}

		$query_args = array(
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

		// Only include the notices for a specific placement.
		if ( ! empty( $args['placement'] ) ) {
			$query_args['tax_query']['relation'] = 'AND';
			$query_args['tax_query'][]           = array(
				'taxonomy' => 'courier_placement',
				'field'    => 'slug',
				'terms'    => is_array( $args['placement'] ) ? $args['placement'] : array( $args['placement'] ),
				'operator' => 'IN',
			);
		}

		$global_notices_query = new \WP_Query( $query_args );

		wp_cache_set( $cache_key, $global_notices_query->posts, 'courier', 300 );

		if ( $ids_only ) {
			return wp_list_pluck( $global_notices_query->posts, 'ID' );
		} else {
			return $global_notices_query->posts;
		}
	}

	/**
	 * Query not dismissible global notices. Cache appropriately.
	 *
	 * @since 1.0.5
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|bool|mixed
	 */
	public function get_persistent_global_notices( $args = array() ) {

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
		$global_notices = $this->get_global_notices( $global_args );

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

		$global_persistent_notices_query = new \WP_Query( $args );

		wp_cache_set( $cache_key, $global_persistent_notices_query->posts, 'courier', 300 );

		if ( isset( $args['ids_only'] ) && true === $args['ids_only'] ) {
			return wp_list_pluck( $global_persistent_notices_query->posts, 'ID' );
		} else {
			return $global_persistent_notices_query->posts;
		}
	}

	/**
	 * Get Courier all notices.
	 *
	 * @since 1.0.5
	 *
	 * @param array $args           Array of arguments.
	 *
	 * @param array $ajax_post_data Array of data to customize the response
	 *
	 * @return array
	 */
	public function get_notices( $args = array(), $ajax_post_data = array() ) {

		$defaults = array(
			'user_id'                      => '',
			'include_global'               => true,
			'include_dismissed'            => false,
			'prioritize_persistent_global' => true,
			'ids_only'                     => true,
			'number'                       => 4,
			'placement'                    => 'header',
		);

		$defaults = apply_filters( 'courier_get_notices_default_settings', $defaults );
		$args     = wp_parse_args( $args, $defaults );
		$number   = min( $args['number'], 100 ); // Catch if someone tries to pass more than 100 notices in one shot. Bad practice and should be filtered.
		$number   = apply_filters( 'courier_override_notices_number', $number );
		$results  = array();

		$ajax_post_data = wp_parse_args( $ajax_post_data, $defaults );

		// Account for global notices.
		$global_posts             = array();
		$global_dismissible_posts = array();

		if ( true === $args['include_global'] ) {
			$global_args             = $args;
			$global_args['ids_only'] = false;

			$global_posts             = $this->get_global_notices( $global_args );
			$global_dismissible_posts = $this->get_dismissible_global_notices( $args, $ajax_post_data, true );

			// Exclude dismissed.
			if ( ! $args['include_dismissed'] ) {
				$global_dismissed = $this->get_global_dismissed_notices( $args['user_id'] );

				foreach ( $global_posts as $key => $global_post ) {
					if ( ( is_object( $global_post ) && in_array( $global_post->ID, $global_dismissed, true ) ) || in_array( $global_post, $global_dismissed, true ) ) {
						unset( $global_posts[ $key ] );
					}
				}
			}
		}

		$post_list = array_merge( $global_posts, $global_dismissible_posts );

		if ( true === $args['ids_only'] ) {
			$post_list = wp_list_pluck( $post_list, 'ID' );
		}

		// Prioritize Persistent Global Notes to the top by getting them separately and putting them at the front of the line.
		if ( true === $args['prioritize_persistent_global'] ) {
			$persistent_global = $this->get_persistent_global_notices(
				array(
					'ids_only'  => false,
					'placement' => $args['placement'],
				)
			);

			if ( ! empty( $persistent_global ) ) {

				if ( true === $args['ids_only'] ) {
					$persistent_global = wp_list_pluck( $persistent_global, 'ID' );
				}

				$results = array_merge( $results, $persistent_global );

				if ( true === $args['ids_only'] ) {
					$difference = array_diff( $post_list, $persistent_global );
				}

				// If there is no difference, then the persistent global notices are the only ones left.
				if ( ! empty( $difference ) ) {
					$post_list = $difference;
				}
			}
		}

		if ( ! empty( $post_list ) && true === $args['ids_only'] ) {
			$post_list = array_filter( $post_list, 'strlen' );
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

		/**
		 * Allow for the ability to override the query used to display notices
		 * $query_args The arguments used for our notice post query
		 * $args The request arguments from our ajax call
		 *
		 * @since 1.0
		 */

		error_log( 'should filter' );

		$query_args          = apply_filters( 'courier_notices_display_notices_query', $query_args, $ajax_post_data );
		$query_args          = wp_parse_args( $args, $query_args );
		$final_notices_query = new \WP_Query( $query_args );

		return array_merge( $results, $final_notices_query->posts );
	}

	/**
	 * Get a user's dismissed global notices
	 *
	 * @since 1.0.5
	 *
	 * @param int $user_id The ID of the user to get notices for.
	 *
	 * @return array|void
	 */
	public function get_global_dismissed_notices( $user_id = 0 ) {

		// If user isn't logged in, use cookies.
		if ( ! is_user_logged_in() && isset( $_COOKIE['dismissed_notices'] ) ) {
			return array_map( 'intval', json_decode( stripslashes( $_COOKIE['dismissed_notices'] ) ) );
		}

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( ! $dismissed_notices = get_user_option( 'courier_dismissals', $user_id ) ) { // phpcs:ignore
			$dismissed_notices = array();
		}

		return array_map( 'intval', $dismissed_notices );
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_user_notices( $args = array() ) {

		if ( ! is_user_logged_in() ) {
			return array();
		}

		$number = min( $args['number'], 100 ); // Catch if someone tries to pass more than 100 notices in one shot. Bad practice and should be filtered.
		$number = apply_filters( 'courier_override_notices_number', $number );

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
			'fields'         => 'ids',
			'no_found_rows'  => true,
		);

		$current_user_id = get_current_user_id();

		$query_args['tax_query'][] = array(
			'taxonomy' => 'courier_visibility_rules',
			'field'    => 'slug',
			'terms'    => array( "rule-is_user_{$current_user_id}" ),
			'operator' => 'IN',
		);

		// Do not include dismissed notices.
		if ( ! $args['include_dismissed'] ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'courier_status',
				'field'    => 'name',
				'terms'    => array( 'Dismissed' ),
				'operator' => 'NOT IN',
			);
		}

		// Only include the notices for a specific placement.
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

		error_log( 'user query');
		error_log( print_r( $query_args, true ) );

		$notices_query = new \WP_Query( $query_args );

		return $notices_query->posts;
	}
}
