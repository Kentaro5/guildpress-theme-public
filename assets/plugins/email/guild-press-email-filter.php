<?php
/**
 * 
 */
class Guild_Press_Email_Filter
{
	public $guild_press_notification_from;
	public $guild_press_notification_from_name;
	public $guild_press_notification_content_type;

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		//フィルター
		add_filter( 'guild_press_message_filter',       array( $this, 'guild_press_message_filter_func' ) , 10, 3 );

		add_filter( 'guild_press_title_filter',       array( $this, 'guild_press_title_filter_func' ) , 10, 3 );

		add_filter( 'guild_press_set_email_header',       array( $this, 'guild_press_set_email_header_func' ) , 10, 3 );

		add_filter( 'wp_mail_from',          array( $this,  'guild_press_mail_from_filter' ) );

		add_filter( 'wp_mail_from_name',     array( $this,  'guild_press_mail_from_name_filter' ) );

		add_filter( 'wp_mail_content_type',  array( $this,  'mail_content_type_filter' ) );

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
	}

	public function guild_press_message_filter_func( $message, $type_name, $user_id ){

		$schedule_email_settings = $this->wpfunc->get_option(SLUGNAME.'_schedule_email_settings');

			//type_nameでアドミンかユーザー側か判断。
		$_message = ( isset($schedule_email_settings[SLUGNAME.'_'.$type_name.'_email_message']) && $schedule_email_settings[SLUGNAME.'_'.$type_name.'_email_message'] !== "" ) ? $schedule_email_settings[SLUGNAME.'_'.$type_name.'_email_message'] : '';


			//管理画面で何も入力されていなかったらデフォルトのメッセージを返す。
		if ( ! empty( $_message ) ) {


			$user = get_userdata( $user_id );

			$message = $this->guild_press_replace_special_chars( $_message, $user_id);

		}


		return $message;
	}

	public function guild_press_title_filter_func( $title, $type_name,$user_id ) {

		$schedule_email_settings = $this->wpfunc->get_option(SLUGNAME.'_schedule_email_settings');

		$_title = ( isset($schedule_email_settings['guild_press_'.$type_name.'_from_subject']) && $schedule_email_settings['guild_press_'.$type_name.'_from_subject'] !== "" ) ? $schedule_email_settings['guild_press_'.$type_name.'_from_subject'] : '';


		return empty( $_title ) ? $title : $this->guild_press_replace_special_chars( $_title, $user_id );
	}

	public function guild_press_set_email_header_func( $input_name ) {

			//option_nameで指定されたオプションを取得
		$options = $this->wpfunc->get_option(SLUGNAME.'_schedule_email_settings');

		$from_email = ( isset($options['salon_'.$input_name.'_from_email']) && $options['salon_'.$input_name.'_from_email'] !== "" ) ? $options['salon_'.$input_name.'_from_email'] : '';

		$from_name = ( isset($options['salon_'.$input_name.'_from_name']) && $options['salon_'.$input_name.'_from_name'] !== "" ) ? $options['salon_'.$input_name.'_from_name'] : '';

		$type = ( isset($options[$input_name.'type']) && $options[$input_name.'type'] !== "" ) ? $options[$input_name.'type'] : '';

		// We'll cheat and set out headers here
		$result = $this->set_mail_headers(

			$from_email,
			$from_name,
			$type

		);

		return true;
	}

	public function set_mail_headers( $mail_from = '', $mail_from_name = '', $mail_content_type = 'text' ) {


		if( ! isset( $mail_from ) || ! isset( $mail_from_name ) ){
			return false;
		}

			//各変数に指定されたメールのヘッダーを入れる。
		$this->guild_press_notification_from         = $mail_from;
		$this->guild_press_notification_from_name    = $mail_from_name;
		$this->guild_press_notification_content_type = $mail_content_type;

		return true;

	}


	public function guild_press_mail_from_filter( $from_email ) {
			//このクラスで定義しておいたmail_fromがNULLなら、デフォルトのワードプレスのメールの値を返す。
			//Trueの場合は管理画面で定義されたFromMailを返す。
		return empty( $this->guild_press_notification_from ) ? $from_email : $guild_press_notification_from;
	}

	public function guild_press_mail_from_name_filter( $from_name ) {
			//このクラスで定義しておいたmail_from_nameがNULLなら、デフォルトのワードプレスのメールの値を返す。
			//Trueの場合は管理画面で定義されたMail_From_Nameを返す。
		return empty( $this->guild_press_notification_from_name ) ? $from_name : $this->guild_press_notification_from_name;
	}


	public function mail_content_type_filter( $content_type ) {
			//このクラスで定義しておいたmail_content_typeがNULLなら、デフォルトのワードプレスのメールの値を返す。
			//Trueの場合は管理画面で定義されたmail_content_typeを返す。
		return empty( $this->guild_press_notification_content_type ) ? $content_type : 'text/' . $this->guild_press_notification_content_type;
	}

	//各メッセージの中の％％のやつを入れ替える。
	public function guild_press_replace_special_chars( $input, $user_id = '', $replacements = array() ) {
		$defaults = array(
			'%site_url%' => get_bloginfo( 'url' ),
			'%user_ip%'  => $_SERVER['REMOTE_ADDR']
		);

		$replacements = wp_parse_args( $replacements, $defaults );


			// Get user data
		$user = false;
		if ( $user_id ){

			$user = $this->wpfunc->get_user_by( 'id', $user_id );

		}

		$user_meta_data =  $this->wpfunc->get_user_meta($user_id);

			//特殊文字を変換したものを取得
		$replacements = $this->return_replacements( $input );

			// Allow replacements to be filtered
		$replacements = apply_filters( 'tml_replace_vars', $replacements, $user_id );

		if ( empty( $replacements ) )
			return $input;

			// Get search values
		$search = array_keys( $replacements );

			// Get replacement values
		$replace = array_values( $replacements );

		return str_replace( $search, $replace, $input );
	}

	//特殊文字を変換したものを返す処理
	public function return_replacements( $input )
	{

		if( $input === '' ){

			return;
		}
		// Get all matches ($matches[0] will be '%value%'; $matches[1] will be 'value')
		preg_match_all( '/%([a-zA-Z0-9-_]*)%/', $input, $matches );

		// Iterate through matches
		foreach ( $matches[0] as $key => $match ) {
			if ( ! isset( $replacements[$match] ) ) {

				if ( $user && isset( $user->{$matches[1][$key]} ) ){
					// Replacement from WP_User object
					$replacements[$match] = $user->{$matches[1][$key]};
				}
					//これ以外の場合は値がおかしくなるので、そのままスルー
				if(preg_match('/blogname/', $matches[1][$key])){

						//サイトのタイトル取得
					$replacements[$match] = get_bloginfo( 'name' );

				}elseif(preg_match('/siteurl/', $matches[1][$key])){

						//サイトのURL取得
					$replacements[$match] = get_bloginfo( $matches[1][$key] );

				}elseif(preg_match('/username/', $matches[1][$key])){

						//ユーザーネームをメッセージの中に入れる。
					$replacements[$match] = $user_meta['last_name'][0].$user_meta['first_name'][0];

				}elseif(preg_match('/account_url/', $matches[1][$key])){

						//アカウントページリンク取得
					if( isset( $page['account'] ) && $page['account'] !== "" ){

						$replacements[$match] = $this->wpfunc->get_page_link($page['account']);

					}

				}elseif(preg_match('/login_url/', $matches[1][$key])){

						// ログインページリンク取得
					if( isset( $page['login'] ) && $page['login'] !== "" ){

						$replacements[$match] = $this->wpfunc->get_page_link($page['login']);
					}

				}elseif(preg_match('/new_register_url/', $matches[1][$key])){

						// 新規登録ページ取得
					if( isset( $page['register'] ) && $page['register'] !== "" ){

						$replacements[$match] = $this->wpfunc->get_page_link($page['register']);
					}

				}elseif(preg_match('/user_emial/', $matches[1][$key])){

						//ユーザーネームをメッセージの中に入れる。
					$user_data =  $this->wpfunc->get_userdata( $user_id );
					$replacements[$match] = $user_data->user_email;

				}
			}
		}

		return $replacements;
	}
}