<?php
/**
*
*/
class QuizPageBox
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
		add_meta_box( 'gp_page_quiz_box', 'クイズを追加する',  array( $this,'page_qyuz' ) , 'guild_lesson_detail', 'normal', 'high' );
	}

	public function page_qyuz(){


		wp_nonce_field( 'guild_press_quiz', 'guild_press_quiz_key', false );

		$post_id = ( isset($_GET['post']) && $_GET['post'] !== "" ) ? intval( $_GET['post'] ) : '';

		$guild_press_quiz_check = $this->wpfunc->get_post_meta( $post_id, 'guild_press_quiz_check', true   );
		$guild_press_quiz_text = $this->wpfunc->get_post_meta( $post_id, 'guild_press_quiz_text', true  );

		$guild_press_quiz_answer_text = $this->wpfunc->get_post_meta( $post_id, 'guild_press_quiz_answer_text', true );

		$guild_press_quiz_correct_answer = $this->wpfunc->get_post_meta( $post_id, 'guild_press_quiz_correct_answer', true );

		if($guild_press_quiz_correct_answer !== ''  ){

			$correct_anwer_num = intval( $guild_press_quiz_correct_answer );
		}else{

			$correct_anwer_num = 0;
		}

		if($guild_press_quiz_answer_text !== ''  ){

			$guild_press_quiz_answer_text_arr = unserialize($guild_press_quiz_answer_text);
		}else{

			$guild_press_quiz_answer_text_arr = array( 0 => '' );
		}


		//最初は配列に値が入っていないため、初期値をセットする。
		if( $guild_press_quiz_check === '' ){

			$guild_press_quiz_check = '1';
		}



		?>

		<h4>このコンテンツにクイズを追加する。</h4>
		<p>
			<input type="radio" id="guild_press_page_non_quiz" name="guild_press_quiz_check" value="1" <?php checked( $guild_press_quiz_check, "1" ); ?> />
			<label for="guild_press_quiz_check">クイズを追加しない場合はこちらにチェックを入れて下さい。</label>
		</p>

		<p>
			<input type="radio" id="guild_press_page_quiz" name="guild_press_quiz_check" value="2" <?php checked( $guild_press_quiz_check, "2" ); ?> />
			<label for="guild_press_quiz_check">クイズを追加する場合は、こちらにチェックを入れて下さい。</label>
		</p>

		<div id="guild_press_quiz_box" style="display:none;">
			<h4>クイズの問題を入力してください。</h4>
			<textarea name="guild_press_quiz_text" style="width: 100%;" rows="6"><?php echo $this->basic->trim_script( $guild_press_quiz_text ); ?></textarea>

			<h4>クイズの答えを入力してください。</h4>
			<div id="guild_press_quiz_answer_box">
				<?php for ($i=0; $i < count( $guild_press_quiz_answer_text_arr ); $i++) : ?>
					<div class="relative mb18" id="guild_press_quiz_answer_texts">
						<label>解答
						<input type="text" class="width100 input_design" name="guild_press_quiz_answer_text[]" id="guild_press_quiz_answer_text" value="<?php echo $this->wpfunc->esc_html( $guild_press_quiz_answer_text_arr[$i] ); ?>">
						</label>

						<?php if( $correct_anwer_num === $i && $guild_press_quiz_answer_text_arr[$i] !== '' ) : ?>

							<label>正しい答えにチェックを入れて下さい。
								<input type="radio" class="width100 input_design" name="guild_press_quiz_correct_answer" id="guild_press_quiz_correct_answer" checked value="<?php echo $i; ?>">
							</label>
						<?php else :  ?>
							<label>正しい答えにチェックを入れて下さい。
								<input type="radio" class="width100 input_design" name="guild_press_quiz_correct_answer" id="guild_press_quiz_correct_answer" value="<?php echo $i; ?>">
							</label>
						<?php endif; ?>

						<div class="position_right" id="guild_press_remove_box">
							<a href="#" id="guild_press_remove_box" class="gp_remove_element" style="color: #555;" >☓</a>
						</div>

					</div>
				<?php endfor; ?>
			</div>

			<button type="button" class="button" id="guild_press_add_quiz_answer" >フィールド追加</button>

		</div>

		<?php
		add_action( 'admin_footer', array( $this, 'page_quiz_box_js' ) );
	}

	public function page_quiz_box_js()
	{
		?>
			<script type="text/javascript">
				admin_js.register_quiz_event();
				admin_js.check_quiz_check_box();
			</script>
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
			'guild_press_quiz_check' => '1',
			'guild_press_quiz_text' => '',
			'guild_press_quiz_answer_text' => '',
			'guild_press_quiz_correct_answer' => '0'
		);



		$save_item = $this->basic->check_save_item( $_POST, $save_item_arr );



		$save_item['guild_press_quiz_answer_text'] = serialize($save_item['guild_press_quiz_answer_text']);

		// Need the post object.
		global $post;
		// Update accordingly.
		if ( $save_item['guild_press_quiz_check'] === '2' ) {

			if( $save_item['guild_press_quiz_answer_text'] === '' ){

				//空の場合は、各ポストメタを削除
				$this->basic->update_custom_post( $post->ID, $save_item_arr );
			}else{

				//クイズにチェックが入っている場合は、各ポストメタを保存
				$this->basic->update_custom_post( $post->ID, $save_item );
			}
		} else if ( $save_item['guild_press_quiz_check'] === '1' ) {

			//クイズにチェックが入っていいない場合は、各ポストメタを削除
			$this->basic->update_custom_post( $post->ID, $save_item_arr );
		}
	}

}
















