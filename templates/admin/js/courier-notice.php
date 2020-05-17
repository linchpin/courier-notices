<script id="courier-notice-template" type="text/template">
	<?php
	use CourierNotices\Core\View;

	$notice_view = new View();
	$notice_view->assign( 'notice_id', $type->term_id );
	$notice_view->assign( 'icon', $icon_class );
	$notice_view->assign( 'post_class', 'post_class' );
	$notice_view->assign( 'post_class', implode( ' ', get_post_class( 'courier-notice courier_notice callout alert alert-box', $type->term_id ) ) );
	$notice_view->assign( 'dismissable', true );
	$notice_view->assign( 'post_content', 'post_content' );
	$notice_view->assign( 'type', $type );
	$notice_view->render( 'notice' );
	?>
</script>
