<?php
/**
*
カスタムポスト Guild-press-paypalのロジック部分
*/
class GuildPressMemberRankMetaBox
{


	public function __construct()
	{
		$this->load();
	}


	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'get_action' ) );

		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_member_rank';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;

	}


	public function admin_menu()
	{
		if( isset( $_GET['page'] ) && $_GET['page'] === 'guild_press_add_new_member_rank' && is_string( $_GET['page'] ) ){

				add_submenu_page(
					SLUGNAME.'_basic_setting',
					'',
					'',
					'administrator',
					SLUGNAME.'_add_new_member_rank',
					array( $this, SLUGNAME.'_settings_page' )
				);

				add_meta_box(
					SLUGNAME.'_add_new_member_rank_box',
					'会員ランク新規登録',
					array( $this, 'add_new_member_rank_box' ),
					SLUGNAME.'_add_new_member_rank',
					'normal'
					);
		}

	}

	//PayPalの登録画面のデフォルトの保存部分を削除する。
	public function remove_paypal_post_save_btn()
	{
		remove_meta_box( 'submitdiv','guild_press_rank','side' );
	}

	//削除したところに新しく保存ボタンのメタボックスを追加する。
	public function add_new_paypal_save()
	{

		add_meta_box(
					SLUGNAME.'_add_new_paypal_save_box',
					'保存',
					array( $this, 'add_new_paypal_save_box' ),
					'guild_press_rank',
					'side'
				);
	}



	public function add_new_member_rank_box()
	{

		$pages = get_pages();
		wp_nonce_field( SLUGNAME.'_add_new_member_rank', 'gp_new_member_rank', false );

		//編集の場合は各値を入れる
		if( isset($_GET['member_id']) && $_GET['member_id'] !== "" ){

			$meta_id = intval( $_GET['member_id'] );
			$post_action = 'guildpresseditmemberrank';
			$posts_meta = get_post_meta($meta_id);
			$member_rank_name = $posts_meta['member_rank_name'][0];

		}else{

			$meta_id = '';
			$post_action = 'guildpressaddnewmemberrank';
			$member_rank_name = '';
		}

		?>

			<table class="form-table">

			<tr>
				<th scope="row"><label for="member_rank_name"><?php _e( '会員レベル名' ); ?></label></th>
				<td>
					<input type="text" name="member_rank_name" size="30" class="title_input" value="<?php echo $this->wpfunc->esc_html($member_rank_name); ?>" >
				</td>
			</tr>
			</table>

		<input type="hidden" name="gp_member_action" value="<?php echo $post_action; ?>" />

		<input type="hidden" name="post_id" id="post_id" value="<?php echo $meta_id; ?>" />

		<input type="hidden" name="action" id="action" value="save" />

		<?php
	}

	//各メタボックスの内容を分岐している処理。
		public function guild_press_settings_page() {

			$metaboxName = ( ! isset( $_GET['page'] ) || ! is_string( $_GET['page'] ) ) ? 'general' : $_GET['page'];

			$action_url = $this->admin_url;

			$member_id = ( isset($_GET['member_id']) && $_GET['member_id'] !== "" ) ? $_GET['member_id'] : '';

			?>
			<div class="wrap">
				<h2><?php echo esc_html( '新規会員ランク登録' ); ?></h2>

				<?php settings_errors(); ?>

				<form method="post" action="<?php echo $this->wpfunc->esc_url($action_url); ?>" name="form">

					<?php

					if( $metaboxName === SLUGNAME.'_add_new_member_rank' ){

						settings_fields( SLUGNAME.'_add_new_member_rank' );
						?>
						<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

							<?php do_meta_boxes( $metaboxName, 'normal', null ); ?>

						</div>

						<?php if( $member_id !== '' ) : ?>

							<?php submit_button('保存する'); ?>
						<?php else : ?>

							<?php submit_button('会員ランクを新しく追加する'); ?>
						<?php endif; ?>
						<?php
					}

					?>

				</form>
			</div>
			<?php
		}


	//各管理画面でポストされたアクションを受け取る。
	public function get_action(  ){

		$this->action = ( isset( $_POST["gp_member_action"] ) ) ? trim( $_POST["gp_member_action"] ) : '';

		$get_request = ( isset( $_GET["action"] ) ) ? trim( $_GET["action"] ) : '';

		$page_request = ( isset( $_GET["page"] ) ) ? trim( $_GET["page"] ) : '';

		$member_id_request = ( isset( $_GET["member_id"] ) ) ? trim( $_GET["member_id"] ) : '';

		//deleteのアクションが来たらポストを削除する。
		if( $member_id_request !== '' && $page_request === SLUGNAME.'_add_new_member_rank'  && $get_request === 'delete'){

			$args = array(
				'get_request' => $get_request,
				'page_request' => $page_request,
				'member_id_request' => $member_id_request,
			);
			$this->delete_post( $args );
		}else{

			$this->action_check( $this->action );
		}

	}

	//リクエストされたポストを削除する。
	public function delete_post( $args = array() ){

		//削除するポストのIDを数値化して変数に入れる。
		$post_id = intval($args['member_id_request']);

		$custom_fileds_items = get_post_meta( $post_id );

		$meta_result = true;
		foreach ($custom_fileds_items as $delete_meta_key => $delete_meta_value) {

			$meta_delete_result = delete_post_meta( $post_id, $delete_meta_key, $delete_meta_value[0] );
			if( $meta_delete_result !== true ){

				$meta_result = false;
			}
		}

		if( $meta_result !== false ){
			$result = wp_delete_post( $post_id , true );
		}

		wp_safe_redirect($this->admin_url);
		exit();

	}


	//各管理画面でポストされたアクションで処理を分ける。
	public function action_check( $action='' ){

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if( ! $action || ! isset( $action ) || ! is_string( $action ) ){
			return;
		}

		switch ($action) {

			case 'guildpressaddnewmemberrank':

				$this->save_member_rank();
			break;

			case 'guildpresseditmemberrank':

				$this->update_member_rank_settings();
			break;


			default:
				die("会員ランクの設定の際エラーが出ました、はじめからやり直して下さい。");
			break;

		}
	}

	public function update_member_rank_settings()
	{

		if( ! $_POST["gp_member_action"] || ! isset( $_POST["gp_member_action"] ) ){
			return;
		}

		if( ! wp_verify_nonce( $_POST['gp_new_member_rank'], SLUGNAME.'_add_new_member_rank' ) && !isset( $_POST['gp_new_member_rank'] ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$post_id = ( isset($_POST['post_id']) && $_POST['post_id'] !== "" ) ? intval(trim($_POST['post_id'])) : '';

		$post_item = $this->basic->checkPostItem( $_POST );
    	$save_item = array();

		$save_item = $this->get_save_item($post_item);

		if ( $post_id ) {

			$post_id = wp_update_post( array(
				'ID' => (int) $post_id,
				'post_status' => 'publish',
				'post_title' => '',
				'post_content' => '',
			) );

			foreach ( $save_item as $post_key => $post_value ) {
				update_post_meta( $post_id, $post_key,
					$post_value);
			}
		}

	}

	public function save_member_rank() {

    	// Save logic goes here. Don't forget to include nonce checks!
    	//ポストが新規か編集家をチェックする。
    	$check_post_status = get_current_screen();

    	$post_item = $this->basic->checkPostItem( $_POST );
    	$save_item = array();

    	if( ! $_POST["gp_member_action"] || ! isset( $_POST["gp_member_action"] ) ){
			return;
		}

		if( ! wp_verify_nonce( $_POST['gp_new_member_rank'], SLUGNAME.'_add_new_member_rank' ) && !isset( $_POST['gp_new_member_rank'] ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$save_item = $this->get_save_item($post_item);

		if( $post_item['action'] === 'save' ){

			$post_id = wp_insert_post( array(
				'post_type' => 'guild_press_rank',
				'post_status' => 'publish',
				'post_title' => '',
				'post_content' => '',
			),true );
		}


		if ( $post_id ) {
			foreach ( $save_item as $post_key => $post_value ) {
				update_post_meta( $post_id, $post_key,
					$post_value);
			}
		}

	}

	public function get_save_item($post_item)
	{
		$save_item = array();

		$save_item['member_rank_name'] = ( isset($post_item['member_rank_name']) && $post_item['member_rank_name'] !== "" ) ? $post_item['member_rank_name'] : '';

		return $save_item;
	}



}