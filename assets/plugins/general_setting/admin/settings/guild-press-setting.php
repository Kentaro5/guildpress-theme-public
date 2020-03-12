<?php

/**
 *
 */
class Guild_Press_Setting
{

	public $guild_press_setting_link = 'admin.php?page=guild_press_setting';
	public $general_metabox_path;
	public $general_metabox_menu_path;
	public function __construct()
	{
		$this->load();
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;

		$this->option_name = SLUGNAME.'_basic_setting';

		$this->general_metabox_path = 'templates/admin/general_setting/metabox/general-setting.php';
		$this->general_metabox_menu_path = 'templates/admin/general_setting/menu/menu.php';
	}

	public function load()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'get_action' ) );

	}

	//各管理画面でポストされたアクションを受け取る。
	public function get_action(){

		$_POST["admin_action"] = ( isset($_POST["admin_action"]) && $_POST["admin_action"] !== "" ) ? $_POST["admin_action"] : '';

		if( $_POST["admin_action"] === '' ){
			return;
		}

		 $_POST["setting-notification-nonce"] = ( isset( $_POST["setting-notification-nonce"]) &&  $_POST["setting-notification-nonce"] !== "" ) ?  $_POST["setting-notification-nonce"] : '';

		if( $_POST["setting-notification-nonce"] === '' && ! wp_verify_nonce( $_POST['setting-notification-nonce'], 'setting-notification' ) ) {
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

			case 'guildpresssavesetting':
			$this->save_settings();
			break;

		}
	}

	public function save_settings(){

		if( ! wp_verify_nonce( $_POST['setting-notification-nonce'], 'setting-notification' ) && !isset( $_POST["setting-notification-nonce"] ) ) {
			return;
		}

		if( ! wp_verify_nonce( $_POST['basic_setting'], SLUGNAME.'_setting' ) && !isset( $_POST['basic_setting'] ) ){

			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$result = $this->wpfunc->register_setting( SLUGNAME.'_basic_setting', SLUGNAME.'_basic_setting', array( $this, 'create_save_settings' ) );

	}

	public function create_save_settings(){


		foreach ($_POST as $key => $value) {
			$settings[ $key ] = ( ! $_POST[ $key ] || ! isset( $_POST[ $key ]  ) || ! is_string( $_POST[ $key ] ) )  ? "" : $_POST[ $key ];
		}

		return $settings;
	}

	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		add_menu_page(
			'GuildPress',
			'GuildPress',
			'administrator',
			SLUGNAME.'_basic_setting',
			array( $this, SLUGNAME.'_manage_setting_page' )//f
		);

		add_meta_box(
			SLUGNAME.'_setting_box',
			'GuildPress設定',
			array( $this, 'basic_setting_box' ),
			SLUGNAME.'_basic_setting',
			'normal'
		);

	}


	public function guild_press_manage_setting_page()
	{
		$metaboxName = ( ! isset( $_GET['tab'] ) || ! is_string( $_GET['tab'] ) ) ? 'general' : $_GET['tab'];
		$metabox = $this->get_metabox_name( $metaboxName );


		$action_url = "options.php";


		?>
		<div class="wrap">
			<h2><?php echo esc_html( 'GuildPress設定' ); ?></h2>

			<?php $this->admin_menu_tab( $metaboxName ); ?>

			<?php settings_errors(); ?>

			<form method="post" action="<?php echo $this->wpfunc->esc_url($action_url); ?>" name="form">

				<?php

				if( $metaboxName === 'general' ){

					settings_fields( SLUGNAME.'_basic_setting' );
					wp_nonce_field( SLUGNAME.'_basic_setting', 'basic_setting', false );
					wp_nonce_field( 'setting-notification', 'setting-notification-nonce', false );
					?>
					<div id="<?php echo SLUGNAME.'_basic_setting'; ?>" class="metabox-holder">

						<?php do_meta_boxes( $metabox, 'normal', null ); ?>

					</div>
					<?php submit_button(); ?>

					<?php
				}
				?>

			</form>
		</div>
		<?php
	}

	public function basic_setting_box()
	{

		$options = $this->wpfunc->get_option( $this->option_name, false );
		$pages   = $this->wpfunc->get_pages();

		$guild_press_check_admin_bar = ( isset($options['guild_press_check_admin_bar']) && $options['guild_press_check_admin_bar'] !== "" ) ? $options['guild_press_check_admin_bar'] : '';

		$gp_data = array(
			'setting_data' => $options,
			'page_lists' => $pages,
			'bar_check' => $guild_press_check_admin_bar,
		);

		if( ! $file_path = $this->basic->load_template( $this->general_metabox_path, false ) ){

			return;
		}

		include( $file_path );
	}

	public function admin_menu_tab( $active = '' ){

		$general_link = '?page=guild_press_basic_setting&tab=general';
		$google_calendar_link = '?page=guild_press_google_calendar_settings&tab=google_calendar_settings';

		$gp_data = array(
			'active' => $active,
			'general_link' => $general_link,
			'google_calendar_link' => $google_calendar_link,
		);
		if( ! $file_path = $this->basic->load_template( $this->general_metabox_menu_path, false ) ){

			return;
		}

		include( $file_path );

	}

	//各タブに合わせてsettingにセットする名前を返す。
	public function get_metabox_name( $tab='' ){

		if( $tab === "general" ){

			return SLUGNAME.'_basic_setting';

		}else{
			return SLUGNAME.'_basic_setting';
		}

	}

}