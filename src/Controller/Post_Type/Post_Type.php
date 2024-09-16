<?php
/**
 * Base Custom Post Type
 */
namespace CourierNotices\Controller\Post_Type;

use CourierNotices\Model\Post_Type\Post_Type as Post_Type_Model;

/**
 * Class Post_Type
 *
 * @package CourierNotices\Controller\Post_Type
 */
class Post_Type implements Post_Type_Interface {

	public $model;


	/**
	 * Post_Type constructor.
	 */
	public function __construct() {
		$this->model = new Post_Type_Model();

	}


	/**
	 * This core Post_Type does not need to register any action
	 * it does however need to exist based on implementation.
	 */
	public function register_actions() {

	}


	/**
	 * Register our custom post type
	 */
	public function register_custom_post_type() {
		register_post_type( $this->model->get_post_type_slug(), $this->model->get_args() );

	}


	/**
	 * Register meta boxes and fields related to the post type
	 * Data comes from the Post Type's Model
	 *
	 * @since 0.1.0
	 */
	public function register_meta() {
		$meta = $this->model->get_meta_boxes();

		if ( empty( $meta ) ) {
			return;
		}

	}


}
