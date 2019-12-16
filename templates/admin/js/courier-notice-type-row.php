<script id="courier-notice-type-template" type="text/template">
	<tr id="courier-notice-type-new">
		<th></th>
		<td></td>
		<td>
			<div class="notice-options">
				<div class="notice-option">
					<label for="courier-notice-type-new-title"><strong class="notice-option-title"><?php esc_html_e( 'Title', 'courier-notices' ); ?></strong></label>
					<input type="text" id="courier-notice-type-new-title" name="courier_notice_type_new_title">
				</div>

				<div class="notice-option">
					<label for="courier-notice-type-new-css-class"><strong class="notice-option-title"><?php esc_html_e( 'Icon Class', 'courier-notices' ); ?></strong></label><br />
					<input type="text" id="courier-notice-type-new-css-class" name="courier_notice_type_new_css_class">
				</div>
			</div>
		</td>
		<td>
			<div class="notice-options">
				<div class="notice-option" data-notice-option-color="accent">
					<label for="courier-notice-type-new-color"><strong class="notice-option-title"><?php esc_html_e( 'Accent Color', 'courier-notices' ); ?></strong></label><br />
					<input type="text" id="courier-notice-type-new-color" name="courier_notice_type_new_color" class="courier-type-color" value="<?php echo esc_attr( $notice_color ); ?>">
				</div>

				<div class="notice-option" data-notice-option-color="icon">
					<label for="courier-notice-type-new-icon-color"><strong class="notice-option-title"><?php esc_html_e( 'Icon Color', 'courier-notices' ); ?></strong></label><br />
					<input type="text" id="courier-notice-type-new-icon-color" name="courier_notice_type_new_icon_color" class="courier-type-color" value="<?php echo esc_attr( $notice_icon_color ); ?>">
				</div>

				<div class="notice-option" data-notice-option-color="text">
					<label for="courier-notice-type-new-text-color"><strong class="notice-option-title"><?php esc_html_e( 'Text Color', 'courier-notices' ); ?></strong></label><br />
					<input type="text" id="courier-notice-type-new-text-color" name="courier_notice_type_new_text_color" class="courier-type-color" value="<?php echo esc_attr( $text_color ); ?>">
				</div>

				<div class="notice-option" data-notice-option-color="bg">
					<label for="courier-notice-type-new-bg-color"><strong class="notice-option-title"><?php esc_html_e( 'Background Color', 'courier-notices' ); ?></strong></label><br />
					<input type="text" id="courier-notice-type-new-bg-color" name="courier_notice_type_new_bg_color" class="courier-type-color" value="<?php echo esc_attr( $notice_bg_color ); ?>">
				</div>
			</div>
		</td>
		<td class="editing-buttons-container">
			<button class="button button-editing button-primary save-button" aria-label="<?php esc_html_e( 'Save', 'courier-notices' ); ?>">
				<span class="dashicons dashicons-yes"></span>
			</button>
			<button class="button button-editing button-secondary close-button" aria-label="<?php esc_html_e( 'Cancel', 'courier-notices' ); ?>">
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
			<label for="courier-notice-type-edit-title"><?php esc_html_e( 'Notice Type Title', 'courier-notices' ); ?></label>
			<input type="text" id="courier-notice-type-edit-title" name="courier_notice_type_edit_title" value="{notice_type_title}">
		</td>
		<td>
			<label for="courier-notice-type-edit-css-class"><?php esc_html_e( 'Notice Type CSS Class', 'courier-notices' ); ?></label>
			<input type="text" id="courier-notice-type-edit-css-class" name="courier_notice_type_edit_css_class" value="{notice_type_css_class}">
		</td>
		<td>
			<input type="text" id="courier-notice-type-edit-color" name="courier_notice_type_edit_color" class="courier-type-color" value="{notice_type_color}">
		</td>
		<td>
			<input type="text" id="courier-notice-type-edit-text-color" name="courier_notice_type_edit_text_color" class="courier-type-color" value="{notice_type_text_color}">
		</td>
		<td>
			<input type="text" id="courier-notice-type-edit-icon-color" name="courier_notice_type_edit_icon_color" class="courier-type-color" value="{notice_type_icon_color}">
		</td>
		<td>
			<input type="text" id="courier-notice-type-edit-bg-color" name="courier_notice_type_edit_bg_color" class="courier-type-color" value="{notice_type_bg_color}">
		</td>
		<td class="editing-buttons-container">
			<button class="button button-editing button-primary save-button courier-help" title="<?php esc_html_e( 'Save', 'courier-notices' ); ?>" aria-label="<?php esc_html_e( 'Save', 'courier-notices' ); ?>">
				<span class="dashicons dashicons-yes"></span>
			</button>
			<button class="button button-editing button-secondary close-button courier-help" title="<?php esc_html_e( 'Cancel', 'courier-notices' ); ?>" aria-label="<?php esc_html_e( 'Cancel', 'courier-notices' ); ?>">
				<span class="dashicons dashicons-no"></span>
			</button>
		</td>
	</tr>
</script>
<script id="courier-save-cancel-template" type="text/template">
	<button class="button button-editing button-primary save-button courier-help" title="<?php esc_html_e( 'Save', 'courier-notices' ); ?>" aria-label="<?php esc_html_e( 'Save', 'courier-notices' ); ?>">
		<span class="dashicons dashicons-yes"></span>
	</button>
	<button class="button button-editing button-secondary close-button courier-help" title="<?php esc_html_e( 'Cancel', 'courier-notices' ); ?>" aria-label="<?php esc_html_e( 'Cancel', 'courier-notices' ); ?>">
		<span class="dashicons dashicons-no"></span>
	</button>
</script>
