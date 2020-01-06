<?php
/**
 * All things related to the admin.
 *
 * @package Courier\Controller\Admin
 */

namespace Courier\Controller\Admin;

/**
 * Admin Class
 */
class Admin {

	/**
	 * Register Actions
	 *
	 * @since 1.0
	 */
	public function register_actions() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'manage_courier_notice_posts_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );
		add_filter( 'manage_courier_notice_posts_columns', array( $this, 'manage_posts_columns' ), 998 );

		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ), 10, 1 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );

		add_action( 'restrict_manage_posts', array( $this, 'filter_courier_notices' ), 10, 2 );

		if ( ! empty( $_GET['post_type'] ) && 'courier_notice' === $_GET['post_type'] ) { // @codingStandardsIgnoreLine
			add_filter( 'months_dropdown_results', '__return_empty_array' );
		}
	}

	/**
	 * Override the publish message to not show a link to the notice
	 *
	 * @since 1.0
	 * @param array $messages Array of post type messages
	 *
	 * @return array Array of messages
	 */
	public function post_updated_messages( $messages ) {

		$messages['courier_notice'][6] = esc_html__( 'Courier Notice Published.', 'courier-notice' );

		return $messages;
	}

	/**
	 * Add custom columns.
	 *
	 * @since 1.0
	 *
	 * @param array $columns The post columns.
	 *
	 * @return array
	 */
	public function manage_posts_columns( $columns ) {
		if ( empty( $_GET['post_type'] ) || 'courier_notice' !== $_GET['post_type'] ) { // @codingStandardsIgnoreLine
			return $columns;
		}

		unset( $columns['date'] );

		return array_merge(
			$columns,
			array(
				'courier-summary'   => esc_html__( 'Summary', 'courier' ),
				'courier-type'      => esc_html__( 'Type', 'courier' ),
				'courier-style'     => esc_html__( 'Style', 'courier' ),
				'courier-placement' => esc_html__( 'Placement', 'courier' ),
				'courier-date'      => wp_kses(
					__( 'Expiration <a href="#" class="courier-info-icon courier-help" title="Non-expiry notices do not expire and will always be shown to users if the notice is not dismissable">?</a>', 'courier' ),
					array(
						'a' => array(
							'href'  => array(),
							'title' => array(),
							'class' => array(),
						),
					)
				),
			)
		);
	}

	/**
	 * Populate custom columns.
	 *
	 * @since 1.0
	 *
	 * @param array $column  The column.
	 * @param int   $post_id The post ID.
	 */
	public function manage_posts_custom_column( $column, $post_id ) {

		global $post;

		switch ( $column ) {
			case 'courier-placement':
				echo esc_html( wp_strip_all_tags( get_the_term_list( $post_id, 'courier_placement', '', ', ' ) ) );
				break;
			case 'courier-style':
				echo esc_html( wp_strip_all_tags( get_the_term_list( $post_id, 'courier_style', '', ', ' ) ) );
				break;
			case 'courier-summary':
				$summary = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );

				if ( ! empty( $summary ) ) {
					echo wp_kses_post( wp_trim_words( $summary, 20 ) );
				}
				break;
			case 'courier-type':
				$types = get_the_terms( $post->ID, 'courier_type' );

				$links = array();

				if ( ! empty( $types ) ) :
					?>
				<ul>
					<?php

					foreach ( $types as $term ) {
						$link    = get_edit_term_link( $term->term_id, 'courier_type' );
						$links[] = '<li class="courier-type courier_type-' . esc_attr( $term->slug ) . '"><a href="' . esc_url( $link ) . '" rel="tag" class="courier-content-wrapper"><span class="courier-icon icon-' . esc_attr( $term->slug ) . '"></span>' . $term->name . '</a></li>';
					}
					echo wp_kses_post( join( '', $links ) );
					?>
				</ul>
					<?php
				endif;
				break;
			case 'courier-date':
				$expiration = (int) get_post_meta( $post_id, '_courier_expiration', true );

				if ( ! empty( $expiration ) ) {
					$expiration = date( get_option( 'date_format' ) . ' h:i A', $expiration );
					echo esc_html( $expiration );
				} else {
					esc_html_e( 'Non-expiry', 'courier' );
				}

				break;
		}
	}

	/**
	 * When a non global notice is being viewed that has been dismissed,
	 * alert the user that the person will not see this notice because it has been dismissed.
	 *
	 * @todo this could probably have a filter the output of the markup.
	 * @since 1.0
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
			<div class="notice notice-warning">
				<p>
					<span class="dashicons dashicons-admin-site"></span>
					<strong><?php esc_html_e( 'This is a global notice and may have been dismissed by some users. It is recommended that you create a new global notice to ensure every user sees your new information.', 'courier' ); ?></strong>
				</p>
			</div>
			<?php
		}

		if ( ! has_term( array( 'dismissed' ), 'courier_status', $post->ID ) ) {
			return;
		}

		?>

		<div class="notice notice-dismissible notice-warning">
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
	 * @param string $hook The hook.
	 */
	public function admin_enqueue_scripts( $hook ) {
		$courier_dependencies = array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-autocomplete',
			'jquery-ui-datepicker',
			'jquery-ui-tooltip',
		);

		if ( ! in_array( $hook, array( 'post-new.php', 'post.php', 'edit.php', 'courier_notice_page_courier' ), true ) ) {
			return;
		}

		global $current_screen, $post;

		if ( 'edit.php' === $hook ) {
			if ( isset( $_GET['post_type'] ) && 'courier_notice' !== $_GET['post_type'] ) { // @codingStandardsIgnoreLine
				return;
			}
		}

		if ( 'courier_notice_page_courier' === $hook ) {
			$courier_dependencies[] = 'wp-color-picker';
		}

		wp_enqueue_script(
			'courier-admin',
			COURIER_PLUGIN_URL . 'js/courier-admin.js',
			$courier_dependencies,
			COURIER_VERSION,
			true
		);

		global $post;

		$status    = '';
		$post_type = '';

		if ( ! empty( $post ) ) {
			$status    = get_post_status( $post );
			$post_type = get_post_type( $post );
		}

		$current_user = wp_get_current_user();

		$strings = array(
			'expired'        => esc_html__( 'Expired', 'courier-notices' ),
			'label'          => esc_html__( 'Expired', 'courier-notices' ),
			'copy'           => esc_html__( 'Copy this shortcode to your clipboard', 'courier-notices' ),
			'copied'         => esc_html__( 'Courier Notice shortcode has been copied to your clipboard.', 'courier-notices' ),
			'confirm_delete' => esc_html__( 'Confirm Delete?', 'courier-notices' ),
			'deleting'       => esc_html__( 'Deleting...', 'courier-notices' ),
		);

		$strings = apply_filters( 'courier_notices_admin_strings', $strings ); // Allow filtering of localization strings.

		$courier_notices_admin_data = array(
			'post_id'             => ! is_null( $post ) ? $post->ID : 0,
			'post_type'           => ! is_null( $post ) ? $post->post_type : $current_screen->post_type,
			'site_uri'            => site_url(),
			'screen'              => $current_screen->base,
			'post_status'         => $status,
			'user_endpoint'       => trailingslashit( site_url( 'courier/user-search' ) ),
			'reactivate_endpoint' => trailingslashit( site_url( 'courier/reactivate' ) ),
			'dateFormat'          => get_option( 'date_format' ),
			'add_nonce'           => wp_create_nonce( 'courier_notices_add_type_nonce' ),
			'update_nonce'        => wp_create_nonce( 'courier_notices_update_type_nonce' ),
			'delete_nonce'        => wp_create_nonce( 'courier_notices_delete_type_nonce' ),
			'dismiss_nonce'       => wp_create_nonce( 'courier_notices_dismiss_nonce' ),
			'current_user'        => array(
				'ID'           => $current_user->ID,
				'display_name' => $current_user->display_name,
			),
			'version'             => COURIER_VERSION,
			'strings'             => $strings,
		);

		$courier_notices_admin_data = apply_filters( 'courier_notices_admin_data', $courier_notices_admin_data ); // Allow filtering of the entire localized dataset.

		wp_localize_script(
			'courier-admin',
			'courier_admin_data',
			$courier_notices_admin_data
		);
	}

	/**
	 * Enqueue our admin styles.
	 *
	 * @since 1.0
	 *
	 * @param string $hook The hook.
	 */
	public function admin_enqueue_styles( $hook ) {
		wp_enqueue_style(
			'courier-admin-global',
			COURIER_PLUGIN_URL . 'css/admin-courier-global.css',
			array(),
			COURIER_VERSION
		);

		if ( ! in_array( $hook, array( 'post-new.php', 'post.php', 'edit.php', 'courier_notice_page_courier' ), true ) ) {
			return;
		}

		if ( 'edit.php' === $hook ) {
			if ( isset( $_GET['post_type'] ) && 'courier_notice' !== $_GET['post_type'] ) { // @codingStandardsIgnoreLine
				return;
			}
		}

		wp_enqueue_style(
			'courier-admin',
			COURIER_PLUGIN_URL . 'css/admin-courier.css',
			array(),
			COURIER_VERSION
		);

		wp_add_inline_style( 'courier-admin', courier_get_css() );

		if ( ! in_array( $hook, array( 'courier_notice_page_courier' ), true ) ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Get a timestamp based on a date and duration
	 * The duration should be the number of months
	 *
	 * @since 1.0
	 *
	 * @throws \Exception If anything goes wrong.
	 *
	 * @param string $date     The date to get the timestamp for.
	 * @param string $duration The duration.
	 *
	 * @return int
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

	/**
	 * Allow for filtering of notices
	 *
	 * @since 1.0
	 *
	 * @param string $post_type The post type.
	 * @param string $which     Which notice.
	 */
	public function filter_courier_notices( $post_type, $which ) {

		if ( 'courier_notice' !== $post_type ) {
			return;
		}

		// A list of taxonomy slugs to filter by.
		$taxonomies = array( 'courier_type', 'courier_style', 'courier_placement', 'courier_status' );

		foreach ( $taxonomies as $taxonomy_slug ) {

			$taxonomy_obj  = get_taxonomy( $taxonomy_slug );
			$taxonomy_name = $taxonomy_obj->labels->name;
			$selected      = ( isset( $_GET[ $taxonomy_slug ] ) && '' !== $_GET[ $taxonomy_slug ] ) ? sanitize_text_field( $_GET[ $taxonomy_slug ] ) : ''; // @codingStandardsIgnoreLine

			wp_dropdown_categories(
				array(
					// translators: %1$s escaped taxonomy name.
					'show_option_all' => sprintf( __( 'All %1$s', 'courier' ), esc_html( $taxonomy_name ) ),
					'orderby'         => 'name',
					'taxonomy'        => $taxonomy_slug,
					'value_field'     => 'slug',
					'name'            => $taxonomy_slug,
					'hide_empty'      => false,
					'selected'        => $selected,
				)
			);
		}
	}
}
