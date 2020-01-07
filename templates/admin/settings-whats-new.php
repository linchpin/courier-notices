<?php
/**
 * Updates, Bug Fixes and News Template
 *
 * @since      1.0.0
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
					<h1><?php echo esc_html__( 'Young Cardinals Take Flight', 'courier' ); ?></h1>
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
				<h2 class="light-weight"><?php esc_html_e( 'More Quick Tips', 'courier' ); ?></h2>
				<div class="courier-row" data-equalizer="">
					<div class="courier-columns-6">
						<div class="grey-box" data-equalizer-watch="">
							<div class="about-box-icon">
								<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/img/icon-familiar.svg' ); ?>" alt=""/>
							</div>
							<div class="about-box-copy">
								<h4 class="no-margin"><?php esc_html_e( 'Familiar &amp; Easy to Use', 'courier' ); ?></h4>
								<p><?php esc_html_e( 'Create site notices using an interface similar to default pages and posts in WordPress.', 'courier' ); ?></p>
							</div>
						</div>
					</div>

					<div class="courier-columns-6">
						<div class="grey-box" data-equalizer-watch="">
							<div class="about-box-icon">
								<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/img/icon-users.svg' ); ?>" alt=""/>
							</div>
							<div class="about-box-copy">
								<h4 class="no-margin"><?php esc_html_e( 'Notices For Users/Visitors', 'courier' ); ?></h4>
								<ul class="courier-notice-types-list">
									<li><?php esc_html_e( 'Global Notices (For all users)', 'courier' ); ?></li>
									<li><?php esc_html_e( 'User Specific Notices', 'courier' ); ?></li>
									<li><?php esc_html_e( 'User Group Notices (Coming Soon)', 'courier' ); ?></li>
								</ul>
								<p><?php esc_html_e( 'More features coming soon.', 'courier' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
