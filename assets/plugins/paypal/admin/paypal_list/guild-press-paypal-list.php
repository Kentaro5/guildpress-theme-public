<?php

/**
 *
 */
class Guild_Press_PayPal_List
{

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';
		$this->bulk_action_url = admin_url().'admin.php?page='.SLUGNAME.'_paypal_form';

		$this->wpfunc = new WpFunc;

	}

	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		add_submenu_page(
			SLUGNAME.'_basic_setting',
			'PayPal設定',
			'PayPal設定',
			'administrator',
			SLUGNAME.'_paypal_form',
			array( $this, SLUGNAME.'_paypal_form_list' )
		);
	}

	//各メタボックスの内容を分岐している処理。
	public function guild_press_paypal_form_list() {

		//リストクラスがなければ読み込み
		if( ! class_exists( 'Guild_Press_PayPal_List_Table' ) ) {
			require_once( TEMP_DIR . '/assets/plugins/wp_list_table/admin/paypal/guild-press-paypal-list-table.php' );
		}

		$metaboxName = ( ! isset( $_GET['tab'] ) || ! is_string( $_GET['tab'] ) ) ? 'general' : $_GET['tab'];

		$list_table = new Guild_Press_PayPal_List_Table();
		$list_table->prepare_items();

		?>

		<div class="wrap">
			<h2><?php echo esc_html( 'GuildPress' ); ?></h2>
			<a href="?page=<?php echo SLUGNAME.'_add_new_paypal_form'; ?>" class="add-new-h2" title="">新規追加</a>

			<?php settings_errors(); ?>

			<form name="wp-lits-table" action="<?php echo $this->wpfunc->esc_url( $this->bulk_action_url ); ?>" method="POST">
				<?php $list_table->display(); ?>
			</form>
		</div>
		<?php
	}


}