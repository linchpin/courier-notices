<?php
/**
 * Post Type Model Interface
 *
 * @package CourierNotices\Model
 */

namespace CourierNotices\Model\Post_Type;

/**
 * Interface Post_Type_Model_Interface
 *
 * @package CourierNotices\Model\Post_Type
 */
interface Post_Type_Model_Interface {


	/**
	 * Sets up the model.
	 *
	 * @param \WP_Post $post This model's post object.
	 */
	public function setup_model( \WP_Post $post );


	/**
	 * Sets the custom post type labels.
	 *
	 * @return mixed
	 */
	public function set_labels();


	/**
	 * Get our labels
	 *
	 * @return mixed
	 */
	public function get_labels();


	/**
	 * Sets the custom post type arguments.
	 * You must override this method to build your args
	 *
	 * @return mixed
	 */
	public function set_args();


	/**
	 * Gets the custom post type arguments.
	 *
	 * @since 0.1.0
	 *
	 * @return mixed
	 */
	public function get_args();


	/**
	 * Get the post type slug
	 *
	 * @return mixed
	 */
	public function get_post_type_slug();


	/**
	 * Return all the meta associated with a given post.
	 *
	 * @since 0.2.8
	 *
	 * @return mixed
	 */
	public function get_all_meta( $post_id );


}
