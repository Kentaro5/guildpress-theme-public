<?php
/**
*
*/
class Guild_Press_Admin_Email_Headers
{
	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->email_header = array( 'charset=UTF-8' );

		$this->admin_user_from_name = "";
		$this->admin_user_from_email = "";
		$this->admin_user_from_cc_email = "";
		$this->admin_user_from_bcc_email = "";
		$this->admin_user_from_subject = "";
		$this->admin_user_from_message = "";
	}

	public function get_admin_user_email_title( $mail_type )
	{
		switch ($mail_type) {
			case 'user_schedule_register':
				//カレンダー登録時のヘッダーをセットする。
				$this->set_admin_user_calendar_email_header();
			break;

			default:
				continue;
			break;
		}

		return $this->email_header;
	}

	public function set_email_setting()
	{
		$schedule_email_settings = $this->wpfunc->get_option( SLUGNAME.'_schedule_email_settings' , '' );

		if( isset($schedule_email_settings[SLUGNAME.'_personal_from_name']) && $schedule_email_settings !== '' ){

			$this->admin_user_from_name = $schedule_email_settings[SLUGNAME.'_admin_from_name'];
			$this->admin_user_from_email = $schedule_email_settings[SLUGNAME.'_admin_from_email'];
			$this->admin_user_from_cc_email = $schedule_email_settings[SLUGNAME.'_admin_CC_email'];
			$this->admin_user_from_bcc_email = $schedule_email_settings[SLUGNAME.'_admin_BCC_email'];
			$this->admin_user_from_subject = $schedule_email_settings[SLUGNAME.'_admin_from_subject'];
			$this->admin_user_from_message = $schedule_email_settings[SLUGNAME.'_admin_email_message'];
		}
	}
	//カレンダー用のヘッダーセット
	public function set_admin_user_calendar_email_header()
	{
		//メール情報セット
		$this->set_email_setting();

		if( $this->admin_user_from_name !== "" &&  $this->admin_user_from_email !== "" ){

			$this->email_header[] = "From:".$this->admin_user_from_name." <".$this->admin_user_from_email.">";
		}

		if( $this->admin_user_from_cc_email !== "" ){

			$this->email_header[] = "Cc:".$this->admin_user_from_cc_email;
		}

		if( $this->admin_user_from_bcc_email !== "" ){

			$this->email_header[] = "Bcc:".$this->admin_user_from_bcc_email;
		}
	}
}

