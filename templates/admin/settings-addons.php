<?php
/**
 * Addons and other fun things
 *
 * @since      1.0
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

<div id="whats-new">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="whatsnew hero negative-bg">
				<div class="hero-text">
					<h1><?php esc_html_e( 'Addons', 'courier' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg versioninfo">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php esc_html_e( 'More Add Ons Coming Soon', 'courier' ); ?>
					</h2>
				</div>
			</div>
			<div class="wrapper">
				<p><?php esc_html_e( 'Add Ons are a great way to extend the functionality of Courier', 'courier' ); ?></p>
				<p><?php esc_html_e( 'There are numerous ways to do this including our own premium library of plugins', 'courier' ); ?></p>
				<p><?php esc_html_e( 'Or take a look at our growing developer documentation', 'courier' ); ?></p>
			</div>
		</div>
	</div>
</div>
