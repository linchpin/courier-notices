<?php
/**
 * This interface is for post types that do not require post meta
 *
 * @since 3.0.0
 * @package CourierNotices\Controller\Post_Type
 */

namespace CourierNotices\Controller\Post_Type;

/**
 * Interface Post_Type_Simple_Interface
 *
 * @package CourierNotices\Controller\Post_Type
 */
interface Post_Type_Simple_Interface {


	/**
	 * All Post Types require registering actions as you cannot
	 * register a post type without a hook
	 *
	 * @since 0.1.0
	 *
	 * @return mixed
	 */
	public function register_actions();


	/**
	 * Register our post type
	 *
	 * @since 0.1.0
	 */
	public function register_custom_post_type();


}
