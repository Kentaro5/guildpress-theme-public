<?php
require_once(  TEMP_DIR . '/assets/plugins/calendar/common/guild-press-calendar-common.php' );

/**
 *
 */
class Guild_Press_Calendar_List
{
	public $wpfunc;
	public $basic;
	public $gp_check_date;
	public $this_month;
	public $gp_date;
	public $calendar_list_layout_path;
	public $calender_slug_name;
	public $schedule_task_id;

	public $register_schedule_data;

	public $max_num;
	public $date_1;
	public $date_2;
	public $schedule_title;
	public $user_ids;
	public $user_book_num;
	public $date_times;


	public $book_user_ID;
	public $book_user_name;
	public $book_user_time;

	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;

		$this->calendar_common = new Guild_Press_Calendar_Common;


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

		$this->calendar_list_layout_path = 'templates/admin/calendar/calendar_list/calendar-list.php';
	}

	public function set_ballon_data( $general, $this_year_month_day_stamp, $loop_num )
	{
		$this->schedule_task_id = $general[$this_year_month_day_stamp]['register_task'][$loop_num];

		$this->register_schedule_data = $this->wpfunc->get_option( $this->schedule_task_id, false );

		if( $this->register_schedule_data === false ){

			return;
		}

		$google_calendar_args = $this->calendar_common->get_google_calendar_args( $general, $this->register_schedule_data, $loop_num );

		//予約最大人数
		$this->max_num = $this->register_schedule_data['max_num'];

		$this->date_1 = $this->register_schedule_data['date_time1'];
		$this->date_2 = $this->register_schedule_data['date_time2'];

		$this->schedule_title = $this->register_schedule_data['title'];

		$this->user_ids = ( isset($general[$this_year_month_day_stamp]['user_id'][$this->schedule_task_id]) && $general[$this_year_month_day_stamp]['user_id'][$this->schedule_task_id] !== "" ) ? $general[$this_year_month_day_stamp]['user_id'][$this->schedule_task_id] : '';

		$this->user_ids = $this->basic->null_check_arr( $this->user_ids, true );

		$this->user_ids = $this->calendar_common->check_user_id_exit( $this->user_ids, $google_calendar_args, $general, $this->calender_slug_name, $this->register_schedule_data['date_id'] );

		$this->user_book_num =  count($this->user_ids).'/'.esc_html($this->max_num);
		$this->date_times = $this->date_1.'~'.$this->date_2;
	}

	public function set_ballon_user_data( $book_user_id )
	{
		//データ取得
		$book_user_data = $this->wpfunc->get_userdata($book_user_id);

		if( $book_user_data === false ){

			return;
		}
		$book_user_front_data = $this->wpfunc->get_option($this->schedule_task_id.'_'.$book_user_data->ID);


		//わかりやすいように変数に格納
		$this->book_user_ID = $book_user_data->ID;
		$this->book_user_name = $book_user_data->display_name;
		$this->book_user_time = $book_user_front_data['date_time1'].'~'.$book_user_front_data['date_time2'];
	}

	public function get_calendar_link( $this_month = '', $this_year = '' )
	{
		$link ='?page='.SLUGNAME.'_settings';
		$link .= '&date_id='.$this->register_schedule_data['date_id'];
		$link .= '&tab=edit_schedule&schedule_task_id='.$this->schedule_task_id;
		$link .= '&gp_month='.$this_month;
		$link .= '&gp_year='.$this_year;

		return $link;
	}
//general_metabox
	public function calendar_list_box(){
		// カレンダー対象年月
		if (isset($_GET['gp_year']) && isset($_GET['gp_month'])) {

			$this->default_year_month_stamp = mktime(0, 0, 0, $_GET['gp_month'], 1, $_GET['gp_year']);
		} else {

			$this->default_year_month_stamp = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
			$this->calender_slug_name = SLUGNAME.'_register_schedule_'.$this->default_year_month_stamp;
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

		$general = $this->wpfunc->get_option($this->calender_slug_name, false );

		$this_month = ( isset($_GET['gp_month']) && $_GET['gp_month'] !== "" ) ? $_GET['gp_month'] : date_i18n('n');
		$this_year = ( isset($_GET['gp_year']) && $_GET['gp_year'] !== "" ) ? $_GET['gp_year'] : date_i18n('Y');

		$prev_link = '?page='.SLUGNAME.'_settings'.'&gp_year='.date('Y', $prev_month)
		. '&gp_month='.date('n', $prev_month).'&action=monthly';

		$next_link = '?page='.SLUGNAME.'_settings'.'&gp_year='.date('Y', $next_month)
		. '&gp_month='.date('n', $next_month).'&action=monthly';

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
			'general' => $general,
		);

		if( ! $file_path = $this->basic->load_template( $this->calendar_list_layout_path, false ) ){

			return;
		}

		include( $file_path );

	}

}