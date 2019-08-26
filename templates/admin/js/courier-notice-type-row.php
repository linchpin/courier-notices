<script id="courier-notice-type-template" type="text/template">
	<tr id="courier-notice-type-new">
		<th></th>
		<td></td>
		<td>
			<label aria-hidden="true" class="hide-if-js" for="courier-notice-type-new-title"><?php esc_html_e( 'Notice Type Title', 'courier' ); ?></label>
			<input type="text" id="courier-notice-type-new-title" name="courier_notice_type_new_title">
		</td>
		<td>
			<label aria-hidden="true" class="hide-if-js" for="courier-notice-type-new-css-class"><?php esc_html_e( 'Notice Type CSS Class', 'courier' ); ?></label>
			<input type="text" id="courier-notice-type-new-css-class" name="courier_notice_type_new_css_class">
		</td>
		<td>
			<input type="text" id="courier-notice-type-new-color" name="courier_notice_type_new_color" class="courier-type-color" value="<?php echo esc_attr( $notice_color ); ?>">
		</td>
		<td>
			<input type="text" id="courier-notice-type-new-text-color" name="courier_notice_type_new_text_color" class="courier-type-color" value="<?php echo esc_attr( $text_color ); ?>">
		</td>
		<td>
			<button class="button button-primary save-button"><?php esc_html_e( 'Save', 'courier' ); ?></button>
			<button class="button button-secondary close-button" aria-label="<?php esc_html_e( 'Close', 'courier' ); ?>">
				<span aria-hidden="true">&times;</span>
			</button>
		</td>
	</tr>
</script>
