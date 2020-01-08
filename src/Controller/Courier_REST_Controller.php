<?php

namespace Courier\Controller;

use Courier\Core\View;
use Courier\Model\Courier_Notice\Data as Courier_Notice_Data;

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

		// Dismiss a single notice
		register_rest_route(
			$namespace,
			'/' . $base . '/(?P<notice_id>\d+)/dismiss',
			array(
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'dismiss_notice' ),
					'permission_callback' => array( $this, 'get_dismiss_notice_permissions_check' ),
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
	 * @since 1.0.5
	 *
	 * @param $request \WP_REST_Request
	 *
	 * @return \WP_REST_Response
	 */
	public function dismiss_notice( $request ) {

		$defaults = array(
			'user_id' => '',
		);

		$defaults = apply_filters( 'courier_get_notices_default_settings', $defaults );
		$args     = wp_parse_args( $request->get_body_params(), $defaults );
		$args     = wp_parse_args( $request->get_params(), $defaults );
		$user_id  = get_current_user_id();

		check_ajax_referer( 'courier_dismiss_' . $user_id . '_notification_nonce', 'dismiss_nonce' );

		$notifications = maybe_unserialize( get_user_option( 'courier_notifications', $user_id ) );

		if ( empty( $notifications ) ) {
			$notifications = array();
		}

		$notifications[ $args['notice_id'] ] = '1';

		update_user_option( $user_id, 'courier_notifications', $notifications );

		return new \WP_REST_Response( 1, 200 );
	}

	/**
	 * Check if a given request has access to get items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function get_dismiss_notice_permissions_check( $request ) {
		return is_user_logged_in();
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
			'ids_only'                     => false,
			'number'                       => 4,
			'placement'                    => 'header',
			'query_args'                   => array(),
		);

		$defaults       = apply_filters( 'courier_get_notices_default_settings', $defaults );
		$args           = wp_parse_args(
			array(
				'contentType' => $request->get_param( 'contentType' ),
				'placement'   => $request->get_param( 'placement' ),
				'format'      => $request->get_param( 'format' ),
			),
			$defaults
		);
		$ajax_post_data = wp_parse_args( $request->get_params(), $defaults );
		$notice_data    = new Courier_Notice_Data();
		$notice_posts   = $notice_data->get_notices( $args, $ajax_post_data );
		$style          = 'notice-informational';

		if ( 'html' === $args['format'] ) {
			$notices = array();

			foreach ( $notice_posts as $courier_notice ) {
				$courier_style = get_the_terms( $courier_notice->ID, 'courier_style' );
				$courier_type  = get_the_terms( $courier_notice->ID, 'courier_type' );

				$notice = new View();
				$post_classes = 'courier-notice courier_notice callout alert alert-box courier_type-' . $courier_type[0]->slug;
				$notice->assign( 'notice_id', $courier_notice->ID );
				$notice->assign( 'notice_class', implode( ' ', get_post_class( $post_classes, $courier_notice->ID ) ) );
				$notice->assign( 'dismissible', get_post_meta( $courier_notice->ID, '_courier_dismissible', true ) );
				$notice->assign( 'icon', get_term_meta( $courier_type[0]->term_id, '_courier_type_icon', true ) );
				$notice->assign( 'notice_content', $courier_notice->post_content );

				if ( ! is_wp_error( $courier_style ) && is_array( $courier_style ) ) {
					$style = 'notice-' . $courier_style[0]->slug;
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
