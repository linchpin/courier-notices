<?php

namespace Courier\Controller;

/**
 * Class Courier
 * @package Courier\Controller
 */
class Courier {

	public function register_actions() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_filter( 'views_edit-courier_notice', array( $this, 'views_addition' ) );

		add_action( 'add_meta_boxes_courier_notice', array( $this, 'add_meta_boxes_courier_notice' ), 99 );
		add_action( 'save_post_courier_notice', array( $this, 'save_post_courier_notice' ), 10, 2 );

		add_action( 'init', array( $this, 'add_expired_status' ) );

		add_action( 'wp_insert_post', array( $this, 'wp_insert_post' ), 10, 3 );

		add_filter( 'request', array( $this, 'request' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		add_filter( 'template_include', array( $this, 'template_include' ) );
		add_filter( 'document_title_parts', array( $this, 'document_title_parts' ) );

		add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );

		add_filter( 'courier_excerpt', 'wp_trim_excerpt' );
		add_filter( 'courier_excerpt', 'wptexturize' );
		add_filter( 'courier_excerpt', 'convert_smilies' );
		add_filter( 'courier_excerpt', 'convert_chars' );

		if ( has_action( 'wp_body_open' ) ) {
			add_action( 'wp_body_open', '' );
		}
	}

	/**
	 * Add a custom post status for expired notices
	 * @since 1.0
	 *
	 */
	public function add_expired_status() {
		register_post_status(
			'courier_expired',
			array(
				'label'                     => esc_html_x( 'Expired', 'courier_notice', 'courier' ),
				'public'                    => false,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				// translators: %1$s count of hoow many terms have expired
				'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'courier' ),
			)
		);
	}

	/**
	 * Check and schedule plugin upgrading if necessary.
	 */
	public function plugins_loaded() {
		if ( version_compare( COURIER_VERSION, get_option( 'courier_version', '0.0.0' ), '>' ) ) {
			add_action( 'admin_init', array( $this, 'upgrade' ), 999 );
		}
	}

	/**
	 * @param $messages
	 *
	 * @return mixed
	 */
	public function post_updated_messages( $messages ) {
		global $post;

		$permalink = get_permalink( $post );

		$messages['courier_notice'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => esc_html__( 'Courier notice updated.', 'courier' ),
			2  => esc_html__( 'Custom field updated.', 'courier' ),
			3  => esc_html__( 'Custom field deleted.', 'courier' ),
			4  => esc_html__( 'Notice updated.', 'courier' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Notice restored to revision from %s', 'courier' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // @codingStandardsIgnoreLine
			/* translators: %s: link to notice */
			6  => sprintf( __( 'Notice published. <a href="%1$s">View notice</a>', 'courier' ), esc_url( $permalink ) ),
			7  => esc_html__( 'Notice saved.', 'courier' ),
			/* translators: %1$s: link to preview */
			8  => sprintf( __( 'Notice submitted. <a target="_blank" href="%1$s">Preview notice</a>', 'courier' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf(
				/* translators: %1$s: date and time of the revision, %2$s: link to notice */
				__( 'Notice scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview notice</a>', 'courier' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ),
				esc_url( $permalink )
			),
			/* translators: %1$s: date and time of the revision, %2$s: link to notice */
			10 => sprintf( __( 'Notice draft updated. <a target="_blank" href="%s">Preview notice</a>', 'courier' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		);

		return $messages;
	}

	/**
	 * Add a links for global and expired notices in the admin table view.
	 *
	 * @param $views
	 *
	 * @return array
	 */
	public function views_addition( $views ) {
		/**
		 * Global Notes
		 */
		$global_notices = new \WP_Query(
			array(
				'post_type'      => 'courier_notice',
				'posts_per_page' => 1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'courier_scope',
						'field'    => 'name',
						'terms'    => array( 'Global' ),
						'operator' => 'IN',
					),
				),
			)
		);

		if ( $global_notices->have_posts() ) {
			$count = $global_notices->found_posts;

			$global_notice_url = esc_url(
				add_query_arg(
					array(
						'post_type'     => 'courier_notice',
						'post_status'   => 'any',
						'courier_scope' => 'global',
					),
					admin_url( 'edit.php' )
				)
			);

			$notice_class = '';

			if ( isset( $_GET['courier_scope'] ) && 'global' === $_GET['courier_scope'] ) {
				$notice_class = ' class="current"';
			}

			$global_notices_link = sprintf(
				'<a href="%1$s" %2$s>%3$s <span class="count">(%4$d)</span></a>',
				$global_notice_url,
				$notice_class,
				esc_html__( 'Global', 'courier' ),
				(int) $count
			);

			$views['courier_global'] = $global_notices_link;
		}

		/**
		 * Expired Notes
		 */
		$expired_notices = new \WP_Query(
			array(
				'post_type'      => 'courier_notice',
				'posts_per_page' => 1,
				'post_status'    => 'courier_expired',
			)
		);

		if ( $expired_notices->have_posts() ) {
			$count = $expired_notices->found_posts;

			$expired_notice_url = esc_url(
				add_query_arg(
					array(
						'post_type'   => 'courier_notice',
						'post_status' => 'courier_expired',
					),
					admin_url( 'edit.php' )
				)
			);

			$expired_notice_class = '';

			if ( isset( $_GET['post_status'] ) && 'courier_expired' === $_GET['post_status'] ) {
				$expired_notice_class = ' class="current"';
			}

			$expired_notices_link = sprintf(
				'<a href="%1$s" %2$s>%3$s <span class="count">(%4$d)</span></a>',
				$expired_notice_url,
				$expired_notice_class,
				esc_html__( 'Expired', 'courier' ),
				(int) $count
			);

			$views['courier_expired'] = $expired_notices_link;
		}

		return $views;
	}

	/**
	 * Show select for selecting notice type
	 */
	public function post_submitbox_misc_actions() {
		global $post;

		wp_nonce_field( '_courier_info_nonce', '_courier_info_noncename' );

		// If auto-draft check the global flag by default, else fall back to the scope
		if ( 'auto-draft' === get_post_status( $post->ID ) ) {
			$scope = true;
		} else {
			$scope = has_term( 'Global', 'courier_scope', $post->ID );
		}

		?>
		<div class="misc-pub-section courier-scope">
			<span class="dashicons dashicons-sticky wp-media-buttons-icon"></span>&nbsp;<label for="courier_scope"><?php esc_html_e( 'Global Notice:', 'courier' ); ?></label>&nbsp
			<input type="checkbox" name="courier_scope" id="courier_scope" value="1" <?php checked( $scope ); ?> />
		</div>
		<div class="misc-pub-section courier-dismissable">
			<span class="dashicons dashicons-no-alt wp-media-buttons-icon"></span>&nbsp;
			<label for="courier_dismissible"><?php esc_html_e( 'Dismissible Notice:', 'courier' ); ?></label>&nbsp;
			<input type="checkbox" name="courier_dismissible" id="courier_dismissible" value="1" <?php checked( get_post_meta( $post->ID, '_courier_dismissible', true ) ); ?> />
		</div>
		<div class="misc-pub-section">
			<label for="courier-shortcode" aria-hidden="true" class="screen-reader-text"><?php esc_html_e( 'Courier Shortcode', 'courier' ); ?></label>
			<textarea id="courier-shortcode" class="widefat">[get_courier_notice id="<?php echo esc_attr( $post->ID ); ?>"]</textarea>
			<p><a class="copy-text button" data-copy="courier-shortcode"><?php esc_html_e( 'Copy Courier Shortcode', 'courier' ); ?></a></p>
			<span class="copy-link-indicator"></span>
		</div>
		<?php
	}

	/**
	 * Add an option for selecting notice type
	 */
	public function add_meta_boxes_courier_notice() {
		add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );

		add_meta_box( 'courier_meta_box', esc_html__( 'Notice Information', 'courier' ), array( $this, 'courier_meta_box' ), 'courier_notice', 'side', 'default' );
	}

	/**
	 * Allow for notices to be customized
	 *
	 * Set expiration on a notice
	 * Assign a notice to a specific user in within WordPress
	 *
	 * @param $post
	 */
	public function courier_meta_box( $post ) {
		wp_nonce_field( 'courier_expiration_nonce', 'courier_expiration_noncename' );

		global $wp_local;

		?>
		<h4><?php esc_html_e( 'Notice Type', 'courier' ); ?></h4>
		<?php

		if ( has_term( '', 'courier_type' ) ) {
			$selected_courier_type = get_the_terms( $post->ID, 'courier_type' );
		}

		if ( ! empty( $selected_courier_type ) ) {
			$selected_courier_type = $selected_courier_type[0]->slug;
		} else {
			$selected_courier_type = 'info';
		}

		// Create and display the dropdown menu.
		wp_dropdown_categories(
			array(
				'orderby'           => 'name',
				'taxonomy'          => 'courier_type',
				'value_field'       => 'slug',
				'name'              => 'courier_type',
				'class'             => 'widefat',
				'hide_empty'        => false,
				'required'          => true,
				'option_none_value' => apply_filters( 'courier_default_notice_type', 'info' ),
				'selected'          => $selected_courier_type,
			)
		);
		?>

		<h4><?php esc_html_e( 'Notice Placement / Type', 'courier' ); ?></h4>
		<?php

		if ( has_term( '', 'courier_placement' ) ) {
			$selected_courier_placement = get_the_terms( $post->ID, 'courier_placement' );
		}

		if ( ! empty( $selected_courier_placement ) ) {
			$selected_courier_placement = $selected_courier_placement[0]->slug;
		} else {
			$selected_courier_placement = 'header';
		}

		// Create and display the dropdown menu.
		wp_dropdown_categories(
			array(
				'orderby'           => 'name',
				'taxonomy'          => 'courier_placement',
				'value_field'       => 'slug',
				'name'              => 'courier_placement',
				'class'             => 'widefat',
				'hide_empty'        => false,
				'required'          => true,
				'option_none_value' => apply_filters( 'courier_default_notice_placement', 'header' ),
				'selected'          => $selected_courier_placement,
			)
		);
		?>
		<?php

		// Date Display

		$current_date = (int) get_post_meta( $post->ID, '_courier_expiration', true );

		if ( ! empty( $current_date ) ) {
			$current_date = date( get_option( 'date_format' ) . ' h:i A', $current_date );
		} else {
			$current_date = '';
		}
		?>
		<h4><?php esc_html_e( 'Notice Expiration', 'courier' ); ?></h4>
		<p class="description"><?php esc_html_e( 'Enter a date and time this notice should expire.', 'courier' ); ?></p>

		<fieldset id="courier-timestampdiv">
			<legend class="screen-reader-text"><?php esc_html_e( 'Expiration date and time', 'courier' ); ?></legend>
			<div class="timestamp-wrap">
				<label for="courier_expire_date">
					<input type="text" class="widefat" autocomplete="off" id="courier_expire_date" name="courier_expire_date" value="<?php echo esc_attr( $current_date ); ?>">
				</label>
			</div>
		</fieldset>

		<?php
		wp_nonce_field( 'courier_recipient_nonce', 'courier_recipient_noncename' );

		$author = new \WP_User( $post->post_author );

		if ( 'auto-draft' === get_post_status( $post->ID ) ) {
			$show_user_select = 'hidden';
		} else {
			$show_user_select = ( has_term( 'Global', 'courier_scope' ) ) ? 'hidden' : '';
		}

		?>
		<div id="courier-author-container" class="<?php echo esc_attr( $show_user_select ); ?>">
			<h4><?php esc_html_e( 'Assign Notice to User', 'courier' ); ?></h4>

			<p class="description">
				<?php if ( ! has_term( 'Global', 'courier_scope' ) ) : ?>
					<?php esc_html_e( "Type in a user's information. You must click on the user you want to assign the notice to.", 'courier' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'Global notices may not be assigned to a specific user. They apply to ALL users', 'courier' ); ?>
				<?php endif; ?>
			</p>
			<p>
				<label for="courier_recipient_field" aria-hidden="true" class="screen-reader-text"><?php esc_html_e( 'Recipient', 'courier' ); ?></label>
				<input type="text" name="courier_recipient" id="courier_recipient_field" class="widefat" placeholder="<?php esc_html_e( 'Type name, username or email...', 'courier' ); ?>" value="<?php echo esc_attr( $author->user_email ); ?>" <?php disabled( has_term( 'Global', 'courier_scope' ) ); ?> />
			</p>
		</div>
		<input type="hidden" name="post_author_override" id="post_author_override" value="<?php echo esc_attr( $author->ID ); ?>" />
		<?php
	}

	/**
	 * Get the currently selected type of notice.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_notice_selected_type() {
		return 'info';
	}

	/**
	 * Save our notice data
	 *
	 * @param int $post_id
	 * @param object|array $post
	 */
	public function save_post_courier_notice( $post_id, $post ) {

		// Skip revisions and autosaves.
		if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return;
		}

		// Users should have the ability to edit listings.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['_courier_info_noncename'] ) && wp_verify_nonce( $_POST['_courier_info_noncename'], '_courier_info_nonce' ) ) {
			if ( empty( $_POST['courier_scope'] ) ) {
				wp_set_object_terms( $post_id, null, 'courier_scope' );
			} else {
				wp_set_object_terms( $post_id, 'Global', 'courier_scope', false );
				wp_remove_object_terms( $post_id, array( 'dismissed' ), 'courier_status' );
			}

			if ( empty( $_POST['courier_dismissible'] ) ) {
				delete_post_meta( $post_id, '_courier_dismissible' );
			} else {
				update_post_meta( $post_id, '_courier_dismissible', 1 );
			}

			if ( empty( $_POST['courier_placement'] ) ) {
				wp_set_object_terms( $post_id, null, 'courier_placement' );
			} else {
				// Only set the courier type if the type actually exists.
				if ( term_exists( $_POST['courier_placement'], 'courier_placement' ) ) {
					wp_set_object_terms( $post_id, (string) $_POST['courier_placement'], 'courier_placement' );
				}
			}

			if ( empty( $_POST['courier_type'] ) ) {
				wp_set_object_terms( $post_id, null, 'courier_type' );
			} else {
				// Only set the courier type if the type actually exists.
				if ( term_exists( $_POST['courier_type'], 'courier_type' ) ) {
					wp_set_object_terms( $post_id, (string) $_POST['courier_type'], 'courier_type' );
				}
			}
		}

		if ( isset( $_POST['courier_expiration_noncename'] ) && wp_verify_nonce( $_POST['courier_expiration_noncename'], 'courier_expiration_nonce' ) ) {

			if ( empty( $_POST['courier_expire_date'] ) ) {
				delete_post_meta( $post_id, '_courier_expiration' );
			} else {
				$expire_date = strtotime( $_POST['courier_expire_date'] );
				update_post_meta( $post_id, '_courier_expiration', $expire_date );
			}
		}

		wp_cache_delete( 'global-notices', 'courier' );
		wp_cache_delete( 'global-dismissible-notices', 'courier' );
		wp_cache_delete( 'global-persistent-notices', 'courier' );
	}

	/**
	 * When creating new notice for a specific user, log who created it.
	 * @since 1.0
	 *
	 * @param int          $post_id
	 * @param array|object $post
	 * @param bool         $update
	 */
	public function wp_insert_post( $post_id, $post, $update ) {
		if ( ! is_admin() ) {
			return;
		}

		if ( $update ) {
			return;
		}

		if ( has_term( 'Global', 'courier_scope', $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, '_courier_sender', get_current_user_id() );
	}

	/**
	 * Add some custom query vars
	 *
	 * @param $vars
	 *
	 * @return array
	 */
	public function query_vars( $vars ) {
		return array_merge(
			$vars,
			array(
				'courier_include_global',
				'courier_include_dismissed',
				'courier_include_expired',
			)
		);
	}

	/**
	 * Force a login when trying to visit the notifications page
	 *
	 * @param $vars
	 *
	 * @return mixed
	 */
	public function request( $vars ) {
		if ( ! empty( $vars['courier_notices'] ) ) {
			if ( ! is_user_logged_in() ) {
				wp_safe_redirect( wp_login_url( trailingslashit( site_url( 'notifications' ) ) ) );
				exit;
			}

			$vars['post_type']                 = 'courier_notice';
			$vars['posts_per_page']            = 100;
			$vars['courier_include_global']    = true;
			$vars['courier_include_dismissed'] = true;
		}

		return $vars;
	}

	/**
	 * Use custom query vars to include specific scopes of notices
	 *
	 * @param $query
	 */
	public function pre_get_posts( $query ) {
		if ( is_admin() ) {
			return;
		}

		if ( ! $query->is_main_query() || 'courier_notice' !== $query->get( 'post_type' ) ) {
			return;
		}

		$include_global    = (boolean) $query->get( 'courier_include_global' );
		$include_dismissed = (boolean) $query->get( 'courier_include_dismissed' );

		$notices = courier_get_notices(
			array(
				'user_id'                      => get_current_user_id(),
				'include_global'               => $include_global,
				'include_dismissed'            => $include_dismissed,
				'prioritize_persistent_global' => true,
				'ids_only'                     => true,
				'number'                       => 100,
			)
		);

		// If no notices are found, be super specific to ensure an empty result is in fact returned to the user.
		if ( empty( $notices ) ) {
			$query->query_vars['author'] = get_current_user_id();
		} else {
			$query->query_vars['post__in'] = $notices;
		}
	}

	/**
	 * If a custom template exists in the current theme for notifications, use that one instead.
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function template_include( $template ) {
		global $wp_query;

		if ( empty( $wp_query->get( 'courier_notices' ) ) ) {
			return $template;
		}

		$new_template = locate_template( array( 'courier/notices.php' ) );

		if ( ! empty( $new_template ) ) {
			$template = $new_template;
		}

		return $template;
	}

	/**
	 * When viewing the notification page, filter the title.
	 *
	 * @param mixed $title
	 *
	 * @return mixed
	 */
	public function document_title_parts( $title ) {
		global $wp_query;

		if ( empty( $wp_query->get( 'courier_notices' ) ) ) {
			return $title;
		}

		$title['title'] = esc_html__( 'Notifications', 'courier' );

		return $title;
	}

	/**
	 * Add classes for dismissed and global notice for notices.
	 *
	 * @param $classes
	 * @param $class
	 * @param $post_id
	 *
	 * @return array
	 */
	public function post_class( $classes, $class, $post_id ) {
		if ( 'courier_notice' !== get_post_type( $post_id ) || ! is_user_logged_in() ) {
			return $classes;
		}

		if ( has_term( 'Global', 'courier_scope', $post_id ) ) {
			$classes[] = 'courier-notice-global';

			// Mark dismissed global notices as such
			if ( $global_dismissals = courier_get_global_dismissed_notices() ) {
				if ( in_array( $post_id, $global_dismissals, true ) ) {
					$classes[] = 'courier-notice-dismissed';
				}
			}
		} elseif ( has_term( 'Dismissed', 'courier_status', $post_id ) ) {
			$classes[] = 'courier-notice-dismissed';
		}

		return $classes;
	}
}

