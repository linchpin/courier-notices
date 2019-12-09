<?php
// Color
$color = get_term_meta( $notice_id, '_courier_type_color', true );

if ( empty( $color ) ) {
	$color = '#cccccc';
}

// Notice Text Color
$text_color = get_term_meta( $notice_id, '_courier_type_text_color', true );

if ( empty( $text_color ) ) {
	$text_color = '#000000';
}

// Notice Background Color
$bg_color = get_term_meta( $notice_id, '_courier_type_bg_color', true );

if ( empty( $bg_color ) ) {
	$bg_color = '#dddddd';
}

// Notice Icon Color
$icon_color = get_term_meta( $notice_id, '_courier_type_icon_color', true );

if ( empty( $icon_color ) ) {
	$icon_color = '#ffffff';
}

?>
<style id="notice-preview-<?php echo esc_attr( $notice_id ); ?>">
	[data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>"] .courier-content-wrapper {
		background: <?php echo esc_attr( $bg_color ); ?>;
		color: <?php echo esc_attr( $text_color ); ?>;
	}
	[data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>"] .courier-icon {
		background: <?php echo esc_attr( $color ); ?>;;
	}

	[data-courier-notice-id="<?php echo esc_attr( $notice_id ); ?>"] .courier-icon:before {
		color: <?php echo esc_attr( $icon_color ); ?>;
	}
</style>
<?php
	use Courier\Core\View;

	$notice_view = new View();
	$notice_view->assign( 'notice_id', $notice_id );
	$notice_view->assign( 'icon', $icon );
	$notice_view->assign( 'post_class', $post_class );
	$notice_view->assign( 'dismissable', $dismissable );
	$notice_view->assign( 'post_content', $post_content );
	$notice_view->render( 'notice' );
?>

<?php

	$color_input = sprintf(
		'<span class="color-editor"><label class="screen-reader-text" for="courier_type_%2$s_color">%3$s</label><input type="text" name="courier_type_%2$s_color" id="courier_type_%2$s_color" class="courier-type-color courier-notice-type-color" value="%1$s" /></span>',
		esc_attr( $color ),
		esc_attr( $type->slug ),
		// translators: %1$s Title of the term.
		sprintf( esc_html__( '%1$s Accent Color', 'courier' ), $type->name )
	);

	$icon_color_input = sprintf(
		'<span class="color-editor"><label class="screen-reader-text" for="courier_type_%2$s_color">%3$s</label><input type="text" name="courier_type_%2$s_color" id="courier_type_%2$s_color" class="courier-type-color courier-notice-type-icon-color" value="%1$s" /></span>',
		esc_attr( $icon_color ),
		esc_attr( $type->slug ),
		// translators: %1$s Title of the term.
		sprintf( esc_html__( '%1$s Icon Color', 'courier' ), $type->name )
	);

	$text_input = sprintf(
		'<span class="color-editor"><label class="screen-reader-text" for="courier_type_%2$s_text_color">%3$s</label><input type="text" name="courier_type_%2$s_text_color" id="courier_type_%2$s_text_color" class="courier-type-color courier-notice-type-text-color" value="%1$s" /></span>',
		esc_attr( $text_color ),
		esc_attr( $type->slug ),
		// translators: %1$s Title of the term.
		sprintf( esc_html__( '%1$s Text Color', 'courier' ), $type->name )
	);

	$bg_color_input = sprintf(
		'<span class="color-editor"><label class="screen-reader-text" for="courier_type_%2$s_color">%3$s</label><input type="text" name="courier_type_%2$s_color" id="courier_type_%2$s_color" class="courier-type-color courier-notice-type-bg-color" value="%1$s" /></span>',
		esc_attr( $bg_color ),
		esc_attr( $type->slug ),
		// translators: %1$s Title of the term.
		sprintf( esc_html__( '%1$s Background Color', 'courier' ), $type->name )
	);
?>

<div class="notice-options hide">
	<div class="notice-option">
		<strong class="notice-option-title">Accent Color</strong><br />
		<input type="text" class="courier-notice-type-edit-color courier-type-color" name="courier_notice_type_edit_color" value="<?php echo esc_attr( $color ); ?>">
	</div>

	<div class="notice-option">
		<strong class="notice-option-title">Icon Color</strong><br />
		<input type="text" class="courier-notice-type-edit-icon-color courier-type-color" name="courier_notice_type_edit_icon_color" value="<?php echo esc_attr( $icon_color ); ?>"">
	</div>

	<div class="notice-option">
		<strong class="notice-option-title">Text Color</strong><br />
		<input type="text" class="courier-notice-type-edit-text-color courier-type-color" name="courier_notice_type_edit_text_color" value="<?php echo esc_attr( $text_color ); ?>">
	</div>

	<div class="notice-option">
		<strong class="notice-option-title">Background Color</strong><br />
		<input type="text" class="courier-notice-type-edit-bg-color courier-type-color" name="courier_notice_type_edit_bg_color" value="<?php echo esc_attr( $bg_color ); ?>">
	</div>
</div>
