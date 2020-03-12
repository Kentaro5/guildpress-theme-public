<?php
/**
*
*/
class Guild_Press_Public_Email_Headers
{
	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->email_header = array( 'charset=UTF-8' );

		$this->user_from_name = "";
		$this->user_from_email = "";
		$this->user_from_cc_email = "";
		$this->user_from_bcc_email = "";
		$this->user_from_subject = "";
		$this->user_from_message = "";
	}

	public function get_public_user_email_title( $mail_type )
	{
		switch ($mail_type) {
			case 'user_schedule_register':
				//カレンダー登録時のヘッダーをセットする。
			$this->set_public_user_calendar_email_header();
			break;

			case 'from_header':
			$this->set_public_user_from_email_header();
			default:
			continue;
			break;
		}

		return $this->email_header;
	}

	public function set_public_user_from_email_header()
	{
		//メールの設定をセットする
		$this->set_email_personal_settings();

		if( $this->user_from_name !== "" &&  $this->user_from_email !== "" ){

			$this->email_header[] = "From:".$this->user_from_name." <".$this->user_from_email.">";
		}
	}

	public function set_email_personal_settings($value='')
	{
		$schedule_email_settings = $this->wpfunc->get_option( SLUGNAME.'_schedule_email_settings' , '' );

		if( isset($schedule_email_settings[SLUGNAME.'_personal_from_name']) && $schedule_email_settings !== '' ){

			$this->user_from_name = $schedule_email_settings[SLUGNAME.'_personal_from_name'];
			$this->user_from_email = $schedule_email_settings[SLUGNAME.'_personal_from_email'];
			$this->user_from_cc_email = $schedule_email_settings[SLUGNAME.'_personal_CC_email'];
			$this->user_from_bcc_email = $schedule_email_settings[SLUGNAME.'_personal_BCC_email'];
			$this->user_from_subject = $schedule_email_settings[SLUGNAME.'_personal_from_subject'];
			$this->user_from_message = $schedule_email_settings[SLUGNAME.'_personal_email_message'];
		}
	}

	//カレンダー用のヘッダーセット
	public function set_public_user_calendar_email_header()
	{
		//メールの設定をセットする
		$this->set_email_personal_settings();

		if( $this->user_from_name !== "" &&  $this->user_from_email !== "" ){

			$this->email_header[] = "From:".$this->user_from_name." <".$this->user_from_email.">";
		}

		if( $this->user_from_cc_email !== "" ){

			$this->email_header[] = "Cc: ".$this->user_from_cc_email;
		}

		if( $this->user_from_bcc_email !== "" ){

			$this->email_header[] = "Bcc: ".$this->user_from_bcc_email;
		}
	}
}

