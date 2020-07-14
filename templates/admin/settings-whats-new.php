<?php
/**
 * Updates, Bug Fixes and News Template
 *
 * @since      1.0.0
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
					<h1><?php echo esc_html__( 'Young Cardinals Take Flight', 'courier-notices' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg versioninfo">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php
						printf(
							'Courier Version %s <span class="green-pipe">|</span> Released %s',
							esc_html( $courier_version ),
							esc_html( $courier_release_date )
						);
						?>
					</h2>
				</div>
			</div>
			<div class="wrapper">
				<h2 class="light-weight"><?php esc_html_e( 'Making Courier easier to use and more extensible', 'courier-notices' ); ?></h2>
				<div class="courier-row" data-equalizer="">
					<div class="courier-columns-12">
						<div class="grey-box" data-equalizer-watch="">
							<div class="whats-new-box-copy">
								<p><?php esc_html_e( 'This release is the culmination of 50+ commits for bug fixes and enhancements.', 'courier-notices' ); ?></p>
								<p><?php esc_html_e( 'For more specifics please see the change log.', 'courier-notices' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
