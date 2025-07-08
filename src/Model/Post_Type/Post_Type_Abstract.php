<?php
/**
 * Post Type Model
 *
 * @package CourierNotices\Model
 */

namespace CourierNotices\Model\Post_Type;

/**
 * Class Post_Type_Abstract
 *
 * @package CourierNotices\Model\Post_Type
 */
abstract class Post_Type_Abstract implements Post_Type_Model_Interface {


	/**
	 * Set the labels of our Post Type
	 *
	 * @param array $labels Array of labels used for Post Type.
	 *
	 * @return mixed|void
	 */
	abstract public function set_labels();


	/**
	 * Get the labels of our post type, with the ability to filter
	 *
	 * @return mixed|void
	 */
	abstract public function get_labels();


	/**
	 * Get our Post ID.
	 *
	 * @return mixed
	 */
	abstract public function get_ID();


	/**
	 * Set the Post ID of our Post Type Model
	 *
	 * @param int $ID Post ID.
	 *
	 * @return void
	 */
	abstract public function set_ID( int $ID ); // phpcs:ignore


	/**
	 * Get the name of our model
	 *
	 * @return string
	 */
	abstract public function get_name();


	/**
	 * Set the name/title
	 *
	 * @param string $name Name/Title.
	 *
	 * @return void
	 */
	abstract public function set_name( string $name = '' );


	/**
	 * Sets up the model.
	 *
	 * @param \WP_Post $post This plan's post object.
	 */
	abstract public function setup_model( $post );


	/**
	 * Sets the custom post type arguments.
	 *
	 * @since 0.1.0
	 *
	 * @return mixed|void
	 */
	abstract public function set_args();


	/**
	 * Get the custom post type arguments
	 *
	 * @return array
	 */
	abstract public function get_args();


	/**
	 * Get the post type slug
	 *
	 * @return mixed
	 */
	abstract public function get_post_type_slug();


	/**
	 * Return all the meta associated with a given post.
	 *
	 * @since 0.2.8
	 *
	 * @return mixed
	 */
	abstract public function get_all_meta( $post_id );


}
