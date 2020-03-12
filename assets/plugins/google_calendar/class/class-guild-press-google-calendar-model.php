<?php
require_once TEMP_DIR.'/vendor/autoload.php';
/**
*
*/
class GoogleCalendarModel
{

	public function __construct()
	{
		// $this->load();
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->admin_url='';

	}

	public function auth_google(){

		if( ! wp_verify_nonce( $_POST['google_calenda'], SLUGNAME.'_google_calendar_settings' ) && !isset( $_POST["google_calenda"] ) ) {

			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$client = $this->get_google_cleint();

		//AuthURLを作成してリダイレクト
		$googleAuthUrl = $client->createAuthUrl();
		wp_redirect($googleAuthUrl);
		exit();

	}

	//auth設定に必要なCient作成
	public function get_google_cleint()
	{

		$google_options = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		$client_id = $google_options['guild_press_google_client_id'];
		$client_secret = $google_options['guild_press_google_client_secret'];
		$redirect_uri = $google_options['guild_press_google_redirect_url'];
		$scopes = 'https://www.googleapis.com/auth/calendar';

		$client = new Google_Client();
		$client->setApplicationName("Google OAuth Login With PHP");
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setScopes($scopes);
		//リフレッシュトークンを取得
		$client->setAccessType('offline');

		return $client;
	}

	//カレンダーを設定して、IDを取得
	public function create_calendar()
	{
		//クライアント情報取得
		$google_client = $this->get_google_cleint();

		$client = $this->set_google_token( $google_client );

		$service = new Google_Service_Calendar($client);

		//WordPressのタイムゾーン取得
		$timezone_string = $this->wpfunc->get_option( 'timezone_string' );

		//カレンダー設定　
		$calendar = new Google_Service_Calendar_Calendar();
		$calendar->setSummary('GuildPressカレンダー');
		$calendar->setTimeZone($timezone_string);


		try {

			$createdCalendar = $service->calendars->insert($calendar);
		} catch ( Exception $ex ) {
			//エラーが出た時は何もせずに処理を返す。
			return;
		}


		return $createdCalendar->getId();
	}

	//tokenのセットとチェックを行う。
	public function set_google_token( $client )
	{
		$google_tokens = $this->wpfunc->get_option( 'guild_press_google_tokens' );

		/*
		{
			  ["error"]=>
			  string(15) "invalid_request"
			  ["error_description"]=>
			  string(41) "Missing required parameter: refresh_token"
			}
			の形のエラー処理
		*/

		if( isset( $google_tokens['error'] ) && $google_tokens['error'] !== '' ){

			$this->set_error_code( $google_tokens );
		}else{

			$client->setAccessToken($google_tokens['access_token']);

			if ($client->isAccessTokenExpired()) {

				if( $google_tokens['refresh_token'] === '' ){

					//refreshtokenが空の場合は、クライアントIDから予備のバックアップのリフレッシュトークンを使用。
					$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );
					$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
					$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );

					//バックアップのリフレッシュトークンを使って、新しくトークンを作成。
					$new_token = $client->refreshToken( $google_client_new_settings['refresh_token'] );

						//新しいトークンを保存
					$this->wpfunc->update_option( 'guild_press_google_tokens', $new_token );

				}else{

					//トークンが切れている場合は、既存のトークンから新しいトークンを作成
					$new_token = $client->refreshToken( $google_tokens['refresh_token'] );
					if( isset( $new_token['error'] ) && $new_token['error'] !== '' ){

						$this->set_error_code( $new_token );
					}
				}

					//新しいトークンをセット。
				$client->setAccessToken( $new_token );
			}

			return $client;
		}


	}

	public function set_error_code( $google_tokens )
	{
		$this->wpfunc->update_option( 'guild_press_google_tokens_error', $google_tokens );
			//エラーがあった場合は、管理画面にエラー内容を反映
		$this->admin_url = $this->wpfunc->admin_url().'admin.php?page='.SLUGNAME.'_google_calendar_settings&tab=google_calendar_settings&auth=google_error';
		wp_redirect($this->admin_url);
		exit();
	}


	//Googleからリダイレクトされた値を保存。
	public function save_auth_google_settings()
	{

		//googleのコードがセットされていれば、それに沿って処理を行う。
		if ( isset( $_GET['code'] ) ) {

			$client = $this->get_google_cleint();
			$auth_codes = $client->authenticate($_GET['code']);

			//エラーの場合は、エラーを返す。
			if( isset( $auth_codes['error'] ) ){

				$this->wpfunc->update_option( 'guild_press_google_tokens_error', $auth_codes );

				$this->admin_url = $this->wpfunc->admin_url().'admin.php?page='.SLUGNAME.'_google_calendar_settings&tab=google_calendar_settings&auth=error';
		 	}else{

		 		//もし、ユーザーがgoogleカレンダーの連携を変更した場合の処理を後で考えて書く。
		 		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

				//GoogleのクライントIDが長すぎるので、.apps以下を切り捨てて、リフレッシュトークンのキーにする。
		 		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );

		 		//各月全体を管理するオプションを登録＆更新。
		 		$google_client_settings = $this->wpfunc->get_option( $guild_press_google_client_id );

		 		$google_client_settings = ( !empty($google_client_settings) || $google_client_settings !== "" ) ? $google_client_settings : '';


		 		//初めて作成する場合は、事前にリフレッシュトークンだけ作成しておく。
		 		if( $google_client_settings === '' ){

		 			$calendar_id = '';
		 			$google_client_new_settings = array(
		 				'google_calendar_id' => $calendar_id,
		 				'refresh_token' => $auth_codes['refresh_token']
		 			);
		 			$this->wpfunc->update_option( $guild_press_google_client_id, $google_client_new_settings );

		 			//もう一度セットしなおす。
		 			$google_client_settings = $this->wpfunc->get_option( $guild_press_google_client_id );
		 		}

				if( isset( $auth_codes['refresh_token'] ) && $auth_codes['refresh_token'] !== "" ){

			 		if( $google_client_settings['refresh_token'] !== $auth_codes['refresh_token'] ){

			 			//カレンダーIDを失くして、新しく作成するようにする。
			 			$google_client_settings['google_calendar_id'] = '';
			 		}

			 		$goole_auth_args = array(
			 			'guild_press_google_client_id' => $guild_press_google_client_id,
			 			'auth_codes' => $auth_codes,
			 			'google_client_settings' => $google_client_settings,
			 		);

			 		$this->save_google_auth( $goole_auth_args );
		 		}else{

		 			$calendar_id_check = ( isset($google_client_settings['google_calendar_id']) && $google_client_settings['google_calendar_id'] !== "" ) ? $google_client_settings['google_calendar_id'] : '';

		 			 //Google_calendaqr_IDが空の場合は、新しく保存
					if( $calendar_id_check === '' ){

						$goole_auth_args = array(
							'guild_press_google_client_id' => $guild_press_google_client_id,
							'auth_codes' => $auth_codes,
							'google_client_settings' => $google_client_settings,
						);
						$this->save_google_auth( $goole_auth_args );
					}
		 		}

		 		$this->admin_url = $this->wpfunc->admin_url().'admin.php?page='.SLUGNAME.'_google_calendar_settings&tab=google_calendar_settings&auth=success';

		 	}

		 	wp_redirect($this->admin_url);
			exit();

		}else{
			//何もない内場合は処理しない。
			return;
		}
	}



	//Googleカレンダーの設定を保存する処理。
	public function save_google_auth( $args = array() )
	{

		$guild_press_google_client_id = $args['guild_press_google_client_id'];
		$auth_codes = $args['auth_codes'];
		$google_client_settings = $args['google_client_settings'];

		$auth_refresh_check = ( isset($auth_codes['refresh_token']) && $auth_codes['refresh_token'] !== "" ) ? $auth_codes['refresh_token'] : '';

		if( $auth_refresh_check === '' ){

			$auth_codes['refresh_token'] = $google_client_settings['refresh_token'];
		}

		 //Googleのauth設定を保存。(カレンダー作成よりも先に処理をおいておく。)
		$this->wpfunc->update_option( 'guild_press_google_tokens', $auth_codes );

		if( $google_client_settings['google_calendar_id'] === NULL ){

			$google_client_settings['google_calendar_id'] = '';
		}

		if( $google_client_settings['google_calendar_id'] === '' ){

		 	//事前にカレンダーを作成して、IDを取得。
			$calendar_id = $this->create_calendar();
		}else{

			$calendar_id = $google_client_settings['google_calendar_id'];
		}

		$google_client_new_settings = array(
			'google_calendar_id' => $calendar_id,
			'refresh_token' => $auth_codes['refresh_token']
		);
		$this->wpfunc->update_option( $guild_press_google_client_id, $google_client_new_settings );

	}

	public function delete_google_calendar( $google_event_id )
	{
		//クライアント情報取得
		$google_client = $this->get_google_cleint();

		//トークンをセット。
		$client = $this->set_google_token( $google_client );

		//カレンダーのIDを取得
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );
		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
		$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );
		$calendar_id = $google_client_new_settings['google_calendar_id'];

		$service = new Google_Service_Calendar($client);
		//アカウントを変更した場合、今まで使っていたカレンダーIDを見つけられなくなるためエラーが出る。
		try {

			$service->events->delete( $calendar_id, $google_event_id );
		} catch ( Exception $ex ) {
			//エラーが出た時は何もせずに処理を返す。
			return;
		}
	}

	public function public_update_google_calendar( $args )
	{

		$date_id = $this->basic->delete_space( $args['date_id'] );
		$schedule_task_id = $this->basic->delete_space( $args['schedule_id'] );
		$google_event_id = $this->basic->delete_space( $args['google_event_id'] );
		$the_month = $this->basic->delete_space( $args['the_month'] );

		$schedule_option = $this->wpfunc->get_option( $schedule_task_id );

		//クライアント情報取得
		$google_client = $this->get_google_cleint();

		$time = date_i18n('Y-m-d', intval( $date_id ) );


		//トークンをセット。
		$client = $this->set_google_token( $google_client );

		$max_num = $this->basic->delete_space( $schedule_option['max_num'] );

		$target_event_id = $google_event_id;

		$description = '定員：'.$max_num.'<br/>';

		$description .= $this->get_google_desc( $args );

		$service = new Google_Service_Calendar($client);

		//カレンダーのIDを取得
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );
		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
		$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );
		$calendar_id = $google_client_new_settings['google_calendar_id'];

		//アカウントを変更した場合、今まで使っていたカレンダーIDを見つけられなくなるためエラーが出る。
		try {

			$event = $service->events->get( $calendar_id, $target_event_id );
		} catch ( Exception $ex ) {
			//エラーが出た時は何もせずに処理を返す。
			return;
		}


		$event->setDescription( $description );

		try {

			$updatedEvent = $service->events->update( $calendar_id, $event->getId(), $event );
		} catch ( Exception $ex ) {
			//エラーが出た時は何もせずに処理を返す。
			return;
		}

	}


	//参加ユーザーの詳細を送る。
	public function get_google_desc( $args )
	{
		$date_id = $this->basic->delete_space( $args['date_id'] );
		$schedule_task_id = $this->basic->delete_space( $args['schedule_id'] );
		$the_month = $this->basic->delete_space( $args['the_month'] );

		//スケジュールの詳細取得
		$option =  $this->wpfunc->get_option(SLUGNAME.'_register_schedule_'.$the_month);

		//ユーザーID一覧取得
		$user_ids = $option[$date_id]['user_id'][$schedule_task_id];
		//NULLチェック。
		$user_ids = $this->basic->null_check_arr( $user_ids, true );

		$description = '';

		//取得したユーザーを登録していく。
		foreach ($user_ids as $book_num_key => $book_user_id){
			//データ取得
			$book_user_data = $this->wpfunc->get_userdata( intval( $book_user_id ) );
			$book_user_front_data = $this->wpfunc->get_option( $schedule_task_id.'_'.$book_user_data->ID, '' );

			if( $book_user_front_data !== '' ){
				//わかりやすいように変数に格納
				$book_user_ID = $book_user_data->ID;
				$book_user_name = $book_user_data->display_name;
				$book_user_time = $book_user_front_data['date_time1'].'~'.$book_user_front_data['date_time2'];

				$description .= '参加ユーザー：'.$book_user_name.' '.$book_user_time.'<br/>';
			}

		}

		return $description;
	}

	public function delete_google_setting()
	{
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );

		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
		$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );

		$token = $this->wpfunc->get_option( 'guild_press_google_tokens' );

		$this->wpfunc->delete_option( SLUGNAME.'_google_calendar_settings' );
		$this->wpfunc->delete_option( $guild_press_google_client_id );
		$this->wpfunc->delete_option( 'guild_press_google_tokens' );

		$this->admin_url = $this->wpfunc->admin_url().'admin.php?page=guild_press_google_calendar_settings&tab=google_calendar_settings&auth=delete';
		wp_redirect($this->admin_url);
		exit();

	}

	//イベントアップデートテスト
	public function admin_edit_google_calendar( $task_id, $save_data )
	{
		//クライアント情報取得
		$google_client = $this->get_google_cleint();

		$time = date_i18n('Y-m-d', intval($save_data['date_id']));

		//トークンをセット。
		$client = $this->set_google_token( $google_client );

		$title = $this->basic->delete_space( $save_data['title'] );
		$max_num = $this->basic->delete_space( $save_data['max_num'] );

		$date_time1 = $this->basic->delete_space( $save_data['date_time1'] );
		$date_time2 = $this->basic->delete_space( $save_data['date_time2'] );
		$start_date = $time.'T'.$date_time1.':00+09:00';
		$end_date = $time.'T'.$date_time2.':00+09:00';

		$target_event_id = $save_data['google_event_id'];

		//カレンダーのIDを取得
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );
		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
		$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );
		$calendar_id = $google_client_new_settings['google_calendar_id'];

		$description = '定員：'.$max_num.'<br/>';
		$description .= '参加ユーザー：';

		$service = new Google_Service_Calendar($client);

		//カレンダーのIDを取得
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );
		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
		$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );
		$calendar_id = $google_client_new_settings['google_calendar_id'];


		//アカウントを変更した場合、今まで使っていたカレンダーIDを見つけられなくなるためエラーが出る。
		try {

			$event = $service->events->get( $calendar_id, $target_event_id );
		} catch ( Exception $ex ) {
			//エラーが出た時は何もせずに処理を返す。
			return;
		}


		$event->setSummary( $title );
		$event->setDescription( $description );

		$start = new Google_Service_Calendar_EventDateTime();
		$start->setDateTime( $start_date );
		$event->setStart( $start );

		$end = new Google_Service_Calendar_EventDateTime();
		$end->setDateTime( $end_date );
		$event->setEnd( $end );

		try {

			$updatedEvent = $service->events->update( $calendar_id, $event->getId(), $event );
		} catch ( Exception $ex ) {
			//エラーが出た時は何もせずに処理を返す。
			return;
		}

	}

	//同期しているGoogleカレンダーに値を送る。
	public function admin_save_google_calendar( $detail_schedule_key )
	{
		$timezone_string = get_option( 'timezone_string' );

		//クライアント情報取得
		$google_client = $this->get_google_cleint();

		//トークンをセット。
		$client = $this->set_google_token( $google_client );

		$service = new Google_Service_Calendar($client);

		//セットするデータ作成
		$time = date_i18n('Y-m-d', intval($_POST['date_id']));
		$title = $this->basic->delete_space( $_POST['title'] );
		$date_time1 = $this->basic->delete_space( $_POST['date_time1'] );
		$date_time2 = $this->basic->delete_space( $_POST['date_time2'] );
		$max_num = $this->basic->delete_space( $_POST['max_num'] );
		$start_date = $time.'T'.$date_time1.':00+09:00';
		$end_date = $time.'T'.$date_time2.':00+09:00';

		//カレンダーのIDを取得
		$google_settings = $this->wpfunc->get_option( SLUGNAME.'_google_calendar_settings' );
		$guild_press_google_client_id = strstr($google_settings['guild_press_google_client_id'], '.apps', true  );
		$google_client_new_settings = $this->wpfunc->get_option( $guild_press_google_client_id );
		$calendar_id = $google_client_new_settings['google_calendar_id'];

		//Googleカレンダーに送るデータ作成。
		$event = new Google_Service_Calendar_Event(array(
			'summary' => $title,
			'description' => '定員：'.$max_num.'人',
			'start' => array(
				'dateTime' => $start_date,
				'timeZone' => $timezone_string,
			),
			'end' => array(
				'dateTime' => $end_date,
				'timeZone' => $timezone_string,
			),
		));


		//アカウントを変更した場合、今まで使っていたカレンダーIDを見つけられなくなるためエラーが出る。
		try {

			//作成したデータをGoogleのイベントに入れる。
			$event = $service->events->insert( $calendar_id, $event );
		} catch ( Exception $ex ) {
			//エラーが出た時は何もせずに処理を返す。
			return;
		}


		//作成したイベントでIDが返ってくるので、そのIDをセットする。
		$shedule_data = $this->wpfunc->get_option( $detail_schedule_key );
		$shedule_data['google_event_id'] = $event['id'];

		//IDを紐付けする。
		$this->wpfunc->update_option( $detail_schedule_key, $shedule_data );
	}


}
