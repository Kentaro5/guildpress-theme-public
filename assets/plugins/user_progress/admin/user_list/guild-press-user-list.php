<?php

/**
 *
 */
class Guild_Press_User_List
{
	public $user_list_box_path;
	public $guild_press_manage_customer_link;
	public $user_id;
	public $user_lesson_progress;
	public $profile_link;
	public $progress_abr_arg;
	public $lesson_posts_str;
	public $user_lesson_data_str;
	public $progress_bar_num;

	public function __construct()
	{

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->guild_press_manage_customer_link = 'admin.php?page=guild_press_manage_customer';
		$this->user_list_box_path =  'templates/admin/user_progress/user_list/user-list.php';
	}

	public function set_user_progress_info( $users_obj )
	{
		$this->user_id = $users_obj->ID;
		//ここで進捗状況を取得
		$this->user_lesson_progress = $this->basic->getUserLessonProgress( $this->user_id );
		$this->profile_link = $this->wpfunc->get_edit_user_link( $this->user_id );
	}

	public function set_progress_bar_info( $taxnomy_name, $taxnomy_arr )
	{
		//プロレグレスバーの数字を取得
		$this->progress_abr_arg = $this->basic->getProgressBarArg( $taxnomy_name, $taxnomy_arr );

		$this->set_lesson_post_str_num( $this->progress_abr_arg );
		$this->set_user_lesson_data_str_num( $this->progress_abr_arg );
		$this->set_progress_bar_num( $this->progress_abr_arg );
	}

	public function set_progress_bar_num( $progress_abr_arg )
	{
		$this->progress_bar_num = $progress_abr_arg['progress_bar_num'];
	}

	public function set_lesson_post_str_num( $progress_abr_arg )
	{
		$this->lesson_posts_str = $progress_abr_arg['str_lesson_posts_str'];
	}

	public function set_user_lesson_data_str_num( $progress_abr_arg )
	{
		$this->user_lesson_data_str = $progress_abr_arg['user_data_lesson_str'];
	}

	public function get_customer_info()
	{

		//GETでページングのナンバーを取得　
		if( isset( $_GET['pagination'] ) && $_GET['pagination'] !== "" ){

			$page = (int) $_GET['pagination'];
			$offset = GUILD_PRESS_GET_USER_NUMBER * ( $page - 1 );
			$now_page = $page;

		}else{

			$page = 1;
			$offset = 0;
			$now_page = $page;
		}

		//ユーザー一覧を取得する。
		$users = $this->wpfunc->get_users( array( 'number' => GUILD_PRESS_GET_USER_NUMBER, 'offset' => $offset, 'orderby' => 'ID', 'order' => 'DESC' ) );

		//トータルのユーザー数取得
		$total_users = count($this->wpfunc->get_users());

		//ページングに関する値を取得
		$paging_arr = $this->basic->getPagingNum( $now_page, $total_users, GUILD_PRESS_GET_USER_NUMBER );

		$total_pages = $paging_arr['total_pages'];
		$next_page = $paging_arr['next_page'];
		$before_page = $paging_arr['before_page'];

		$pagination = ( isset( $_GET['pagination']) &&  $_GET['pagination'] !== "" ) ?  $_GET['pagination'] : 1;
 		$first_page_link = $this->guild_press_manage_customer_link."&pagination=1";
 		$prev_link = $this->guild_press_manage_customer_link."&pagination=".$before_page;

 		$next_link = $this->guild_press_manage_customer_link."&pagination=".$next_page;
 		$last_page_link = $this->guild_press_manage_customer_link."&pagination=".$total_pages;

		$gp_data = array(
			'users' => $users,
			'pagination' => $pagination,
			'first_page_link' => $first_page_link,
			'prev_link' => $prev_link,
			'next_link' => $next_link,
			'now_page' => $now_page,
			'total_pages' => $total_pages,
			'last_page_link' => $last_page_link,
		);

		if( ! $file_path = $this->basic->load_template( $this->user_list_box_path, false ) ){

            return;
        }

        include( $file_path );
	}
}