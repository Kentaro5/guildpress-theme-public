<?php
/**
*
*/
class PageBlockByMemberRank
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
		$this->model = new MemberRankModel;
	}

	public function add_contents_block_meta_box(){

		add_meta_box( 'gp_page_block_box', 'ページブロック',  array( $this,'page_block_by_member_rank_meta_box' ) , 'post', 'normal', 'high' );

		add_meta_box( 'gp_page_block_box', 'ページブロック',  array( $this,'page_block_by_member_rank_meta_box' ) , 'page', 'normal', 'high' );

		//カスタム投稿には、４つ目の引数にカスタム投稿タイプ名を指定する。
		add_meta_box( 'gp_page_block_box', 'ページブロック',  array( $this,'page_block_by_member_rank_meta_box' ) , 'guild_lesson', 'normal', 'high' );

		//カスタム投稿には、４つ目の引数にカスタム投稿タイプ名を指定する。
		add_meta_box( 'gp_page_block_box', 'ページブロック',  array( $this,'page_block_by_member_rank_meta_box' ) , 'guild_lesson_detail', 'normal', 'high' );
	}

	public function page_block_by_member_rank_meta_box(){

		$text = "「PayPalの決済コードを貼り付けているページ」、「ユーザー登録に使用するページ」の場合はチェックを入れて下さい。";

		$meta = '_guild_press_register_page_check';
		wp_nonce_field( 'guild_press_block_by_memeber_rank', 'guild_press_member_block_box', false );

		$post_id = ( isset($_GET['post']) && $_GET['post'] !== "" ) ? intval( $_GET['post'] ) : '';

		$guild_press_page_block = $this->wpfunc->get_post_meta( $post_id, 'guild_press_page_block' );

		$guild_press_page_block_text = $this->wpfunc->get_post_meta( $post_id, 'guild_press_block_member_text' );


		//最初は配列に値が入っていないため、初期値をセットする。
		if( $guild_press_page_block === false ){

			$guild_press_page_block[0] = '1';
		}else if( count( $guild_press_page_block ) === 0 ){

			$guild_press_page_block[0] = '1';
		}

		//最初は配列に値が入っていないため、初期値をセットする。
		if( $guild_press_page_block_text === false ){

		}else if( count( $guild_press_page_block_text ) === 0 ){

			$guild_press_page_block_text[0] = '';
		}

		$guild_press_block_member_rank = $this->wpfunc->get_post_meta( $post_id, 'guild_press_block_member_rank' );
		$guild_press_block_member_rank = $this->basic->check_associative_array( $guild_press_block_member_rank );

		$rank_query = $this->model->return_member_rank_query();


		?>

		<?php if( ! $rank_query->have_posts() ) : ?>
			<p>会員ランクが作成されていません。</p>
			<p>コンテンツをブロックするには、会員ランクを作成してください。</p>
		<?php else : ?>
		<h4>このコンテンツを会員ランクでブロックしますか?</h4>
		<p>
			<input type="radio" id="guild_press_page_non_block" name="guild_press_page_block" value="1" <?php checked( $guild_press_page_block[0], "1" ); ?> />
			<label for="guild_press_page_block">ブロックしない場合はこちらにチェックを入れてください。</label>
		</p>

		<p>
			<input type="radio" id="guild_press_page_block" name="guild_press_page_block" value="2" <?php checked( $guild_press_page_block[0], "2" ); ?> />
			<label for="guild_press_page_block">ブロックする場合はこちらにチェックを入れてください。</label>
		</p>

		<div id="guild_press_member_list_box">
			<h4>コンテンツを閲覧できる会員ランクを選んでください。</h4>

			<?php while( $rank_query->have_posts() ) : ?>
				<p>
					<?php
					$rank_query->the_post();
					$member_id = get_the_ID();

					//IDを元に、会員ランク取得
					$member_rank = get_post_meta($member_id);

					$check_result = false;
					//最初は配列に値が入っていないため、初期値をセットする。
					if( $guild_press_block_member_rank[0] !== NULL ){

						//$guild_press_block_member_rank[0]が空の場合は、空の配列をセットする。
						$guild_press_block_member_rank[0] = ( isset($guild_press_block_member_rank[0]) && $guild_press_block_member_rank[0] !== "" ) ? $guild_press_block_member_rank[0] : array();

						$check_result = $this->basic->in_array( strval ( $member_id ), $guild_press_block_member_rank[0] );
					}

					?>
					<input type="checkbox" id="guild_press_block_member_rank" name="guild_press_block_member_rank[]" value="<?php echo $member_id; ?>" <?php checked( $check_result, true ); ?> />

					<label for="guild_press_block_member_rank">
						<?php echo $this->wpfunc->esc_html($member_rank['member_rank_name'][0]); ?>
					</label>

				</p>
			<?php endwhile; ?>
			<h4>ブロックした時に表示するテキスト</h4>
<textarea name="guild_press_block_member_text" style="width: 100%;" rows="6"><?php echo $this->basic->trim_script( $guild_press_page_block_text[0] ); ?></textarea>
		</div>
		<?php endif; ?>
		<?php
		$this->wpfunc->wp_reset_postdata();
		add_action( 'admin_footer', array( $this, 'page_block_by_member_rank_meta_box_js' ) );
	}

	public function page_block_by_member_rank_meta_box_js()
	{
		?>
			<script type="text/javascript">
				admin_js.register_member_block_event();
				admin_js.check_member_block_state();
			</script>
		<?php
	}


	//postが更新された時に、各postにブロックのチェックの値を更新。
	public function save_contents_meta(){

		// Quit if the nonce isn't there, or is wrong.
		if ( ! isset( $_POST['guild_press_member_block_box'] ) || ! wp_verify_nonce( $_POST['guild_press_member_block_box'], 'guild_press_block_by_memeber_rank' ) ) {
			return;
		}

		// Quit if the current user cannot edit posts.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		$save_item_arr = array();

		$save_item_arr = array(
			'guild_press_page_block' => '',
			'guild_press_block_member_rank' => '',
			'guild_press_block_member_text' => ''
		);
		// Get value.
		$block = isset( $_POST['guild_press_register_page_check'] ) ? $_POST['guild_press_register_page_check'] : null;

		$save_item = $this->model->check_save_item( $_POST, $save_item_arr );

		// Need the post object.
		global $post;
		// Update accordingly.
		if ( $save_item['guild_press_page_block'] === '2' ) {

			foreach ($save_item as $save_item_key => $save_item_value) {

				update_post_meta( $post->ID, $save_item_key, $save_item_value );
			}
		} else if ( $save_item['guild_press_page_block'] === '1' ) {

			foreach ($save_item as $save_item_key => $save_item_value) {

				if( $save_item_key === 'guild_press_page_block' ){

					update_post_meta( $post->ID, $save_item_key, '1' );
				}else if( $save_item_key === 'guild_press_block_member_rank' ){

					update_post_meta( $post->ID, $save_item_key, array() );
				}else{

					update_post_meta( $post->ID, $save_item_key, '' );
				}

			}
		}
	}

}
