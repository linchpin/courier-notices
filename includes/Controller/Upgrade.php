<?php
/**
 * Upgrade Controller
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Model\Config;

/**
 * Class Upgrade
 */
class Upgrade {

	/**
	 * Configuration
	 *
	 * @var Config
	 */
	private $config;


	/**
	 * Install constructor
	 */
	public function __construct() {
		$this->config = new Config();
	}


	/**
	 * Registers hooks and filters
	 *
	 * @since 1.0
	 */
	public function register_actions() {
		add_action( 'admin_init', array( $this, 'upgrade' ), 999 );
		add_action( 'admin_notices', array( $this, 'show_review_nag' ), 11 );
		add_action( 'admin_notices', array( $this, 'show_action_scheduler_migration_notice' ), 10 );
		add_action( 'wp_ajax_courier_notices_dismiss_migration_notice', array( $this, 'dismiss_migration_notice' ) );
	}


	/**
	 * Check and schedule plugin upgrading if necessary.
	 *
	 * @since 1.0
	 */
	public function upgrade() {
		$plugin_options = get_option( 'courier_options', array() );

		if ( empty( $plugins_options ) ) {
			$plugin_options = get_option( 'courier_notices_options', array( 'plugin_version' => '0.0.0' ) );
		}

		$stored_version = ( $plugin_options['plugin_version'] ) ? $plugin_options['plugin_version'] : '0.0.0';

		// Keep the plugin version up to date.
		if ( version_compare( '1.0.0', $stored_version, '>' ) ) {
			flush_rewrite_rules();
			wp_schedule_event( time(), 'hourly', 'courier_expire' );

			update_option( 'courier_version', '1.0.0' );
		}

		if ( version_compare( '1.0.5', $stored_version, '>' ) ) {
			$plugin_options['plugin_version'] = '1.0.5';
			update_option( 'courier_options', $plugin_options );
		}

		if ( version_compare( '1.1.0', $stored_version, '>' ) ) {

			// Create our default style of courier notices.
			if ( ! term_exists( 'Informational', 'courier_style' ) ) {
				wp_insert_term( esc_html__( 'Informational', 'courier-notices' ), 'courier_style' );
			}

			if ( ! term_exists( 'popup-modal', 'courier_style' ) ) {
				wp_insert_term( esc_html__( 'Pop Over / Modal', 'courier-notices' ), 'courier_style', array( 'slug' => 'popup-modal' ) );
			}

			// Just in case we don't have a modal in our placement taxonomy, create one.
			if ( ! term_exists( 'popup-modal', 'courier_placement' ) ) {
				wp_insert_term( esc_html__( 'Pop Over / Modal', 'courier-notices' ), 'courier_placement', array( 'slug' => 'popup-modal' ) );
			}

			// Remove the version from it's own variable.
			delete_option( 'courier_version' );

			$plugin_options['plugin_version'] = '1.1.0';
			update_option( 'courier_options', $plugin_options );
		}

		if ( version_compare( '1.2.0', $stored_version, '>' ) ) {
			$plugin_options['plugin_version'] = '1.2.0';
			update_option( 'courier_notices_options', $plugin_options ); // Save options in the new namespace.
			delete_option( 'courier_options' ); // Remove older options that exist.

			// Rename the design options to the new name space.
			$courier_design_settings = get_option( 'courier_design', array() );

			update_option( 'courier_notices_design', $courier_design_settings );
			delete_option( 'courier_design' );
		}

		// Migrate to Action Scheduler for version 1.8.0
		if ( version_compare( '1.8.0', $stored_version, '>' ) ) {
			$this->migrate_to_action_scheduler();
			$plugin_options['plugin_version'] = '1.8.0';
			update_option( 'courier_notices_options', $plugin_options );
		}

		// Do one last check to make sure our stored version is the latest.
		if ( version_compare( COURIER_NOTICES_VERSION, $stored_version, '>' ) ) {

			delete_transient( 'courier_notices_notice_css' );
			delete_transient( 'courier_notice_css' );
			courier_get_css();

			$plugin_options['plugin_version'] = COURIER_NOTICES_VERSION;
			update_option( 'courier_notices_options', $plugin_options );
		}
	}


	/**
	 * Show a nag to the user to review Courier Notices.
	 * Because it's awesome! -Patrick Swayze
	 *
	 * @since 1.0
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function show_review_nag() {
		$options       = get_option( 'courier_notices_options' );
		$notifications = get_user_option( 'courier_notices_notifications' );

		// If we don't have a date die early.
		if ( ! isset( $options['first_activated_on'] ) || '' === $options['first_activated_on'] ) {
			return '';
		}

		$now          = new \DateTime();
		$install_date = new \DateTime();
		$install_date->setTimestamp( $options['first_activated_on'] );

		if ( $install_date->diff( $now )->days < 30 ) {
			return '';
		}

		if ( false !== $options && ( ! empty( $notifications['update-notice'] ) && empty( $notifications['review-notice'] ) ) ) {
			include COURIER_NOTICES_PATH . 'templates/admin/review-notice.php';
		}

		return '';
	}

	/**
	 * Show notice about Action Scheduler migration
	 *
	 * @since 1.8.0
	 */
	public function show_action_scheduler_migration_notice() {
		// Only show to users who can manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Check if we should show the notice.
		if ( ! get_transient( 'courier_notices_action_scheduler_migrated' ) ) {
			return;
		}

		// Don't show on every page, just on relevant admin pages.
		$screen = get_current_screen();
		if ( ! $screen || ( 'edit' !== $screen->base && 'post' !== $screen->base && 'courier_notice' !== $screen->post_type ) ) {
			return;
		}

		?>
		<div class="notice notice-success is-dismissible">
			<h3><?php esc_html_e( 'Courier Notices: Migration Complete', 'courier-notices' ); ?></h3>
			<p>
				<?php esc_html_e( 'Great news! Courier Notices has been successfully upgraded to use Action Scheduler for more reliable notice expiration.', 'courier-notices' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Benefits of this upgrade:', 'courier-notices' ); ?>
			</p>
			<ul style="margin-left: 20px;">
				<li><?php esc_html_e( '• More reliable notice expiration timing', 'courier-notices' ); ?></li>
				<li><?php esc_html_e( '• Better performance and scalability', 'courier-notices' ); ?></li>
				<li><?php esc_html_e( '• Individual notice scheduling instead of bulk checking', 'courier-notices' ); ?></li>
				<li><?php esc_html_e( '• Improved error handling and retry logic', 'courier-notices' ); ?></li>
			</ul>
			<p>
				<?php esc_html_e( 'Your existing notices have been automatically migrated. No further action is required.', 'courier-notices' ); ?>
			</p>
			<script>
				jQuery(document).ready(function($) {
					$(document).on('click', '.notice-dismiss', function() {
						$.post(ajaxurl, {
							action: 'courier_notices_dismiss_migration_notice',
							nonce: '<?php echo esc_attr( wp_create_nonce( 'courier_notices_dismiss_migration' ) ); ?>'
						});
					});
				});
			</script>
		</div>
		<?php
	}

	/**
	 * Handle AJAX request to dismiss migration notice
	 *
	 * @since 1.8.0
	 */
	public function dismiss_migration_notice() {
		// Verify nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'courier_notices_dismiss_migration' ) ) {
			wp_die( 'Invalid nonce' );
		}

		// Check permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Insufficient permissions' );
		}

		// Delete the transient.
		delete_transient( 'courier_notices_action_scheduler_migrated' );

		wp_die( 'success' );
	}

	/**
	 * Migrate from WP Cron to Action Scheduler
	 *
	 * @since 1.8.0
	 */
	private function migrate_to_action_scheduler() {
		// Clear existing WP Cron jobs.
		wp_clear_scheduled_hook( 'courier_notices_purge' );
		wp_clear_scheduled_hook( 'courier_notices_expire' );
		wp_clear_scheduled_hook( 'courier_purge' );
		wp_clear_scheduled_hook( 'courier_expire' );

		// Mark that we should use Action Scheduler.
		$plugin_options                         = get_option( 'courier_notices_options', [] );
		$plugin_options['use_action_scheduler'] = true;
		update_option( 'courier_notices_options', $plugin_options );

		// If Action Scheduler is available, schedule existing notices.
		if ( function_exists( 'as_schedule_single_action' ) ) {
			$this->schedule_existing_notices_with_action_scheduler();
		}

		// Set a transient to show admin notice about the migration.
		set_transient( 'courier_notices_action_scheduler_migrated', true, DAY_IN_SECONDS );
	}

	/**
	 * Schedule existing notices with Action Scheduler
	 *
	 * @since 1.8.0
	 */
	private function schedule_existing_notices_with_action_scheduler() {
		$args = [
			'post_type'      => 'courier_notice',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'post_status'    => [ 'publish', 'private' ],
			'meta_query'     => [
				[
					'key'     => '_courier_expiration',
					'value'   => time(),
					'compare' => '>',
					'type'    => 'NUMERIC',
				],
			],
		];

		$notices_query = new \WP_Query( $args );

		if ( $notices_query->have_posts() ) {
			foreach ( $notices_query->posts as $post_id ) {
				$expiration_timestamp = get_post_meta( $post_id, '_courier_expiration', true );

				if ( ! empty( $expiration_timestamp ) && $expiration_timestamp > time() ) {
					as_schedule_single_action(
						$expiration_timestamp,
						'courier_notices_expire_notice',
						[ $post_id ],
						'courier_notices'
					);
				}
			}
		}

		// Schedule recurring purge action.
		if ( ! as_next_scheduled_action( 'courier_notices_purge_notices', [], 'courier_notices' ) ) {
			as_schedule_recurring_action(
				time(),
				DAY_IN_SECONDS,
				'courier_notices_purge_notices',
				[],
				'courier_notices'
			);
		}

		// Schedule recurring bulk expire action as fallback.
		if ( ! as_next_scheduled_action( 'courier_notices_bulk_expire', [], 'courier_notices' ) ) {
			as_schedule_recurring_action(
				time(),
				HOUR_IN_SECONDS,
				'courier_notices_bulk_expire',
				[],
				'courier_notices'
			);
		}
	}
}
