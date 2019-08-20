<?php
/**
 * Handle uninstalling the plugin
 *
 * @package Courier
 * @since 1.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN || dirname( WP_UNINSTALL_PLUGIN ) !== dirname( plugin_basename( __FILE__ ) ) ) {
	status_header( 404 );
	exit;
}

/**
 *
 * @param $post_type
 */
function courier_uninstall_delete_posts( $post_type ) {

	$post_types = array( 'courier_notice' );

	if ( ! in_array( $post_type, $post_types, true ) ) {
		return;
	}

	$total = 0;
	$args  = array(
		'post_type'      => $post_type,
		'post_status'    => array( 'any', 'auto-draft' ),
		'posts_per_page' => 100,
		'no_found_rows'  => true,
		'fields'         => 'ids',
		'offset'         => 0,
	);

	$courier_notices = new WP_Query( $args );

	if ( $courier_notices->have_posts() ) {

		while ( $courier_notices->have_posts() && $total < 10000 ) {

			$courier_notices->the_post();

			wp_delete_post( get_the_ID(), true );
			$total += $args['posts_per_page'];
		}

		$mesh_posts = new WP_Query( $args );
	}
}

/**
 * Delete all terms in in the given taxonomy
 *
 * @param $taxonomy
 */
function courier_uninstall_delete_terms( $taxonomy ) {

	$taxonomies = array(
		'courier_type',
		'courier_scope',
		'courier_placement',
		'courier_status',
	);

	if ( ! in_array( $taxonomy, $taxonomies, true ) ) { // Only allow our taxonomies to be removed using our method
		return;
	}

	if ( ! taxonomy_exists( $taxonomy ) ) { // Make sure the taxonomy actually exists.
		return;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'fields'     => 'ids',
			'hide_empty' => false,
		)
	);

	foreach ( $terms as $value ) {
		wp_delete_term( $value, $taxonomy );
	}
}

/**
 * If our mesh uninstall setting is enabled clear everything out on uninstall/delete of the plugin
 */
$courier_settings = get_option( 'courier_options' );

if ( ! empty( $courier_settings['uninstall'] ) ) {

	// Delete all Courier Notice related custom post types
	courier_uninstall_delete_posts( 'courier_notice' );  // Delete all courier notices

	// Delete all Courier Notice taxonomy terms
	courier_uninstall_delete_terms( 'courier_type' );
	courier_uninstall_delete_terms( 'courier_scope' );
	courier_uninstall_delete_terms( 'courier_placement' );
	courier_uninstall_delete_terms( 'courier_status' );

	// Delete all Courier Notice settings.
	delete_option( 'courier_options' );
	delete_option( 'courier_version' );
	delete_option( 'courier_activation' );
}
