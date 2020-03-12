<?php
/**
*
*/
class GoogleCalendarSettings
{

	public function __construct()
	{

		$this->load();
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_google_calendar_settings&tab=google_calendar_settings';
		$this->option_name = SLUGNAME.'_google_calendar_settings';
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->google_model = new GoogleCalendarModel();

	}

	public function load()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'get_action' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_google_clendar_js' ) );

		//authの値がセットした場合は各メッセージをユーザーに表示
		if( isset( $_GET['auth'] ) &&  $_GET['auth'] !== '' ){

			$this->show_message( $_GET['auth'] );
		}

	}

	public function add_google_clendar_js()
	{
		$js_url = get_template_directory_uri() . '/assets/plugins/google_calendar/lib/js/guild_press_google_calendar.js';

		wp_enqueue_script( 'guild_press_google_calendar', $js_url, "", "20181001", false );
	}


	public function show_message($get_val)
	{

		switch ($get_val) {
			case 'success':
				add_action( 'admin_notices', array( $this, 'add_auth_success_notice' ) );
			break;

			case 'error':
				add_action( 'admin_notices', array( $this, 'add_auth_error_notice' ) );
			break;

			case 'delete':
				add_action( 'admin_notices', array( $this, 'add_delete_error_notice' ) );
			break;

			case 'google_error':
				add_action( 'admin_notices', array( $this, 'add_google_error_notice' ) );
			break;

		}

	}

	public function add_delete_error_notice() {

	    ?>
	    <div class="updated notice">
	        <p><?php _e( '設定の削除が完了しました。'); ?></p>
	    </div>
	    <?php
	}

	public function add_auth_success_notice() {

		$error_codes = $this->wpfunc->get_option( 'guild_press_google_tokens_error' );
	    ?>
	    <div class="updated notice">
	        <p><?php _e( '認証が完了しました。'); ?></p>
	    </div>
	    <?php
	}

	public function add_auth_error_notice() {

		$error_codes = $this->wpfunc->get_option( 'guild_press_google_tokens_error' );
	    ?>
	    <div class="error notice">
	        <p><?php _e( 'エラーがでました。エラーの内容: '.$error_codes['error'].' and '.$error_codes['error_description'] ); ?></p>
	    </div>
	    <?php
	}

	public function add_google_error_notice() {

		$error_codes = $this->wpfunc->get_option( 'guild_press_google_tokens_error' );
	    ?>
	    <div class="error notice">
	        <p><?php _e( 'エラーがでました。エラーの内容: '.$error_codes['error'].' and '.$error_codes['error_description'] ); ?></p>
	    </div>
	    <?php
	}


	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		if( isset( $_GET['tab'] ) && $_GET['tab'] === 'google_calendar_settings' && is_string( $_GET['tab'] ) ){

			add_submenu_page(
				SLUGNAME.'_basic_setting',
				'Googleカレンダー設定',
				'',
				'administrator',
				SLUGNAME.'_google_calendar_settings',
				array( $this, SLUGNAME.'_settings_page' )
			);

			add_meta_box(
				SLUGNAME.'_google_calendar_settings_box',
				'スケジュール登録画面',
				array( $this, 'google_calendar_settings_box' ),
				SLUGNAME.'_google_calendar_settings',
				'normal'
			);

		}

	}

	public function google_calendar_settings_box()
	{

		$google_tokens = $this->wpfunc->get_option( 'guild_press_google_tokens' );
		$refresh_token = ( isset($google_tokens['refresh_token']) && $google_tokens['refresh_token'] !== "" ) ? $google_tokens['refresh_token'] : '';


		$google_api_options = get_option( $this->option_name );
		$home_url = $this->wpfunc->home_url();

		$guild_press_google_client_secret = ( isset($google_api_options['guild_press_google_client_secret']) && $google_api_options['guild_press_google_client_secret'] !== "" ) ? $google_api_options['guild_press_google_client_secret'] : '';

		$guild_press_google_client_id = ( isset($google_api_options['guild_press_google_client_id']) && $google_api_options['guild_press_google_client_id'] !== "" ) ? $google_api_options['guild_press_google_client_id'] : '';


		?>
		<p class="description">ここに項目を設定することで、予約カレンダーで登録したものをGoogleカレンダーで確認できるようになります。</p>
			<table class="form-table">
					<tr>
						<th scope="row">
							<label for="<?php echo SLUGNAME; ?>_google_client_id"><?php _e( 'クライアントID' ); ?></label>
						</th>
						<td>
							<input name="<?php echo SLUGNAME; ?>_google_client_id" type="text" id="<?php echo SLUGNAME; ?>_google_client_id" value="<?php echo $this->wpfunc->esc_html($guild_press_google_client_id); ?>" class="large-text" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="secret"><?php _e( 'クライアントシークレット' ); ?></label>
						</th>
						<td>
							<input name="<?php echo SLUGNAME; ?>_google_client_secret" type="text" id="<?php echo SLUGNAME; ?>_google_client_secret" value="<?php echo $this->wpfunc->esc_html($guild_press_google_client_secret); ?>" class="large-text" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo SLUGNAME; ?>_personal_from_name"><?php _e( 'リダイレクトURL' ); ?></label>
						</th>
						<td>
							<p class=""><?php echo $home_url.'/guild-press-google-auth/'; ?></p>
							<p class="description">Googleの設定画面で入力するURLです。</p>
							<p class="description">「承認済みのリダイレクト URI」の項目に入力するようにしてください。</p>
							<input name="<?php echo SLUGNAME; ?>_google_redirect_url" type="hidden" id="<?php echo SLUGNAME; ?>_google_redirect_url" value="<?php echo $home_url.'/guild-press-google-auth/'; ?>" class="large-text" />
						</td>
					</tr>
					<?php if( $guild_press_google_client_secret !== '' && $guild_press_google_client_id !== '' ) : ?>
					<tr>
						<th scope="row">
							<button type="button" id="google_auth_btn">Googleの認証を行う</button>
						</th>
						<td>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<button type="button" id="delete_google_setting">認証設定を削除する</button>
						</th>
						<td>
						</td>
					</tr>
					<?php endif; ?>

				</table>
				<input type="hidden" name="admin_action" value="add_google_calendar_settings" />
		<?php
			if( $guild_press_google_client_secret !== '' && $guild_press_google_client_id !== '' ){

				add_action( 'admin_footer', array( $this, 'delete_google_setting_js' ) );
			}
			if( $refresh_token !== '' ){

				add_action( 'admin_footer', array( $this, 'check_google_auth_js' ) );
			}else{

				add_action( 'admin_footer', array( $this, 'google_auth_js' ) );
			}

	}
	//googleのAuth処理を行うためのJS
	public function delete_google_setting_js()
	{
		?>
		<script type="text/javascript">

			auth_google_js.delete_google_auth_setting();
		</script>
		<?php
	}

	//googleのAuth処理を行うためのJS
	public function check_google_auth_js()
	{
		?>
		<script type="text/javascript">

			auth_google_js.check_auth_google_action();
		</script>
		<?php
	}

	//googleのAuth処理を行うためのJS
	public function google_auth_js()
	{

		?>
		<script type="text/javascript">

			auth_google_js.auth_google_action();
		</script>
		<?php
	}
	//各メタボックスの内容を分岐している処理。
	public function guild_press_settings_page() {

		$metaboxName = ( ! isset( $_GET['tab'] ) || ! is_string( $_GET['tab'] ) ) ? 'general' : $_GET['tab'];
		$metabox = $this->get_metabox_name( $metaboxName );

		if( $metaboxName === 'google_calendar_settings' ){
			$action_url = "options.php";
		}else{
			$action_url = $this->admin_url;
		}

		?>
		<div class="wrap">
			<h2><?php echo esc_html( 'GuildPress' ); ?></h2>

			<?php $this->admin_menu_tab( $_GET['tab'] );  ?>

			<?php settings_errors(); ?>

			<form method="post" action="<?php echo $this->wpfunc->esc_url($action_url); ?>" name="form">

				<?php

				if( $metaboxName === 'google_calendar_settings' ){

					settings_fields( SLUGNAME.'_google_calendar_settings' );
					wp_nonce_field( SLUGNAME.'_google_calendar_settings', 'google_calenda', false );
					wp_nonce_field( 'notification', 'notification-nonce', false );

					?>
					<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

						<?php do_meta_boxes( $metabox, 'normal', null ); ?>
					</div>

					<?php submit_button('', 'primary', 'set'); ?>
					<?php

				}else{

					settings_fields( SLUGNAME.'_general_settings' );
					wp_nonce_field( SLUGNAME.'_reserved_calender', 'reserved_calender', false );
					wp_nonce_field( 'notification', 'notification-nonce', false );

					?>
					<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

						<?php do_meta_boxes( $metabox, 'normal', null ); ?>

					</div>

					<?php

				}

				?>

			</form>
		</div>
		<?php
	}

	public function admin_menu_tab( $active = '' ){

		?>
		<div id="guild_press_tabs" class="nav-tab-wrapper">
			<a class="nav-tab <?php if( $active == 'general' || $active == '' ) { ?> nav-tab-active <?php } ?>" href="?page=guild_press_basic_setting&tab=general">
				一般設定
			</a>
			<a class="nav-tab <?php if( $active == 'google_calendar_settings' ) { ?> nav-tab-active <?php } ?>" href="?page=guild_press_google_calendar_settings&tab=google_calendar_settings">
				Googleカレンダー設定
			</a>
		</div>
		<?php

	}

	public function get_metabox_name( $tab='' ){

		if( $tab === "google_calendar_settings" ){

			return SLUGNAME.'_google_calendar_settings';
		}else{

			return SLUGNAME.'_settings';
		}

	}

	//各管理画面でポストされたアクションを受け取る。
	public function get_action(){

		$admin_action = ( isset($_POST["admin_action"]) && $_POST["admin_action"] !== "" ) ? $_POST["admin_action"] : '';

		if( $admin_action === '' ){

			return;
		}

		$notification_nonce  = ( isset($_POST["notification-nonce"] ) && $_POST["notification-nonce"]  !== "" ) ? $_POST["notification-nonce"]  : '';

		if( $notification_nonce === '' && ! wp_verify_nonce( $_POST['notification-nonce'], 'notification' ) ) {

			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$this->action = ( isset( $_POST["admin_action"] ) ) ? trim( $_POST["admin_action"] ) : '';

		$this->action_check( $this->action );
	}

	//各管理画面でポストされたアクションで処理を分ける。
	public function action_check( $action='' ){

		if( ! $action || ! isset( $action ) || ! is_string( $action ) ){
			return;
		}

		switch ($action) {

			case 'add_google_calendar_settings':
			$this->save_google_api_settings();
			break;

			case 'auth_google':
			$this->google_model->auth_google();
			break;

			case 'delete_google':
			$this->google_model->delete_google_setting();
			break;
		}
	}

	public function save_google_api_settings(){

		if( ! wp_verify_nonce( $_POST['google_calenda'], SLUGNAME.'_google_calendar_settings' ) && !isset( $_POST["google_calenda"] ) ) {

			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$result = $this->wpfunc->register_setting( SLUGNAME.'_google_calendar_settings', SLUGNAME.'_google_calendar_settings', array( $this, 'create_save_settings' ) );

	}

	public function create_save_settings(){

		foreach ($_POST as $key => $value) {
			$settings[ $key ] = ( ! $_POST[ $key ] || ! isset( $_POST[ $key ]  ) || ! is_string( $_POST[ $key ] ) )  ? "" : $this->basic->delete_space( $_POST[ $key ] );
		}

		return $settings;
	}
}
