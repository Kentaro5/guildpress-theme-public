<?php

/**
 *
 */
class Guild_Press_Calendar_Register
{

	public $wpfunc;
	public $basic;
	public $gp_check_date;
	public $this_month;
	public $gp_date;
	public $add_new_shcedule_list_path;
	public $new_shcedule_box_path;
	public $calender_slug_name;


	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		//月日をセット
		$this->gp_date = $this->basic->check_date();

		$this->gp_check_date = ( isset($this->gp_date) && $this->gp_date !== '' ) ? true : false;

		//月日をセット
		if( $this->gp_check_date ){

			$this->default_year_month_stamp = mktime(0, 0, 0, $this->gp_date['month'], 1, $this->gp_date['year'] );
		}else{

			$this->default_year_month_stamp = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
		}
		$this->calender_slug_name = SLUGNAME.'_register_schedule_'.$this->default_year_month_stamp;

		$this->schedule_slug_name = '';

		$this->add_new_shcedule_list_path = 'templates/admin/calendar/add_new_list/add-new-list.php';

		$this->new_shcedule_box_path = 'templates/admin/calendar/new_box/calendar-new-form.php';
	}

	public function register_shcedule_box()
	{
		$gp_month = ( isset($_GET['gp_month']) && $_GET['gp_month'] !== "" ) ? $_GET['gp_month'] : '';
		$gp_year = ( isset($_GET['gp_year']) && $_GET['gp_year'] !== "" ) ? $_GET['gp_year'] : '';
		$date_id = ( isset($_GET['date_id']) && $_GET['date_id'] !== "" ) ? $_GET['date_id'] : '';

		$gp_data = array(
			'id_name' =>  SLUGNAME.'_settings',
			'gp_month' => $gp_month,
			'gp_year' => $gp_year,
			'date_id' => $date_id,
		);
		if( ! $file_path = $this->basic->load_template( $this->new_shcedule_box_path, false ) ){

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

	public function register_schedule_list_box(){

		// カレンダー対象年月
		if (isset($_GET['gp_year']) && isset($_GET['gp_month'])) {

			$this->default_year_month_stamp = mktime(0, 0, 0, $_GET['gp_month'], 1, $_GET['gp_year']);
		} else {

			$this->default_year_month_stamp = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
		}

		$weeks = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

		$this_time = mktime(0, 0, 0, date_i18n('n'), date_i18n('j'), date_i18n('Y'));

		// カレンダー生成パラメータ
		$default_year = date('Y', $this->default_year_month_stamp);
		$default_month = date('n', $this->default_year_month_stamp);

		// リンク
		$prev_month = mktime(0, 0, 0, $default_month - 1, 1, $default_year);
		$prev_str = date('Y-m', $prev_month);
		$next_month = mktime(0, 0, 0, $default_month + 1, 1, $default_year);
		$next_str = date('Y-m', $next_month);

		$days = (mktime(0, 0, 0, $default_month + 1, 1, $default_year) - $this->default_year_month_stamp) / 86400;

		$starti = date('w', $this->default_year_month_stamp);
		$endi = $starti + $days + 5 - date('w', mktime(0, 0, 0, $default_month, $days, $default_year));

		$day = 1 - $starti;

		$this_month = ( isset($_GET['gp_month']) && $_GET['gp_month'] !== "" ) ? $_GET['gp_month'] : date_i18n('n');
		$this_year = ( isset($_GET['gp_year']) && $_GET['gp_year'] !== "" ) ? $_GET['gp_year'] : date_i18n('Y');


		$general = $this->wpfunc->get_option( $this->calender_slug_name, false );

		$prev_link = '?page='.SLUGNAME.'_register_schedule_list'.'&gp_year='.date('Y', $prev_month)
		. '&gp_month='.date('n', $prev_month).'&action=monthly&tab=register_schedule_list';
		$next_link = '?page='.SLUGNAME.'_register_schedule_list'.'&gp_year='.date('Y', $next_month)
		. '&gp_month='.date('n', $next_month).'&action=monthly&tab=register_schedule_list';

		$gp_data = array(
			'year_month_text' => date('Y-m', $this->default_year_month_stamp),
			'prev_link' => $prev_link,
			'prev_str' => $prev_str,
			'next_link' => $next_link,
			'next_str' => $next_str,
			'weeks' => $weeks,
			'day' => $day,
			'endi' => $endi,
			'this_month' => $this_month,
			'this_year' => $this_year,
		);

		if( ! $file_path = $this->basic->load_template( $this->add_new_shcedule_list_path, false ) ){

			return;
		}

		include( $file_path );
	}

	public function get_calendar_register_link( $this_year_month_day_stamp, $this_month, $this_year )
	{
		$link = '?page='.SLUGNAME.'_register_schedule';
		$link .= '&amp;date_id='.$this_year_month_day_stamp;
		$link .= '&amp;tab=register_schedule';
		$link .= '&amp;gp_month='.$this_month;
		$link .= '&amp;gp_year='.$this_year;

		return $link;
	}

	public function save_new_scheule(){

		if( ! wp_verify_nonce( $_POST['notification-nonce'], 'notification' ) && !isset( $_POST["notification-nonce"] ) ) {
			return;
		}

		if( ! wp_verify_nonce( $_POST['register_schedule'], SLUGNAME.'_register_schedule' ) && !isset( $_POST["register_schedule"] ) ){
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}


		$_POST['date_id'] = ( isset($_POST['date_id']) && $_POST['date_id'] !== "" ) ? $_POST['date_id'] : '';

		$_POST['max_num'] = ( isset($_POST['max_num']) && $_POST['max_num'] !== "" ) ? $this->basic->cut_except_num($_POST['max_num']) : '';

		$_POST['date_time1'] = ( isset($_POST['date_time1']) && $_POST['date_time1'] !== "" ) ? $this->basic->return_only_time($_POST['date_time1']) : '';

		$_POST['date_time2'] = ( isset($_POST['date_time2']) && $_POST['date_time2'] !== "" ) ? $this->basic->return_only_time($_POST['date_time2']) : '';

		//スラッグ名をセットする。
		$result_option = $this->wpfunc->get_option( $this->calender_slug_name, false );

		if( $result_option === false ){

			$result_option = array();
			$result_option[$_POST['date_id']] = array();
			$result_option[$_POST['date_id']]['register_task'] = array();
		}

		$result_option[$_POST['date_id']]['register_task'] = ( isset($result_option[$_POST['date_id']]['register_task']) && $result_option[$_POST['date_id']]['register_task'] !== "" ) ? $result_option[$_POST['date_id']]['register_task'] : '';

		$register_task_arr = $this->basic->null_check_arr( $result_option[$_POST['date_id']]['register_task'], true );

		//既に登録がある場合はタスクを取得する。
		if( count( $register_task_arr ) > 0 && $register_task_arr !== array() ){

		 	$register_task_count = $this->return_count_num(count($register_task_arr));

		 	//ここでupdate_optionに渡すための値を取得する。
		 	$update_options = $this->create_calender_settings( $register_task_count );

		 	//各月全体を管理するオプションを登録＆更新。
		 	$this->wpfunc->update_option( $this->calender_slug_name, $update_options );
		 	$this->wpfunc->add_option( $this->schedule_slug_name.'_'.$register_task_count,  $_POST );

		 	$detail_schedule_key = $this->schedule_slug_name.'_'.$register_task_count;
		 }else{

		 	$register_task_count = $this->return_count_num();

		 	//ない場合は普通に登録。
		 	$this->wpfunc->add_option( $this->schedule_slug_name.'_'.$register_task_count,  $_POST );
		 	$detail_schedule_key = $this->schedule_slug_name.'_'.$register_task_count;

		 	//各月全体を管理するオプションを登録＆更新。
		 	$this->wpfunc->add_option( $this->calender_slug_name,  $_POST );

		 	//ここでupdate_optionに渡すための値を取得する。
		 	$update_options = $this->create_calender_settings( $register_task_count );

		 	//各月全体を管理するオプションを登録＆更新。
		 	$this->wpfunc->update_option( $this->calender_slug_name, $update_options );
		 }

		 //オリジナルフック(Googleカレンダー用)
		 do_action( 'admin_'.SLUGNAME.'_after_register_schedule', $detail_schedule_key );
	}

	//register_taskをカウントして文字列の数字を返す。
	public function return_count_num($num=0)
	{
		global $wpdb;
		//$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_id = 1", OBJECT );
		//ユニークなIDの最後の行を返す。
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options ORDER BY option_id DESC LIMIT 1" );
		$register_task_count = $results[0]->option_id;

		return strval($register_task_count);
	}

	/**
		calender_slug_nameのオプションを取得した上で、register_taskに新しく登録されたスラッグ名を登録して返す。
	*/
	public function create_calender_settings( $count_num ){

		$result_option = $this->wpfunc->get_option( $this->calender_slug_name, false );

		if( $result_option === false ){

			$result_option = array();
			$result_option[$_POST['date_id']] = array();
			$result_option[$_POST['date_id']]['register_task'] = array();
		}
		$register_task_count = $count_num;

		$result_option[$_POST['date_id']]['register_task'][] = $this->schedule_slug_name.'_'.$register_task_count;

		return $result_option;
	}

	//regsiter_taskを追加して登録。
	public function create_new_calender_settings(){

		foreach ($_POST as $key => $value) {
			$settings[ $key ] = ( ! $_POST[ $key ] || ! isset( $_POST[ $key ]  ) || ! is_string( $_POST[ $key ] ) )  ? "" : $_POST[ $key ];
		}

		$schedule_slug_name = SLUGNAME.'_register_schedule_'.$_POST['date_id'];
		$settings[$_POST['date_id']]['register_task'] = array();
		$settings[$_POST['date_id']]['register_task'][] = $schedule_slug_name.'_1';

		return $settings;
	}
}