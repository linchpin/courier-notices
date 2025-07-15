<?php
/**
 * WP-CLI Integration Controller
 *
 * @package CourierNotices\Controller\Integrations
 * @since 1.8.0
 */

namespace CourierNotices\Controller\Integrations;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP_CLI Class
 *
 * Provides WP-CLI commands for managing Courier Notices.
 */
class WP_CLI {

	/**
	 * Register actions and filters
	 *
	 * @since 1.8.0
	 */
	public function register_actions() {
		// Only register CLI commands if WP-CLI is available.
		if ( ! defined( 'WP_CLI' ) || ! \WP_CLI ) {
			return;
		}

		\WP_CLI::add_command( 'courier-notices', $this );
	}

	/**
	 * Create a new Courier notice with optional expiration
	 *
	 * ## OPTIONS
	 *
	 * <title>
	 * : The notice title
	 *
	 * <content>
	 * : The notice content
	 *
	 * [--expires=<minutes>]
	 * : Minutes from now when the notice should expire (default: no expiration)
	 *
	 * [--type=<type>]
	 * : Notice type (default: Info)
	 *
	 * [--style=<style>]
	 * : Notice style (default: Informational)
	 *
	 * [--placement=<placement>]
	 * : Notice placement (default: header)
	 *
	 * [--global]
	 * : Make this a global notice (default: false)
	 *
	 * [--dismissible]
	 * : Make the notice dismissible (default: true)
	 *
	 * [--user-id=<user-id>]
	 * : User ID for user-specific notice (default: 0 for global)
	 *
	 * ## EXAMPLES
	 *
	 *     # Create a simple notice
	 *     wp courier-notices create "Welcome Message" "Welcome to our site!"
	 *
	 *     # Create a notice that expires in 5 minutes
	 *     wp courier-notices create "Temporary Notice" "This will expire soon" --expires=5
	 *
	 *     # Create a global notice with custom type
	 *     wp courier-notices create "Important Update" "Please read this" --global --type="Warning" --expires=30
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function create( $args, $assoc_args ) {
		// Check if Courier Notices is active.
		if ( ! function_exists( 'courier_notices_add_notice' ) ) {
			\WP_CLI::error( 'Courier Notices plugin is not active.' );
		}

		// Parse arguments.
		$title           = $args[0];
		$content         = $args[1];
		$expires_minutes = isset( $assoc_args['expires'] ) ? intval( $assoc_args['expires'] ) : 0;
		$type            = isset( $assoc_args['type'] ) ? sanitize_text_field( $assoc_args['type'] ) : 'info';
		$style           = isset( $assoc_args['style'] ) ? sanitize_text_field( $assoc_args['style'] ) : 'informational';
		$placement       = isset( $assoc_args['placement'] ) ? sanitize_text_field( $assoc_args['placement'] ) : 'header';
		$global          = isset( $assoc_args['global'] ) ? true : true; // Default to global notices
		$dismissible     = ! isset( $assoc_args['dismissible'] ) || $assoc_args['dismissible'];
		$user_id         = isset( $assoc_args['user-id'] ) ? intval( $assoc_args['user-id'] ) : 0;

		// Validate inputs.
		if ( empty( $title ) ) {
			\WP_CLI::error( 'Notice title is required.' );
		}

		if ( empty( $content ) ) {
			\WP_CLI::error( 'Notice content is required.' );
		}

		if ( $expires_minutes < 0 ) {
			\WP_CLI::error( 'Expiration time must be 0 or greater.' );
		}

		// Create the notice.
		$notice_id = courier_notices_add_notice(
			array(
				'post_title'   => $title,
				'post_content' => $content,
			),
			array( $type ),
			$global,
			$dismissible,
			$user_id,
			$style,
			array( $placement )
		);

		if ( ! $notice_id ) {
			\WP_CLI::error( 'Failed to create notice.' );
		}

		// Verify the notice was created with correct taxonomies.
		$style_terms     = get_the_terms( $notice_id, 'courier_style' );
		$type_terms      = get_the_terms( $notice_id, 'courier_type' );
		$placement_terms = get_the_terms( $notice_id, 'courier_placement' );
		$scope_terms     = get_the_terms( $notice_id, 'courier_scope' );

		\WP_CLI::log( sprintf( 'Notice created with ID: %d', $notice_id ) );
		\WP_CLI::log( sprintf( 'Style terms: %s', $style_terms ? implode( ', ', wp_list_pluck( $style_terms, 'slug' ) ) : 'none' ) );
		\WP_CLI::log( sprintf( 'Type terms: %s', $type_terms ? implode( ', ', wp_list_pluck( $type_terms, 'slug' ) ) : 'none' ) );
		\WP_CLI::log( sprintf( 'Placement terms: %s', $placement_terms ? implode( ', ', wp_list_pluck( $placement_terms, 'slug' ) ) : 'none' ) );
		\WP_CLI::log( sprintf( 'Scope terms: %s', $scope_terms ? implode( ', ', wp_list_pluck( $scope_terms, 'slug' ) ) : 'none' ) );

		// Set expiration if specified.
		if ( $expires_minutes > 0 ) {
			$expiration_time = time() + ( $expires_minutes * 60 );
			update_post_meta( $notice_id, '_courier_expiration', $expiration_time );

			// Schedule expiration via Action Scheduler if available.
			if ( class_exists( 'ActionScheduler' ) ) {
				$action_scheduler = new \CourierNotices\Controller\Action_Scheduler();
				$action_scheduler->schedule_notice_expiration( $notice_id, $expiration_time );
			}
		}

		// Clear cache to ensure the notice appears on frontend immediately.
		if ( function_exists( 'courier_notices_clear_cache' ) ) {
			courier_notices_clear_cache();
		}

		// Display success message.
		$message = sprintf( 'Successfully created notice ID %d: "%s"', $notice_id, $title );

		if ( $expires_minutes > 0 ) {
			$message .= sprintf( ' (expires in %d minutes)', $expires_minutes );
		}

		\WP_CLI::success( $message );

		// Show additional details.
		$details = array(
			'ID'          => $notice_id,
			'Title'       => $title,
			'Type'        => $type,
			'Style'       => $style,
			'Placement'   => $placement,
			'Global'      => $global ? 'Yes' : 'No',
			'Dismissible' => $dismissible ? 'Yes' : 'No',
		);

		if ( $user_id > 0 ) {
			$details['User ID'] = $user_id;
		}

		if ( $expires_minutes > 0 ) {
			$details['Expires'] = gmdate( 'Y-m-d H:i:s', $expiration_time ) . ' UTC';
		}

		\WP_CLI\Utils\format_items( 'table', array( $details ), array_keys( $details ) );
	}

	/**
	 * Expire a notice by ID
	 *
	 * ## OPTIONS
	 *
	 * <notice-id>
	 * : The ID of the notice to expire
	 *
	 * [--force]
	 * : Force expiration even if the notice is already expired
	 *
	 * ## EXAMPLES
	 *
	 *     # Expire a notice
	 *     wp courier-notices expire 123
	 *
	 *     # Force expire a notice
	 *     wp courier-notices expire 123 --force
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function expire( $args, $assoc_args ) {
		$notice_id = intval( $args[0] );
		$force     = isset( $assoc_args['force'] );

		// Validate notice ID.
		if ( $notice_id <= 0 ) {
			\WP_CLI::error( 'Invalid notice ID.' );
		}

		// Check if notice exists.
		$notice = get_post( $notice_id );
		if ( ! $notice || 'courier_notice' !== $notice->post_type ) {
			\WP_CLI::error( sprintf( 'Notice with ID %d does not exist or is not a Courier notice.', $notice_id ) );
		}

		// Check current status.
		$current_status = $notice->post_status;
		if ( 'courier_expired' === $current_status && ! $force ) {
			\WP_CLI::warning( sprintf( 'Notice ID %d is already expired. Use --force to expire it again.', $notice_id ) );
			return;
		}

		// Expire the notice.
		$result = wp_update_post(
			array(
				'ID'          => $notice_id,
				'post_status' => 'courier_expired',
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			\WP_CLI::error( sprintf( 'Failed to expire notice: %s', $result->get_error_message() ) );
		}

		// Clear any scheduled Action Scheduler actions for this notice.
		if ( class_exists( 'ActionScheduler' ) ) {
			$action_scheduler = new \CourierNotices\Controller\Action_Scheduler();
			$action_scheduler->unschedule_notice_expiration( $notice_id );
		}

		// Clear cache to ensure the notice is immediately reflected on frontend.
		courier_notices_clear_cache();

		\WP_CLI::success( sprintf( 'Successfully expired notice ID %d: "%s"', $notice_id, $notice->post_title ) );
	}

	/**
	 * List Courier notices
	 *
	 * ## OPTIONS
	 *
	 * [--status=<status>]
	 * : Filter by status (publish, courier_expired, draft, etc.)
	 *
	 * [--type=<type>]
	 * : Filter by notice type
	 *
	 * [--placement=<placement>]
	 * : Filter by placement
	 *
	 * [--expired]
	 * : Show only expired notices
	 *
	 * [--active]
	 * : Show only active (non-expired) notices
	 *
	 * [--format=<format>]
	 * : Output format (table, csv, json, count)
	 *
	 * [--fields=<fields>]
	 * : Comma-separated list of fields to display
	 *
	 * ## EXAMPLES
	 *
	 *     # List all notices
	 *     wp courier-notices list
	 *
	 *     # List only expired notices
	 *     wp courier-notices list --expired
	 *
	 *     # List notices in JSON format
	 *     wp courier-notices list --format=json
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function list( $args, $assoc_args ) {
		// Build query arguments.
		$query_args = array(
			'post_type'      => 'courier_notice',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		// Handle status filters.
		if ( isset( $assoc_args['status'] ) ) {
			$query_args['post_status'] = $assoc_args['status'];
		} elseif ( isset( $assoc_args['expired'] ) ) {
			$query_args['post_status'] = 'courier_expired';
		} elseif ( isset( $assoc_args['active'] ) ) {
			$query_args['post_status'] = 'publish';
		}

		// Handle taxonomy filters.
		$tax_query = array();

		if ( isset( $assoc_args['type'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'courier_type',
				'field'    => 'slug',
				'terms'    => $assoc_args['type'],
			);
		}

		if ( isset( $assoc_args['placement'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'courier_placement',
				'field'    => 'slug',
				'terms'    => $assoc_args['placement'],
			);
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query;
		}

		// Get notices.
		$notices = get_posts( $query_args );

		if ( empty( $notices ) ) {
			\WP_CLI::warning( 'No notices found matching the criteria.' );
			return;
		}

		// Prepare data for display.
		$items = array();
		foreach ( $notices as $notice ) {
			$expiration      = get_post_meta( $notice->ID, '_courier_expiration', true );
			$type_terms      = get_the_terms( $notice->ID, 'courier_type' );
			$placement_terms = get_the_terms( $notice->ID, 'courier_placement' );

			$item = array(
				'ID'        => $notice->ID,
				'Title'     => $notice->post_title,
				'Status'    => $notice->post_status,
				'Type'      => $type_terms ? $type_terms[0]->name : '',
				'Placement' => $placement_terms ? $placement_terms[0]->name : '',
				'Created'   => gmdate( 'Y-m-d H:i:s', strtotime( $notice->post_date ) ),
				'Expires'   => $expiration ? gmdate( 'Y-m-d H:i:s', $expiration ) : 'Never',
			);

			$items[] = $item;
		}

		// Display results.
		$format = isset( $assoc_args['format'] ) ? $assoc_args['format'] : 'table';
		$fields = isset( $assoc_args['fields'] ) ? explode( ',', $assoc_args['fields'] ) : array_keys( $items[0] );

		\WP_CLI\Utils\format_items( $format, $items, $fields );
	}

	/**
	 * Get information about a specific notice
	 *
	 * ## OPTIONS
	 *
	 * <notice-id>
	 * : The ID of the notice to get information about
	 *
	 * [--format=<format>]
	 * : Output format (table, json)
	 *
	 * ## EXAMPLES
	 *
	 *     # Get notice information
	 *     wp courier-notices get 123
	 *
	 *     # Get notice information in JSON format
	 *     wp courier-notices get 123 --format=json
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function get( $args, $assoc_args ) {
		$notice_id = intval( $args[0] );

		// Validate notice ID.
		if ( $notice_id <= 0 ) {
			\WP_CLI::error( 'Invalid notice ID.' );
		}

		// Get notice.
		$notice = get_post( $notice_id );
		if ( ! $notice || 'courier_notice' !== $notice->post_type ) {
			\WP_CLI::error( sprintf( 'Notice with ID %d does not exist or is not a Courier notice.', $notice_id ) );
		}

		// Get additional data.
		$expiration      = get_post_meta( $notice_id, '_courier_expiration', true );
		$dismissible     = get_post_meta( $notice_id, '_courier_dismissible', true );
		$type_terms      = get_the_terms( $notice_id, 'courier_type' );
		$style_terms     = get_the_terms( $notice_id, 'courier_style' );
		$placement_terms = get_the_terms( $notice_id, 'courier_placement' );
		$scope_terms     = get_the_terms( $notice_id, 'courier_scope' );

		$data = array(
			'ID'          => $notice_id,
			'Title'       => $notice->post_title,
			'Content'     => wp_strip_all_tags( $notice->post_content ),
			'Status'      => $notice->post_status,
			'Type'        => $type_terms ? $type_terms[0]->name : '',
			'Style'       => $style_terms ? $style_terms[0]->name : '',
			'Placement'   => $placement_terms ? $placement_terms[0]->name : '',
			'Scope'       => $scope_terms ? $scope_terms[0]->name : '',
			'Dismissible' => $dismissible ? 'Yes' : 'No',
			'Created'     => gmdate( 'Y-m-d H:i:s', strtotime( $notice->post_date ) ),
			'Modified'    => gmdate( 'Y-m-d H:i:s', strtotime( $notice->post_modified ) ),
			'Expires'     => $expiration ? gmdate( 'Y-m-d H:i:s', $expiration ) : 'Never',
		);

		$format = isset( $assoc_args['format'] ) ? $assoc_args['format'] : 'table';
		\WP_CLI\Utils\format_items( $format, array( $data ), array_keys( $data ) );
	}
}
