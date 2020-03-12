<?php
/**
 *
 */
class Guild_Press_Calendar_Email
{
	public $event_user_from_name;
	public $event_user_from_email;
	public $event_user_from_cc_email;
	public $event_user_from_bcc_email;
	public $event_user_from_subject;
	public $event_user_from_message;
	public $event_admin_from_name;
	public $event_admin_from_email;
	public $event_admin_from_cc_email;
	public $event_admin_from_bcc_email;
	public $event_admin_from_subject;
	public $event_admin_from_message;

	public $wpfunc;
	public $email_layout_path;

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email_layout_path = 'templates/admin/calendar/email_form/email-layout.php';
	}

	public function set_schedule_email_settings()
	{
		$schedule_email_settings = $this->wpfunc->get_option(SLUGNAME.'_schedule_email_settings', false);

		if( isset($schedule_email_settings[SLUGNAME.'_personal_from_name']) && $schedule_email_settings !== false ){

			$this->event_user_from_name = $schedule_email_settings[SLUGNAME.'_personal_from_name'];
			$this->event_user_from_email = $schedule_email_settings[SLUGNAME.'_personal_from_email'];
			$this->event_user_from_cc_email = $schedule_email_settings[SLUGNAME.'_personal_CC_email'];
			$this->event_user_from_bcc_email = $schedule_email_settings[SLUGNAME.'_personal_BCC_email'];
			$this->event_user_from_subject = $schedule_email_settings[SLUGNAME.'_personal_from_subject'];
			$this->event_user_from_message = $schedule_email_settings[SLUGNAME.'_personal_email_message'];

			$this->event_admin_from_name = $schedule_email_settings[SLUGNAME.'_admin_from_name'];
			$this->event_admin_from_email = $schedule_email_settings[SLUGNAME.'_admin_from_email'];
			$this->event_admin_from_cc_email = $schedule_email_settings[SLUGNAME.'_admin_CC_email'];
			$this->event_admin_from_bcc_email = $schedule_email_settings[SLUGNAME.'_admin_BCC_email'];
			$this->event_admin_from_subject = $schedule_email_settings[SLUGNAME.'_admin_from_subject'];
			$this->event_admin_from_message = $schedule_email_settings[SLUGNAME.'_admin_email_message'];
		}
	}

	public function schedule_email_settings_box()
	{
		//必要な変数をセットする。
		$this->set_schedule_email_settings();

		$admin_arr = array(
			'admin_from_name' => SLUGNAME.'_admin_from_name',
			'event_admin_from_name' => $this->event_admin_from_name,
			'admin_from_email' => SLUGNAME.'_admin_from_email',
			'event_admin_from_email' => $this->event_admin_from_email,
			'admin_to_email' => SLUGNAME.'_admin_To_email',
			'admin_cc_email' => SLUGNAME.'_admin_CC_email',
			'event_admin_from_cc_email' => $this->event_admin_from_cc_email,
			'admin_bcc_email' => SLUGNAME.'_admin_BCC_email',
			'event_admin_from_bcc_email' => $this->event_admin_from_bcc_email,
			'admin_from_subject' => SLUGNAME.'_admin_from_subject',
			'event_admin_from_subject' => $this->event_admin_from_subject,
			'admin_email_message' => SLUGNAME.'_admin_email_message',
			'event_admin_from_message' => $this->event_admin_from_message,
		);

		$user_arr = array(
			'personal_from_name' => SLUGNAME.'_personal_from_name',
			'event_user_from_name' => $this->event_user_from_name,
			'personal_from_email' => SLUGNAME.'_personal_from_email',
			'event_user_from_email' => $this->event_user_from_email,
			'personal_to_email' => SLUGNAME.'_personal_To_email',
			'personal_cc_email' => SLUGNAME.'_personal_CC_email',
			'event_user_from_cc_email' => $this->event_user_from_cc_email,
			'personal_bcc_email' => SLUGNAME.'_personal_BCC_email',
			'event_user_from_bcc_email' => $this->event_user_from_bcc_email,
			'personal_from_subject' => SLUGNAME.'_personal_from_subject',
			'event_user_from_subject' => $this->event_user_from_subject,
			'personal_email_message' => SLUGNAME.'_personal_email_message',
			'event_user_from_message' => $this->event_user_from_message,
		);

		$gp_data = array_merge( $admin_arr, $user_arr );

		if( ! $file_path = $this->basic->load_template( $this->email_layout_path, false ) ){

			return;
		}

		include( $file_path );

	}

	public function save_settings(){

		if( ! wp_verify_nonce( $_POST['notification-nonce'], 'notification' ) && !isset( $_POST["notification-nonce"] ) ) {
			return;
		}


		if( ! wp_verify_nonce( $_POST['schedule_email_settings'], SLUGNAME.'_schedule_email_settings_schedule_email_settings' ) && !isset( $_POST['schedule_email_settings'] ) ){

			return;

		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}


		$result = $this->wpfunc->register_setting( SLUGNAME.'_schedule_email_settings', SLUGNAME.'_schedule_email_settings', array( $this, 'create_save_settings' ) );

	}

	public function create_save_settings(){

		foreach ($_POST as $key => $value) {
			$settings[ $key ] = ( ! $_POST[ $key ] || ! isset( $_POST[ $key ]  ) || ! is_string( $_POST[ $key ] ) )  ? "" : $_POST[ $key ];
		}

		return $settings;
	}
}