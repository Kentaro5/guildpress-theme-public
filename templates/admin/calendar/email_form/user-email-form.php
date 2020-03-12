<div >
	<h3>ユーザーイベント登録メール(ユーザー側)</h3>
	<p class="description">
		<?php _e( 'このメールはカレンダーページでユーザーが予定を登録した時にユーザーに自動で返信されるメールになります。' ); ?><br/>
		<?php _e( 'もし、どの項目も埋まっていなかった場合はデフォルトのメッセージなどが代わりに送信されます。' ); ?><br/>

		<?php _e( '下記の特殊文字を使うことでユーザー名、ブログ名などをいれることができます。' ); ?><br/><br/>
		<?php _e( '%blogname%:このサイトのタイトル名が入ります。' ); ?><br/>
		<?php _e( '%siteurl%:このサイトのTOPのURLが入ります。' ); ?><br/>

		<?php _e( '%user_login%:新規登録をした際に設定された「ユーザー名」が入ります。' ); ?><br/>
		<?php _e( '%user_email%:新規登録をした際に設定されたユーザーの「メールアドレス」が入ります。' ); ?><br/>

		<?php _e( '%username%:新規登録をした際に設定されたユーザーの「姓・名」が入ります。' ); ?><br/><br/>
	</p>

	<table class="form-table">
		<tr>
			<th scope="row"><label for="<?php echo $gp_data['personal_from_name']; ?>"><?php _e( '送信元 (Name)' ); ?></label></th>
			<td><input name="<?php echo $gp_data['personal_from_name']; ?>" type="text" id="<?php echo $gp_data['personal_from_name']; ?>" value="<?php echo $this->wpfunc->esc_html( $gp_data['event_user_from_name'] ); ?>" class="large-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="<?php echo $gp_data['personal_from_email']; ?>"><?php _e( '送信元 (From)(半角英数字で入力して下さい。)' ); ?></label></th>
			<td><input name="<?php echo $gp_data['personal_from_email']; ?>" type="text" id="<?php echo $gp_data['personal_from_email']; ?>" value="<?php echo $this->wpfunc->esc_html($gp_datap['event_user_from_email']); ?>" class="large-text" /></td>
		</tr>

		<tr>
			<th scope="row"><label for="<?php echo $gp_data['personal_to_email']; ?>"><?php _e( '送信元 (To)' ); ?></label></th>
			<td>こちらは送信する時に各ユーザーのメールアドレスが自動で入ります。</td>
		</tr>

		<tr>
			<th scope="row"><label for="<?php echo $gp_data['personal_cc_email']; ?>"><?php _e( 'CC(半角英数字で入力して下さい。)' ); ?></label></th>
			<td><input name="<?php echo $gp_data['personal_cc_email']; ?>" type="text" id="<?php echo $gp_data['personal_cc_email']; ?>" value="<?php echo $this->wpfunc->esc_html($gp_data['event_user_from_cc_email']); ?>" class="large-text" /></td>
		</tr>

		<tr>
			<th scope="row"><label for="<?php echo $gp_data['personal_bcc_email']; ?>"><?php _e( 'BCC(半角英数字で入力して下さい。)' ); ?></label></th>
			<td><input name="<?php echo $gp_data['personal_bcc_email']; ?>" type="text" id="<?php echo $gp_data['personal_bcc_email']; ?>" value="<?php echo $this->wpfunc->esc_html($gp_data['event_user_from_bcc_email']); ?>" class="large-text" /></td>
		</tr>

		<tr>
			<th scope="row"><label for="<?php echo $gp_data['personal_from_subject']; ?>"><?php _e( '件名' ); ?></label></th>
			<td><input name="<?php echo $gp_data['personal_from_subject']; ?>" type="text" id="<?php echo $gp_data['personal_from_subject']; ?>" value="<?php echo $this->wpfunc->esc_html($gp_data['event_user_from_subject']); ?>" class="large-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="<?php echo $gp_data['personal_email_message']; ?>"><?php _e( '本文' ); ?></label></th>
			<td>
				<textarea name="<?php echo $gp_data['personal_email_message']; ?>" id="<?php echo $gp_data['personal_email_message']; ?>" class="large-text" rows="10"><?php echo $this->wpfunc->esc_html($gp_data['event_user_from_message']); ?></textarea></p>
			</td>
		</tr>
	</table>


</div>