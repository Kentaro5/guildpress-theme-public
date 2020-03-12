<?php

/**
 *
 */
class Guild_Press_Public_Calendar_Edit
{

	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;
		$this->load();
	}

	public function load()
	{
		$this->calendar_edit_template_path = 'templates/public/calendar/edit/calendar-edit-schedule-page.php';
	}

	public function update_user_schedule()
	{

		if( ! wp_verify_nonce( $_POST['update_user_schedule'], SLUGNAME.'_update_user_schedule' ) && !isset( $_POST["update_user_schedule"] ) ){
			return;
		}

		$google_event_id = ( isset($_POST['google_event_id']) && $_POST['google_event_id'] !== "" ) ? $_POST['google_event_id'] : '';

		$_POST['date_time1'] = ( isset($_POST['date_time1']) && $_POST['date_time1'] !== "" ) ? $this->basic->return_only_time($_POST['date_time1']) : '';

		$_POST['date_time2'] = ( isset($_POST['date_time2']) && $_POST['date_time2'] !== "" ) ? $this->basic->return_only_time($_POST['date_time2']) : '';

		$args = array(
			'date_id' => $_POST['date_id'],
			'user_key' => 'user_id',
			'schedule_id' => $_POST['s_id'],
			'google_event_id' => $google_event_id,
			'the_month' => $_POST['the_month']
		);

		$current_user_id = $this->wpfunc->get_current_user_id();

		$this->email->send_mail($current_user_id, 'user_schedule_update');

		$update_schedule_id = ( isset($_POST['update_schedule_id']) && $_POST['update_schedule_id'] !== "" ) ? $_POST['update_schedule_id'] : '';

			//各月全体を管理するオプションを登録＆更新。
		$this->wpfunc->update_option( $update_schedule_id, $_POST );

		$google_event_id = ( isset($_POST['google_event_id']) && $_POST['google_event_id'] !== "" ) ? $_POST['google_event_id'] : '';

		$args = array(
			'date_id' => $_POST['date_id'],
			'user_key' => 'user_id',
			'schedule_id' => $_POST['s_id'],
			'google_event_id' => $google_event_id,
			'the_month' => $_POST['the_month']
		);

		do_action( 'public_'.SLUGNAME.'_after_update_schedule', $args );


	}

	public function edit_shcedule_page()
	{

		global $wp;

		$wp_nonce = wp_nonce_field( SLUGNAME.'_update_user_schedule', 'update_user_schedule', false );

		$user_id = $this->wpfunc->get_current_user_id();

		$schedule_task_id = ( isset($_GET['s_id']) && $_GET['s_id'] !== "" ) ? $this->wpfunc->esc_html( $_GET['s_id'] ) : '';

			//各スケジュールのIDに_useridをつけるとオプションのIDになるので、それをまとめて変数に入れる。
		$user_option_id = $schedule_task_id.'_'.$user_id;

		$user_data = $this->wpfunc->get_option( $user_option_id, false );

		$schedule_data = $this->wpfunc->get_option($schedule_task_id);

		$google_event_id = ( isset($schedule_data['google_event_id']) && $schedule_data['google_event_id'] !== "" ) ? $schedule_data['google_event_id'] : '';

		$date_time1 = ( isset($schedule_data['date_time1']) && $schedule_data['date_time1'] !== "" ) ? $schedule_data['date_time1'] : '';
		$date_time2 = ( isset($schedule_data['date_time2']) && $schedule_data['date_time2'] !== "" ) ? $schedule_data['date_time2'] : '';

		$gp_month = ( isset($_GET['gp_month']) && $_GET['gp_month'] !== "" ) ? $_GET['gp_month'] : '';
		$gp_year = ( isset($_GET['gp_year']) && $_GET['gp_year'] !== "" ) ? $_GET['gp_year'] : '';

		$the_month = ( isset($_GET['the_month']) && $_GET['the_month'] !== "" ) ? $_GET['the_month'] : '';
		$d_id = ( isset($_GET['d_id']) && $_GET['d_id'] !== "" ) ? $_GET['d_id'] : '';

		$comment = ( isset($schedule_data['comment']) && $schedule_data['comment'] !== "" ) ? $schedule_data['comment'] : '';

		$gp_data = array(
			'wp_nonce' => $wp_nonce,
			'user_id' => $user_id,
			'schedule_task_id' => $schedule_task_id,
			'user_option_id' => $user_option_id,
			'user_data' => $user_data,
			'schedule_data' => $schedule_data,
			'date_time1' => $date_time1,
			'date_time2' => $date_time2,
			'comment' => $comment,
			'wp_request' => $wp->request,
			'google_event_id' => $google_event_id,
			'gp_month' => $gp_month,
			'gp_year' => $gp_year,
			'the_month' => $the_month,
			'd_id' => $d_id,
		);

		if( ! $file_path = $this->basic->load_template( $this->calendar_edit_template_path, false ) ){

			return;
		}

		include( $file_path );

		add_action( 'wp_footer', array( $this, 'edit_schedule_js' ), 10 );

	}

	public function edit_schedule_js()
		{
			$schedule_task_id = ( isset($_GET['s_id']) && $_GET['s_id'] !== "" ) ? $this->wpfunc->esc_html( $_GET['s_id'] ) : '';
			$data = $this->wpfunc->get_option($schedule_task_id);
			$date_time1 = $data['date_time1'];
			$date_time2 = $data['date_time2'];

			?>
			<script>
				let id_list = [
					'date_time1',
					'date_time2',
				]

				let name = [
					"時間指定1",
					"時間指定2",
				];
				calendar_js.set_fixed_time_picker( 'date_time1', '<?php echo $date_time1; ?>', '<?php echo $date_time2; ?>' );
				calendar_js.set_fixed_time_picker( 'date_time2', '<?php echo $date_time1; ?>', '<?php echo $date_time2; ?>' );

				calendar_js.change_zen_num_to_han_num( 'date_time1' );
				calendar_js.change_zen_num_to_han_num( 'date_time2' );

				calendar_js.submit_date_val( 'submit', id_list, name );

			</script>

				<?php
		}
}