<?php
/**
 * Addons and other fun things
 *
 * @since      1.0
 * @package    Courier
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
					<h1><?php esc_html_e( 'Courier Change Log', 'courier' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg versioninfo">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php esc_html_e( 'See what\'s happened in our latest release', 'courier' ); ?>
					</h2>
				</div>
			</div>
			<div class="wrapper">

			</div>
		</div>
	</div>
</div>
