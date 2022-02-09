<?php
/**
 * Welcome template
 *
 * @package Courier
 * @since   1.0.0
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

// @todo this logic should be done before the template.
$classes = 'courier-welcome-panel';

$option = get_user_meta( get_current_user_id(), 'show_courier_welcome_panel', true );

// 1 = show, 0 = hide.
$show = ( '' === $option ) ? 1 : (int) $option;

if ( ! $show ) {
	$classes .= ' hidden';
}

?>
<div id="courier-notices-welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
	<?php wp_nonce_field( 'courier_notices_welcome_panel_nonce', 'courier_notices_welcome_panel', false ); ?>
	<a class="courier-notices-welcome-panel-close" href="<?php echo esc_url( admin_url( 'edit.php?post_type=courier_notice&courier_welcome=0' ) ); ?>" aria-label="<?php esc_attr_e( 'Dismiss the Courier welcome panel', 'mesh' ); ?>"><?php esc_html_e( 'Dismiss', 'courier-notices' ); ?></a>
	<div class="courier courier-welcome-panel-content">
		<h2><?php esc_html_e( 'Get ready to keep your users in the loop with Courier', 'courier-notices' ); ?></h2>
		<div class="courier-intro grid-x">
			<div class="cell shrink">
				<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'img/courier-logo-mark@2x.png' ); ?>" alt="<?php esc_attr_e( 'Courier Logo', 'courier-notices' ); ?>" width="100" />
			</div>
			<div class="cell auto padding-horizontal">
				<p class="about-description"><?php esc_html_e( 'Courier allows you to notify users about the happening(s) on your site', 'mesh' ); ?></p>
				<p class="about-description"><?php esc_html_e( "You can have notices displayed globally, per user, per page, by shortcode or even using it's numerous filters", 'courier-notices' ); ?></p>
				<p class="about-description"><?php esc_html_e( 'We&#8217;ve assembled some links to get you started:', 'courier-notices' ); ?></p>
			</div>
		</div>
		<div class="welcome-panel-column-container grid-x">
			<div class="cell shrink small-full">
				<h3><?php esc_html_e( 'Get Started', 'courier-notices' ); ?></h3>
				<a class="courier-add-notice button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=courier_notice' ) ); ?>">
					<?php esc_html_e( 'Create New Courier Notice', 'courier-notices' ); ?>
				</a>
			</div>
			<div class="cell auto small-full padding-horizontal">
				<h3><?php esc_html_e( 'More Actions', 'mesh' ); ?></h3>
				<ul>
					<li><?php printf( '<a href="%s" target="_blank"><span class="dashicons dashicons-welcome-learn-more"></span>' . esc_html__( 'Learn more about Courier', 'courier-notices' ) . '</a>', esc_url( __( 'https://github.com/linchpin/courier', 'courier-notices' ) ) ); ?></li>
					<li><?php printf( '<a href="%s" target="_blank"><span class="courier-icon icon-linchpin-logo-fill"></span>' . esc_html__( 'About Linchpin', 'mesh' ) . '</a>', esc_url( __( 'https://linchpin.com', 'courier-notices' ) ) ); ?></li>
					<li><?php printf( '<a href="%s" target="_blank"><span class="courier-icon icon-github-logo"></span>' . esc_html__( 'View Features Requests', 'mesh' ) . '</a>', esc_url( __( 'https://github.com/linchpin/courier/issues', 'courier-notices' ) ) ); ?></li>
				</ul>
			</div>
			<div class="cell shrink small-full align-bottom">
				<img src="<?php echo esc_url( COURIER_NOTICES_PLUGIN_URL . 'img/logo-linchpin.svg' ); ?>" alt="Linchpin linchpin.com" width="100" />
			</div>
		</div>
	</div>
</div>
