<?php

$user_email_form_file_path = $this->wpfunc->locate_template( 'templates/admin/calendar/email_form/user-email-form.php', false );

$admin_email_form_file_path = $this->wpfunc->locate_template( 'templates/admin/calendar/email_form/admin-email-form.php', false );

?>
<div id="guild_press_email_user">

	<?php
		//ユーザー側メールテンプレートを表示
		if( !file_exists($user_email_form_file_path) ){
			return;
		}
		include( $user_email_form_file_path );

		//管理者側メールテンプレートを表示
		if( !file_exists($admin_email_form_file_path) ){
			return;
		}
		include( $admin_email_form_file_path );
	?>


	<input type="hidden" name="admin_action" value="add_email_settings" />
</div>