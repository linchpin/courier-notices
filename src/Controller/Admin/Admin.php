<?php

namespace Courier\Controller\Admin;

/**
 * Class Admin
 *
 * All things related to the admin.
 *
 * @package Courier\Controller\Admin
 */
class Admin {

	/**
	 * Register Actions
	 */
	public function register_actions() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'manage_courier_notice_posts_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );
		add_filter( 'manage_courier_notice_posts_columns', array( $this, 'manage_posts_columns' ), 999 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
	}

	/**
	 * Add some custom columns.
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_posts_columns( $columns ) {
		if ( empty( $_GET['post_type'] ) || 'courier_notice' !== $_GET['post_type'] ) { // wpcs ok.
			return $columns;
		}

		$type      = $columns['taxonomy-courier_type'];
		$placement = $columns['taxonomy-courier_placement'];

		unset( $columns['date'] );
		unset( $columns['taxonomy-courier_type'] );
		unset( $columns['taxonomy-courier_placement'] );

		return array_merge(
			$columns,
			array(
				'courier-summary'            => esc_html__( 'Summary', 'courier' ),
				'taxonomy-courier_type'      => $type,
				'taxonomy-courier_placement' => $placement,
				'courier-global'             => esc_html__( 'Global', 'courier' ),
				'courier-date'               => esc_html__( 'Expiration', 'courier' ),
			)
		);
	}

	/**
	 * Populate custom columns.
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function manage_posts_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'courier-global':
				if ( has_term( 'global', 'courier_scope', $post_id ) ) {
					echo '<span class="dashicons dashicons-admin-site"></span>';
				}
				break;
			case 'courier-summary':
				$summary = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );

				if ( ! empty( $summary ) ) {
					echo wp_kses_post( wp_trim_words( $summary, 20 ) );
				}
				break;
			case 'courier-date':
				$expiration = (int) get_post_meta( $post_id, '_courier_expiration', true );

				if ( ! empty( $expiration ) ) {
					$expiration = date( get_option( 'date_format' ) . ' h:i A', $expiration );
				} else {
					$expiration = '';
				}

				echo esc_html( $expiration );

				break;
		}
	}

	/**
	 * When a non global notice is being viewed that has been dismissed,
	 * alert the user that the person will not see this notice because it has been dismissed.
	 *
	 * @todo this could probably have a filter the output of the markup.
	 *
	 * @since 1.0
	 *
	 */
	public function admin_notices() {
		$current_screen = get_current_screen();

		if ( 'post' !== $current_screen->base || 'courier_notice' !== $current_screen->id ) {
			return;
		}

		global $post;

		if ( empty( $post ) ) {
			return;
		}

		if ( has_term( array( 'global' ), 'courier_scope', $post->ID ) && 'publish' === $post->post_status ) {
			?>

			<div class="notice notice-dismissible update-nag">
				<strong><?php esc_html_e( 'This is a global notice and may have been dismissed by some users. It is recommended that you create a new global notice to ensure every user sees your new information.', 'courier' ); ?></strong>
			</div>

			<?php
		}

		if ( ! has_term( array( 'dismissed' ), 'courier_status', $post->ID ) ) {
			return;
		}

		?>

		<div class="notice notice-dismissible update-nag">
			<?php esc_html_e( 'This notice has already been dismissed. Any changes made will not be seen by the user.', 'courier' ); ?>
			<a href="#" class="courier-reactivate-notice" data-courier-notice-id="<?php echo esc_attr( $post->ID ); ?>">
				<?php esc_html_e( 'Reactivate this notice', 'courier' ); ?>
			</a>.
		</div>

		<?php
	}

	/**
	 * Enqueue our admin scripts.
	 *
	 * @since 1.0
	 *
	 * @param $hook
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( ! in_array( $hook, array( 'post-new.php', 'post.php', 'edit.php' ), true ) ) {
			return;
		}

		if ( 'edit.php' === $hook ) {
			if ( isset( $_GET['post_type'] ) && 'courier_notice' !== $_GET['post_type'] ) {
				return;
			}
		}

		wp_enqueue_script( 'courier-admin', COURIER_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-autocomplete', 'jquery-ui-datepicker' ), COURIER_VERSION, true );

		global $post;

		$status    = '';
		$post_type = '';

		if ( ! empty( $post ) ) {
			$status    = get_post_status( $post );
			$post_type = get_post_type( $post );
		}

		$current_user = wp_get_current_user();

		wp_localize_script(
			'courier-admin',
			'courier_admin_data',
			array(
				'post_status'         => $status,
				'post_type'           => $post_type,
				'user_endpoint'       => trailingslashit( site_url( 'courier/user-search' ) ),
				'reactivate_endpoint' => trailingslashit( site_url( 'courier/reactivate' ) ),
				'dateFormat'          => get_option( 'date_format' ),
				'current_user'        => array(
					'ID'           => $current_user->ID,
					'display_name' => $current_user->display_name,
				),
				'strings'             => array(
					'expired' => esc_html__( 'Expired', 'courier' ),
					'label'   => esc_html__( 'Expired', 'courier' ),
					'copy'    => esc_html__( 'Copy this shortcode to your clipboard', 'courier' ),
					'copied'  => esc_html__( 'Courier shortcode has been copied to your clipboard.', 'courier' ),
				),
			)
		);
	}

	/**
	 * Enqueue our admin styles.
	 *
	 * @since 1.0
	 *
	 * @param $hook
	 */
	public function admin_enqueue_styles( $hook ) {

		if ( ! in_array( $hook, array( 'post-new.php', 'post.php', 'edit.php' ), true ) ) {
			return;
		}

		if ( 'edit.php' === $hook ) {
			if ( isset( $_GET['post_type'] ) && 'courier_notice' !== $_GET['post_type'] ) {
				return;
			}
		}

		wp_enqueue_style( 'courier-admin', COURIER_PLUGIN_URL . 'assets/css/admin.css', array(), COURIER_VERSION );
	}

	/**
	 * Get a timestamp based on a date and duration
	 * The duration should be the number of months
	 *
	 * @param $date
	 * @param $duration
	 *
	 * @return int
	 * @throws \Exception
	 */
	private function get_timestamp( $date, $duration ) {
		$date_time = new \DateTime( $date );
		$old_day   = $date_time->format( 'd' );

		$date_time->add( new \DateInterval( 'P' . $duration . 'M' ) );

		$new_day = $date_time->format( 'd' );

		if ( $old_day !== $new_day ) {
			$date_time->sub( new \DateInterval( 'P' . $new_day . 'D' ) );
		}

		return $date_time->getTimestamp();
	}
}
