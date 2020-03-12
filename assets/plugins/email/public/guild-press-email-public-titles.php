<?php
/**
*
*/
class Guild_Press_Public_Email_Titles
{

	public function set_public_user_email_title( $mail_type, $email_items, $user_id  )
	{
		switch ($mail_type) {
			case 'user_schedule_register':
			//ユーザーが新しくスケジュールを登録した時用のタイトル
			$title = $this->get_register_shedule_public_user_title( $email_items );

			//もし管理画面でユーザーが指定した文言があればそれに変更。
			$title = apply_filters( 'guild_press_title_filter', $title, 'personal', $user_id );

			break;

			case 'user_schedule_update':

			//ユーザーがスケジュールを変更した時のタイトル取得
			$title = $this->get_update_shedule_public_user_title( $email_items );

			break;

			case 'delete_user_shedule':

			//ユーザーがスケジュールを削除した時用のタイトル取得
			$title = $this->get_delete_shedule_public_user_title( $email_items );
			break;

			case 'user_register':

			//ユーザーが新しく登録した時用のタイトル取得
			$title = $this->get_user_register_public_user_title( $email_items );
			break;


			case 'admin_delete_schedule':

			//管理者が新しくスケジュールを登録した時用のタイトルを取得
			$title = $this->get_admin_user_delete_schedule_public_title( $email_items );
			break;

			case 'user_payment_complete':

			//ユーザーが決済を完了した時に送るメール
			$title = $this->get_user_payment_complete_public_user_title( $email_items );
			break;

			case 'user_rank_payment_complete':

			//ユーザーが会員ランクの決済が完了した時に送るタイトル取得
			$title = $this->get_user_rank_payment_complete_public_user_title( $email_items );
			break;

			default:
			$title = '';
			break;
		}

		return $title;


	}

	//カレンダー登録時
	public function get_register_shedule_public_user_title( $email_items )
	{
		$title = __('カレンダーに予約完了のお知らせ。');

		return $title;
	}

	//カレンダーの予約が完了した時
	public function get_update_shedule_public_user_title( $email_items )
	{
		$title = __('カレンダーの予約変更完了のお知らせ。');

		return $title;
	}

	//カレンダーに登録した予定の削除が終わった時
	public function get_delete_shedule_public_user_title( $email_items )
	{
		$title = __('カレンダーの予約削除完了のお知らせ。');

		return $title;
	}

	//ユーザーが登録した時
	public function get_user_register_public_user_title( $email_items )
	{
		$title = sprintf(__('[%s]への登録が完了しました。'), $email_items['site_name'], $email_items['register_user_name'] );

		return $title;
	}

	//管理者が登録していた予定を削除した時
	public function get_admin_user_delete_schedule_public_title( $email_items )
	{
		$title = sprintf(__('管理者が予定を削除しました。'), $email_items['site_name'], $email_items['register_user_name'] );

		return $title;
	}

		//ユーザーの決済が完了したことを管理者に知らせるメールタイトル
	public function get_user_payment_complete_public_user_title( $email_items )
	{
		$title = sprintf(__('決済が完了しました。') );

		return $title;
	}

	//ユーザーのランクアップの決済が完了したことを知らせるメール。
	public function get_user_rank_payment_complete_public_user_title( $email_items )
	{
		$title = sprintf(__('会員ランクアップの決済が完了しました。') );

		return $title;
	}

}


















