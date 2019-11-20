<?php

namespace Courier\Controller;

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
		$base      = 'post-type-search';

		register_rest_route(
			$namespace,
			'/' . $base . '/(?P<post_type>[\w]+)/(?P<post_title>[\w]+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_post_types' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => array(),
				),
			)
		);
	}

	/**
	 * Get a collection of items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_post_types( $request ) {

		$data = array();

		$args = array(
			'post_type'      => sanitize_text_field( $request['post_type'] ),
			'post_status'    => 'publish',
			's'              => sanitize_text_field( $request['post_title'] ),
			'no_found_rows'  => true,
			'posts_per_page' => 50,
		);

		$search = new \WP_Query( $args );

		if ( $search->have_posts() ) {

			while ( $search->have_posts() ) {
				$search->the_post();

				$item = array(
					'id'    => get_the_ID(),
					'title' => get_the_title(),
				);

				$data[] = $this->prepare_response_for_collection( $item );
			}

			wp_reset_postdata();
		}

		return new \WP_REST_Response( $data, 200 );
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
