<?php
/**
 * Welcome template
 *
 * @package Courier
 * @since   1.0.0
 */

$classes = 'welcome-panel';

$option = get_user_meta( get_current_user_id(), 'show_courier_welcome_panel', true );

// 1 = show, 0 = hide.
$show = ( '' === $option ) ? 1 : (int) $option;

if ( ! $show ) {
	$classes .= ' hidden';
}

?>
<div id="courier-notices-welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
	<?php wp_nonce_field( 'courier_welcome_nonce', 'courier-welcome-nonce', false ); ?>
	<a class="courier-notices-welcome-panel-close" href="<?php echo esc_url( admin_url( 'edit.php?post_type=courier_notice&courier_welcome=0' ) ); ?>" aria-label="<?php esc_attr_e( 'Dismiss the Mesh templates welcome panel', 'mesh' ); ?>"><?php esc_html_e( 'Dismiss', 'mesh' ); ?></a>
	<div class="courier welcome-panel-content">
		<h2><?php esc_html_e( 'Get ready to keep your users in the loop with Courier', 'courier' ); ?></h2>
		<p class="about-description"><?php esc_html_e( 'Courier allows you to notify users about the happening(s) on your site', 'mesh' ); ?></p>
		<p class="about-description"><?php esc_html_e( "You can have notices displayed globally, per user, per page, by shortcode or even using it's numerous filters", 'courier' ); ?></p>
		<p class="about-description"><?php esc_html_e( 'We&#8217;ve assembled some links to get you started:', 'courier' ); ?></p>
		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<h3><?php esc_html_e( 'Get Started', 'courier' ); ?></h3>
				<a class="courier-add-notice button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=courier_notice' ) ); ?>">
					<?php esc_html_e( 'Create New Courier Notice', 'courier' ); ?>
				</a>
			</div>
			<div class="welcome-panel-column">
				<h3><?php esc_html_e( 'More Actions', 'mesh' ); ?></h3>
				<ul>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more" target="_blank">' . esc_html__( 'Learn more about Courier', 'courier' ) . '</a>', esc_url( __( 'https://github.com/linchpin/courier', 'courier' ) ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-linchin icon-linchpin-logo" target="_blank">' . esc_html__( 'About Linchpin', 'mesh' ) . '</a>', esc_url( __( 'https://linchpin.com', 'courier' ) ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-github icon-github" target="_blank">' . esc_html__( 'View Features Requests', 'mesh' ) . '</a>', esc_url( __( 'https://github.com/linchpin/courier/issues', 'courier' ) ) ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
