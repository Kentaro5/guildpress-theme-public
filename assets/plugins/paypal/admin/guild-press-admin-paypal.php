<?php

require(  TEMP_DIR . '/assets/plugins/paypal/admin/paypal_common/guild-press-paypal-common.php' );
require(  TEMP_DIR . '/assets/plugins/paypal/admin/paypal_register/guild-press-paypal-register.php' );
require(  TEMP_DIR . '/assets/plugins/paypal/admin/paypal_edit/guild-press-paypal-edit.php' );
require(  TEMP_DIR . '/assets/plugins/paypal/admin/paypal_delete/guild-press-paypal-delete.php' );

/**
*
カスタムポスト Guild-press-paypalのロジック部分
*/
class Guild_Press_Admin_Paypal
{

	protected $paypal_register;
	protected $post_title;
	protected $paypal_address;
	protected $amount;
	protected $currency;
	protected $currency_symbol;
	protected $sandbox;
	protected $paypal_lang;
	protected $item_name ;
	protected $payment;
	protected $payment_period;
	protected $payment_cycle_number;
	protected $payment_cycle;
	protected $submit_btn_text;
	protected $selected_member_rank;
	public $paypal_form_path;


	public function __construct()
	{
		$this->load();
	}


	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'get_action' ) );

		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_paypal_form';
		$this->paypal_form_path = 'templates/admin/paypal/paypal_form/paypal-form.php';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;

		$this->paypal_register = new Guild_Press_PayPal_Register();
		$this->paypal_edit = new Guild_Press_PayPal_Edit();
		$this->paypal_delete = new Guild_Press_PayPal_Delete();

	}


	public function admin_menu()
	{
		if( isset( $_GET['page'] ) && $_GET['page'] === 'guild_press_add_new_paypal_form' && is_string( $_GET['page'] ) ){

				add_submenu_page(
					SLUGNAME.'_basic_setting',
					'',
					'',
					'administrator',
					SLUGNAME.'_add_new_paypal_form',
					array( $this, SLUGNAME.'_settings_page' )
				);

				add_meta_box(
					SLUGNAME.'_add_new_paypal_form_box',
					'PayPal設定新規登録',
					array( $this, 'paypal_form_box' ),
					SLUGNAME.'_add_new_paypal_form',
					'normal'
					);

		}else if( isset( $_GET['page'] ) && $_GET['page'] === 'guild_press_edit_paypal_form' && is_string( $_GET['page'] ) ){

				add_submenu_page(
					SLUGNAME.'_basic_setting',
					'',
					'',
					'administrator',
					SLUGNAME.'_edit_paypal_form',
					array( $this, SLUGNAME.'_settings_page' )
				);

				add_meta_box(
					SLUGNAME.'_edit_paypal_form_box',
					'PayPal設定編集',
					array( $this, 'paypal_form_box' ),
					SLUGNAME.'_edit_paypal_form',
					'normal'
					);

		}else if( isset( $_GET['page'] ) && $_GET['page'] === 'guild_press_delete_paypal_form' && is_string( $_GET['page'] ) ){


				//チェックして、int化
				$form_id = ( isset($_GET['form_id']) && $_GET['form_id'] !== "" ) ? intval( $_GET['form_id'] ) : '';

				if( is_int( $form_id ) && $form_id > 0 ){

					$this->wpfunc->wp_delete_post( $form_id, true );
				}

				$this->wpfunc->wp_redirect( $this->admin_url );

		}

	}

	//各メタボックスの内容を分岐している処理。
		public function guild_press_settings_page() {

			$metaboxName = ( ! isset( $_GET['page'] ) || ! is_string( $_GET['page'] ) ) ? 'general' : $_GET['page'];

			if( $metaboxName === 'schedule_email_settings' ){
				$action_url = "options.php";
			}else{
				$action_url = $this->admin_url;
			}


			?>
			<div class="wrap">
				<h2><?php echo esc_html( 'GuildPress' ); ?></h2>

				<?php settings_errors(); ?>

				<form method="post" action="<?php echo $this->wpfunc->esc_url($action_url); ?>" name="form">

					<?php


					if( $metaboxName === SLUGNAME.'_add_new_paypal_form' ){

						settings_fields( SLUGNAME.'_add_new_paypal_form' );
						?>
						<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

						<?php do_meta_boxes( $metaboxName, 'normal', null ); ?>

					</div>

					<?php submit_button(); ?>
						<?php
					}else if( $metaboxName === SLUGNAME.'_edit_paypal_form' ){

						settings_fields( SLUGNAME.'_edit_paypal_form' );
						?>
							<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

							<?php do_meta_boxes( $metaboxName, 'normal', null ); ?>

						</div>

						<?php submit_button(); ?>
						<?php
					}

					?>

				</form>
			</div>
			<?php
		}


	public function paypal_form_box()
	{

		$pages = get_pages();
		wp_nonce_field( SLUGNAME.'_paypal_settings', 'paypal_settings', false );
		wp_nonce_field( 'setting-notification', 'setting-notification-nonce', false );

		//編集の場合は各値を入れる
		if( isset($_GET['form_id']) && $_GET['form_id'] !== "" ){

			$meta_id = intval( $_GET['form_id'] );
			$posts_meta = get_post_meta($meta_id);

			$post_action = 'guildeditpaymentform';
			$this->set_paypal_items($posts_meta);
		}else{
			$meta_id = '';
			$post_action = 'guildcreatenewpaymentform';
		}

		$rank_query = $this->return_member_rank_query();

		$gp_data = array(
			'title' => $this->post_title,
			'paypal_address' => $this->paypal_address,
			'submit_btn_text' => $this->submit_btn_text,
			'amount' => $this->amount,
			'currency' => $this->currency,
			'currency_symbol' => $this->currency_symbol,
			'sandbox' => $this->sandbox,
			'paypal_lang' => $this->paypal_lang,
			'item_name' => $this->item_name,
			'payment' => $this->payment,
			'selected_member_rank' => $this->selected_member_rank,
			'payment_cycle_number' => $this->payment_cycle_number,
			'payment_cycle' => $this->payment_cycle,
			'post_action' => $post_action,
			'payment_period' => $this->payment_period,
			'meta_id' => $meta_id,
		);

        $file_path = $this->wpfunc->locate_template( $this->paypal_form_path, false );

		if( !file_exists($file_path) ){
	 		return;
		}

		include( $file_path );

		add_action( 'admin_footer', array( $this, 'paypal_js' ) );
		$this->wpfunc->wp_reset_postdata();
	}

	public function set_paypal_items( $posts_meta )
	{
		$this->post_title             = $posts_meta['post_title'][0];
        $this->paypal_address         = $posts_meta['paypal_address'][0];
        $this->amount                 = $posts_meta['amount'][0];
        $this->currency               = $posts_meta['currency'][0];
        $this->currency_symbol        = $posts_meta['currency_symbol'][0];
        $this->sandbox                = $posts_meta['sandbox'][0];
        $this->paypal_lang            = $posts_meta['paypal_lang'][0];
        $this->item_name              = $posts_meta['item_name'][0];
        $this->payment                = $posts_meta['payment'][0];
        $this->payment_period         = $posts_meta['payment_period'][0];
        $this->payment_cycle_number   = $posts_meta['payment_cycle_number'][0];
        $this->payment_cycle          = $posts_meta['payment_cycle'][0];
        $this->submit_btn_text        = $posts_meta['submit_btn_text'][0];
        $this->selected_member_rank   = $posts_meta['member_rank'][0];
	}

	public function paypal_js()
	{
		?>
		<script type="text/javascript">

			admin_js.regsiter_paypal_event_listener();
		</script>
		<?php
	}


	//各管理画面でポストされたアクションを受け取る。
	public function get_action(  ){


		$this->action = ( isset( $_POST["payment_register_action"] ) ) ? trim( $_POST["payment_register_action"] ) : '';

		$get_request = ( isset( $_GET["action"] ) ) ? trim( $_GET["action"] ) : '';

		$page_request = ( isset( $_GET["page"] ) ) ? trim( $_GET["page"] ) : '';

		$form_id_request = ( isset( $_GET["form_id"] ) ) ? trim( $_GET["form_id"] ) : '';

		//deleteのアクションが来たらポストを削除する。
		if( $form_id_request !== '' && $page_request === SLUGNAME.'_add_new_paypal_form'  && $get_request === 'delete'){

			$args = array(
				'get_request' => $get_request,
				'page_request' => $page_request,
				'form_id_request' => $form_id_request,
			);
			$this->paypal_delete->delete_post( $args );
		}else{

			$this->action_check( $this->action );
		}

	}


	//各管理画面でポストされたアクションで処理を分ける。
	public function action_check( $action='' ){

		$_POST["payment_register_action"] = ( isset($_POST["payment_register_action"]) && $_POST["payment_register_action"] !== "" ) ? $_POST["payment_register_action"] : '';

		if( $_POST["payment_register_action"] === '' ){
			return;
		}

		$_POST["setting-notification-nonce"] = ( isset($_POST["setting-notification-nonce"]) && $_POST["setting-notification-nonce"] !== "" ) ? $_POST["setting-notification-nonce"] : '';

		if( $_POST["setting-notification-nonce"] === '' && ! wp_verify_nonce( $_POST['setting-notification-nonce'], 'setting-notification' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if( ! $action || ! isset( $action ) || ! is_string( $action ) ){
			return;
		}

		switch ($action) {

			case 'guildcreatenewpaymentform':

				$this->paypal_register->save_paypal_settings();
			break;

			case 'guildeditpaymentform':

				$this->paypal_edit->update_paypal_settings();
			break;

			default:
				die("PayPalの設定の際エラーが出ました、はじめからやり直して下さい。");
			break;

		}
	}

	public function return_member_rank_query()
	{
		$rank_query = new WP_Query(
				array(
				'post_type' => 'guild_press_rank',
				)
			);

		return $rank_query;
	}

}










