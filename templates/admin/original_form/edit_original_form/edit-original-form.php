<div id="guild_press_fields_edit_metabox">
			<table>
	        	<tbody>
	        		<tr>
	        			<th class="left">ラベル</th>
	        			<td><input type="text" name="add_label" value="<?php echo $this->wpfunc->esc_attr( $gp_data['edit_field'][1] ); ?>" /></td>
	        		</tr>

	        		<tr>
	        			<th class="left">オプション名</th>
	        			<td>
	        				<input type="hidden" name="add_option" value="<?php echo $this->wpfunc->esc_attr( $gp_data['edit_field'][2] ); ?>" />
					<?php echo $this->wpfunc->esc_html( $gp_data['edit_field'][2] ); ?>
	        			</td>
	        		</tr>

	        		<tr>
	        			<th class="left">表示</th>
	        			<td>
	        				<input type="checkbox" name="add_display" value="y" <?php if( $gp_data['edit_field'][4] === "y" ) : ?> checked <?php endif; ?> />
	        			</td>
	        		</tr>

	        		<tr>
	        			<th class="left">必須</th>
	        			<td>
	        				<input type="checkbox" name="add_required" value="y" <?php if( $gp_data['edit_field'][5] === "y" ) : ?> checked <?php endif; ?> />
	        			</td>
	        		</tr>

				<?php if( $gp_data['edit_field'][3] === "text" ) : ?>

					<input type="hidden" name="add_type" value="text" >

				<?php elseif( $gp_data['edit_field'][3] === "textarea" ) : ?>

					<input type="hidden" name="add_type" value="textarea" >

				<?php elseif( $gp_data['edit_field'][3] === "checkbox" ) : ?>
					<!-- <div id="wpmem_checkbox_info"> -->
					<tr>
						<th class="left">表示された時に最初からチェックする。</th>
						<td>
							<input type="checkbox" name="add_checked_default" value="y" <?php if( $gp_data['edit_field'][8] === "y" ) : ?> checked <?php endif; ?>  />
						</td>
					</tr>

					<tr>
						<th class="left">チェックされた時に保持する値。</th>
						<td>
							<input type="text" name="add_checked_value" value="<?php echo $this->wpfunc->esc_attr( $gp_data['edit_field'][7] ); ?>" class="small-text" />
						</td>
					</tr>
					<!-- </div> -->


							<input type="hidden" name="add_type" value="checkbox" >
				<?php endif; ?>
				<?php if( $gp_data['edit_field'][3] === "select" ) : ?>
					<!-- <div id="wpmem_dropdown_info"> -->
						<tr>
						<th class="left">ドロップダウンリストの中身</th>
						<td>
<textarea name="add_dropdown_value" rows="5" cols="40">
<?php echo $this->wpfunc->esc_html( $this->show_text( $gp_data['edit_field'] ) ); ?>
</textarea>
						</td>
					</tr>
				<!-- </div> -->


							<label>&nbsp;</label>
							<span class="description">オプションの記述方法（オプション名|値,）</span>
					</div>
					<input type="hidden" name="add_type" value="select" >
				<?php endif; ?>

				</tbody>

			<tfoot>
				<tr>
				<td class="pt20" colspan="2"><input type="submit" name="submit" id="add_field" class="button button-primary" value="設定を保存" /></td>
				</tr>
			</tfoot>

		</table>
					<input type="hidden" name="field_id" value="<?php echo $this->wpfunc->esc_attr($gp_data['field_id']); ?>" />
					<input type="hidden" name="option_page" value="<?php echo $gp_data['option_page_val']; ?>" />
	  				<input type="hidden" name="action" value="update" />
	        		<input type="hidden" name="admin_action" value="guildpresseditfield" />
				</div>