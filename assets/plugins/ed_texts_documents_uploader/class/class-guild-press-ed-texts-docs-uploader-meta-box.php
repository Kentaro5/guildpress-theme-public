<?php
/**
*
カスタムポスト Guild-press-paypalのロジック部分
*/
class GuildPressEdTextsDocsMetaBox
{


	public function __construct()
	{
		$this->load();
	}


	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'get_action' ) );
		add_action('add_meta_boxes', array( $this, 'add_new_paypal_save' ) );

		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_texts_docs';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->ed_texts_docs_model = new EdTextsDocsModel;

	}


	public function admin_menu()
	{
		if( isset( $_GET['page'] ) && $_GET['page'] === 'guild_press_add_texts_docs' && is_string( $_GET['page'] ) ){

				add_submenu_page(
					SLUGNAME.'_basic_setting',
					'',
					'',
					'administrator',
					SLUGNAME.'_add_texts_docs',
					array( $this, SLUGNAME.'_settings_page' )
				);

				add_meta_box(
					SLUGNAME.'_add_new_member_rank_box',
					'教材・資料アップロード画面',
					array( $this, 'add_new_texts_docs_box' ),
					SLUGNAME.'_add_texts_docs',
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
					SLUGNAME.'_add_new_member_rank_box',
					'教材・資料アップロード画面',
					array( $this, 'add_new_texts_docs_box' ),
					'guild_press_text_doc',
					'normal'
					);
	}

	public function add_new_texts_docs_box()
	{

		wp_nonce_field( SLUGNAME.'_add_text_docs', 'gp_text_doc', false );

		//編集の場合は各値を入れる
		if( isset($_GET['gp_text_doc_id']) && $_GET['gp_text_doc_id'] !== "" ){

			$meta_id = intval( $_GET['gp_text_doc_id'] );
			$post_action = 'guildpressedittextsdocs';
			$posts_meta = get_post_meta($meta_id);

			$gp_post_title = $posts_meta['gp_post_title'][0];
			$gp_texts_docs_title = $posts_meta['gp_texts_docs_title'][0];
			$gp_texts_docs_url = $posts_meta['gp_texts_docs_url'][0];
			$gp_page_block = $posts_meta['guild_press_rank_check'][0];
			$gp_lesson_category = $posts_meta['gp_lesson_category'][0];
			$gp_block_member_rank = @unserialize( $posts_meta['guild_press_block_texts_docs_rank'][0] );
			// $gp_block_member_text = $posts_meta['guild_press_block_texts_docs_rank_text'][0];

			if( $gp_block_member_rank === false ){
				$gp_block_member_rank = array();
			}

		}else{

			$meta_id = '';
			$post_action = 'guildpressnewtextsdocs';

			$gp_post_title = '';
			$gp_texts_docs_title = '';
			$gp_texts_docs_url = '';
			$gp_page_block = '1';
			$gp_lesson_category = '';
			$gp_block_member_rank = '';
			$gp_block_member_text = '';
		}

		$args = array(
        	'taxonomy'=> 'guild_lesson_category',
    	);

    	$leson_categories = $this->wpfunc->get_categories($args);


    	$rank_query = $this->ed_texts_docs_model->return_member_rank_query();

		wp_enqueue_media();


		?>

			<h3><?php _e( '教材名' ); ?></h3>

					<input type="text" name="gp_post_title" size="30" class="title_input" value="<?php echo $gp_post_title; ?>" id="gp_post_title" spellcheck="true" autocomplete="off">

			<table class="file-form-table mt22">

			<thead>
				<tr>
					<th class="thead_color p8" colspan="2">使用する教材・資料</th>
				</tr>
			</thead>
			<tbody class="tbody_color">


			<tr class="">
				<th scope="row pl10" width="30%"><p class="p8 margin0"><?php _e( 'ファイル名(ダウンロードリンクを表示する時にユーザー側に表示される名前です。)' ); ?></p></th>
				<th scope="row pl10" width="60%"><p class="p8 margin0"><?php _e( 'ファイルURL' ); ?></p></th>
			</tr>

			<tr>
				<td class="pl8 pb8">
					<input type="text" style="font-size: 16px;" name="gp_texts_docs_title" size="5" class="title_input" value="<?php echo $gp_texts_docs_title; ?>" id="gp_texts_docs_title" spellcheck="true" autocomplete="off">
				</td>
				<td class="pl8 pb8">
					<input type="text" style="font-size: 16px;" name="gp_texts_docs_url" size="5" class="title_input" value="<?php echo $gp_texts_docs_url; ?>" id="gp_texts_docs_url" spellcheck="true" autocomplete="off">
				</td>
			</tr>

			<tr>
				<td class="pl8 pb8" colspan="2">

                <button type="button" class="file_input" id="img_uploader" name="csv">クリックしてファイルを選んで下さい。</button>

				</td>
			</tr>
			</tbody>
			</table>

			<h3><?php _e( '紐づけするカテゴリー' ); ?></h3>

			<select name="gp_lesson_category">
				<option value=""></option>

				<?php foreach ($leson_categories as $leson_categories_key => $leson_categories_value) : ?>

					<option value="<?php echo $this->wpfunc->esc_html($leson_categories_value->slug) ?>" <?php echo $this->basic->check_selected( $gp_lesson_category, $leson_categories_value->slug ); ?>>
						<?php echo $this->wpfunc->esc_html($leson_categories_value->name) ?>
					</option>
				<?php endforeach; ?>
			</select>

		<?php if( ! $rank_query->have_posts() ) : ?>
			<p>会員ランクが作成されていません。</p>
			<p>コンテンツをブロックするには、会員ランクを作成してください。</p>
		<?php else : ?>
		<h4>このコンテンツを会員ランクでブロックしますか?</h4>

		<p>
			<input type="radio" id="guild_press_page_non_block" name="guild_press_rank_check" value="1" <?php checked( $gp_page_block, "1" ); ?> />
			<label for="guild_press_rank_check">ブロックしない場合はこちらにチェックを入れてください。</label>
		</p>

		<p>
			<input type="radio" id="guild_press_rank_check" name="guild_press_rank_check" value="2" <?php checked( $gp_page_block, "2" ); ?> />
			<label for="guild_press_rank_check">ブロックする場合はこちらにチェックを入れてください。</label>
		</p>

		<div id="guild_press_member_list_box" style="">
			<h4>コンテンツを閲覧できる会員ランクを選んでください。</h4>

			<?php while( $rank_query->have_posts() ) : ?>
				<p>
					<?php
					$rank_query->the_post();
					$member_id = get_the_ID();
					//IDを元に、会員ランク取得
					$member_rank = get_post_meta($member_id);

					//最初は配列に値が入っていないため、初期値をセットする。
					if( $gp_block_member_rank !== NULL ){

						//$gp_block_member_rankが空の場合は、空の配列をセットする。
						$gp_block_member_rank = ( isset($gp_block_member_rank) && $gp_block_member_rank !== "" ) ? $gp_block_member_rank : array();

						$check_result = $this->basic->in_array( strval ( $member_id ), $gp_block_member_rank );
					}

					?>
					<input type="checkbox" id="guild_press_block_texts_docs_rank" name="guild_press_block_texts_docs_rank[]" value="<?php echo $member_id; ?>" <?php checked( $check_result, true ); ?> />

					<label for="guild_press_block_texts_docs_rank">
						<?php echo $this->wpfunc->esc_html($member_rank['member_rank_name'][0]); ?>
					</label>

				</p>
			<?php endwhile; ?>

		</div>
		<?php endif; ?>


		<input type="hidden" name="gp_text_doc_action" value="<?php echo $post_action; ?>" />

		<input type="hidden" name="gp_texts_docs_id" id="gp_texts_docs_id" value="<?php echo $meta_id; ?>" />

		<input type="hidden" name="action" id="action" value="save" />

		<input type="hidden" name="post_id" id="post_id" value="<?php echo $meta_id; ?>" />

		<input type="hidden" name="gp_now_page" value="guild_press_add_new_texts_docs" >

		<?php

		$this->wpfunc->wp_reset_postdata();

		add_action( 'admin_footer', array( $this, 'media_selector_print_scripts' ) );

	}


	public function media_selector_print_scripts() {

		$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
		?>
		<script type='text/javascript'>

			ed_texts_docs_js.wp_file_uploader(<?php echo $my_saved_attachment_post_id; ?>);
			ed_texts_docs_js.box_check();
		</script>
		<?php
	}


		//各メタボックスの内容を分岐している処理。
		public function guild_press_settings_page() {

			$metaboxName = ( ! isset( $_GET['page'] ) || ! is_string( $_GET['page'] ) ) ? 'general' : $_GET['page'];

			$action_url = $this->admin_url;

			$gp_text_doc_id = ( isset($_GET['gp_text_doc_id']) && $_GET['gp_text_doc_id'] !== "" ) ? $_GET['gp_text_doc_id'] : '';

			?>
			<div class="wrap">
				<h2><?php echo esc_html( '教材・資料アップロード画面' ); ?></h2>

				<?php settings_errors(); ?>

				<form method="post" action="<?php echo $this->wpfunc->esc_url($action_url); ?>" name="form">

					<?php


					if( $metaboxName === SLUGNAME.'_add_texts_docs' ){

						settings_fields( SLUGNAME.'_add_texts_docs' );
						?>
						<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

							<?php do_meta_boxes( $metaboxName, 'normal', null ); ?>

						</div>

						<?php if( $gp_text_doc_id !== '' ) : ?>

							<?php submit_button('変更を保存する'); ?>
						<?php else : ?>

							<?php submit_button('教材・資料を新しく追加する'); ?>
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

		$this->action = ( isset( $_POST["gp_text_doc_action"] ) ) ? trim( $_POST["gp_text_doc_action"] ) : '';

		$get_request = ( isset( $_GET["action"] ) ) ? trim( $_GET["action"] ) : '';

		$page_request = ( isset( $_GET["page"] ) ) ? trim( $_GET["page"] ) : '';

		$gp_text_doc_id_request = ( isset( $_GET["gp_text_doc_id"] ) ) ? trim( $_GET["gp_text_doc_id"] ) : '';

		//deleteのアクションが来たらポストを削除する。
		if( $gp_text_doc_id_request !== '' && $page_request === SLUGNAME.'_add_texts_docs'  && $get_request === 'delete'){

			$args = array(
				'get_request' => $get_request,
				'page_request' => $page_request,
				'gp_text_doc_id_request' => $gp_text_doc_id_request,
			);

			$this->delete_post( $args );
		}else{

			$this->action_check( $this->action );
		}

	}

	//リクエストされたポストを削除する。
	public function delete_post( $args = array() ){

		//削除するポストのIDを数値化して変数に入れる。
		$post_id = intval($args['gp_text_doc_id_request']);

		$custom_fileds_items = get_post_meta( $post_id );

		$meta_result = true;
		foreach ($custom_fileds_items as $delete_meta_key => $delete_meta_value) {

			if( $delete_meta_key === 'guild_press_block_texts_docs_rank' ){

				$meta_delete_result = delete_post_meta( $post_id, $delete_meta_key, '' );
			}else{

				$meta_delete_result = delete_post_meta( $post_id, $delete_meta_key, $delete_meta_value[0] );
			}

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

			case 'guildpressnewtextsdocs':

				$this->save_texts_docs_info();
			break;

			case 'guildpressedittextsdocs':

				$this->update_texts_docs_info();
			break;


			default:
				die("会員ランクの設定の際エラーが出ました、はじめからやり直して下さい。");
			break;

		}
	}

	public function update_texts_docs_info()
	{

		if( ! $_POST["gp_text_doc_action"] || ! isset( $_POST["gp_text_doc_action"] ) ){
			return;
		}


		if( ! wp_verify_nonce( $_POST['gp_text_doc'], SLUGNAME.'_add_text_docs' ) && !isset( $_POST['gp_text_doc'] ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}


		$post_id = ( isset($_POST['post_id']) && $_POST['post_id'] !== "" ) ? intval(trim($_POST['post_id'])) : '';

		$post_item = $this->basic->checkPostItem( $_POST );
    	$save_item = array();

		$save_item = $this->get_save_item($post_item);

		$check_result = $this->check_save_item( $save_item );

		if( $check_result === true ){
			if ( $post_id ) {

				//指定されたIDのカスタムポストを保存して、IDを改めて取得。
				$post_id = $this->basic->update_post( $post_id );

				//ポストのIDを使って、値を新しく保存
				$this->basic->update_custom_post( $post_id, $save_item );
			}
		}else{
			return;
		}

	}

	public function save_texts_docs_info() {

    	// Save logic goes here. Don't forget to include nonce checks!


    	$post_item = $this->basic->checkPostItem( $_POST );
    	$save_item = array();

    	if( ! $_POST["gp_text_doc_action"] || ! isset( $_POST["gp_text_doc_action"] ) ){
			return;
		}

		if( ! wp_verify_nonce( $_POST['gp_text_doc'], SLUGNAME.'_add_text_docs' ) && !isset( $_POST['gp_text_doc'] ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$save_item = $this->get_save_item($post_item);

		$check_result = $this->check_save_item( $save_item );


		if( $check_result === true ){
			if( $post_item['action'] === 'save' ){

				$post_id = $this->basic->insert_post( 'guild_press_text_doc' );
			}

			if ( $post_id ) {

				$this->basic->update_custom_post( $post_id, $save_item );
			}

		}else{

			return;
		}

	}


	public function check_save_item($save_item)
	{

		if( $save_item['gp_post_title'] === '' ){

			return false;
		}

		if( $save_item['gp_texts_docs_url'] === '' ){

			return false;
		}

		if( $save_item['guild_press_block_texts_docs_rank'] === '' && $save_item['guild_press_rank_check'] === "2" ){

			return false;
		}

		return true;
	}

	public function get_save_item($post_item)
	{
		$save_item = array();

		$save_item['gp_post_title'] = ( isset($post_item['gp_post_title']) && $post_item['gp_post_title'] !== "" ) ? $post_item['gp_post_title'] : '';

		$save_item['gp_texts_docs_title'] = ( isset($post_item['gp_texts_docs_title']) && $post_item['gp_texts_docs_title'] !== "" ) ? $post_item['gp_texts_docs_title'] : '';

		$save_item['gp_texts_docs_url'] = ( isset($post_item['gp_texts_docs_url']) && $post_item['gp_texts_docs_url'] !== "" ) ? $post_item['gp_texts_docs_url'] : '';

		$save_item['guild_press_rank_check'] = ( isset($post_item['guild_press_rank_check']) && $post_item['guild_press_rank_check'] !== "" ) ? $post_item['guild_press_rank_check'] : '';

		$save_item['gp_lesson_category'] = ( isset($post_item['gp_lesson_category']) && $post_item['gp_lesson_category'] !== "" ) ? $post_item['gp_lesson_category'] : '';

		$save_item['guild_press_block_texts_docs_rank'] = ( isset($post_item['guild_press_block_texts_docs_rank']) && $post_item['guild_press_block_texts_docs_rank'] !== "" ) ? $post_item['guild_press_block_texts_docs_rank'] : '';

		return $save_item;
	}



}