<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Block Bindings Controller
 *
 * @package CourierNotices\Controller
 * @since 2.0.0
 */

namespace CourierNotices\Controller;

/**
 * Block_Bindings Class
 *
 * Registers the `courier/notice` block bindings source, which lets a notice's
 * blocks resolve courier-specific values at render time instead of baking them
 * into serialized markup.
 *
 * The key design point is the `message` key. Core skips a binding whose source
 * returns null (`WP_Block::process_block_bindings()` only assigns when
 * `! is_null( $source_value )`), so the authored paragraph content stands
 * unless something actively supplies a replacement. That is what makes an
 * informational notice "dynamically adjustable" without moving its message out
 * of `post_content` and away from the legacy render path.
 *
 * Core's bindable attributes are a hardcoded allowlist
 * (`get_block_bindings_supported_attributes()`), so only `core/paragraph`,
 * `core/heading`, `core/image`, `core/button`, `core/post-date` and the two
 * navigation blocks can carry bindings. The `block_bindings_supported_attributes`
 * filter that would let `courier/notice` expose its own attributes is WP 6.9+,
 * above this plugin's 6.8 floor, so it is deliberately not used.
 *
 * @since 2.0.0
 */
class Block_Bindings implements Controller_Interface {

	/**
	 * The source name, shared with the editor registration in
	 * src/blocks/notice/bindings.js.
	 *
	 * @since 2.0.0
	 */
	const SOURCE_NAME = 'courier/notice';

	/**
	 * Register actions and filters
	 *
	 * @since 2.0.0
	 */
	public function register_actions(): void {
		add_action( 'init', [ $this, 'register_source' ] );
	}

	/**
	 * Register the courier/notice bindings source.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_source() {
		if ( ! function_exists( 'register_block_bindings_source' ) ) {
			return;
		}

		// The suite re-runs init, and Pro or a site could register first.
		if ( array_key_exists( self::SOURCE_NAME, get_all_registered_block_bindings_sources() ) ) {
			return;
		}

		register_block_bindings_source(
			self::SOURCE_NAME,
			array(
				'label'              => __( 'Courier Notice', 'courier-notices' ),
				'get_value_callback' => [ $this, 'get_value' ],
				'uses_context'       => [ 'postId' ],
			)
		);
	}

	/**
	 * Resolve a bound value for the notice in context.
	 *
	 * @since 2.0.0
	 *
	 * @param array<string, mixed> $source_args    The `args` object from the block's binding metadata.
	 * @param \WP_Block            $block_instance The block carrying the binding.
	 *
	 * @return string|null The value, or null to leave the authored attribute untouched.
	 */
	public function get_value( $source_args, $block_instance ) {
		$key = isset( $source_args['key'] ) ? (string) $source_args['key'] : '';

		if ( '' === $key ) {
			return null;
		}

		$notice_id = 0;

		if ( $block_instance instanceof \WP_Block && isset( $block_instance->context['postId'] ) ) {
			$notice_id = (int) $block_instance->context['postId'];
		}

		if ( $notice_id <= 0 ) {
			$notice_id = (int) get_the_ID();
		}

		if ( $notice_id <= 0 || 'courier_notice' !== get_post_type( $notice_id ) ) {
			return null;
		}

		$value = $this->resolve( $key, $notice_id );

		/**
		 * Filter a courier/notice bound value.
		 *
		 * This is the extension point that makes a notice dynamically
		 * adjustable. Returning null leaves the block's authored attribute
		 * in place, which is the default for the `message` key — so an
		 * informational notice renders what its author wrote until something
		 * deliberately substitutes a value.
		 *
		 * @since 2.0.0
		 *
		 * @param string|null $value     The resolved value, or null to keep the authored attribute.
		 * @param string      $key       The requested key.
		 * @param int         $notice_id The notice being rendered.
		 */
		return apply_filters( 'courier_notices_binding_value', $value, $key, $notice_id );
	}

	/**
	 * Resolve the plugin's own keys.
	 *
	 * @since 2.0.0
	 *
	 * @param string $key       The requested key.
	 * @param int    $notice_id The notice being rendered.
	 *
	 * @return string|null
	 */
	private function resolve( $key, $notice_id ) {
		switch ( $key ) {
			case 'title':
				return get_the_title( $notice_id );

			case 'type':
				$types = get_the_terms( $notice_id, 'courier_type' );

				if ( ! is_array( $types ) || array() === $types ) {
					return '';
				}

				return $types[0]->name;

			case 'expiration':
				$expiration = (int) get_post_meta( $notice_id, '_courier_expiration', true );

				if ( $expiration <= 0 ) {
					return '';
				}

				// Site timezone, matching both editors. See the note in
				// Courier::save_post_courier_notice().
				$formatted = wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $expiration );

				return is_string( $formatted ) ? $formatted : '';

			case 'message':
				// Null on purpose: the authored paragraph content is the
				// message. The filter above is where a dynamic value comes
				// from, so notices stay authored-by-default.
				return null;
		}

		return null;
	}
}
