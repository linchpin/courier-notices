<?php

namespace Courier\Controller;

use Courier\Core\View;

/**
 * Class Courier_REST_Controller
 *
 * @package Courier\Controller
 */
class Courier_REST_Controller extends \WP_REST_Controller {

	public function register_actions() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version   = '1';
		$namespace = 'courier/v' . $version;
		$base      = 'notice';

		// Display a single notice
		register_rest_route(
			$namespace,
			'/' . $base . '/(?P<notice_id>\d+)/',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_notice' ),
					'permission_callback' => array( $this, 'get_notice_permissions_check' ),
					'args'                => array(),
				),
			)
		);

		// Display all notices for the specific user
		register_rest_route(
			$namespace,
			'/notices/display/',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'display_notices' ),
					'permission_callback' => array( $this, 'get_notice_permissions_check' ),
					'args'                => array(
						'placement' => array(
							'description'       => esc_html__( 'Set where the notices should display.', 'courier' ),
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'format'    => array(
							'description'       => esc_html__( 'Set the response, either html or json.', 'courier' ),
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			)
		);
	}

	/**
	 * Get a single notice
	 *
	 * @since 1.0
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_notice( $request ) {

		$data = array();

		$args = array(
			'post_type'      => 'courier_notice',
			'post_status'    => 'publish',
			'no_found_rows'  => true,
			'posts_per_page' => 1,
			'p'              => (int) $request['notice_id'],
		);

		$search = new \WP_Query( $args );

		if ( $search->have_posts() ) {

			while ( $search->have_posts() ) {
				$search->the_post();

				$item = array(
					'id'          => get_the_ID(),
					'title'       => get_the_title(),
					'description' => get_the_content(),
				);

				$data[] = $this->prepare_response_for_collection( $item );
			}

			wp_reset_postdata();
		}

		return new \WP_REST_Response( $data, 200 );
	}

	/**
	 * Disable a notice in the front end.
	 *
	 * @param $request
	 */
	public function dismiss_notice( $request ) {

	}

	/**
	 * Display all notices on the frontend based on our logic
	 *
	 * @since 1.0
	 *
	 * Retrieves the following courier notices
	 *
	 * 1. User Specific Notices
	 * 2. Global Notices
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function display_notices( $request ) {

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

		$defaults     = apply_filters( 'courier_get_notices_default_settings', $defaults );
		$args         = wp_parse_args( $request->get_params(), $defaults );
		$number       = min( $args['number'], 100 ); // Catch if someone tries to pass more than 100 notices in one shot. Bad practice and should be filtered.
		$number       = apply_filters( 'courier_override_notices_number', $number );
		$results      = array();
		$user_notices = courier_get_user_notices( $args );

		// Account for global notices.
		$global_posts = array();

		if ( true === $args['include_global'] ) {
			$global_posts = courier_get_global_notices(
				array(
					'ids_only'  => false,
					'placement' => $args['placement'],
				)
			);

			// Exclude dismissed.
			if ( ! $args['include_dismissed'] ) {
				$global_dismissed = courier_get_global_dismissed_notices( $args['user_id'] );

				foreach ( $global_posts as $key => $global_post ) {
					if ( ( is_object( $global_post ) && in_array( $global_post->ID, $global_dismissed, true ) ) || in_array( $global_post, $global_dismissed, true ) ) {
						unset( $global_posts[ $key ] );
					}
				}
			}
		}

		$post_list = array_merge( $user_notices, $global_posts );
		$post_list = wp_list_pluck( $post_list, 'ID' );

		// Prioritize Persistent Global Notices to the top by getting them separately and putting them at the front of the line.
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
					return new \WP_REST_Response( $results );
				} else {
					$post_list = $difference;
				}
			}
		}

		$final_notices = array();

		if ( ! empty( $post_list ) ) {
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
			 * @since 1.0
			 */
			$query_args          = apply_filters( 'courier_notices_display_notices_query', $query_args );
			$query_args          = wp_parse_args( $args['query_args'], $query_args );
			$final_notices_query = new \WP_Query( $query_args );

			if ( $final_notices_query->have_posts() ) {
				$final_notices = $final_notices_query->posts;
			}
		}

		$final_notices = array_merge( $results, $final_notices );

		if ( 'html' === $args['format'] ) {
			$notices = array();

			foreach ( $final_notices as $courier_notice ) {
				$notice = new View();
				$notice->assign( 'notice_id', $courier_notice->ID );
				$notice->assign( 'post_class', implode( ' ', get_post_class( 'courier-notice courier_notice callout alert alert-box', $courier_notice->ID ) ) );
				$notice->assign( 'dismissable', get_post_meta( $courier_notice->ID, '_courier_dismissible', true ) );
				$notice->assign( 'post_content', $courier_notice->post_content );
				$notices[] = $notice->get_text_view( 'notice' );
			}

			$final_notices = $notices;
		}

		$dataset = array(
			'notices' => $final_notices,
		);

		/**
		 * Allow for the dataset to be filtered one last time before display
		 */
		$dataset = apply_filters( 'courier_notices_display_notices', $dataset );

		return new \WP_REST_Response( $dataset, 200 );
	}

	/**
	 * Check if a given request has access to get items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function get_notice_permissions_check( $request ) {
		return true;
	}

	/**
	 * Check if a given request has access to get a specific item
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed $item WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		return array();
	}

	/**
	 * Get the query params for collections
	 *
	 * @return array
	 */
	public function get_collection_params() {
		return array(
			'page'     => array(
				'description'       => 'Current page of the collection.',
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
			),
			'per_page' => array(
				'description'       => 'Maximum number of items to be returned in result set.',
				'type'              => 'integer',
				'default'           => 100,
				'sanitize_callback' => 'absint',
			),
			'search'   => array(
				'description'       => 'Limit results to those matching a string.',
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
		);
	}
}
