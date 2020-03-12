<div id="<?php echo $gp_data['id_name']; ?>">
	<div class="wrap">

		<div id="icon-edit" class="icon32">
			<br>
		</div>
		<h2>予約カレンダー</h2>

		<table>
			<tbody>
				<tr>
					<td>タイトル</td>
					<th col="2">
						<input type="text" name="title" id="title" value="<?php echo $this->wpfunc->esc_html($gp_data['title']); ?>">
					</th>
				</tr>
				<tr>
					<td>人数</td>
					<th>
						<input type="text" name="max_num" id="max_num" value="<?php echo $this->wpfunc->esc_html($gp_data['max_num']); ?>">
					</th>
				</tr>
				<tr>
					<td>時間帯指定</td>

					<th><input type="text" name="date_time1" value="<?php echo $this->wpfunc->esc_html($gp_data['date_time1']); ?>" id="date_time1"></th>
					<th>〜</th>
					<th>
						<input type="text" name="date_time2" value="<?php echo $this->wpfunc->esc_html($gp_data['date_time2']); ?>" id="date_time2">
					</th>
				</tr>
			</tbody>
		</table>

		<a href="#" onclick="admin_js.delete_schedule('<?php echo $this->wpfunc->esc_html($gp_data['date_id']); ?>','<?php echo $gp_data['schedule_task_id']; ?>','<?php echo $gp_data['admin_url']; ?>' );return false;">予約を削除する場合はこちらをクリックして下さい。</a>

		<input type="hidden" name="admin_action" value="edit_new_schedule" />
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="date_id" value="<?php echo $this->wpfunc->esc_html($gp_data['date_id']); ?>" />

		<input type="hidden" name="google_event_id" value="<?php echo $gp_data['google_event_id']; ?>" />

		<input type="hidden" name="task_id" value="<?php echo $gp_data['schedule_task_id']; ?>" />

		<input type="hidden" name="add_flag" value="salonpaymentfieldadd" />

		<input type="hidden" name="gp_month" value="<?php echo  $this->wpfunc->esc_html($gp_data['gp_month']); ?>" />
		<input type="hidden" name="gp_year" value="<?php echo  $this->wpfunc->esc_html($gp_data['gp_year']); ?>" />
		<div id="loadingAnim" class="loadingAnim" style="display:none;">
			<i class="loadingAnim_line"></i>
		</div>
	</div>
</div>