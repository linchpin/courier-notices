<?php
/**
 * Changelog tab.
 *
 * Links out to the published changelog rather than rendering CHANGELOG.md in
 * the admin. Rendering it required shipping a full CommonMark parser as a
 * production dependency purely to format a static file we author ourselves -
 * see docs/2.0-MIGRATION-PLAN.md.
 *
 * @since      1.0
 * @package    CourierNotices
 * @subpackage Admin
 *
 * @var string $courier_version The currently installed plugin version.
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

$courier_version = isset( $courier_version ) ? $courier_version : '';

$courier_changelog_links = array(
	array(
		'url'         => 'https://github.com/linchpin/courier-notices/releases',
		'label'       => __( 'Release notes on GitHub', 'courier-notices' ),
		'description' => __( 'Every release, with the changes grouped by type.', 'courier-notices' ),
	),
	array(
		'url'         => 'https://github.com/linchpin/courier-notices/blob/main/CHANGELOG.md',
		'label'       => __( 'Full changelog', 'courier-notices' ),
		'description' => __( 'The complete history in one file.', 'courier-notices' ),
	),
);

/**
 * Filter the links shown on the changelog tab.
 *
 * @since 2.0.0
 *
 * @param array $courier_changelog_links Array of link arrays, each with url, label and description keys.
 */
$courier_changelog_links = apply_filters( 'courier_notices_changelog_links', $courier_changelog_links );
?>

<div id="whats-new">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="whatsnew hero negative-bg">
				<div class="hero-text">
					<h1><?php esc_html_e( 'Courier Changelog', 'courier-notices' ); ?></h1>
					<?php if ( $courier_version ) : ?>
						<p>
							<?php
							printf(
								/* translators: %s: the installed plugin version number. */
								esc_html__( 'You are running version %s.', 'courier-notices' ),
								esc_html( $courier_version )
							);
							?>
						</p>
					<?php endif; ?>
				</div>
			</div>
			<div class="wrapper">
				<ul class="courier-changelog-links">
					<?php foreach ( $courier_changelog_links as $courier_changelog_link ) : ?>
						<?php if ( empty( $courier_changelog_link['url'] ) || empty( $courier_changelog_link['label'] ) ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<li>
							<a href="<?php echo esc_url( $courier_changelog_link['url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo esc_html( $courier_changelog_link['label'] ); ?>
							</a>
							<?php if ( ! empty( $courier_changelog_link['description'] ) ) : ?>
								<span class="description"><?php echo esc_html( $courier_changelog_link['description'] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
