
	<div id="salon_field" class="postbox" >
	<div id="guild_press_fields_metabox">
	<h3 style="margin-left: 10px;">新規項目追加</h3>
		<div class="inside">
	        <table>
	        	<tbody>
	        		<tr>
	        			<th class="left">ラベル</th>
	        			<td><input type="text" name="add_label" value="<?php echo $this->wpfunc->esc_attr( $gp_data['add_label'] ); ?>" /></td>
	        		</tr>

	           	<tr>
	        			<th class="left">オプション名(半角英数字で入力して下さい。)</th>
	        			<td><input type="text" name="add_option" id="add_option" oninput="common_js.only_eisu('add_option')" value="<?php echo $this->wpfunc->esc_attr( $gp_data['add_option'] ); ?>" /></td>
	        		</tr>
	        	<tr>
	        			<th class="left">フィールド形式</th>
	        			<td><select name="add_type" id="guild_press_field_type_select">
	              <option value="text" >テキスト</option>
	              <option value="textarea" >テキストエリア</option>
	              <option value="checkbox" >チェックボックス</option>
	              <option value="select" >セレクトボックス</option>

	            </select></td>
	        		</tr>

	        		<tr>
	        			<th class="left">表示</th>
	        			<td><input type="checkbox" name="add_display" value="y" <?php echo $gp_data['add_display']; ?> /></td>
	        		</tr>

	        		<tr>
	        			<th class="left">必須</th>
	        			<td><input type="checkbox" name="add_required" value="y" <?php echo $gp_data['add_required']; ?>  /></td>
	        		</tr>

	          	<tr id="guild_press_checkbox_info" class="display_none">
	        			<th class="left">表示された時に最初からチェックする。</th>
	        			<td>
	        				<input type="checkbox" name="add_checked_default" id="" value="y"  <?php echo $gp_data['add_checked_default']; ?> />
	        			</td>
	        		</tr>

	        		<tr id="guild_press_checkbox_info2" class="display_none">
	        			<th class="left">チェックされた時に保持する値。</th>
	        			<td><input type="text" name="add_checked_value" value="<?php echo $this->wpfunc->esc_attr( $gp_data['add_checked_value'] ); ?>" class="small-text" /></td>
	        		</tr>

	          	<tr id="guild_press_dropdown_info" class="display_none">
	        			<th class="left">ドロップダウンリストの中身</th>

	        			<td>
<textarea name="add_dropdown_value" rows="5" cols="40">
<?php echo $this->show_text(); ?>
</textarea>
	        			</td>
	        			<label>&nbsp;</label>
	              <span class="description">オプションの記述方法（オプション名|値,）</span>

	              <label>&nbsp;</label>
	        		</tr>

</tbody>

			<tfoot>
				<tr>
				<td class="pt20" colspan="2"><input type="submit" name="form_submit" id="add_field" class="button button-primary" value="フィールド追加" /></td>
				</tr>
			</tfoot>

		</table>


	        <input type="hidden" name="action" value="update" />
	        <input type="hidden" name="admin_action" value="" />

		</div>
	</div>
</div>
