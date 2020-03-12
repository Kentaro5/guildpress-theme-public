<div class="wrap">
	<h3>ページ設定</h3>
	<p class="description">
		<?php _e( 'ショートコードを設定した固定ページを選択して下さい。' ); ?>
	</p>
	<p class="description">
		<?php _e( '新規登録ページショートコード：[guild_press_user_register]' ); ?>
	</p>
	<p class="description">
		<?php _e( 'マイページショートコード：[guild_press_my_page]' ); ?>
	</p>
	<p class="description">
		<?php _e( 'ログインページショートコード：[guild_press_login_page]' ); ?>
	</p>
	<p class="description">
		<?php _e( 'カレンダーページショートコード：[guild_press_calender]' ); ?>
	</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( '新規登録ページ(必須)' ); ?></label></th>
			<td>
				<select name="guild_press_register" id="guild_press_register" style="width: 300px;">
					<option value="">固定ページを一覧から選択</option>
					<?php foreach ( $gp_data['page_lists'] as $page ) : ?>
						<option value="<?php echo $page->ID; ?>" <?php selected( $gp_data['setting_data']['guild_press_register'],  $page->ID ); ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( 'マイページ(必須)' ); ?></label></th>
			<td>
				<select name="guild_press_mypage" id="guild_press_mypage" style="width: 300px;">
					<option value="">固定ページを一覧から選択</option>
					<?php foreach ( $gp_data['page_lists'] as $page ) : ?>
						<option value="<?php echo $page->ID; ?>" <?php selected( $gp_data['setting_data']['guild_press_mypage'],  $page->ID ); ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( 'ユーザー情報更新ページ(必須)' ); ?></label></th>
			<td>
				<select name="guild_press_edit_user_info" id="guild_press_edit_user_info" style="width: 300px;">
					<option value="">固定ページを一覧から選択</option>
					<?php foreach ( $gp_data['page_lists'] as $page ) : ?>
						<option value="<?php echo $page->ID; ?>" <?php selected( $gp_data['setting_data']['guild_press_edit_user_info'],  $page->ID ); ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>


		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( 'ログインページ(必須)' ); ?></label></th>
			<td>
				<select name="guild_press_login" id="guild_press_login" style="width: 300px;">
					<option value="">固定ページを一覧から選択</option>
					<?php foreach ( $gp_data['page_lists'] as $page ) : ?>
						<option value="<?php echo $page->ID; ?>" <?php selected( $gp_data['setting_data']['guild_press_login'],  $page->ID ); ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( 'ログイン後のページ' ); ?></label></th>
			<td>
				<select name="guild_press_after_login" id="guild_press_after_login" style="width: 300px;">
					<option value="">固定ページを一覧から選択</option>
					<?php foreach ( $gp_data['page_lists'] as $page ) : ?>
						<option value="<?php echo $page->ID; ?>" <?php selected( $gp_data['setting_data']['guild_press_after_login'],  $page->ID ); ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>


		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( '決済完了後に表示するページ' ); ?></label></th>
			<td>
				<select name="guild_press_after_payment" id="guild_press_after_payment" style="width: 300px;">
					<option value="">固定ページを一覧から選択</option>
					<?php foreach ( $gp_data['page_lists'] as $page ) : ?>
						<option value="<?php echo $page->ID; ?>" <?php selected( $gp_data['setting_data']['guild_press_after_payment'],  $page->ID ); ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( '決済をキャンセルした場合に表示するページ' ); ?></label></th>
			<td>
				<select name="guild_press_payment_cancel_url" id="guild_press_payment_cancel_url" style="width: 300px;">
					<option value="">固定ページを一覧から選択</option>
					<?php foreach ( $gp_data['page_lists'] as $page ) : ?>
						<option value="<?php echo $page->ID; ?>" <?php selected( $gp_data['setting_data']['guild_press_payment_cancel_url'],  $page->ID ); ?>><?php echo $page->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="paypal_address"><?php _e( '管理ツールバー表示の有無' ); ?></label></th>
			<td>

				<input type="checkbox" name="guild_press_check_admin_bar" value="1" <?php echo $this->basic->check_checked( $gp_data['bar_check'] ); ?> >
			</td>
		</tr>

	</table>

	<input type="hidden" name="admin_action" value="guildpresssavesetting" />
</div>