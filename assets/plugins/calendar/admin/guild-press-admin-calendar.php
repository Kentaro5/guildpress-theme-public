<?php
require(  TEMP_DIR . '/assets/plugins/calendar/admin/calendar_email/guild-press-calendar-email.php' );
require(  TEMP_DIR . '/assets/plugins/calendar/admin/calendar_list/guild-press-calendar-list.php' );
require(  TEMP_DIR . '/assets/plugins/calendar/admin/calendar_register/guild-press-calendar-register.php' );
require(  TEMP_DIR . '/assets/plugins/calendar/admin/calendar_edit/guild-press-calendar-edit.php' );
require(  TEMP_DIR . '/assets/plugins/calendar/admin/calendar_metabox/guild-press-calendar-metabox.php' );


/**
 *
 */
class Guild_Press_Admin_Calendar
{
	public $calendar_email_func;
	public $calendar_list_func;
	public $calendar_register_func;
	public $calendar_edit_func;
	public $calendar_metabox_func;
	public $wpfunc;
	public $basic;
	public $email;

	public function __construct()
	{

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;
		$this->calendar_email_func = new Guild_Press_Calendar_Email();
		$this->calendar_list_func = new Guild_Press_Calendar_List();
		$this->calendar_register_func = new Guild_Press_Calendar_Register();
		$this->calendar_edit_func = new Guild_Press_Calendar_Edit();
		$this->calendar_metabox_func = new Guild_Press_Calendar_Metabox();

		$this->load();
	}

	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'get_action' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'create_ajax_url' ), 10 );

		$this->calendar_menu_path = 'templates/admin/calendar/menu/calendar-menu.php';
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';

	}

	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		add_submenu_page(
			SLUGNAME.'_basic_setting',
			'予約一覧',
			'予約一覧',
			'administrator',
			SLUGNAME.'_settings',
			array( $this, SLUGNAME.'_settings_page' )
		);

		if( isset( $_GET['tab'] ) && is_string( $_GET['tab'] ) ){

			$this->get_menu_tab_metabox( $_GET['tab'] );

		}else{
			add_meta_box(
				SLUGNAME.'_reserved_metabox',
				'予約一覧',
				array( $this->calendar_list_func, 'calendar_list_box' ),
				SLUGNAME.'_settings',
				'normal'
			);
		}


	}

	public function get_menu_tab_metabox( $tab_name='' )
	{
		switch ($tab_name) {
			case 'register_schedule':
			add_submenu_page(
				SLUGNAME.'_basic_setting',
				'スケジュール登録',
				'',
				'administrator',
				SLUGNAME.'_register_schedule',
				array( $this, SLUGNAME.'_settings_page' )
			);

			add_meta_box(
				SLUGNAME.'_register_schedule_box',
				'スケジュール登録画面',
				array( $this->calendar_register_func, 'register_shcedule_box' ),
				SLUGNAME.'_register_schedule',
				'normal'
			);
			break;

			case 'register_schedule_list':
			add_submenu_page(
				SLUGNAME.'_basic_setting',
				'スケジュール登録一覧',
				'',
				'administrator',
				SLUGNAME.'_register_schedule_list',
					array( $this, SLUGNAME.'_settings_page' )//f
				);

			add_meta_box(
				SLUGNAME.'register_schedule_list_box',
				'スケジュール登録一覧画面',
				array( $this->calendar_register_func, 'register_schedule_list_box' ),
				SLUGNAME.'_register_schedule_list',
				'normal'
			);
			break;

			case 'edit_schedule':
			add_submenu_page(
				SLUGNAME.'_basic_setting',
				'スケジュール編集',
				'',
				'administrator',
				SLUGNAME.'_edit_schedule',
					array( $this, SLUGNAME.'_settings_page' )//f
				);

			add_meta_box(
				SLUGNAME.'_edit_schedule_box',
				'スケジュール編集画面',
				array( $this->calendar_edit_func, 'edit_shcedule_box' ),
				SLUGNAME.'_edit_schedule',
				'normal'
			);
			break;

			case 'schedule_email_settings':
			add_submenu_page(
				SLUGNAME.'_basic_setting',
				'カレンダー登録時メール設定',
				'',
				'administrator',
				SLUGNAME.'_schedule_email_settings',
				array( $this, SLUGNAME.'_settings_page' )
			);

			add_meta_box(
				SLUGNAME.'_schedule_email_settings_box',
				'カレンダー登録時メール設定画面',
				array( $this->calendar_email_func, 'schedule_email_settings_box' ),
				SLUGNAME.'_schedule_email_settings',
				'normal'
			);
			break;

			case 'general':
			add_meta_box(
				SLUGNAME.'_reserved_metabox',
				'予約一覧',
				array( $this->calendar_list_func, 'calendar_list_box' ),
				SLUGNAME.'_settings',
				'normal'
			);
			break;
		}
	}

	public function admin_menu_tab( $active = '' ){

		$file_path = $this->wpfunc->locate_template( $this->calendar_menu_path, false );

		if( !file_exists($file_path) ){
			return;
		}

		include( $file_path );

	}



	/******************************************AJAX**********************************/
	//javascirpt内でajaxurでajaxのURLが取得できるようにするための関数
	public function create_ajax_url(){
		?>
		<script>
			let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
			let security = '<?php echo wp_create_nonce( SLUGNAME."_delete_schedule" ) ?>';
		</script>
		<?php

	}
	//なぜか動かないので、settings.phpへ
	/******************************************AJAX**********************************/
	//各メタボックスの内容を分岐している処理。
	public function guild_press_settings_page() {

		$metaboxName = ( ! isset( $_GET['tab'] ) || ! is_string( $_GET['tab'] ) ) ? 'general' : $_GET['tab'];
		$metabox = $this->get_metabox_name( $metaboxName );

		if( $metaboxName === 'schedule_email_settings' ){

			$action_url = "options.php";
		}else{

			$action_url = $this->admin_url;
		}

		?>
		<div class="wrap">
			<h2><?php echo esc_html( 'GuildPress' ); ?></h2>

			<?php $this->admin_menu_tab( $metaboxName ); ?>

			<?php settings_errors(); ?>

			<form method="post" action="<?php echo $this->wpfunc->esc_url($action_url); ?>" name="form">

				<?php $this->calendar_metabox_func->get_metabox_by_metabox_name( $metaboxName, $metabox ); ?>

			</form>
		</div>
		<?php
	}


	//deleteする時に使用するAjaxです。
	public function setAjaxFunc()
	{

		?>
		<script>
			function delete_schedule(date_id,delete_option_id){

				let form = document.form.getElementsByTagName("input");
				let google_event_id = form.google_event_id.value;

				let gp_month_val = form.gp_month.value;
				let gp_year_val = form.gp_year.value;

				let load_animation = document.getElementById("loadingAnim");
				load_animation.style.display = 'block';

				jQuery.ajax({
					type: 'POST',
					url: ajaxUrl,
					data: {
						'delete_option_id': delete_option_id,
						'date_id': date_id,
						'gp_month': gp_month_val,
						'gp_year': gp_year_val,
						'google_event_id': google_event_id,
						'action' : 'guild_press_delete_schedule_action',
						'security': security,
					},

					success: function( response ){

						if(response === 'success'){

							load_animation.style.display = 'none';
							alert("登録したスケジュールを削除しました。");
									//location.reload();
									location.href='<?php echo $this->admin_url; ?>'
								}else{
									console.log("response:"+response)
									alert("不具合がおきました。");
								}
							}
						});
				return false;
			};
		</script>
		<?php

	}

	//各管理画面でポストされたアクションを受け取る。
	public function get_action(){

		$_POST["admin_action"] = ( isset($_POST["admin_action"]) && $_POST["admin_action"] !== "" ) ? $_POST["admin_action"] : '';

		if( $_POST["admin_action"] === '' ){

			return;
		}

		$_POST["notification-nonce"] = ( isset($_POST["notification-nonce"]) && $_POST["notification-nonce"] !== "" ) ? $_POST["notification-nonce"] : '';

		if( $_POST["notification-nonce"] === '' && ! wp_verify_nonce( $_POST['notification-nonce'], 'notification' ) ) {

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

			case 'register_new_schedule':
			$this->calendar_register_func->save_new_scheule();
			break;

			case 'edit_new_schedule':
			$this->calendar_edit_func->edit_new_scheule();
			break;

			case 'add_email_settings':
			$this->calendar_email_func->save_settings();
			break;

		}
	}

	public function get_metabox_name( $tab='' ){

		return $this->calendar_metabox_func->get_metabox_name( $tab );

	}
}












