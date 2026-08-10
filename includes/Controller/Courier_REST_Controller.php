<?php

namespace CourierNotices\Controller;

use WP_REST_Response;
use WP_Error;
use WP_REST_Request;
use CourierNotices\Controller\REST\REST_Base;
use CourierNotices\Core\View;
use CourierNotices\Model\Courier_Notice\Data as Courier_Notice_Data;

/**
 * Class Courier_REST_Controller
 *
 * @package CourierNotices\Controller
 */
class Courier_REST_Controller extends REST_Base {

	/**
	 * Register the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		$namespace = $this->get_api_namespace();
		$base      = 'notice';

		// Display a single notice
		register_rest_route(
			$namespace,
			'/' . $base . '/(?P<notice_id>\d+)/',
			[
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_notice' ],
					'permission_callback' => [ $this, 'get_public_permissions' ],
					'args'                => [],
				],
			]
		);

		// Dismiss a single notice
		register_rest_route(
			$namespace,
			'/' . $base . '/(?P<notice_id>\d+)/dismiss',
			[
				[
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'dismiss_notice' ],
					'permission_callback' => [ $this, 'get_logged_in_permissions' ],
					'args'                => [],
				],
			]
		);

		// Reactivate an expired or dismissed notice.
		register_rest_route(
			$namespace,
			'/reactivate/(?P<notice_id>\d+)/',
			[
				[
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'reactivate_notice' ],
					'permission_callback' => [ $this, 'get_reactivate_permissions' ],
					'args'                => [
						'notice_id' => [
							'description'       => esc_html__( 'The notice to reactivate.', 'courier-notices' ),
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
					],
				],
			]
		);

		// Display all notices for the specific user.
		register_rest_route(
			$namespace,
			'/notices/display/',
			[
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [ $this, 'display_notices' ],
					'permission_callback' => [ $this, 'get_public_permissions' ],
					'args'                => [
						'placement' => [
							'description'       => esc_html__( 'Set where the notices should display.', 'courier-notices' ),
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'format'    => [
							'description'       => esc_html__( 'Set the response, either html or json.', 'courier-notices' ),
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'post_info' => [
							'description'       => esc_html__( 'Our queried post info', 'courier-notices' ),
							'type'              => 'object',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'user_id'   => [
							'description'       => esc_html__( 'Our queried post info', 'courier-notices' ),
							'type'              => 'int',
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
					],
				],
			]
		);

		// Display all notices grouped by placement in a single call
		register_rest_route(
			$namespace,
			'/notices/display/all/',
			[
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [ $this, 'display_all_notices' ],
					'permission_callback' => [ $this, 'get_public_permissions' ],
					'args'                => [
						'format'    => [
							'description'       => esc_html__( 'Set the response, either html or json.', 'courier-notices' ),
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'post_info' => [
							'description'       => esc_html__( 'Our queried post info', 'courier-notices' ),
							'type'              => 'object',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'user_id'   => [
							'description'       => esc_html__( 'User ID', 'courier-notices' ),
							'type'              => 'int',
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
					],
				],
			]
		);
	}


	/**
	 * Get a single notice
	 *
	 * @since 1.0
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_notice( WP_REST_Request $request ) {
		$data = [];

		$args = [
			'post_type'      => 'courier_notice',
			'post_status'    => 'publish',
			'no_found_rows'  => true,
			'posts_per_page' => 1,
			'p'              => (int) $request['notice_id'],
		];

		$search = new \WP_Query( $args );

		if ( $search->have_posts() ) {

			while ( $search->have_posts() ) {
				$search->the_post();

				$item = array(
					'id'          => get_the_ID(),
					'title'       => get_the_title(),
					'description' => get_the_content(),
				);

				$data[] = $item;
			}

			wp_reset_postdata();
		}

		return new \WP_REST_Response( $data, 200 );
	}


	/**
	 * Disable a notice in the front end.
	 *
	 * @since 1.0.5
	 *
	 * @param $request WP_REST_Request
	 *
	 * @return WP_REST_Response
	 */
	public function dismiss_notice( WP_REST_Request $request ) {
		$defaults = array(
			'user_id' => '',
		);

		$defaults = apply_filters( 'courier_notices_get_notices_default_settings', $defaults );
		$args     = wp_parse_args( $request->get_body_params(), $defaults );
		$args     = wp_parse_args( $request->get_params(), $defaults );
		$user_id  = get_current_user_id();

		check_ajax_referer( 'courier_notices_dismiss_' . $user_id . '_notice_nonce', 'dismiss_nonce' );

		/**
		 * todo it looks like user options are called courier_notifications
		 */
		$notifications = maybe_unserialize( get_user_option( 'courier_notifications', $user_id ) );

		if ( empty( $notifications ) ) {
			$notifications = [];
		}

		$notifications[ $args['notice_id'] ] = '1';

		update_user_option( $user_id, 'courier_notifications', $notifications );

		return new WP_REST_Response( 1, 200 );
	}


	/**
	 * Reactivating edits the notice, so the caller needs edit rights on
	 * that specific post.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return bool
	 */
	public function get_reactivate_permissions( WP_REST_Request $request ): bool {
		return current_user_can( 'edit_post', (int) $request['notice_id'] );
	}


	/**
	 * Reactivate an expired or dismissed notice.
	 *
	 * The route existed in the admin UI for years without a handler — the
	 * localized endpoint, the row action, and the edit-screen banner all
	 * posted into a void. See COURIER-1031.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function reactivate_notice( WP_REST_Request $request ) {
		$notice_id = (int) $request['notice_id'];
		$result    = ( new Courier_Notice_Data() )->reactivate_notice( $notice_id );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return new WP_REST_Response(
			array(
				'success'    => true,
				'notice_id'  => $notice_id,
				'expiration' => (int) get_post_meta( $notice_id, '_courier_expiration', true ),
			),
			200
		);
	}


	/**
	 * Display all notices on the frontend based on our logic
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 * @since 1.0
	 *
	 * Retrieves the following courier notices
	 *
	 * 1. User Specific Notices
	 * 2. Global Notices
	 */
	public function display_notices( WP_REST_Request $request ) {
		$defaults = [
			'user_id'                      => '',
			'include_global'               => true,
			'include_dismissed'            => false,
			'prioritize_persistent_global' => true,
			'ids_only'                     => false,
			'number'                       => 10, // @todo this should be a setting.
			'placement'                    => 'header',
			'query_args'                   => [],
		];

		$defaults       = apply_filters( 'courier_notices_get_notices_default_settings', $defaults );
		$args           = wp_parse_args(
			[
				'contentType' => $request->get_param( 'contentType' ),
				'placement'   => $request->get_param( 'placement' ),
				'format'      => $request->get_param( 'format' ),
			],
			$defaults
		);
		$ajax_post_data = wp_parse_args( $request->get_params(), $defaults );
		$notices_data   = new Courier_Notice_Data();
		$notice_posts   = $notices_data->get_notices( $args, $ajax_post_data );
		$style          = 'notice-informational';

		if ( 'html' === $args['format'] ) {
			$notices = [];

			foreach ( $notice_posts as $courier_notice ) {
				$notice_data    = $notices_data->get_notice_meta( $courier_notice->ID );
				$notice         = new View();
				$post_classes   = array( 'courier-notice courier_notice alert alert-box' );
				$post_classes[] = 'courier_type-' . $notice_data['type'][0]->slug;
				$post_classes[] = $notice_data['is_confirmation'] ? 'gform-confirmation' : '';

				$notice->assign( 'notice_id', $courier_notice->ID );
				$notice->assign( 'show_hide_title', $notice_data['show_hide_title'] );

				$notice_title = courier_notices_the_notice_title( $courier_notice->post_title, '<h6 class="courier-notice-title">', '</h6>', false );

				$notice->assign( 'notice_title', $notice_title );
				$notice->assign( 'notice_class', implode( ' ', get_post_class( $post_classes, $courier_notice->ID ) ) );
				$notice->assign( 'dismissible', get_post_meta( $courier_notice->ID, '_courier_dismissible', true ) );
				$notice->assign( 'icon', $notice_data['icon'] );
				$notice->assign( 'notice_content', $courier_notice->post_content );

				if ( ! is_wp_error( $notice_data['style'] ) && is_array( $notice_data['style'] ) ) {
					$style = 'notice-' . $notice_data['style'][0]->slug;
				}

				$notices[ $courier_notice->ID ] = $notice->get_text_view( $style );
			}

			$notice_posts = $notices;
		}

		$dataset = array(
			'notices' => $notice_posts,
		);

		/**
		 * Allow for the dataset to be filtered one last time before display
		 */
		$dataset = apply_filters( 'courier_notices_display_notices', $dataset );

		return new WP_REST_Response( $dataset, 200 );
	}


	/**
	 * Display all notices grouped by placement in a single call
	 *
	 * @since 1.7.2
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function display_all_notices( WP_REST_Request $request ) {
		$defaults = [
			'user_id'                      => '',
			'include_global'               => true,
			'include_dismissed'            => false,
			'prioritize_persistent_global' => true,
			'ids_only'                     => false,
			'number'                       => 10,
			'query_args'                   => [],
		];

		$defaults       = apply_filters( 'courier_notices_get_notices_default_settings', $defaults );
		$placements     = $request->get_param( 'placements' );
		$format         = $request->get_param( 'format' );
		$ajax_post_data = wp_parse_args( $request->get_params(), $defaults );
		$notices_data   = new Courier_Notice_Data();
		$dataset        = [];

		// If no placements specified, return all
		if ( empty( $placements ) ) {
			$placements = [ 'header', 'footer', 'popup-modal' ];
		}

		// Always include popup-modal if not already specified
		if ( ! in_array( 'popup-modal', $placements, true ) ) {
			$placements[] = 'popup-modal';
		}

		// Create cache key for the entire response
		$cache_key = 'courier_notices_display_all_' . md5( serialize( $placements ) . serialize( $ajax_post_data ) . $format );
		$cache     = wp_cache_get( $cache_key, 'courier-notices' );

		// Check object cache first
		if ( false !== $cache ) {
			return new WP_REST_Response( $cache, 200 );
		}

		// Check transient cache
		$transient_key   = 'courier_notices_display_all_transient_' . md5( serialize( $placements ) . serialize( $ajax_post_data ) . $format );
		$transient_cache = get_transient( $transient_key );
		if ( false !== $transient_cache ) {
			wp_cache_set( $cache_key, $transient_cache, 'courier-notices', 300 );
			return new WP_REST_Response( $transient_cache, 200 );
		}

		// Fetch notices for each placement
		foreach ( $placements as $placement ) {
			$args = wp_parse_args(
				[
					'placement' => $placement,
					'format'    => $format,
				],
				$defaults
			);

			$notice_posts = $notices_data->get_notices( $args, $ajax_post_data );
			$style        = 'notice-informational';

			if ( 'html' === $format ) {
				$notices = [];

				foreach ( $notice_posts as $courier_notice ) {
					$notice_data    = $notices_data->get_notice_meta( $courier_notice->ID );
					$notice         = new View();
					$post_classes   = [ 'courier-notice courier_notice alert alert-box' ];
					$post_classes[] = 'courier_type-' . $notice_data['type'][0]->slug;
					$post_classes[] = $notice_data['is_confirmation'] ? 'gform-confirmation' : '';

					$notice->assign( 'notice_id', $courier_notice->ID );
					$notice->assign( 'show_hide_title', $notice_data['show_hide_title'] );

					$notice_title = courier_notices_the_notice_title( $courier_notice->post_title, '<h6 class="courier-notice-title">', '</h6>', false );

					$notice->assign( 'notice_title', $notice_title );
					$notice->assign( 'notice_class', implode( ' ', get_post_class( $post_classes, $courier_notice->ID ) ) );
					$notice->assign( 'dismissible', get_post_meta( $courier_notice->ID, '_courier_dismissible', true ) );
					$notice->assign( 'icon', $notice_data['icon'] );
					$notice->assign( 'notice_content', $courier_notice->post_content );

					// Determine the correct template based on placement
					if ( $placement === 'popup-modal' ) {
						$style = 'notice-popup-modal';
					} else {
						$style = 'notice-informational';
						if ( ! is_wp_error( $notice_data['style'] ) && is_array( $notice_data['style'] ) ) {
							$style = 'notice-' . $notice_data['style'][0]->slug;
						}
					}

					$notices[ $courier_notice->ID ] = $notice->get_text_view( $style );
				}

				$dataset[ $placement ] = $notices;
			} else {
				$dataset[ $placement ] = $notice_posts;
			}
		}

		// Filter the dataset before returning
		$dataset = apply_filters( 'courier_notices_display_all_notices', $dataset );

		// Cache the result
		wp_cache_set( $cache_key, $dataset, 'courier-notices', 300 );
		set_transient( $transient_key, $dataset, 600 ); // 10 minutes

		return new WP_REST_Response( $dataset, 200 );
	}


	/**
	 * Sanitize placements array
	 *
	 * @since 1.7.2
	 *
	 * @param array $placements Array of placement strings.
	 *
	 * @return array
	 */
	public function sanitize_placements( $placements ) {
		if ( ! is_array( $placements ) ) {
			return [];
		}

		// This comes from the placement taxonomy terms.
		$valid_placements = get_terms(
			[
				'taxonomy'   => 'courier_placement',
				'hide_empty' => false,
			]
		);

		$sanitized = [];

		foreach ( $placements as $placement ) {
			$placement = sanitize_text_field( $placement );
			if ( in_array( $placement, $valid_placements, true ) ) {
				$sanitized[] = $placement;
			}
		}

		return $sanitized;
	}


}
