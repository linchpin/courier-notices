<?php

namespace Courier\Controller;

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
					'args'                => array(),
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
	public function disable_notice( $request ) {

	}

	/**
	 * Display all notices on the frontend based on our logic
	 *
	 * @since 1.0
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function display_notices( $request ) {

	}

	/**
	 * Check if a given request has access to get items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		return current_user_can( 'edit_posts' );
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
