<?php
/**
*
*/
class LockPageBox
{

	public function __construct()
	{

		$this->load();
	}

	public function load()
	{
		add_action( 'add_meta_boxes',             array( $this, 'add_contents_block_meta_box' ) );
		add_action( 'save_post',                  array( $this, 'save_contents_meta' ) );

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
	}

	public function add_contents_block_meta_box(){

		//カスタム投稿には、４つ目の引数にカスタム投稿タイプ名を指定する。
		// add_meta_box( 'gp_page_lock_box', 'ページロック',  array( $this,'page_lock' ) , 'guild_lesson', 'normal', 'high' );

		//カスタム投稿には、４つ目の引数にカスタム投稿タイプ名を指定する。
		add_meta_box( 'gp_page_lock_box', 'ページに鍵をかける。',  array( $this,'page_lock' ) , 'guild_lesson_detail', 'normal', 'high' );
	}

	public function page_lock(){

		wp_nonce_field( 'guild_press_lock_page', 'guild_press_lock_page_key', false );

		$post_id = ( isset($_GET['post']) && $_GET['post'] !== "" ) ? intval( $_GET['post'] ) : '';

		$guild_press_lock_page = $this->wpfunc->get_post_meta( $post_id, 'guild_press_lock_page' );

		//最初は配列に値が入っていないため、初期値をセットする。
		if( $guild_press_lock_page === false || $guild_press_lock_page[0] === '' ){

			$guild_press_lock_page[0] = '1';
		}else if( count( $guild_press_lock_page ) === 0 ){

			$guild_press_lock_page[0] = '1';
		}

		?>

		<h4>このコンテンツに鍵をかけますか?</h4>
		<p>
			<input type="radio" id="guild_press_page_non_lock" name="guild_press_lock_page" value="1" <?php checked( $guild_press_lock_page[0], "1" ); ?> />
			<label for="guild_press_lock_page">鍵をかけない場合はこちらにチェックをいれてください。</label>
		</p>

		<p>
			<input type="radio" id="guild_press_page_lock" name="guild_press_lock_page" value="2" <?php checked( $guild_press_lock_page[0], "2" ); ?> />
			<label for="guild_press_lock_page">鍵をかける場合はこちらにチェックをいれてください。</label>
		</p>

		<?php
	}

	public function save_contents_meta()
	{
		// Quit if the nonce isn't there, or is wrong.
		if ( ! isset( $_POST['guild_press_lock_page_key'] ) || ! wp_verify_nonce( $_POST['guild_press_lock_page_key'], 'guild_press_lock_page' ) ) {
			return;
		}

		// Quit if the current user cannot edit posts.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		$save_item_arr = array();

		//フィルタリングするポスト名をセット
		$save_item_arr = array(
			'guild_press_lock_page' => '',
		);

		$save_item = $this->basic->check_save_item( $_POST, $save_item_arr );

		// Need the post object.
		global $post;
		// Update accordingly.
		if ( $save_item['guild_press_lock_page'] === '2' ) {

			//ページに鍵を掛ける場合は、各ポストメタを保存
			update_post_meta( $post->ID, 'guild_press_lock_page', $save_item['guild_press_lock_page'] );

			update_post_meta( $post->ID, 'guild_press_lock_page_id', $post->ID );

		} else if ( $save_item['guild_press_lock_page'] === '1' ) {

			//ページに鍵を掛けない場合は、各ポストメタを削除
			update_post_meta( $post->ID, 'guild_press_lock_page', '' );
			update_post_meta( $post->ID, 'guild_press_lock_page_id', '' );

		}
	}

}
