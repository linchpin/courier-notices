<?php
/**
 * Welcome / About
 *
 * @since      1.0
 * @package    CourierNotices
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

use CourierNotices\Helper\Utils as Utils;

?>
<div id="about-courier">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="about hero negative-bg">
				<div class="hero-text">
					<h1><?php esc_html_e( 'Thank you for installing Courier Notices', 'courier-notices' ); ?></h1>
				</div>
			</div>

			<div class="gray-bg negative-bg">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php
						printf(
							wp_kses(
								// translators: %1$s Getting Started, %2$s Using Courier.
								__( '<span class="bold">%1$s</span> %2$s', 'courier-notices' ),
								Utils::get_safe_markup()
							),
							esc_html( 'Getting Started:' ),
							esc_html( 'Using Courier Notices to provide your vistors/users with helpful information' )
						);
						?>
					</h2>
				</div>
			</div>

			<div class="wrapper courier-row table">
				<div class="courier-columns-6 table-cell">
					<h3><?php esc_html_e( 'A Quick 2 Minute Primer', 'courier-notices' ); ?></h3>
					<p><?php esc_html_e( 'Courier allows you to notify your site visitors/users of different information and events on your site.', 'courier-notices' ); ?></p>
					<p><?php esc_html_e( 'We built this plugin as a base notification system for a few other projects and decided to share it with the community.', 'courier-notices' ); ?></p>
					<?php if ( ! Utils::is_wp_cron_disabled() ) : ?>
					<p>
						<strong><?php esc_html_e( 'Recommendation: If you are using the default WP Cron, we suggest utilizing an alternate cron so the timing of notice expiration is more accurate.', 'courier-notices' ); ?></strong>
					</p>
					<p>
						<?php
						echo wp_kses(
							sprintf(
								// translators: %1$s WP Cron Documentation.
								__( 'You can read more about <a href="%1$s" target="_blank" rel="noopener">WP Cron Here</a>.', 'courier-notices' ),
								esc_url( 'https://developer.wordpress.org/plugins/cron/' )
							),
							Utils::get_safe_markup()
						);
						?>
					</p>
					<?php endif; ?>
				</div>

				<div class="courier-columns-6 right table-cell">
					<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'assets/images/help-admin-comp2.gif' ); ?>" alt="<?php esc_attr_e( 'Enable Courier', 'courier-notices' ); ?>" width="90%"/>
				</div>
			</div>

			<div class="gray-bg negative-bg">
				<div class="wrapper">
					<h2 class="light-weight"><?php esc_html_e( 'More Quick Tips', 'courier-notices' ); ?></h2>
					<div class="grey-box-container courier-row" data-equalizer="">
						<div class="courier-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'assets/img/icon-familiar.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Easy to Use', 'courier-notices' ); ?></h4>
									<p><?php esc_html_e( 'Create site notices using an interface similar to default pages and posts in WordPress.', 'courier-notices' ); ?></p>
								</div>
							</div>
						</div>

						<div class="courier-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'assets/img/icon-users.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Quickly get started', 'courier-notices' ); ?></h4>
									<ul class="courier-notice-types-list">
										<li><?php esc_html_e( 'Different notice types', 'courier-notices' ); ?></li>
										<li><?php esc_html_e( 'Easy to design/style', 'courier-notices' ); ?></li>
										<li><?php esc_html_e( 'Easy to extend', 'courier-notices' ); ?></li>
									</ul>
									<p><?php esc_html_e( 'More features coming soon.', 'courier-notices' ); ?></p>
								</div>
							</div>
						</div>

						<div class="courier-columns-6">
							<div class="grey-box " data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'assets/img/icon-types.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Create Your Own Notice Types ', 'courier-notices' ); ?></h4>
									<p><?php esc_html_e( 'You can use the default Courier notice types or feel free to add your own.', 'courier-notices' ); ?></p>
									<p><?php esc_html_e( 'Pick your own notice colors and icons, disable the styles completely to theme Courier yourself.', 'courier-notices' ); ?></p>
								</div>
							</div>
						</div>

						<div class="courier-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'assets/img/icon-plays-well.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Plays Well with Others', 'courier-notices' ); ?></h4>
									<p>
										<?php
										printf(
											// translators: %s Courier github URL.
											wp_kses( __( 'Continually updated with hooks and filters to extend functionality. For a full list, check out <a href="%s" target="_blank" rel="noopener">Courier on github.com</a>.', 'courier-notices' ), Utils::get_safe_markup() ),
											esc_url( 'https://github.com/linchpin/courier' )
										);
										?>
									</p>
								</div>
							</div>
						</div>

						<div class="courier-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'assets/img/icon-add-on.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Addons', 'courier-notices' ); ?></h4>
									<p>
										<?php
										printf(
											// translators: %s Courier github URL.
											wp_kses( __( 'If you\'re using gravity forms, you can even use courier as your gravity forms confirmation. Simply visit your forms confirmation area and you will see a new confirmation type of "Courier Notice"', 'courier-notices' ), Utils::get_safe_markup() ),
											esc_url( 'https://github.com/linchpin/courier' )
										);
										?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

