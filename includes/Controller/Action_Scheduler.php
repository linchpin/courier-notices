<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Action Scheduler Controller.
 *
 * @package CourierNotices\Controller
 * @since 1.8.0
 */

namespace CourierNotices\Controller;

/**
 * Action Scheduler Class
 *
 * Handles scheduling of notice expiration and purging using Action Scheduler
 * instead of WP Cron for better reliability and scalability.
 *
 * @since 1.8.0
 */
class Action_Scheduler {

	/**
	 * Action hook for expiring individual notices
	 *
	 * @since 1.8.0
	 */
	const EXPIRE_NOTICE_ACTION = 'courier_notices_expire_notice';

	/**
	 * Action hook for purging old notices
	 *
	 * @since 1.8.0
	 */
	const PURGE_NOTICES_ACTION = 'courier_notices_purge_notices';

	/**
	 * Action hook for bulk expiration check (fallback)
	 *
	 * @since 1.8.0
	 */
	const BULK_EXPIRE_ACTION = 'courier_notices_bulk_expire';

	/**
	 * Action Scheduler Group
	 *
	 * @since 1.8.0
	 */
	const SCHEDULER_GROUP = 'courier_notices';

	/**
	 * Register actions and filters
	 *
	 * @since 1.8.0
	 */
	public function register_actions() {
		// Register action scheduler hooks
		add_action( self::EXPIRE_NOTICE_ACTION, [ $this, 'expire_notice' ] );
		add_action( self::PURGE_NOTICES_ACTION, [ $this, 'purge_notices' ] );
		add_action( self::BULK_EXPIRE_ACTION, [ $this, 'bulk_expire_notices' ] );

		// Hook into notice save to schedule expiration
		add_action( 'save_post', [ $this, 'schedule_notice_expiration' ], 10, 2 );

		// Initialize recurring actions
		add_action( 'init', [ $this, 'init_recurring_actions' ] );

		// Clean up actions on post deletion
		add_action( 'before_delete_post', [ $this, 'unschedule_notice_expiration' ] );
	}

	/**
	 * Initialize recurring actions
	 *
	 * @since 1.8.0
	 */
	public function init_recurring_actions() {
		// Schedule purge action if not already scheduled
		if ( ! as_next_scheduled_action( self::PURGE_NOTICES_ACTION, [], self::SCHEDULER_GROUP ) ) {
			as_schedule_recurring_action(
				time(),
				DAY_IN_SECONDS,
				self::PURGE_NOTICES_ACTION,
				[],
				self::SCHEDULER_GROUP
			);
		}

		// Schedule bulk expiration check as fallback (runs less frequently)
		if ( ! as_next_scheduled_action( self::BULK_EXPIRE_ACTION, [], self::SCHEDULER_GROUP ) ) {
			as_schedule_recurring_action(
				time(),
				HOUR_IN_SECONDS,
				self::BULK_EXPIRE_ACTION,
				[],
				self::SCHEDULER_GROUP
			);
		}
	}

	/**
	 * Schedule notice expiration when a notice is saved
	 *
	 * @since 1.8.0
	 *
	 * @param int     $post_id The post ID.
	 * @param WP_Post $post    The post object.
	 */
	public function schedule_notice_expiration( $post_id, $post ) {
		// Only process courier_notice post type
		if ( 'courier_notice' !== $post->post_type ) {
			return;
		}

		// Get the expiration date
		$expiration_timestamp = get_post_meta( $post_id, '_courier_expiration', true );

		// Unschedule any existing expiration action for this notice
		$this->unschedule_notice_expiration( $post_id );

		// Schedule new expiration if date is set and in the future
		if ( ! empty( $expiration_timestamp ) && $expiration_timestamp > time() ) {
			as_schedule_single_action(
				$expiration_timestamp,
				self::EXPIRE_NOTICE_ACTION,
				[ $post_id ],
				self::SCHEDULER_GROUP
			);
		}
	}

	/**
	 * Unschedule notice expiration
	 *
	 * @since 1.8.0
	 *
	 * @param int $post_id The post ID.
	 */
	public function unschedule_notice_expiration( $post_id ) {
		as_unschedule_all_actions( self::EXPIRE_NOTICE_ACTION, [ $post_id ], self::SCHEDULER_GROUP );
	}

	/**
	 * Expire a specific notice
	 *
	 * @since 1.8.0
	 *
	 * @param int $post_id The post ID to expire.
	 */
	public function expire_notice( $post_id ) {
		// Verify the post exists and is a courier notice
		$post = get_post( $post_id );
		if ( ! $post || 'courier_notice' !== $post->post_type ) {
			return;
		}

		// Check if notice should be expired
		$expiration_timestamp = get_post_meta( $post_id, '_courier_expiration', true );
		if ( empty( $expiration_timestamp ) || $expiration_timestamp > time() ) {
			return;
		}

		// Update post status to expired
		wp_update_post( [
			'ID'          => $post_id,
			'post_status' => 'courier_expired',
		] );

		// Clear related caches
		$this->clear_notice_caches();

		/**
		 * Fires after a notice has been expired via Action Scheduler
		 *
		 * @since 1.8.0
		 *
		 * @param int $post_id The expired notice ID.
		 */
		do_action( 'courier_notices_notice_expired', $post_id );
	}

	/**
	 * Purge old notices (older than 6 months)
	 *
	 * @since 1.8.0
	 */
	public function purge_notices() {
		$args = [
			'post_type'      => 'courier_notice',
			'posts_per_page' => 100,
			'fields'         => 'ids',
			'date_query'     => [
				[
					'column' => 'post_date',
					'before' => '6 months ago',
				],
			],
		];

		$notices_query = new \WP_Query( $args );

		if ( $notices_query->have_posts() ) {
			foreach ( $notices_query->posts as $post_id ) {
				wp_trash_post( $post_id );
				// Also unschedule any pending expiration for this notice
				$this->unschedule_notice_expiration( $post_id );
			}
		}

		$this->clear_notice_caches();

		/**
		 * Fires after notices have been purged via Action Scheduler
		 *
		 * @since 1.8.0
		 *
		 * @param array $purged_ids Array of purged notice IDs.
		 */
		do_action( 'courier_notices_notices_purged', $notices_query->posts );
	}

	/**
	 * Bulk expire notices (fallback method)
	 *
	 * This method serves as a fallback to catch any notices that might have
	 * missed their individual expiration scheduling.
	 *
	 * @since 1.8.0
	 */
	public function bulk_expire_notices() {
		$args = [
			'post_type'      => 'courier_notice',
			'posts_per_page' => 100,
			'fields'         => 'ids',
			'post_status'    => [ 'publish', 'private' ],
			'meta_query'     => [
				[
					'key'     => '_courier_expiration',
					'value'   => time(),
					'compare' => '<',
					'type'    => 'NUMERIC',
				],
			],
		];

		$notices_query = new \WP_Query( $args );

		if ( $notices_query->have_posts() ) {
			foreach ( $notices_query->posts as $post_id ) {
				wp_update_post( [
					'ID'          => $post_id,
					'post_status' => 'courier_expired',
				] );
			}
		}

		$this->clear_notice_caches();

		/**
		 * Fires after bulk expiration via Action Scheduler
		 *
		 * @since 1.8.0
		 *
		 * @param array $expired_ids Array of expired notice IDs.
		 */
		do_action( 'courier_notices_bulk_expired', $notices_query->posts );
	}

	/**
	 * Clear notice-related caches
	 *
	 * @since 1.8.0
	 */
	private function clear_notice_caches() {
		wp_cache_delete( 'courier-global-header-notices', 'courier-notices' );
		wp_cache_delete( 'courier-global-footer-notices', 'courier-notices' );
		wp_cache_delete( 'global-footer-notices', 'courier-notices' );
		wp_cache_delete( 'global-footer-dismissible-notices', 'courier-notices' );
	}

	/**
	 * Get scheduled actions for a notice
	 *
	 * @since 1.8.0
	 *
	 * @param int $post_id The post ID.
	 * @return array Array of scheduled actions.
	 */
	public function get_scheduled_actions_for_notice( $post_id ) {
		return as_get_scheduled_actions( [
			'hook'  => self::EXPIRE_NOTICE_ACTION,
			'args'  => [ $post_id ],
			'group' => self::SCHEDULER_GROUP,
		] );
	}

	/**
	 * Get all scheduled courier notice actions
	 *
	 * @since 1.8.0
	 *
	 * @return array Array of all scheduled actions.
	 */
	public function get_all_scheduled_actions() {
		return as_get_scheduled_actions( [
			'group' => self::SCHEDULER_GROUP,
		] );
	}

	/**
	 * Cancel all scheduled actions for the plugin
	 *
	 * @since 1.8.0
	 */
	public function cancel_all_scheduled_actions() {
		as_unschedule_all_actions( null, [], self::SCHEDULER_GROUP );
	}

	/**
	 * Reschedule all existing notices
	 *
	 * Used during migration from WP Cron to Action Scheduler
	 *
	 * @since 1.8.0
	 */
	public function reschedule_all_notices() {
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
				$post = get_post( $post_id );
				$this->schedule_notice_expiration( $post_id, $post );
			}
		}

		return count( $notices_query->posts );
	}
}