<?php
/**
 * Block Controller
 *
 * Actions and filters for the admin area of WordPress
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use CourierNotices\Controller\Blocks\Block;

/**
 * Block Class
 *
 * @package CourierNotices\Controller
 */
class CourierNotices extends Block {


	/**
	 * Registers the hooks and filters for this Controller.
	 *
	 * @since 1.0.0
	 */
	public function register_actions():void {
		add_filter( 'courier_notices_blocks', [ $this, 'add_block' ] );

	}

	/**
	 * Add our Card Items block to our list of blocks.
	 *
	 * @param $blocks
	 *
	 * @return mixed
	 */
	public function add_block( $blocks ): array {
		$blocks['courier-notices'] = [
			'render_callback' => [ __CLASS__, 'dynamic_render_callback' ],
			'editor_script'   => 'courier-notices-block',
		];

		return $blocks;
	}

	/**
	 * Get the Side Content block and output them into some HTML
	 *
	 * @since 0.2.1
	 *
	 * @param $block_attributes
	 * @param $content
	 *
	 * @return string
	 */
	public static function dynamic_render_callback( $block_attributes, $content, $block ) {
		$output = '';

		$wrapper_attributes = get_block_wrapper_attributes();
		$attributes         = $block_attributes;

		$columns      = $attributes['columns'] ?? 2;
		$display_type = $attributes['displayType'] ?? 'generic';
		$has_border   = $attributes['hasBorder'] ?? false;
		$border_color = $attributes['borderColor'] ?? '#61366F';
		$has_number   = ( $attributes['displayType'] === 'numbered' ) ?? false;
		$number_color = $attributes['numberColor'] ?? '#FFFFFF';

		ob_start();

		$block_unique_id = 'wp-block-courier-notices-' .  wp_generate_password( 8, false, false );

		$container_class = [
			'wp-block-courier-notices',
			'wp-block-courier-notices__columns-' . esc_attr( $attributes['columns'] ?? 2 ),
			'wp-block-courier-notices__' . esc_attr( $display_type ),
			$block_unique_id
		];

		if ( $has_border ) {
			$container_class[] = 'wp-block-courier-notices__has-border';

			?><style>
				.<?php echo $block_unique_id; ?> .wp-block-courier-notice__inner {
					border-left: 6px solid <?php echo esc_attr( $border_color ); ?>;
				}
			</style><?php
		}

		if ( $has_number ) {

			?><style>
				.<?php echo $block_unique_id; ?> .wp-block-courier-notice__title:before {
					background-color: <?php echo esc_attr( $border_color ); ?>;
					color: <?php echo esc_attr( $number_color ); ?>;
				}
			</style><?php
		}

		?>
		<div class="<?php echo implode(' ', $container_class); ?>">
			<?php echo $content; ?>
		</div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
