<?php
/**
 * Stream Controller
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller\Integrations;

use CourierNotices\Controller\Controller_Interface;

/**
 * Stream Class
 */
class Stream implements Controller_Interface {


	/**
	 * Registers Stream related actions.
	 *
	 * @since 1.0
	 */
	public function register_actions(): void {
		add_filter( 'wp_stream_log_data', array( $this, 'wp_stream_log_data' ), 999 );
	}


	/**
	 * Prevent actions to Courier notices from being logged in Stream.
	 *
	 * @since 1.0
	 *
	 * @param array $data Array of data.
	 *
	 * @return array
	 */
	public function wp_stream_log_data( $data ) {
		if ( ( ! empty( $data['connector'] ) && 'posts' === $data['connector'] ) && ( ! empty( $data['context'] ) && 'courier_notice' === $data['context'] ) ) {
			return array();
		}

		return $data;
	}
}
