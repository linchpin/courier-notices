<?php
/**
 * Block Controller
 *
 * Actions and filters for the admin area of WordPress
 *
 * @package Linchpin_Blocks\Controller
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
 * @package CourierPro\Controller
 */
class CourierNotice extends Block {


	/**
	 * Registers the hooks and filters for this Controller.
	 *
	 * @since 1.0.0
	 */
	public function register_actions(): void {
		add_filter( 'courier_notice_blocks', [ $this, 'add_block' ] );

	}

	/**
	 * Add our Card Items block to our list of blocks.
	 *
	 * @param $blocks
	 *
	 * @return mixed
	 */
	public function add_block( $blocks ): array {
		$blocks['courier-notice'] = [
			'render_callback' => [ __CLASS__, 'dynamic_render_callback' ],
			'editor_script'   => 'courier-notice-block',
		];

		return $blocks;
	}

	/**
	 * Get the Side Content block and output them into some HTML
	 *
	 * @since 1.0.0
	 *
	 * @param $block_attributes
	 * @param $content
	 *
	 * @return string
	 */
	public static function dynamic_render_callback( $block_attributes, $content, $block ): string {
		$output = '';

		$wrapper_attributes = get_block_wrapper_attributes();
		$attributes         = $block_attributes;

		$card_title = $attributes['cardTitle'] ?? '';
		$has_icon   = $attributes['hasIcon'] ?? false;
		$media_id   = $attributes['cardMediaId'] ?? 0;

		ob_start();

		?>
		<div class="wp-block-courier-notice">
			<div class="wp-block-courier-notice__inner">
				<?php if ( $has_icon && $media_id ) : ?>
					<div class="wp-block-courier-notice__icon">
						<?php echo wp_get_attachment_image( $media_id, [50, 50] ); ?>
					</div>
				<?php endif; ?>

				<div class="wp-block-courier-notice__content">
					<h2 class="wp-block-courier-notice__title"><?php echo esc_html( $card_title ); ?></h2>
					<?php echo $content; ?>
				</div>
			</div>
		</div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
