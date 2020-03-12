<?php
require(  TEMP_DIR . '/assets/plugins/original_form/admin/common/guild-press-original-form-common.php' );
require(  TEMP_DIR . '/assets/plugins/original_form/admin/edit_original_form/guild-press-edit-original-form.php' );
require(  TEMP_DIR . '/assets/plugins/original_form/admin/new_original_form/guild-press-new-original-form.php' );
require(  TEMP_DIR . '/assets/plugins/original_form/admin/edit/guild-press-original-form-edit.php' );
require(  TEMP_DIR . '/assets/plugins/original_form/admin/new/guild-press-original-form-new.php' );
require(  TEMP_DIR . '/assets/plugins/original_form/admin/normal/guild-press-original-form-normal.php' );


/**
*
*/
class Guild_Press_Original_Form
{

	public $guild_press_manage_customer_link = 'admin.php?page=guild_press_manage_customer';
	public $guild_press_edit_original_form;
	public $guild_press_new_original_form;
	public $guild_press_original_form_commmon;

	public function __construct()
	{
		$this->load();
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_regsiter_item_field';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;

		$this->guild_press_edit_original_form = new Guild_Press_Edit_Original_Form();
		$this->guild_press_new_original_form = new Guild_Press_New_Original_Form();
		$this->original_form_edit = new Guild_Press_Original_Form_Edit();
		$this->original_form_new = new Guild_Press_Original_Form_New();
		$this->original_form_normal = new Guild_Press_Original_Form_Nomal();

	}

	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'get_action' ) );

	}

		//各管理画面でポストされたアクションで処理を分ける。
	public function action_check( $action='' ){

		if( ! $action || ! isset( $action ) || ! is_string( $action ) ){
			return;
		}

		switch ($action) {

			case 'guildpressaddfield':
				$this->original_form_new->save_field_settings();
			break;

			case 'guildpresseditfield':
				$this->original_form_edit->save_field_settings();
			break;

			case 'guildpressnormal':
				$this->original_form_normal->save_field_settings();
			break;
		}
	}

	//各管理画面でポストされたアクションを受け取る。
	public function get_action(){

		$admin_action = ( isset($_POST["admin_action"]) && $_POST["admin_action"] !== "" ) ? $_POST["admin_action"] : '';

		if( $admin_action === '' ){
			return;
		}

		$_POST['item_field'] = ( isset($_POST["item_field"]) && $_POST["item_field"] !== "" ) ? $_POST["item_field"] : '';

		if( $_POST['item_field'] === '' && ! wp_verify_nonce( $_POST['item_field'], SLUGNAME.'regsiter_item_field' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$this->action = ( isset( $_POST["admin_action"] ) ) ? trim( $_POST["admin_action"] ) : '';

		$this->action_check( $this->action );
	}

	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		add_submenu_page(
			SLUGNAME.'_basic_setting',
			'ユーザー登録フォーム項目',
			'ユーザー登録フォーム項目',
			'administrator',
			SLUGNAME.'_regsiter_item_field',
			array( $this, SLUGNAME.'_manage_setting_page' )//f
		);

		add_meta_box(
			SLUGNAME.'_manage_customer_box',
			'ユーザー登録フォーム項目',
			array( $this, 'regsiter_item_field_box' ),
			SLUGNAME.'_regsiter_item_field',
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
			<h2><?php echo esc_html( 'ユーザー登録フォーム項目' ); ?></h2>

			<?php settings_errors(); ?>

			<form method="post" action="<?php echo $this->wpfunc->esc_url( $action_url ); ?>" name="field_form">

				<?php settings_fields( SLUGNAME.'_regsiter_item_field' ); ?>
				<?php wp_nonce_field( SLUGNAME.'regsiter_item_field', 'item_field', false ); ?>
				<div id="<?php echo SLUGNAME.'regsiter_item_field'; ?>" class="metabox-holder">
					<?php do_meta_boxes( $metabox, 'normal', null ); ?>
				</div>
			</form>

		</div>
		<?php
	}

	public function regsiter_item_field_box()
	{
		$options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

		if( isset( $_GET['field'] ) && $_GET['field'] !== "" ) {

			$this->guild_press_edit_original_form->edit_field_item_form( $options );
		}else{

			$this->guild_press_new_original_form->original_normal_form( $options );
			$this->guild_press_new_original_form->new_field_item_form( $options );
		}

	}

	//各タブに合わせてsettingにセットする名前を返す。
	public function get_metabox_name( $tab='' ){

		return SLUGNAME.'_regsiter_item_field';
	}

}



