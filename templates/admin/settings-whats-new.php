<?php
/**
 * Updates, Bug Fixes and News Template
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

<div id="whats-new">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="whatsnew hero negative-bg">
				<div class="hero-text">
					<h1><?php echo esc_html__( 'Everything is new', 'courier' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg versioninfo">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php
						printf(
							'Courier Version %s <span class="green-pipe">|</span> Released %s',
							esc_html( get_option( 'courier_version' ) ),
							esc_html__( 'August 22nd, 2019', 'courier' )
						);
						?>
					</h2>
				</div>
			</div>
			<div class="wrapper">
				<p>It's new we don't have much to say</p>
			</div>
		</div>
	</div>
</div>
