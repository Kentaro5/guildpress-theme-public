<?php
/**
*
*/
class GoogleCalendarAuth
{

	public function __construct()
	{
		$this->load();
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->google_model = new GoogleCalendarModel();

	}

	public function load()
	{

		add_action( 'pre_get_posts', array( $this, 'check_auth_query' ) );
		add_action( 'admin_'.SLUGNAME.'_after_register_schedule', array( $this, 'admin_add_google_calendar' ) );
		add_action( 'admin_'.SLUGNAME.'_after_edit_schedule', array( $this, 'admin_edit_google_calendar' ) );
		add_action( 'admin_'.SLUGNAME.'_after_delete_schedule', array( $this, 'admin_delete_google_calendar' ) );

		add_action( 'public_'.SLUGNAME.'_after_register_schedule', array( $this, 'public_add_google_calendar' ) );
		add_action( 'public_'.SLUGNAME.'_after_update_schedule', array( $this, 'public_update_google_calendar' ) );
		add_action( 'public_'.SLUGNAME.'_after_delete_schedule', array( $this, 'public_update_google_calendar' ) );

	}

	public function check_auth_query( $wp_query ) {

		$auth_check = $wp_query->get( 'guildpress_auth' );
		//google_authの場合は、auth処理を行う。
		if( $auth_check === 'google_auth' ){

			$this->google_model->save_auth_google_settings();
		}
	}

	//Googleカレンダーに同期する処理
	public function public_add_google_calendar( $args )
	{
		$calendar_id = $this->return_google_calendar_id();

		if( $calendar_id === '' ){

			return;
		}
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		if( !empty($google_settings) && $google_settings['guild_press_google_client_id'] !== '' && $google_settings['guild_press_google_client_secret'] ){

			if( $args['google_event_id'] !== '' ){

				$this->google_model->public_update_google_calendar( $args );
			}
		}
	}

	//Googleカレンダーに同期する処理
	public function public_update_google_calendar( $args )
	{

		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		if( !empty($google_settings) && $google_settings['guild_press_google_client_id'] !== '' && $google_settings['guild_press_google_client_secret'] ){

			if( $args['google_event_id'] !== '' ){

				$this->google_model->public_update_google_calendar( $args );
			}
		}
	}


	//Googleカレンダーに同期する処理
	public function admin_add_google_calendar( $detail_schedule_key )
	{

		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		if( !empty($google_settings) && $google_settings['guild_press_google_client_id'] !== '' && $google_settings['guild_press_google_client_secret'] ){

			$this->google_model->admin_save_google_calendar( $detail_schedule_key );
		}
	}

	//Googleカレンダーに同期する処理
	public function admin_delete_google_calendar( $google_event_id )
	{

		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		if( !empty($google_settings) && $google_settings['guild_press_google_client_id'] !== '' && $google_settings['guild_press_google_client_secret'] ){

			//goolge_idがセットされていない場合は処理をしない。
			if( $google_event_id !== '' ){

				$this->google_model->delete_google_calendar( $google_event_id );
			}

		}
	}

	//Googleカレンダーに同期する処理
	public function admin_edit_google_calendar( $args )
	{

		$task_id = $args['task_id'];
		$save_data = $args['save_data'];

		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		if( !empty($google_settings) && $google_settings['guild_press_google_client_id'] !== '' && $google_settings['guild_press_google_client_secret'] ){

			$this->google_model->admin_edit_google_calendar( $task_id, $save_data );
		}
	}

	public function return_google_calendar_id()
	{
		//カレンダーのIDを取得
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		$guild_press_google_client_id = ( isset($google_settings['guild_press_google_client_id']) && $google_settings['guild_press_google_client_id'] !== "" ) ? $google_settings['guild_press_google_client_id'] : '';

		if( $guild_press_google_client_id === '' || empty( $guild_press_google_client_id ) ){

			return '';
		}

		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
		$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );
		$calendar_id = $google_client_new_settings['google_calendar_id'];

		return $google_client_new_settings;
	}

}
