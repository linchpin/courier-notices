<?php
/**
 * Addons and other fun things
 *
 * @since      1.0
 * @package    CourierNotices
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

?>

<div id="whats-new">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="whatsnew hero negative-bg">
				<div class="hero-text">
					<h1><?php esc_html_e( 'Now Available Courier Notices Pro', 'courier-notices' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg versioninfo">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php esc_html_e( 'Features', 'courier-notices' ); ?>
					</h2>
					<ul>
						<li><strong><?php esc_html_e( 'Tons of visibility options', 'courier-notices' ); ?></strong></li>
						<li><?php esc_html_e( 'Show courier notices per user, role, logged / logged out', 'courier-notices' ); ?></li>
						<li><?php esc_html_e( 'Show courier notices for pages, posts, custom post types', 'courier-notices' ); ?></li>
						<li><?php esc_html_e( 'Show courier notices for custom urls and more', 'courier-notices' ); ?></li>
						<li><?php esc_html_e( 'Integrations with Gravity Forms confirmation messages', 'courier-notices' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="wrapper">

			</div>
		</div>
	</div>
</div>
