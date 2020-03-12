<?php
/**
*
*/
class Guild_Press_Admin_Email_Titles
{

	public function set_admin_user_email_title( $mail_type,  $email_items, $user_id  )
	{

		switch ($mail_type) {
			case 'user_schedule_register':
					//スケジュール登録用の文章取得
			$title = $this->get_register_shedule_admin_user_title( $email_items );

					//もし管理画面でユーザーが指定した文言があればそれに変更。
			$title = apply_filters( 'guild_press_title_filter', $title, 'admin', $user_id );

			break;

			case 'user_schedule_update':
					//スケジュール変更用の文章取得
			$title = $this->get_update_shedule_admin_user_title( $email_items );

					//もし管理画面でユーザーが指定した文言があればそれに変更。
			// $title = apply_filters( 'guild_press_title_filter', $title, 'admin', $user_id );
			break;

			case 'delete_user_shedule':
					//ユーザーが予定を削除したことを管理者に知らせるタイトル
			$title = $this->get_delete_shedule_admin_title( $email_items );
			break;

			case 'user_register':
					//ユーザーが新しく登録された時に管理者に知らせる用のタイトル
			$title = $this->get_user_register_admin_user_title( $email_items );
			break;

			case 'admin_delete_schedule':
					//スケジューを削除した時のタイトルを取得
			$title = $this->get_admin_user_delete_schedule_admin_title( $email_items );
			break;

			case 'user_payment_complete':
					//ユーザーが決済を完了したことを知らせるタイトル取得
			$title = $this->get_user_payment_complete_admin_title( $email_items );
			break;

			case 'user_rank_payment_complete':
					//ユーザーが会員ランクの決済を完了したことを知らせるタイトル取得
			$title = $this->get_user_rank_payment_complete_admin_title( $email_items );
			break;

			default:
			$title = '';
			break;
		}

		return $title;

	}

	//予定を登録した時
	public function get_register_shedule_admin_user_title( $email_items )
	{
		$title = sprintf(__('[%s]%sさんが新しく予定を登録しました。'), $email_items['site_name'], $email_items['register_user_name'] );

		return $title;
	}

	//予定を変更した時
	public function get_update_shedule_admin_user_title( $email_items )
	{
		$title = sprintf(__('[%s]%sさんが登録した予定を変更しました。'), $email_items['site_name'], $email_items['register_user_name'] );

		return $title;
	}

	//予定を削除した時
	public function get_delete_shedule_admin_title( $email_items )
	{
		$title = sprintf(__('[%s]%sさんが登録した予定を削除しました。'), $email_items['site_name'], $email_items['register_user_name'] );

		return $title;
	}

	//ユーザーが登録された時
	public function get_user_register_admin_user_title( $email_items )
	{
		$title = sprintf(__('新しくユーザーが登録されました。'), $email_items['register_user_name']  );

		return $title;
	}

	//予定を削除した時
	public function get_admin_user_delete_schedule_admin_title( $email_items )
	{
		$title = sprintf(__('予定を削除しました。'), $email_items['register_user_name'] );

		return $title;
	}

	//ユーザーの決済が完了したことを管理者に知らせるメールタイトル
	public function get_user_payment_complete_admin_title( $email_items )
	{
		$title = sprintf(__('ユーザーの決済が完了しました。') );

		return $title;
	}

	//ユーザーのランクアップの決済が完了したことを知らせるメール。
	public function get_user_rank_payment_complete_admin_title( $email_items )
	{
		$title = sprintf(__('ユーザーの会員ランクアップの決済が完了しました。') );

		return $title;
	}

}









