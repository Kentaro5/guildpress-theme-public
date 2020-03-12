<?php
require_once( TEMP_DIR . '/assets/plugins/user_progress/admin/user_list/guild-press-user-list.php' );
/**
*
*/
class Guild_Press_User_Progress
{


	public $menu_box_path;
	public $user_id;
	public $user_lesson_progress;
	public $profile_link;


	public function __construct()
	{
		$this->load();
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->user_list = new Guild_Press_User_List;
		$this->menu_box_path =  'templates/admin/user_progress/menu/menu.php';

	}

	public function load()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		add_submenu_page(
			SLUGNAME.'_basic_setting',
			'顧客進捗状況',
			'顧客進捗状況',
			'administrator',
			SLUGNAME.'_manage_customer',
					array( $this, SLUGNAME.'_manage_setting_page' )//f
				);

		add_meta_box(
			SLUGNAME.'_manage_customer_box',
			'顧客進捗状況',
			array( $this, 'manage_customer_box' ),
			SLUGNAME.'_manage_customer',
			'normal'
		);

	}

	public function manage_customer_box()
	{
		$this->user_list->get_customer_info();
	}

	public function admin_menu_tab( $active = '' ){

		if( ! $file_path = $this->basic->load_template( $this->menu_box_path, false ) ){

            return;
        }

        include( $file_path );

	}

	//各タブに合わせてsettingにセットする名前を返す。
	public function get_metabox_name( $tab='' ){

		if( $tab === "general" ){

			return SLUGNAME.'_manage_customer';

		}else{
			return SLUGNAME.'_manage_customer';
		}

		return SLUGNAME.'_settings';
	}

	public function guild_press_manage_setting_page()
	{
		$metaboxName = ( ! isset( $_GET['tab'] ) || ! is_string( $_GET['tab'] ) ) ? 'general' : $_GET['tab'];
		$metabox = $this->get_metabox_name( $metaboxName );

		if( $metaboxName === 'schedule_email_settings' ){
			$action_url = "options.php";
		}else{
			$action_url = $this->admin_url;
		}

		?>
		<div class="wrap">
			<h2><?php echo esc_html( '顧客進捗状況' ); ?></h2>

			<?php $this->admin_menu_tab( $metaboxName ); ?>

			<?php settings_errors(); ?>

			<form method="post" action="<?php echo $this->wpfunc->esc_url($action_url); ?>" name="form">

				<?php

				if( $metaboxName === 'general' ){

					settings_fields( SLUGNAME.'_manage_customer' );
					wp_nonce_field( SLUGNAME.'_manage_customer', 'general', false );
					wp_nonce_field( 'notification', 'notification-nonce', false );
					?>
					<div id="<?php echo SLUGNAME.'_manage_customer'; ?>" class="metabox-holder">

						<?php do_meta_boxes( $metabox, 'normal', null ); ?>

					</div>

					<?php

				}
				?>

			</form>
		</div>
		<?php
	}




}



