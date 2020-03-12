<div id="<?php echo $gp_data['id_name']; ?>">
	<div class="wrap">

		<div id="icon-edit" class="icon32">
			<br>
		</div>
		<h2>予約カレンダー</h2>
		<div class="inside">
			<table>
				<tbody>
					<tr>
						<td>タイトル</td>
						<th col="2"><input type="text" name="title" value=""></th>
					</tr>
					<tr>
						<td>人数</td>
						<th><input type="text" name="max_num" id="max_num" value=""></th>
					</tr>
					<tr>
						<td>時間帯指定</td>

						<th><input type="text" name="date_time1" id="date_time1" value=""></th>
						<th>〜</th>
						<th><input type="text" name="date_time2" id="date_time2" value=""></th>
					</tr>
				</tbody>
			</table>

		</div>

		<input type="hidden" name="admin_action" value="register_new_schedule" />

		<input type="hidden" name="gp_month" value="<?php echo $this->wpfunc->esc_attr($gp_data['gp_month']); ?>" />
		<input type="hidden" name="gp_year" value="<?php echo $this->wpfunc->esc_attr($gp_data['gp_year']); ?>" />


		<input type="hidden" name="date_id" value="<?php echo $this->wpfunc->esc_attr($gp_data['date_id']); ?>" />
	</div>
</div>
