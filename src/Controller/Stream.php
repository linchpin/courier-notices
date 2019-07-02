<?php

namespace Courier\Controller;

/**
 * Class Stream
 * @package Courier\Controller
 */
class Stream {

	/**
	 * Register our Stream related actions.
	 */
	function register_actions() {
		add_filter( 'wp_stream_log_data', array( $this, 'wp_stream_log_data' ), 999 );
	}

	/**
	 * Prevent actions to Courier notices from being logged in Stream.
	 *
	 * @since 1.0
	 *
	 * @param $data
	 *
	 * @return array
	 */
	function wp_stream_log_data( $data ) {
		if ( ( ! empty( $data['connector'] ) && 'posts' == $data['connector'] ) && ( ! empty( $data['context'] ) && 'courier_notice' == $data['context'] ) ) {
			return array();
		}

		return $data;
	}
}
