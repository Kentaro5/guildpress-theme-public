<?php
/**
*
*/
class Guild_Press_Admin_Email_Messages
{
	public function __construct()
	{
		$this->wpfunc = new WpFunc();
	}
	public function set_admin_user_email_message( $mail_type='',  $email_items, $user_id  )
	{

		if( $mail_type === '' ){

			return '';
		}

			//メッセージセット
		switch ($mail_type) {
			case 'user_schedule_register':
					//スケジュール登録用の文章取得
			$message = $this->get_register_shedule_admin_user_message( $email_items );

					//もし管理画面でユーザーが指定した文言があればそれに変更。
			$message = apply_filters( 'guild_press_message_filter', $message, 'admin', $user_id );

			break;

			case 'user_schedule_update':
					//スケジュール変更用の文章取得
			$message = $this->get_update_shedule_admin_user_message( $email_items );

			//もし管理画面でユーザーが指定した文言があればそれに変更。
			//$message = apply_filters( 'guild_press_message_filter', $message, 'admin', $user_id );
			break;

			case 'delete_user_shedule':
					//ユーザーがスケジュールを削除した時用の文章を取得
			$message = $this->get_delete_shedule_admin_user_message( $email_items );
			break;

			case 'user_register':
					//ユーザーが登録した時ようの文章を取得
			$message = $this->get_user_register_admin_user_message( $email_items );
			break;

			case 'admin_delete_schedule':
					//アドミンがスケジュールを削除した時用の文章を取得
			$message = $this->setAdminDeleteScheduleMessege( $email_items );
			break;

			case 'user_payment_complete':
					//ユーザーが決済を完了した時に送る文章を取得
			$message = $this->get_user_payment_complete_admin_user_message( $email_items );
			break;

			case 'user_rank_payment_complete':
					//ユーザーが会員ランクの決済を完了した時に送る文章を取得
			$message = $this->get_user_rank_payment_complete_admin_message( $email_items, $user_id );
			break;

			default:
			$message = '';
			break;
		}

		return $message;
	}

	//スケジュール登録用の文章取得
	public function get_register_shedule_admin_user_message( $email_items )
	{
		$message  = sprintf( __('%sさんが新しく予定を登録しました。'), $email_items['register_user_name']  ) . "\r\n\r\n";
		$message .= sprintf( __('ユーザー名: %s'), $email_items['register_user_name']  ) . "\r\n\r\n";
		$message .= sprintf( __('メールアドレス: %s'), $email_items['register_user_email']  ) . "\r\n";

		$message .= sprintf( __('登録した日付: %s'), $email_items['registed_date']  ) . "\r\n";

		$message .= sprintf( __('登録した時間帯: %s~%s '), $email_items['registered_time1'] , $email_items['registered_time2']   ) . "\r\n";

		$message .= sprintf( __('ユーザーからのコメント: %s'), $email_items['registered_comment'] ) . "\r\n";

		return $message;
	}



	//スケジュール変更用の文章取得
	public function get_update_shedule_admin_user_message( $email_items )
	{
		$message  = sprintf( __('%sさんが登録した予定を変更しました。'), $email_items['register_user_name']  ) . "\r\n\r\n";
		$message .= sprintf( __('ユーザー名: %s'), $email_items['register_user_name']  ) . "\r\n\r\n";
		$message .= sprintf( __('メールアドレス: %s'), $email_items['register_user_email']  ) . "\r\n";

		$message .= sprintf( __('登録した日付: %s'), $email_items['registed_date']  ) . "\r\n";

		$message .= sprintf( __('登録した時間帯: %s~%s '), $email_items['registered_time1'] , $email_items['registered_time2']   ) . "\r\n";


		$message .= sprintf( __('ユーザーからのコメント: %s'), $email_items['registered_comment'] ) . "\r\n";

		return $message;
	}

	//スケジュール削除用の文章取得
	public function get_delete_shedule_admin_user_message( $email_items )
	{
		$message  = sprintf( __('%sさんが登録した予定を削除しました。'), $email_items['register_user_name'] ) . "\r\n\r\n";
		$message .= sprintf( __('ユーザー名: %s'), $email_items['register_user_name'] ) . "\r\n\r\n";
		$message .= sprintf( __('メールアドレス: %s'), $email_items['register_user_email'] ) . "\r\n";

		$message .= sprintf( __('削除した日付: %s'), $email_items['registed_date'] ) . "\r\n";

		return $message;
	}

	//スケジュール変更用の文章取得
	public function get_user_register_admin_user_message( $email_items )
	{

		$message  = sprintf( __('ユーザーが新しく登録されました。'), $email_items['register_user_name'] ) . "\r\n\r\n";
		$message .= sprintf( __('ユーザー名: %s'), $email_items['register_user_name'] ) . "\r\n\r\n";
		$message .= sprintf( __('メールアドレス: %s'), $email_items['register_user_email'] ) . "\r\n";

		$message .= sprintf( __('登録した日付: %s'), date_i18n("Y-n-j H:i:s") ) . "\r\n";

		return $message;
	}


	//管理者が管理者側のページで、予定を削除した時に使用するメッセージ
	public function setAdminDeleteScheduleMessege( $email_items )
	{

		$message  = sprintf( __('予定を削除しました。'), $email_items['register_user_name'] ) . "\r\n\r\n";
		$message .= sprintf( __('削除した予定の日付: %s'),  $email_items['registed_date']  ) . "\r\n";

		return $message;
	}




	//スケジュール削除用の文章作成
	public function get_user_payment_complete_admin_user_message( $email_items )
	{

		$message  = sprintf( __('登録されたユーザーの決済が完了しました。'), $email_items['register_user_name']  ) . "\r\n\r\n";
			//$message  = sprintf( __('削除した予定のタイトル：%s'), $email_items['register_user_name']  ) . "\r\n\r\n";

		$message  = sprintf( __('決済が完了したユーザー名%s'), $email_items['register_user_name']  ) . "\r\n\r\n";

		return $message;
	}


	//会員ランクを紐づけた場合のメールの文章
	public function get_user_rank_payment_complete_admin_message( $email_items, $user_id )
	{
		$member_id = $this->wpfunc->get_user_meta( $user_id, 'gp_member_rank', true );
		$member_rank = $this->wpfunc->get_post_meta( $member_id, 'member_rank_name', true );

		$message  = sprintf( __('ユーザーの会員ランクアップの決済が完了しました。'), $email_items['register_user_name']  ) . "\r\n\r\n";
			//$message  = sprintf( __('削除した予定のタイトル：%s'), $email_items['register_user_name']  ) . "\r\n\r\n";

		$message  = sprintf( __('決済が完了したユーザー名:%s'), $email_items['register_user_name']  ) . "\r\n\r\n";

		$message  .= sprintf( __('ランクアップした会員ランク:%s'), $member_rank  ) . "\r\n\r\n";

		return $message;
	}


}