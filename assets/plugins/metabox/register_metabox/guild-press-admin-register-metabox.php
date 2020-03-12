<?php
/**
*
*/
class Admin_Register_Metabox
{
	public $metabox_path;
	public $wpfunc;

	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic();
		$this->load();
	}

	public function load()
	{
		add_action( 'add_meta_boxes',             array( $this, 'add_contents_block_meta_box' ) );
		add_action( 'save_post',                  array( $this, 'save_contents_block' ) );
		$this->metabox_path = 'templates/admin/metabox/register_metabox/register-metabox.php';

	}

	public function add_contents_block_meta_box(){


		add_meta_box( 'gp_register_block_check', '登録ページチェックボックス',  array( $this,'register_check_meta_box' ) , 'post', 'side', 'high' );

		add_meta_box( 'gp_register_block_check', '登録ページチェックボックス',  array( $this,'register_check_meta_box' ) , 'page', 'side', 'high' );
	}

	public function register_check_meta_box(){
		global $post;

		$text = "「PayPalの決済コードを貼り付けているページ」、「ユーザー登録に使用するページ」の場合はチェックを入れて下さい。";

		$meta_name = '_guild_press_register_page_check';
		wp_nonce_field( 'guild_press_block', 'guild_press_block_box', false );

		$gp_data = array(
			'post_id' => $post->ID,
			'meta' => $meta_name,
			'text' => $text
		);

		if( ! $file_path = $this->basic->load_template( $this->metabox_path, false ) ){
            return;
        }

        include( $file_path );

	}

	//ページがブロックしているかチェックする
	public function check_page_blocked(){
		global $post;

		if( $post ){
			// Backward compatibility for old block/unblock meta.
			$meta = get_post_meta( $post->ID, '_guild_press_register_page_check', true );

			return $meta;
		}
	}

	//postが更新された時に、各postにブロックのチェックの値を更新。
	public function save_contents_block(){

		// Quit if the nonce isn't there, or is wrong.
		if ( ! isset( $_POST['guild_press_block_box'] ) || ! wp_verify_nonce( $_POST['guild_press_block_box'], 'guild_press_block' ) ) {
			return;
		}

		// Quit if the current user cannot edit posts.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		// Get value.
		$block = isset( $_POST['guild_press_register_page_check'] ) ? $_POST['guild_press_register_page_check'] : null;

		// Need the post object.
		global $post;

		// Update accordingly.
		if ( $block !== null ) {
			update_post_meta( $post->ID, '_guild_press_register_page_check', $block );
		} else {
			delete_post_meta( $post->ID, '_guild_press_register_page_check' );
		}
	}

}
