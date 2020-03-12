<?php

/**
 *
 */
class Guild_Press_Public_Calendar_Register
{

	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->load();
	}

	public function load()
	{
		//月日をセット
		$gp_date = $this->basic->check_date();

		$gp_check_date = ( isset($gp_date) && $gp_date !== '' ) ? true : false;

		//月日をセット
		if( $gp_check_date ){

			$this->default_year_month_stamp = mktime(0, 0, 0, $gp_date['month'], 1, $gp_date['year'] );
		}else{

			$this->default_year_month_stamp = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
		}

		$this->calender_slug_name = SLUGNAME.'_register_schedule_'.$this->default_year_month_stamp;
		$this->register_schedule_template = 'templates/public/calendar/register/calendar-register-schedule-page.php';
	}

	public function register_new_schedule()
	{

		if( ! wp_verify_nonce( $_POST['register_new_schedule'], SLUGNAME.'_register_new_schedule' ) && !isset( $_POST["register_schedule"] ) ){
			return;
		}

		$schedule_email_settings = $this->wpfunc->get_option(SLUGNAME.'_schedule_email_settings');

		$current_user_id = $this->wpfunc->get_current_user_id();

		$_POST['date_id'] = ( isset($_POST['date_id']) && $_POST['date_id'] !== "" ) ? $_POST['date_id'] : '';

		$_POST['date_time1'] = ( isset($_POST['date_time1']) && $_POST['date_time1'] !== "" ) ? $this->basic->return_only_time($_POST['date_time1']) : '';

		$_POST['date_time2'] = ( isset($_POST['date_time2']) && $_POST['date_time2'] !== "" ) ? $this->basic->return_only_time($_POST['date_time2']) : '';

		//スラッグ名をセットする。
		$result_option = $this->wpfunc->get_option($this->calender_slug_name);

		//既に登録がある場合はタスクを取得する。
		if( count($result_option) > 0 && $result_option !== "" ){

				//$register_task_count = $this->return_count_num(count($result_option[$_POST['date_id']]['register_task']));
			$current_user_id = $this->wpfunc->get_current_user_id();


			$current_user_id = strval($current_user_id);
			$result_option[$_POST['date_id']]['user_id'][$_POST['s_id']][] = $current_user_id;

			$setting_id = $_POST['s_id']."_".$current_user_id;

				//各月全体を管理するオプションを登録＆更新。
			$this->wpfunc->update_option( $this->calender_slug_name, $result_option );

				//ここで新しくオプションの値をschedule_id_user_idという構成で作成。
			$this->wpfunc->add_option( $setting_id, $_POST );

		}

		$google_event_id = ( isset($_POST['google_event_id']) && $_POST['google_event_id'] !== "" ) ? $_POST['google_event_id'] : '';

		$args = array(
			'date_id' => $_POST['date_id'],
			'user_key' => 'user_id',
			'schedule_id' => $_POST['s_id'],
			'google_event_id' => $google_event_id,
			'the_month' => $_POST['the_month']
		);

		do_action( 'public_'.SLUGNAME.'_after_register_schedule', $args );

	}

	public function register_shcedule_page()
	{

		global $wp;

		$wp_nonce = wp_nonce_field( SLUGNAME.'_register_new_schedule', 'register_new_schedule', false );

		$schedule_task_id = ( isset($_GET['s_id']) && $_GET['s_id'] !== "" ) ? $this->wpfunc->esc_html( $_GET['s_id'] ) : '';

		$schedule_data = $this->wpfunc->get_option($schedule_task_id);

		$google_event_id = ( isset($schedule_data['google_event_id']) && $schedule_data['google_event_id'] !== "" ) ? $schedule_data['google_event_id'] : '';

		$date_id = ( isset($_GET['d_id']) && $_GET['d_id'] !== "" ) ? $_GET['d_id'] : '';
		$the_month = ( isset($_GET['the_month']) && $_GET['the_month'] !== "" ) ? $_GET['the_month'] : '';
		$gp_month = ( isset($_GET['gp_month']) && $_GET['gp_month'] !== "" ) ? $_GET['gp_month'] : '';
		$gp_year = ( isset($_GET['gp_year']) && $_GET['gp_year'] !== "" ) ? $_GET['gp_year'] : '';

		$date_time1 = $schedule_data['date_time1'];
		$date_time2 = $schedule_data['date_time2'];

		$gp_data = array(
			'id_name' => SLUGNAME.'_settings',
			'form_id_name' => SLUGNAME."_calender_form",
			'wp_nonce' => $wp_nonce,
			'schedule_task_id' => $schedule_task_id,
			'schedule_data' => $schedule_data,
			'date_time1' => $date_time1,
			'date_time2' => $date_time2,
			'google_event_id' => $google_event_id,
			'wp_request' => $wp->request,
			'the_month' => $the_month,
			'gp_month' => $gp_month,
			'gp_year' => $gp_year,
			'date_id' => $date_id,
		);

		if( ! $file_path = $this->basic->load_template( $this->register_schedule_template, false ) ){

			return;
		}

		include( $file_path );

		//編集画面のテンプレート出力
		//$this->render_register_template( $dat );


		add_action( 'wp_footer', array( $this, 'register_schedule_js' ), 10 );
	}

	public function render_register_template( $args )
	{
		$this->wpfunc->set_query_var( 'gp_calendar_register_schedule_data_args', $args );
		$this->wpfunc->get_template_part( '/templates/public/calendar/calendar-register-schedule-page' );
	}

	public function register_schedule_js()
	{

		$otpion_id = ( isset($_GET['s_id']) && $_GET['s_id'] !== "" ) ? $this->wpfunc->esc_html( $_GET['s_id'] ) : '';
		$data = $this->wpfunc->get_option($otpion_id);

		$date_time1 = $data['date_time1'];
		$date_time2 = $data['date_time2'];

		?>
		<script  type="text/javascript">
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