<?php
/**
*
*/
class MemberRankModel
{

	public function __construct()
	{
		// $this->load();
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;
		$this->admin_url='';
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

	//update_post_metaで使う値をチェックして返す。
	public function check_save_item( $check_item, $save_item_arr=array() )
	{

		$return_item = array();
		foreach ($save_item_arr as $key => $value) {

			$return_item[$key] = ( isset($check_item[$key]) && $check_item[$key] !== "" ) ? $check_item[$key] : '';
		}

		return $return_item;
	}

	public function check_login( $the_content )
	{
		//現在のページのID取得
		$post_id = $this->wpfunc->get_the_ID();
		$posts_data = $this->wpfunc->get_post_meta( $post_id );

		//ページブロック指定がされていたら、各ランクとユーザーのランキングをチェックする。
		if( isset( $posts_data['guild_press_page_block'][0] ) && $posts_data['guild_press_page_block'][0] === '2' ){

			$guild_press_page_block_text = $this->wpfunc->get_post_meta( $post_id, 'guild_press_block_member_text' );

			$block_member_rank_arr = unserialize( $posts_data['guild_press_block_member_rank'][0] );

			$current_user_id = $this->wpfunc->get_current_user_id();
			$gp_member_rank = $this->wpfunc->get_user_meta( $current_user_id, 'gp_member_rank' );
			$gp_member_rank = $this->basic->check_associative_array( $gp_member_rank );

			if( $block_member_rank_arr === false ){

				$block_member_rank_arr = array();
			}

			$check_result = $this->basic->in_array( $gp_member_rank[0], $block_member_rank_arr );
			$is_user_admin = $this->wpfunc->current_user_can( 'administrator' );
			//ランクの制限がある場合は、ランクの制限があることをユーザーに伝える。
			if( $check_result !== true && $is_user_admin !== true ){

				if( $guild_press_page_block_text[0] !== '' ){

					$desc = $this->basic->add_br_tag( $guild_press_page_block_text[0] );

					return '<p>'.$this->basic->trim_script( $desc ).'</p>';
				}else{
					for ($i=0; $i < count($block_member_rank_arr); $i++) {

						$custom_post_id = intval( $block_member_rank_arr[$i] );
						$block_members = $this->wpfunc->get_post_custom( $custom_post_id );

						if( $i === 0 ){

							$output_text = $block_members['member_rank_name'][0];
						}else{

							$output_text .= ','.$block_members['member_rank_name'][0];
						}

					}

					return '<p>ここは、'.$output_text.'専用ページです。</p>';
				}


			}else{

				return $the_content;
			}

		}else{

			return $the_content;
		}
	}

	public function update_user_rank_info( $args )
	{
		/*
			一回の決済の処理が完了するごとに通知が来るから、ステータスの更新は一回のみにするようにする。
		*/

		$post_items = $args['post_items'];

		//ユーザーのPayPal設定を取得。
		$user_payment_status = get_user_meta( $args['current_user_id'], 'p_s_id_'.$args['custom_items'][1], true );

		$user_payment_status = ( ! empty( $user_payment_status ) ) ? $user_payment_status : '';

		//ステータス更新は一度のみ行う。
		if( $user_payment_status === '' ){

			update_user_meta( $args['current_user_id'], 'gp_member_rank', $args['custom_items'][1] );

			//ランクアップした会員ランク名を取得
			$rank_name = $this->wpfunc->get_post_meta( intval( $args['custom_items'][1] ), 'member_rank_name' );

			//ユーザーと管理者に決済完了のお知らせメールを飛ばす。
			$this->email->send_mail( intval( $args['current_user_id'] ), 'user_rank_payment_complete', $rank_name[0] );
		}
	}

	public function add_user_member_rank_payment_id( $args )
	{
		/*
			一回の決済の処理が完了するごとに通知が来るから、ステータスの更新は一回のみにするようにする。
		*/

		$post_items = $args['post_items'];

		//ユーザーのPayPal設定を取得。
		$user_payment_status = get_user_meta( $args['current_user_id'], 'p_s_id_'.$args['custom_items'][1], true );

		$user_payment_status = ( ! empty( $user_payment_status ) ) ? $user_payment_status : '';

		//ステータス更新は一度のみ行う。
		if( $user_payment_status === '' ){

			//ユーザーのPayPalの支払いIDを更新する。
			update_user_meta( $args['current_user_id'], 'p_s_id_'.$args['custom_items'][1], $post_items['subscr_id'] );
		}
	}

}


