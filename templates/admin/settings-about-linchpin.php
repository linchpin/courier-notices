<?php
/**
 * About Linchpin
 *
 * @since      1.0
 * @package    Courier
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
					<h1><?php esc_html_e( 'About the Team Behind Courier', 'courier-notices' ); ?></h1>
				</div>
			</div>

			<div class="wrapper courier-row">
				<div class="about-devs-container help-columns-12">
					<div class="no-margin">
						<p class="no-margin">
							<?php
							printf(
								wp_kses(
								// translators: %1$s Linchpin URL, %2$s Linchpin Profile URL, %3$s WordPress RI meetup URL.
									__(
										'<a href="%1$s">Linchpin</a> is a Digital Agency that specializes in WordPress.
										Committed to contributing to the WordPress community, Linchpin has released several
										<a href="%2$s">plugins</a> on WordPress.org. Linchpin is also an active member in
										their local WordPress communities, not only leading the <a href="%3$s">WordPress
										Rhode Island Meetup</a> group for several years, but also organizing, volunteering,
										speaking at or sponsoring local WordCamp conferences in the greater New England area.',
										'courier'
									),
									Utils::get_safe_markup()
								),
								esc_url( 'https://linchpin.com' ),
								esc_url( 'https://profiles.wordpress.org/linchpin_agency/' ),
								esc_url( 'https://www.meetup.com/WordPressRI/' )
							);
							?>
						</p>
						<p>
							<?php
							printf(
								wp_kses(
								// translators: %s Linchpin URL.
									__(
										' <a href="%s">Check out our website</a>, connect with us or come say hi at a local event.',
										'courier'
									),
									Utils::get_safe_markup()
								),
								esc_url( 'https://linchpin.com' )
							);
							?>
						</p>
						<p class="no-margin">
							<?php

							printf(
								wp_kses(
								// translators: %1$s Linchpin URL, %2$s Linchpin. %3$s Jetpack.pro url, %4$s Jetpack.pro label.
									__(
										'<a href="%1$s">%2$s</a> |',
										'courier'
									),
									Utils::get_safe_markup()
								),
								esc_url( 'https://linchpin.com' ),
								esc_html__( 'Linchpin', 'courier-notices' )
							);
							?>
							<?php
							printf(
								wp_kses(
								// translators: %1$s Linchpin Facebook URL, %2$s Facebook Label. %3$s Linchpin Twitter url, %4$s Twitter label.
									__(
										'<a href="%1$s">%2$s</a> | <a href="%3$s">%4$s</a> | ',
										'courier'
									),
									Utils::get_safe_markup()
								),
								esc_url( 'https://facebook.com/linchpinagency' ),
								esc_html__( 'Facebook', 'courier-notices' ),
								esc_url( 'https://twitter.com/linchpin_agency' ),
								esc_html__( 'Twitter', 'courier-notices' )
							);
							?>
							<?php
							printf(
								wp_kses(
								// translators: %1$s Linchpin Instagram URL, %2$s Instagram Label.
									__(
										'<a href="%1$s">%2$s</a>',
										'courier'
									),
									Utils::get_safe_markup()
								),
								esc_url( 'https://www.instagram.com/linchpinagency/' ),
								esc_html__( 'Instagram', 'courier-notices' )
							);
							?>
						</p>
						<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'assets/img/logo-linchpin.svg' ); ?>" width="200px"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

