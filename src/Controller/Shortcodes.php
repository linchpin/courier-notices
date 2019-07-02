<?php

namespace Courier\Controller;

/**
 * Class Shortcodes
 * @package Courier\Controller
 */
class Shortcodes {

	public function register_actions() {
		add_shortcode( 'courier_notifications', array( $this, 'courier_notifications' ) );
		add_shortcode( 'get_courier_notice', array( $this, 'get_courier_notice' ) );
	}

	/**
	 * Output Courier Notifications.
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function courier_notifications( $atts = array() ) {
		$atts = wp_parse_args(
			$atts,
			array(
				'user_id'                      => get_current_user_id(),
				'include_global'               => true,
				'include_dismissed'            => false,
				'prioritize_persistent_global' => true,
				'number'                       => 6,
				'format'                       => 'list',
			)
		);

		$notices = courier_get_notices( $atts['user_id'], $atts['include_global'], $atts['include_dismissed'], $atts['prioritize_persistent_global'], false, $atts['number'] );

		ob_start();
		if ( empty( $notices ) ) : ?>
			<p>You do not have any new notifications. View old <a href="<?php echo esc_url( trailingslashit( site_url( 'notifications' ) ) ); ?>">notifications here</a>.</p>
		<?php else : ?>
			<?php if ( 'posts' === $atts['format'] ) : ?>
				<div class="courier-notifications courier-shortcode">
			<?php else : ?>
				<ul class="courier-notifications courier-shortcode">
			<?php endif; ?>

			<?php

			foreach ( $notices as $notice ) :

				$element = ( 'posts' === $atts['format'] ) ? 'div' : 'li';

				sprintf(
					'<%1$s class="%2$s" data-courier-notice-id="%3$d"><span class="meta">%4$s</span> %5$s</%1$s>',
					esc_html( $element ),
					implode( ' ', array_map( 'esc_attr', get_post_class( array(), $notice->ID ) ) ),
					esc_attr( $notice->ID ),
					esc_html( get_the_time( 'm.j.y', $notice ) ),
					wp_kses_post( apply_filters( 'courier_excerpt', $notice->post_content ) )
				);

			endforeach;
			wp_reset_postdata();
			?>

			<?php if ( 'posts' === $atts['format'] ) : ?>
				</div>
			<?php else : ?>
				</ul>
			<?php endif; ?>
		<?php
		endif;

		return ob_get_clean();
	}

	/**
	 * Get the courier notice based on the attributes
	 *
	 * @since 1.0
	 * @param      $atts
	 * @param null $content
	 *
	 * @return false|string|void
	 */
	function get_courier_notice( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'id'        => '',
			'flag'      => 'Alert',
			'show_flag' => 'true',
		), $atts, 'get_courier_notice' );

		$id = (int) $atts['id'];

		if ( empty( $id ) ) {
			return;
		}

		$notice_args = array(
			'post_type'     => 'courier_notiice',
			'post_per_page' => 1,
			'no_found_rows' => true,
			'p'             => $atts['id'],
		);

		$notice = new \WP_Query( $notice_args );

		if ( $notice->have_posts() ) {
			$notice->the_post();

			ob_start();
			?>
			<div <?php post_class(); ?>>
				<?php if ( 'true' == $atts['show_flag'] ) : ?>
					<span class="courier-notice-flag"><?php _e( $atts['flag'], 'courier' ) ?></span>
				<?php endif; ?>
				<?php the_title( '<h4>', '</h4>', true ); ?>
				<small><?php echo get_the_content(); ?></small>
			</div>
			<?php
			$output = ob_get_contents();

			ob_end_clean();

            wp_reset_postdata();

			return $output;
		} else {
			return;
		}
	}
}
