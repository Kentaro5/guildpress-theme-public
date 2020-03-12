<?php

/**
 *
 */
class Guild_Press_Calendar_Edit
{
	public $edit_shcedule_box_path;
	public $wpfunc;
	public $basic;

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->edit_shcedule_box_path = 'templates/admin/calendar/edit_box/calendar-edit-form.php';
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';
	}

	public function edit_shcedule_box()
	{

		$schedule_task_id = ( isset($_GET['schedule_task_id']) && $_GET['schedule_task_id'] !== "" ) ? $_GET['schedule_task_id'] : '';

		$date_id = ( isset($_GET['date_id']) && $_GET['date_id'] !== "" ) ? $_GET['date_id'] : '';

		$gp_month = ( isset($_GET['gp_month']) && $_GET['gp_month'] !== "" ) ? $_GET['gp_month'] : '';

		$gp_year = ( isset($_GET['gp_year']) && $_GET['gp_year'] !== "" ) ? $_GET['gp_year'] : '';

		if($schedule_task_id !== ""){

			$edit_schedule_data =  $this->wpfunc->get_option( $schedule_task_id, false );

			$google_event_id = ( isset($edit_schedule_data['google_event_id']) && $edit_schedule_data['google_event_id'] !== "" ) ? $edit_schedule_data['google_event_id'] : '';

		}else{
			//schedule_task_idがない場合は予約一覧画面に戻す。
			$home_url = $this->wpfunc->home_url();
			$this->wpfunc->wp_redirect($home_url);

		}

		$gp_data = array(
			'id_name' => SLUGNAME.'_settings',
			'title' => $edit_schedule_data['title'],
			'date_time1' => $edit_schedule_data['date_time1'],
			'date_time2' => $edit_schedule_data['date_time2'],
			'max_num' => $edit_schedule_data['max_num'],
			'date_id' => $date_id,
			'google_event_id' => $google_event_id,
			'schedule_task_id' => $schedule_task_id,
			'gp_month' => $gp_month,
			'gp_year' => $gp_year,
			'admin_url' => $this->admin_url,
		);

		if( ! $file_path = $this->basic->load_template( $this->edit_shcedule_box_path, false ) ){

			return;
		}

		include( $file_path );

		add_action( 'admin_footer', array( $this, 'register_delete_js' ) );
	}

	public function register_delete_js()
	{
		?>
		<script>
			admin_js.calendar_form_check();
		</script>
		<?php
	}

	public function edit_new_scheule(){

		if( ! wp_verify_nonce( $_POST['notification-nonce'], 'notification' ) && !isset( $_POST["notification-nonce"] ) ) {
			return;
		}

		if( ! wp_verify_nonce( $_POST['edit_schedule'], SLUGNAME.'_edit_schedule' ) && !isset( $_POST["edit_schedule"] ) ){

			return;
		}
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$task_id = ( isset($_POST['task_id']) && $_POST['task_id'] !== "" ) ? $_POST['task_id'] : '';

		$_POST['max_num'] = ( isset($_POST['max_num']) && $_POST['max_num'] !== "" ) ? $this->basic->cut_except_num($_POST['max_num']) : '';

		$_POST['date_time1'] = ( isset($_POST['date_time1']) && $_POST['date_time1'] !== "" ) ? $this->basic->return_only_time($_POST['date_time1']) : '';

		$_POST['date_time2'] = ( isset($_POST['date_time2']) && $_POST['date_time2'] !== "" ) ? $this->basic->return_only_time($_POST['date_time2']) : '';

		//データがあるかチェックをするためにデータを取得
		$result_option = $this->wpfunc->get_option( $task_id, false );

		//更新する内容を取得
		$save_data = $this->create_save_settings();

		//既に登録がある場合はタスクを取得する。
		if( $result_option !== false ){

			//データを更新するoption_idと更新するデータをセットしてデータを更新
			$this->wpfunc->update_option( $task_id, $save_data );

		}else{

			//データがない場合はホームに飛ばす。
			$home_url = $this->wpfunc->home_url();
			$this->wpfunc->wp_redirect($home_url);
		}

		$args = array(
			'task_id' => $task_id,
			'save_data' => $save_data
		);


		do_action( 'admin_'.SLUGNAME.'_after_edit_schedule', $args );

	}

	public function create_save_settings(){

		foreach ($_POST as $key => $value) {
			$settings[ $key ] = ( ! $_POST[ $key ] || ! isset( $_POST[ $key ]  ) || ! is_string( $_POST[ $key ] ) )  ? "" : $_POST[ $key ];
		}

		return $settings;
	}

}








