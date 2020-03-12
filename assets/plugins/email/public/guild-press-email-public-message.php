<?php

/**
 * 
 */
class Guild_Press_Public_Email_Messages
{
	public function set_public_user_email_message( $mail_type, $email_items, $user_id, $member_rank=''  )
	{
			//メッセージセット
		switch ($mail_type) {
			case 'user_schedule_register':

			//スケジュール登録用の文章取得
			$message = $this->get_register_shedule_public_user_message( $email_items );

			//もし管理画面でユーザーが指定した文言があればそれに変更。
			$message = apply_filters( 'guild_press_message_filter', $message, 'personal', $user_id );
			break;

			case 'user_schedule_update':

			//スケジュール変更用の文章取得
			$message = $this->get_update_shedule_public_user_message( $email_items );

			//もし管理画面でユーザーが指定した文言があればそれに変更。
			//$message = apply_filters( 'guild_press_message_filter', $message, 'personal', $user_id );
			break;

			case 'delete_user_shedule':

			//ユーザーがスケジュールを削除した時のメッセージを取得
			$message = $this->get_delete_shedule_public_user_message( $email_items );	
			break;

			case 'user_register':

			//ユーザーが登録した時のメッセージを取得
			$message = $this->get_user_register_public_user_message( $email_items );	
			break;

			case 'admin_delete_schedule':

			//管理者が予定を削除した時のメッセージを取得
			$message = $this->get_admin_user_delete_schedule_public_user_messege( $email_items );	
			break;

			case 'user_payment_complete':

			//ユーザーが決済を完了した時に送るメッセージを取得
			$message = $this->get_user_payment_complete_public_user_message( $email_items );	
			break;

			case 'user_rank_payment_complete':

			//会員ランクの決済をした時に送るメッセージを取得
			$message = $this->get_user_rank_payment_complete_public_user_message( $email_items, $member_rank );
			break;


			default:
			$message = '';
			break;
		}

		return $message;

	}

	//管理者が予定を削除した時の文章
	public function get_admin_user_delete_schedule_public_user_messege( $email_items )
	{

		$message  = sprintf( __('管理者が予定を削除しました。'), $email_items['register_user_name']  ) . "\r\n\r\n";
			//$message  = sprintf( __('削除した予定のタイトル：%s'), $email_items['register_user_name']  ) . "\r\n\r\n";

		$message  = sprintf( __('削除した予定の日付：%s'), $email_items['registed_date']  ) . "\r\n\r\n";

		$message .= sprintf( __('もし、何かご質問などありましたら下記アドレスよりお問い合わせいただくようお願い致します。') ) . "\r\n\r\n";

		$message .= sprintf( __(' %s.'), $email_items['admin_email']  ) . "\r\n\r\n";


		return $message;
	}
	//スケジュール登録用の文章取得
	public function get_register_shedule_public_user_message( $email_items )
	{

			//ユーザー側にお知らせメールを送信
		$message = sprintf( __("指定した日付のカレンダーの予約が完了致しました。"), get_option('blogname')) . "\r\n\r\n";

		$message = sprintf( __("%s様の予約した内容は以下のようになっております。"),  $email_items['register_user_name']  ) . "\r\n\r\n";

		$message .= sprintf( __('登録した日付: %s'), $email_items['registed_date']  ) . "\r\n";

		$message .= sprintf( __('登録した時間帯: %s~%s '), $email_items['registered_time1'] , $email_items['registered_time2']   ) . "\r\n";

		$message .= sprintf( __('入力されたコメント: %s'), $email_items['registered_comment'] ) . "\r\n";


		$message .= sprintf( __('もし、何かご質問などありましたら下記アドレスよりお問い合わせいただくようお願い致します。') ) . "\r\n\r\n";

		$message .= sprintf( __(' %s.'), $email_items['admin_email'] ) . "\r\n\r\n";

		return $message;
	}

	//スケジュール変更を変更した時用の文章
	public function get_update_shedule_public_user_message( $email_items )
	{


			//ユーザー側にお知らせメールを送信
		$message = sprintf( __("指定した日付のカレンダーの予約を変更致しました。"), get_option('blogname')) . "\r\n\r\n";

		$message = sprintf( __("%s様の変更した内容は以下のようになっております。"),  $email_items['register_user_name']  ) . "\r\n\r\n";

		$message .= sprintf( __('変更した日付: %s'), $email_items['registed_date']  ) . "\r\n";


		$message .= sprintf( __('変更した時間帯: %s~%s '), $email_items['registered_time1'] , $email_items['registered_time2']   ) . "\r\n";

		$message .= sprintf( __('入力されたコメント: %s'), $email_items['registered_comment'] ) . "\r\n";

		$message .= sprintf( __('もし、何かご質問などありましたら下記アドレスよりお問い合わせいただくようお願い致します。') ) . "\r\n\r\n";
		$message .= sprintf( __(' %s.'), $email_items['admin_email'] ) . "\r\n\r\n";

		return $message;
	}

	//スケジュール削除した時用の文章
	public function get_delete_shedule_public_user_message( $email_items )
	{
		$message = sprintf( __("指定した日付のカレンダーの予約を削除致しました。"), get_option('blogname')) . "\r\n\r\n";

		$message .= sprintf( __("%s様が削除された内容は以下のようになっております。"),  $email_items['register_user_name'] ) . "\r\n\r\n";

		$message .= sprintf( __('登録した日付: %s'), $email_items['registed_date'] ) . "\r\n";

		$message .= sprintf( __('もし、何かご質問などありましたら下記アドレスよりお問い合わせいただくようお願い致します。') ) . "\r\n\r\n";
		$message .= sprintf( __(' %s.'), $email_items['admin_email'] ) . "\r\n\r\n";

		return $message;
	}


	//ユーザーが登録した時用の文章
	public function get_user_register_public_user_message( $email_items )
	{

		$message  = sprintf( __('ユーザー登録が完了しました！'), $email_items['register_user_name'] ) . "\r\n\r\n";
		$message .= sprintf( __('ユーザー名: %s'), $email_items['register_user_name'] ) . "\r\n\r\n";
		$message .= sprintf( __('メールアドレス: %s'),  $email_items['register_user_email'] ) . "\r\n";
		$message .= sprintf( __('パスワード: 設定されたパスワード'), $email_items['register_user_email'] ) . "\r\n";
		$message .= sprintf( __('登録した日付: %s'), date_i18n("Y-n-j H:i:s") ) . "\r\n";

		$message .= sprintf( __('もし、何かご質問などありましたら下記アドレスよりお問い合わせいただくようお願い致します。') ) . "\r\n\r\n";
		$message .= sprintf( __(' %s.'), $email_items['admin_email'] ) . "\r\n\r\n";


		return $message;
	}


	//決済が完了した時用の文章
	public function get_user_payment_complete_public_user_message( $email_items )
	{

		$message  = sprintf( __('決済が完了しました。') ) . "\r\n\r\n";

		return $message;
	}

	//会員ランクを紐づけた場合のメールの文章
	public function get_user_rank_payment_complete_public_user_message( $email_items, $member_rank='' )
	{

		$message  = sprintf( __('会員ランクアップの決済が完了しました。') ) . "\r\n\r\n";
		$message  .= sprintf( __('ランクアップした会員ランク:%s'), $member_rank  ) . "\r\n\r\n";

		return $message;
	}
}










