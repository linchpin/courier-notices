<?php
/**
 * This interface is for post types that require post meta
 *
 * @since 3.0.0
 * @package CourierNotices\Controller\Post_Type
 */

namespace CourierNotices\Controller\Post_Type;

/**
 * Interface Post_Type_Interface
 *
 * @package CourierNotices\Controller\Post_Type
 */
interface Post_Type_Interface extends Post_Type_Simple_Interface {


	/**
	 * Register meta
	 *
	 * @since 0.1.0
	 */
	public function register_meta();


}
