<?php
/**
 * Welcome / About
 *
 * @since      1.0
 * @package    Courier
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

use \Courier\Helper\Utils as Utils;

?>
<div id="about-courier">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="about hero negative-bg">
				<div class="hero-text">
					<h1><?php esc_html_e( 'Thank you for installing Courier', 'courier' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php
						printf(
							wp_kses(
								// translators: %1$s Getting Started, %2$s Using Courier.
								__( '<span class="bold">%1$s</span> %2$s', 'courier' ),
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
					<h3><?php esc_html_e( 'A Quick 2 Minute Primer', 'courier' ); ?></h3>
					<p><?php esc_html_e( 'Courier allows you to notify your site visitors/users of different information and events on your site.', 'courier' ); ?></p>
					<p><?php esc_html_e( 'We built this plugin as a base notification system for a few other projects and decided to share it with the community.', 'courier' ); ?></p>
					<?php if ( ! Utils::is_wp_cron_disabled() ) : ?>
					<p>
						<strong><?php esc_html_e( 'Recommendation: If you are using the default WP Cron, we suggest utilizing an alternate cron so the timing of notice expiration is more accurate.', 'courier' ); ?></strong>
					</p>
					<p>
						<?php
						echo wp_kses(
							sprintf(
								// translators: %1$s WP Cron Documentation.
								__( 'You can read more about <a href="%1$s" target="_blank" rel="noopener">WP Cron Here</a>.', 'courier' ),
								esc_url( 'https://developer.wordpress.org/plugins/cron/' )
							),
							Utils::get_safe_markup()
						);
						?>
					</p>
					<?php endif; ?>
				</div>

				<div class="courier-columns-6 right table-cell">
					<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/images/help-admin-comp2.gif' ); ?>" alt="<?php esc_attr_e( 'Enable Courier', 'courier' ); ?>" width="90%"/>
				</div>
			</div>

			<div class="gray-bg negative-bg">
				<div class="wrapper">
					<h2 class="light-weight"><?php esc_html_e( 'Getting Started / Directions', 'courier' ); ?></h2>
					<div class="grey-box-container courier-row" data-equalizer="">
						<div class="courier-columns-12">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/img/icon-settings.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Settings', 'courier' ); ?></h4>
									<p><?php esc_html_e( 'Create site notices using an interface similar to default pages and posts in WordPress.', 'courier' ); ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="grey-box-container courier-row" data-equalizer="">
						<div class="courier-columns-12">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/img/icon-adding-editing.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Adding / Editing Notices', 'courier' ); ?></h4>
									<p><?php esc_html_e( 'Creating and Editing notices is similar to pages and posts. Where each notice has a title and content.', 'courier' ); ?></p>
									<h5 class="no-margin"><?php esc_html_e( 'More Settings', 'courier' ); ?></h5>
									<dl>
										<dt><?php esc_html_e( 'Notice Types', 'courier' ); ?></dt>
										<dd><?php esc_html_e( 'Notice Types are used to display, success, error, warning and informational notices to your users' ); ?></dd>
										<dd><?php esc_html_e( 'You can also create more notice types as needed.' ); ?></dd>
										<dt><?php esc_html_e( 'Notice Placement', 'courier' ); ?></dt>
										<dd>
											<ul>
												<li><?php esc_html_e( 'Header', 'courier' ); ?></li>
												<li><?php esc_html_e( 'Footer', 'courier' ); ?></li>
												<li><?php esc_html_e( 'Modal / Popup', 'courier' ); ?></li>
											</ul>
										</dd>
										<dt><?php esc_html_e( 'Notice Expriration', 'courier' ); ?></dt>
									</dl>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="gray-bg negative-bg">
				<div class="wrapper">
					<h2 class="light-weight"><?php esc_html_e( 'More Quick Tips', 'courier' ); ?></h2>
					<div class="grey-box-container courier-row" data-equalizer="">
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

						<div class="courier-columns-6">
							<div class="grey-box " data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/img/icon-types.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Create Your Own Notice Types ', 'courier' ); ?></h4>
									<p><?php esc_html_e( 'You can use the default Courier notice types or feel free to add your own.', 'courier' ); ?></p>
									<p><?php esc_html_e( 'Pick your own notice colors and icons, disable the styles completely to theme Courier yourself.', 'courier' ); ?></p>
								</div>
							</div>
						</div>

						<div class="courier-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/img/icon-plays-well.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Plays Well with Others', 'courier' ); ?></h4>
									<p>
										<?php
										printf(
											// translators: %s Courier github URL.
											wp_kses( __( 'Continually updated with hooks and filters to extend functionality. For a full list, check out <a href="%s" target="_blank" rel="noopener">Courier on github.com</a>.', 'courier' ), Utils::get_safe_markup() ),
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
									<img src="<?php echo esc_url( COURIER_PLUGIN_URL . 'assets/img/icon-add-on.svg' ); ?>" alt=""/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Addons', 'courier' ); ?></h4>
									<p>
										<?php
										printf(
											// translators: %s Courier github URL.
											wp_kses( __( 'If you\'re using gravity forms, you can even use courier as your gravity forms confirmation. Simply visit your forms confirmation area and you will see a new confirmation type of "Courier Notice"', 'courier' ), Utils::get_safe_markup() ),
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

