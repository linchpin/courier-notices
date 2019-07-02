<?php
/**
 * Welcome / About
 *
 * @since      1.0.0
 * @package    Courier
 * @subpackage Admin
 */

$safe_content = array(
	'a'    => array(
		'href'  => array(),
		'class' => array(),
	),
	'span' => array(
		'class' => array(),
	),
);

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
					<h2 class="color-darkpurple light-weight">

						<?php
							// translators: %1$s Getting Started, %2$s Using Courier.
							printf( wp_kses( __( '<span class="bold">%1$s</span> %2$s', 'courier' ), $safe_content ),
								esc_html( 'Getting Started:' ),
								esc_html( 'Using Courier to notify your users of anything you want!' )
							);
						?>
					</h2>
				</div>
			</div>

			<div class="wrapper help-row table">
				<div class="help-columns-6 table-cell">
					<h3><?php esc_html_e( 'A Quick 2 Minute Primer', 'courier' ); ?></h3>
					<p class="steps"><strong><?php esc_html_e( 'Enable:', 'courier' ); ?></strong>
						<?php
							// translators: %1$s Settings Tab, %2$s Settings Label.
							printf( wp_kses( __( 'By default Courier is only enabled on Pages. If you you would like to enable Courier for other post types (including blog posts) head over to the <a href="%1$s">%2$s</a>.', 'courier' ), $safe_content ),
								esc_url( admin_url( '/options-general.php?page=help&tab=settings' ) ),
								esc_html__( 'settings', 'courier' )
							);
						?>
					</p>
					<p><?php esc_html_e( 'Visit an existing page, post, custom post type or create a new one where Courier has been enabled.', 'courier' ); ?></p>
					<p><?php esc_html_e( 'You will now see a new area below "The Editor".', 'courier' ); ?></p>

					<img src="<?php echo esc_url( LINCHPIN_HELP_PLUGIN_URL . 'assets/images/add-new-section2.png' ); ?>" alt="<?php esc_attr_e( 'Add New Courier Section', 'courier' ); ?>" height="40%">

					<h3 class="steps"><?php esc_html_e( 'Follow the Tool Tips', 'courier' ); ?></h3>
					<p><?php esc_html_e( 'The first time you create sections and blocks in Courier you will be presented with useful tool tips that will guide you along the way.', 'courier' ); ?></p>
				</div>

				<div class="help-columns-6 right table-cell">
					<img src="<?php echo esc_url( LINCHPIN_HELP_PLUGIN_URL . 'assets/images/help-admin-comp2.gif' ); ?>" alt="<?php esc_attr_e( 'Enable Courier', 'courier' ); ?>" width="90%"/>
				</div>
			</div>

			<div class="gray-bg negative-bg">
				<div class="wrapper">
					<h2 class="color-darkpurple light-weight"><?php esc_html_e( 'More Quick Tips', 'courier' ); ?></h2>
					<div class="grey-box-container help-row" data-equalizer="">
						<div class="help-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( LINCHPIN_HELP_PLUGIN_URL . 'assets/images/feature-easy-familiar-2.svg' ); ?>"/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Familiar &amp; Easy to Use', 'courier' ); ?></h4>
									<p><?php esc_html_e( 'Create content using an interface similar to default pages and posts in WordPress.', 'courier' ); ?></p>
								</div>
							</div>
						</div>

						<div class="help-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( LINCHPIN_HELP_PLUGIN_URL . 'assets/images/feature-visualize.svg' ); ?>"/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Lay Out Your Content', 'courier' ); ?></h4>
									<p><?php esc_html_e( 'Easily build rows and columns of content without writing a line of code or editing templates.', 'courier' ); ?></p>
								</div>
							</div>
						</div>

						<div class="help-columns-6">
							<div class="grey-box " data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( LINCHPIN_HELP_PLUGIN_URL . 'assets/images/feature-responsive.svg' ); ?>"/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Fully Responsive', 'courier' ); ?></h4>
									<p><?php esc_html_e( 'Rest easy knowing your content will display seamlessly across browsers and devices.', 'courier' ); ?></p>
								</div>
							</div>
						</div>

						<div class="help-columns-6">
							<div class="grey-box" data-equalizer-watch="">
								<div class="about-box-icon">
									<img src="<?php echo esc_url( LINCHPIN_HELP_PLUGIN_URL . 'assets/images/feature-plays-well.svg' ); ?>"/>
								</div>
								<div class="about-box-copy">
									<h4 class="no-margin"><?php esc_html_e( 'Plays Well with Others', 'courier' ); ?></h4>
									<p>
										<?php
											printf(
												// translators: %s Courier Knowlegebase URL.
												wp_kses( __( 'Continually updated with hooks and filters to extend functionality. For a full list, check out <a href="%s" target="_blank" rel="noopener">CourierPlugin.com</a>.', 'courier' ), $safe_content ),
												esc_url( 'https://meshplugin.com/knowledge-base' )
											);
										?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="wrapper help-row">
				<h2 class="color-darkpurple light-weight"><?php esc_html_e( 'About the Team Behind .help', 'courier' ); ?></h2>
				<div class="about-devs-container help-columns-12">
					<div class="no-margin">
						<p class="no-margin">
							<?php
							// translators: %1$s Linchpin URL, %2$s Linchpin Profile URL, %3$s WordPress RI meetup URL.
							printf( wp_kses( __( '<a href="%1$s">Linchpin</a> is a Digital Agency that specializes in WordPress. 
                                Committed to contributing to the WordPress community, Linchpin has released several 
                                <a href="%2$s">plugins</a> on WordPress.org. Linchpin is also an active member in 
                                their local WordPress communities, not only leading the <a href="%3$s">WordPress 
                                Rhode Island Meetup</a> group for several years, but also organizing, volunteering, 
                                speaking at or sponsoring local WordCamp conferences in the greater New England area.', 'courier' ), $safe_content ),
								esc_url( 'https://linchpin.com' ),
								esc_url( 'https://profiles.wordpress.org/linchpin_agency/' ),
								esc_url( 'https://www.meetup.com/WordPressRI/' )
							);
							?>
						</p>
						<p>
							<?php
								// translators: %s Linchpin URL.
								printf( wp_kses( __( 'Check out our <a href="%s">site</a>, connect with us or come say hi at a local event.', 'courier' ), $safe_content ), esc_url( 'https://linchpin.com' ) );
							?>
						</p>
						<p class="no-margin">
							<?php
								// translators: %1$s Linchpin URL, %2$s Linchpin. %3$s Jetpack.pro url, %4$s Jetpack.pro label.
								printf( wp_kses( __( '<a href="%1$s">%2$s</a> | <a href="%3$s">%4$s</a> | ', 'courier' ), $safe_content ),
									esc_url( 'https://linchpin.com' ),
									esc_html( 'Linchpin' ),
									esc_url( 'https://jetpack.pro/profile/linchpin/' ),
									esc_html( 'jetpack.pro' )
								);
							?>
							<?php
								// translators: %1$s Linchpin Facebook URL, %2$s Facebook Label. %3$s Linchpin Twitter url, %4$s Twitter label.
								printf( wp_kses( __( '<a href="%1$s">%2$s</a> | <a href="%3$s">%4$s</a> | ', 'courier' ), $safe_content ),
									esc_url( 'https://facebook.com/linchpinagency' ),
									esc_html( 'Facebook' ),
									esc_url( 'https://twitter.com/linchpin_agency' ),
									esc_html( 'Twitter' )
								);
							?>
							<?php
								// translators: %1$s Linchpin Insta URL, %2$s Insta Label.
								printf( wp_kses( __( '<a href="%1$s">%2$s</a>', 'courier' ), $safe_content ),
									esc_url( 'https://www.instagram.com/linchpinagency/' ),
									esc_html( 'Instagram' )
								);
								?>
							</p>
						<img src="<?php echo esc_url( LINCHPIN_HELP_PLUGIN_URL . 'assets/images/linchpin-logo-lockup-fill-gray.svg' ); ?>" width="200px"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

