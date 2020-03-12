<?php
/**
*
*/
class LockContentModel
{

	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;
		$this->admin_url='';
	}

	public function content_lock_check( $content )
	{
		$post_id = get_the_ID();
		$guild_press_lock_page = $this->wpfunc->get_post_meta( $post_id, 'guild_press_lock_page' );
		$guild_press_lock_page = $this->basic->check_value_of_post_meta( $guild_press_lock_page );

		//鍵を掛けるにチェックが入っているかどうかを確認。
		if( $guild_press_lock_page[0] === '2' ){

			$prev_post = get_previous_post( true, '', 'guild_lesson_category' );

			if( $prev_post === '' ){

				$prev_post_id = 0;
			}else{
				$prev_post_id = $prev_post->ID;
			}
			$user_lesson_detail = $this->basic->return_user_complete_lesson_lists( $post_id );
			$user_lesson_detail[0] = $this->basic->check_array( $user_lesson_detail[0] );

			//ポストIDを文字列化
			$str_now_post_id = strval($post_id);
			$str_prev_post_id = strval($prev_post_id);

			$is_now_post_comp = $this->basic->in_array( $str_now_post_id , $user_lesson_detail[0]);
			$is_prev_post_comp = $this->basic->in_array( $str_prev_post_id , $user_lesson_detail[0]);

			if( $is_prev_post_comp || $is_now_post_comp ){

				//元のコンテンツを返す。
				return $content;
			}else{

				$message = $this->basic->get_lock_lesson_message();
				return $message;
			}
		}else{

			//元のコンテンツを返す。
			return $content;
		}
	}
}
