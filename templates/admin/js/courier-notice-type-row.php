<script id="courier-notice-type-template" type="text/template">
	<tr id="courier-notice-type-new">
		<th></th>
		<td></td>
		<td>
			<label for="courier-notice-type-new-title"><?php esc_html_e( 'Notice Type Title', 'courier' ); ?></label>
			<input type="text" id="courier-notice-type-new-title" name="courier_notice_type_new_title">
		</td>
		<td>
			<label for="courier-notice-type-new-css-class"><?php esc_html_e( 'Notice Type CSS Class', 'courier' ); ?></label>
			<input type="text" id="courier-notice-type-new-css-class" name="courier_notice_type_new_css_class">
		</td>
		<td>
			<input type="text" id="courier-notice-type-new-color" name="courier_notice_type_new_color" class="courier-type-color" value="<?php echo esc_attr( $notice_color ); ?>">
		</td>
		<td>
			<input type="text" id="courier-notice-type-new-text-color" name="courier_notice_type_new_text_color" class="courier-type-color" value="<?php echo esc_attr( $text_color ); ?>">
		</td>
		<td class="editing-buttons-container">
			<button class="button button-editing button-primary save-button" aria-label="<?php esc_html_e( 'Save', 'courier' ); ?>">
				<span class="dashicons dashicons-yes"></span>
			</button>
			<button class="button button-editing button-secondary close-button" aria-label="<?php esc_html_e( 'Cancel', 'courier' ); ?>">
				<span class="dashicons dashicons-no"></span>
			</button>
		</td>
	</tr>
</script>
<script id="courier-notice-type-edit-template" type="text/template">
	<tr id="courier-notice-type-edit" class="courier-notice-editing" data-term-id="{notice_type_id}">
		<th></th>
		<td></td>
		<td>
			<label for="courier-notice-type-edit-title"><?php esc_html_e( 'Notice Type Title', 'courier' ); ?></label>
			<input type="text" id="courier-notice-type-edit-title" name="courier_notice_type_edit_title" value="{notice_type_title}">
		</td>
		<td>
			<label for="courier-notice-type-edit-css-class"><?php esc_html_e( 'Notice Type CSS Class', 'courier' ); ?></label>
			<input type="text" id="courier-notice-type-edit-css-class" name="courier_notice_type_edit_css_class" value="{notice_type_css_class}">
		</td>
		<td>
			<input type="text" id="courier-notice-type-edit-color" name="courier_notice_type_edit_color" class="courier-type-color" value="{notice_type_color}">
		</td>
		<td>
			<input type="text" id="courier-notice-type-edit-text-color" name="courier_notice_type_edit_text_color" class="courier-type-color" value="{notice_type_text_color}">
		</td>
		<td class="editing-buttons-container">
			<button class="button button-editing button-primary save-button courier-help" title="<?php esc_html_e( 'Save', 'courier' ); ?>" aria-label="<?php esc_html_e( 'Save', 'courier' ); ?>">
				<span class="dashicons dashicons-yes"></span>
			</button>
			<button class="button button-editing button-secondary close-button courier-help" title="<?php esc_html_e( 'Cancel', 'courier' ); ?>" aria-label="<?php esc_html_e( 'Cancel', 'courier' ); ?>">
				<span class="dashicons dashicons-no"></span>
			</button>
		</td>
	</tr>
</script>
