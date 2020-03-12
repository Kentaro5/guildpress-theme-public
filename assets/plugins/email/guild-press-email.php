<?php

require_once( TEMP_DIR . '/assets/plugins/email/admin/guild-press-email-admin-header.php' );
require_once( TEMP_DIR . '/assets/plugins/email/admin/guild-press-email-admin-titles.php' );
require_once( TEMP_DIR . '/assets/plugins/email/admin/guild-press-email-admin-messages.php' );

require_once( TEMP_DIR . '/assets/plugins/email/public/guild-press-email-public-header.php' );
require_once( TEMP_DIR . '/assets/plugins/email/public/guild-press-email-public-titles.php' );
require_once( TEMP_DIR . '/assets/plugins/email/public/guild-press-email-public-message.php' );


/**
*
*/
class Guild_Press_Email
{

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;

		$this->admin_email_headers = new Guild_Press_Admin_Email_Headers;
		$this->admin_email_titles = new Guild_Press_Admin_Email_Titles;
		$this->admin_email_messages = new Guild_Press_Admin_Email_Messages;

		$this->public_email_headers = new Guild_Press_Public_Email_Headers;
		$this->public_email_titles = new Guild_Press_Public_Email_Titles;
		$this->public_email_messages = new Guild_Press_Public_Email_Messages;

		$this->user_header = array( 'Content-Type: text/html; charset=UTF-8' );

	}

	//管理者と登録者にそれぞれメールを送る。
	public function send_mail( $user_id, $mail_type='', $member_rank='' ){

		if( ! isset( $user_id ) || ! $user_id || is_string($user_id) ){
			return;
		}

		//メールを送信するのに必用な値を取得する
		$email_items = array();
		$email_items = $this->set_email_items( $user_id );

		if( isset($email_items['registed_date']) && $email_items['registed_date'] !== "" ){

			$email_items['registed_date'] = date_i18n("Y-n-j H:i:s");
		}

		$email_items['admin_email'] = $this->wpfunc->get_option('admin_email');

		$email_items['site_name'] = $this->wpfunc->get_option('blogname');

		//管理者にメールを送る
		$admin_email_result = $this->send_to_admin( $mail_type, $email_items, $user_id, $member_rank );

		if( ! $admin_email_result ){
			return false;
		}

		$this->register_user_data = $this->wpfunc->get_user_by( 'id', intval($user_id) );

		//ユーザーにメールを送る。
		$user_email_result = $this->send_to_user( $mail_type, $email_items, $user_id, $member_rank );

		if( ! $user_email_result ){
			return false;
		}

		return true;

	}

	public function send_to_user( $mail_type, $email_items, $user_id )
	{
		//管理画面側で保存したメールのタイトルやメールのタイプなどをここでセットする。
		if ( apply_filters( 'guild_press_set_email_header', 'personal' ) ) {

			$user = get_user_by( 'email', $email_items['register_user_email'] );

				//ヘッダーセット
			$public_header = $this->public_email_headers->get_public_user_email_title( $mail_type );

				//タイトルセット
			$public_title = $this->public_email_titles->set_public_user_email_title( $mail_type, $email_items, $user_id );

				//メッセージセット
			$public_message = $this->public_email_messages->set_public_user_email_message( $mail_type, $email_items, $user_id );


			$result = $this->wpfunc->wp_mail(
				$email_items['register_user_email'],
				$public_title,
				$public_message,
				$public_header
			);


			if( ! $result ){

				return false;

			}
		}

		return true;
	}

	public function send_to_admin( $mail_type, $email_items, $user_id, $header_flg = true )
	{
		//管理画面側で保存したメールのタイトルやメールのタイプなどをここでセットする。
		if( apply_filters( 'guild_press_set_email_header', 'admin' ) ){

			//ヘッダーセット
			$admin_header = $this->admin_email_headers->get_admin_user_email_title( $mail_type );

			//タイトルセット
			$admin_title = $this->admin_email_titles->set_admin_user_email_title( $mail_type, $email_items, $user_id );

			//メッセージセット
			$admin_message = $this->admin_email_messages->set_admin_user_email_message( $mail_type, $email_items, $user_id );

			$result = $this->wpfunc->wp_mail(
				$email_items['admin_email'],
				$admin_title,
				$admin_message,
				$admin_header
			);

			if( ! $result ){
				return false;
			}
		}

		return true;
	}

	//登録者にそれぞれメールを送る。
	public function send_mail_to_user( $user_id, $mail_type='' ){

		if( ! isset( $user_id ) || ! $user_id || is_string($user_id) ){
			return;
		}

		$email_items = array();
		$email_items = $this->set_email_items( $user_id );

		$email_items['admin_email'] = $this->wpfunc->get_option('admin_email');

		$email_items['site_name'] = $this->wpfunc->get_option('blogname');

		//ユーザーにメールを送る。
		$user_email_result = $this->send_to_user( $mail_type, $email_items, $user_id );

		if( ! $user_email_result ){
			return false;
		}

		return true;

	}
		//管理者にのみメールを送る。
	public function send_mail_to_admin( $mail_type='' ){

		$user_id = 1;
		$email_items = array();
		$email_items = $this->set_email_items( $user_id );

		$email_items['admin_email'] = $this->wpfunc->get_option('admin_email');

		$email_items['site_name'] = $this->wpfunc->get_option('blogname');

		//管理者にメールを送る
		$admin_email_result = $this->send_to_admin( $mail_type, $email_items, $user_id );

		if( ! $admin_email_result ){
			return false;
		}
		return true;

	}

	//メールを送信するのに必用なメールの要素をセットする
	public function set_email_items( $user_id )
	{

		$email_items = array();
		$email_items['register_user_data'] = $this->wpfunc->get_user_by( 'id', intval($user_id) );

		$email_items['register_user_name'] = $email_items['register_user_data']->display_name;

		$email_items['register_user_email'] = $email_items['register_user_data']->user_email;

		$email_items['registed_date'] = ( isset( $_POST['date_id'] ) && $_POST['date_id'] !== "" ) ? date( "Y/m/d", $_POST['date_id'] ) : '';

		$email_items['registered_time1'] = ( isset( $_POST['date_time1'] ) && $_POST['date_time1'] !== "" ) ? $_POST['date_time1'] : '';

		$email_items['registered_time2'] = ( isset( $_POST['date_time2'] ) && $_POST['date_time2'] !== "" ) ? $_POST['date_time2'] : '';

		$email_items['registered_comment'] = ( isset( $_POST['comment'] ) && $_POST['comment'] !== "" ) ? $_POST['comment'] : '';

		return $email_items;

	}


}



