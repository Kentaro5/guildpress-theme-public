<?php
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/register/num_parts/guild-press-public-register-num-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/register/title_parts/guild-press-public-title-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/register/date_parts/guild-press-public-register-date-time.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/register/comment_parts/guild-press-public-register-comment-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/register/btn_parts/guild-press-public-register-btn-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/register/hidden_field_parts/guild-press-public-register-hidden-field-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/register/load_anime_parts/guild-press-public-register-load-anime-parts.php' );

require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/edit/title_parts/guild-press-public-edit-title-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/edit/edit_date_parts/guild-press-public-edit-date-time-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/edit/comment_parts/guild-press-public-edit-comment-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/edit/hidden_field_parts/guild-press-public-edit-hidden-field-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/edit/edit_btn_parts/guild-press-public-edit-btn-parts.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/edit/load_anime_parts/guild-press-public-edit-load-anime-parts.php' );

require_once(  TEMP_DIR . '/assets/plugins/calendar/common/guild-press-calendar-common.php' );

/*

マイページでカレンダーを表示する時に使用されるクラスです。

*/

class Rendar_Calendar_Parts{

	public $wpfunc;
	public $register_num_parts;
	public $title_parts;
	public $calendar_edit_html;
	public $calendar_register_html;
	public $google_event_id;
	public $max_num;
	public $register_schedule_data;
	public $regsiter_date_parts;
	public $register_load_anime_parts;

	public $edit_title_parts;
	public $edit_date_parts;
	public $edit_comment_parts;
	public $edit_btn_parts;
	public $edit_hidden_field_parts;
	public $edit_load_anime_parts;

	public function __construct()
	{
		$this->wpfunc = new WpFunc();
		$this->register_num_parts = new Guild_Press_Public_Register_Num();
		$this->title_parts = new Guild_Press_Public_Title_Parts();
		$this->regsiter_date_parts = new Guild_Press_Public_Register_Date_Time();
		$this->register_comment_parts = new Guild_Press_Public_Register_Comment();
		$this->register_btn_parts = new Guild_Press_Public_Register_Btn();
		$this->register_hidden_field_parts = new Guild_Press_Public_Register_Hidden_Field();
		$this->register_load_anime_parts = new Guild_Press_Public_Register_Load_Anime();

		$this->edit_title_parts = new Guild_Press_Public_Edit_Title();
		$this->edit_date_parts = new Guild_Press_Public_Edit_Date_Time();
		$this->edit_comment_parts = new Guild_Press_Public_Edit_Comment();
		$this->edit_btn_parts = new Guild_Press_Public_Edit_Btn();
		$this->edit_hidden_field_parts = new Guild_Press_Public_Edit_Hidden_Field();
		$this->edit_load_anime_parts = new Guild_Press_Public_Edit_Load_Anime();

		$this->calendar_common = new Guild_Press_Calendar_Common;


	}
	public function return_calendar_prev_month_link($prev_month=''){

		if( $prev_month === '' ){

			return;
		}

		return '?gp_year=' . date('Y', $prev_month) . '&gp_month=' . date('n', $prev_month) . '&action=monthly';
	}

	public function return_calendar_next_month_link($next_month='')
	{
		if( $next_month === '' ){

			return;
		}
		return '?gp_year=' . date('Y', $next_month) . '&gp_month=' . date('n', $next_month) . '&action=monthly';
	}

	//カレンダーの日〜土までのテーブルセルを返す処理。
	public function return_week_name_th( $week_name='', $week_jp_name='' )
	{
		if( $week_name === '' || $week_jp_name === '' ){
			return;
		}

		$html='';
		if( $week_name === 'Sun' ){

			$html .= '<th class="calendar-day-of-the-week-item">';
			$html .= '<span class="holiday">';
			$html .= $week_jp_name;
			$html .= '</span>';
			$html .= '</th>';
		}else {

			$html .= '<th class="calendar-day-of-the-week-item">';
			$html .= '<span class="normal-day">';
			$html .= $week_jp_name;
			$html .= '</span>';
			$html .= '</th>';

		}
		return $html;
	}

	public function return_calnedar_tr_tag($loop_num='')
	{
		if( is_int( $loop_num ) !== true ){

			die("loop_numが数値ではありません。");
		}
		return (0 < $loop_num ? '</tr>' : '') . '<tr class="calendar-each-day-of-the-week-items">';
	}

	public function set_google_event_id()
	{
		$this->google_event_id = ( isset($this->register_schedule_data['google_event_id']) && $this->register_schedule_data['google_event_id'] !== "" ) ? $this->register_schedule_data['google_event_id'] : '';
	}

	public function set_register_schedule_data( $schedule_task_id )
	{
		$this->register_schedule_data = $this->wpfunc->get_option($schedule_task_id, '');
	}

	public function set_max_num( $str_max_num )
	{
		$this->max_num = intval( $str_max_num );
	}

	public function set_user_register_data( $user_data_id )
	{

		$user_register_data = $this->wpfunc->get_option( $user_data_id, '' );

		$this->user_register_data = ( isset($user_register_data) && $user_register_data !== "" ) ? $user_register_data : array();
	}

	public function get_calendar_link( $mk_time, $schedule_task_id, $calendar )
	{
		$link = '?d_id='.$mk_time;
		$link .= '&s_id='.$schedule_task_id;
		$link .= '&salon_tab=register_schedule';
		$link .= '&the_month='.$calendar['the_month'];
		$link .= '&gp_month='.$calendar['this_month'];
		$link .= '&gp_year='.$calendar['this_year'];

		return $link;
	}

	public function get_edit_calendar_link( $mk_time, $schedule_task_id, $calendar )
	{
		$link = '?d_id='.$mk_time;
		$link .= '&s_id='.$schedule_task_id;
		$link .= '&salon_tab=edit_schedule';
		$link .= '&the_month='.$calendar['the_month'];
		$link .= '&gp_month='.$calendar['this_month'];
		$link .= '&gp_year='.$calendar['this_year'];

		return $link;
	}

	public function return_calendar_detail( $calendar, $mk_time, $calender_slug_name )
	{

		$general;
		$general = $calendar['general'];
		$html = '';

		$args = array();
		if( isset( $general[$mk_time]) && count($general[$mk_time]['register_task'] ) > 0  ){

			for ($p=0; $p < count($general[$mk_time]['register_task']); $p++){

				$schedule_task_id = $general[$mk_time]['register_task'][$p];

				$this->set_register_schedule_data( $schedule_task_id );

				$this->set_google_event_id();

				//各イベントのMaxの人数を取得
				$this->set_max_num( $this->register_schedule_data['max_num'] );
				$this->set_user_register_data( $schedule_task_id."_".$calendar['user_id_str'] );

				//ループの最後に新規登録ボタンを表示する。
				if( count( $general[$mk_time]['register_task']) > $p ){

					$google_calendar_args = $this->calendar_common->get_google_calendar_args( $general, $this->register_schedule_data, $p );

					$date_id = $this->register_schedule_data['date_id'];

					$user_ids = ( isset($general[$mk_time]['user_id'][$schedule_task_id]) && $general[$mk_time]['user_id'][$schedule_task_id] !== "" ) ? $general[$mk_time]['user_id'][$schedule_task_id] : array();


					$general[$mk_time]['user_id'][$schedule_task_id] = $this->calendar_common->check_user_id_exit( $user_ids, $google_calendar_args, $general, $calender_slug_name, $date_id );

					//既にユーザーの登録がある場合は登録をしないようにする。
					if( is_null( $general[$mk_time]['user_id'][$schedule_task_id] ) ) {
						$general[$mk_time]['user_id'][$schedule_task_id] = array();
					}

					//現在予約しているユーザー数取得
					$all_registered_num = count($general[$mk_time]['user_id'][$schedule_task_id]);

					$num = ($all_registered_num / $this->max_num ) * 100 ;

					//現在の人数とMaxの人数のパーセンテージ取得
					$register_percentage = floor( $num );

					if( !in_array( $calendar['user_id_str'], $general[$mk_time]['user_id'][$schedule_task_id], true ) && $this->max_num  > $all_registered_num ) {

						$html .= $this->return_schedule_title( $this->wpfunc->esc_html($this->register_schedule_data['title']), $schedule_task_id, $register_percentage, 0 );

						$link = $this->get_calendar_link( $mk_time, $schedule_task_id, $calendar );

						$args = array(
							'title' => $this->register_schedule_data['title'],
							'date_time1' => $this->register_schedule_data['date_time1'],
							'date_time2' => $this->register_schedule_data['date_time2'],
							'register_link' => $link,
							'google_event_id' => $this->google_event_id,
							'mk_time' => $mk_time,
							'the_month' => $calendar['the_month'],
							'schedule_task_id' => $schedule_task_id,
							'register_percentage' => $register_percentage
						);

						$html .= $this->return_register_shedule_box( $args );

					}elseif( !in_array( $calendar['user_id_str'], $general[$mk_time]['user_id'][$schedule_task_id], true ) ){

						//満席の場合は、予約していないユーザーに満席であることを伝える。
						$html .= $this->return_schedule_title( $this->wpfunc->esc_html($this->register_schedule_data['title']), $schedule_task_id, $register_percentage, 0, 'none' );
					}

				}

				if( count( $this->user_register_data ) > 1 ) {

					$link = $this->get_edit_calendar_link( $mk_time, $schedule_task_id, $calendar );

					$html .= $this->return_schedule_title( $this->wpfunc->esc_html($this->register_schedule_data['title']), $schedule_task_id, $register_percentage, 1 );

					$args = array(
						'title' => $this->register_schedule_data['title'],
						'date_time1' => $this->register_schedule_data['date_time1'],
						'date_time2' => $this->register_schedule_data['date_time2'],
						'edit_link' => $link,
						'google_event_id' => $this->google_event_id,
						'mk_time' => $mk_time,
						'the_month' => $calendar['the_month'],
						'schedule_task_id' => $schedule_task_id,
						'user_register_data' => $this->user_register_data,
						'register_percentage' => $register_percentage
					);


					$html .= $this->return_edit_shedule_user_box( $args );

				}

			}
		}

		return $html;

	}

	public function return_schedule_title( $title, $schedule_task_id, $register_percentage, $user_register_status, $max_registered='' )
	{
		$html = '';
		//0=未登録、1=イベント登録済み
		//満員の場合は、クリックしないようにプレーンテキストを返す。
		$html .= '<div class="calendar-schedule-box">';
		if( $max_registered === 'none' ){
			$html .= $this->return_register_color_text( intval( $register_percentage ) );
			$html .= '<span class="calendar-schedule-text">';
				$html .= $title;
				$html .= '</span>';
		}else{

			$html .= '<a href="#light_box_'.$schedule_task_id.'" class="dom-box" id="pop_box">';

			$html .= $this->return_register_color_text( intval( $register_percentage ) );

			//0=未登録の場合は登録用のものを返す。
			if( $user_register_status === 0 ){

				$html .= '<span class="calendar-schedule-text">';
				$html .= $title;
				$html .= '</span>';

			//登録されている場合は、文字を赤色にして返す。
			}elseif( $user_register_status === 1 ) {

				$html .= '<span style="color:red;">';
				$html .= $title;
				$html .= '</span>';

			}
			$html .= '</a>';
		}

		$html .= '</div>';

		return $html;
	}

	public function return_register_color_text( $register_percentage )
	{

		if( ! is_int( $register_percentage ) ){

			return;
		}

		$html = '';
		if( $register_percentage <= 25 ){

			$html .= '<span class="calendar-schedule-text green"></span>';

		}elseif( $register_percentage <= 50 ){

			$html .= '<span class="calendar-schedule-text orange"></span>';

		}elseif( $register_percentage <= 99 ){

			$html .= '<span class="calendar-schedule-text red"></span>';

		}elseif( $register_percentage === 100 ){

			$html .= '<span class="calendar-schedule-text gray"></span>';

		}

		return $html;
	}

	public function return_edit_shedule_user_box( $args )
	{
		$this->calendar_edit_html = '';

		$this->calendar_edit_html .= '<div id="light_box_'.$args['schedule_task_id'].'" style="z-index: 120000000; display: none;">';


		//残席数を表すパーセンテージを獲得。
		$this->calendar_edit_html .= $this->register_num_parts->return_register_border( intval( $args['register_percentage'] ) );

		$this->calendar_edit_html .= '<div class="calendar-detail-box">';

		$this->calendar_edit_html .= $this->edit_title_parts->get_calendar_edit_title( $args );

		$this->calendar_edit_html .= $this->edit_date_parts->get_calendar_edit_registered_date( $args );

		$this->calendar_edit_html .= $this->edit_comment_parts->get_calendar_edit_comments( $args );

		$this->calendar_edit_html .= $this->edit_btn_parts->get_calendar_edit_box( $args );

		$this->calendar_edit_html .= $this->edit_hidden_field_parts->get_calendar_edit_hidden_field( $args );

		$this->calendar_edit_html .= $this->edit_load_anime_parts->get_calendar_edit_load_animation( $args );

		$this->calendar_edit_html .= '</div>';

		$this->calendar_edit_html .= '</div>';

		return $this->calendar_edit_html;
	}


	public function return_register_shedule_box( $args )
	{

		$this->calendar_register_html = '';

		$this->calendar_register_html .= '<div id="light_box_'.$args['schedule_task_id'].'" style="z-index: 120000000; display: none;">';

		//残席数を表すパーセンテージを獲得。
		$this->calendar_register_html .= $this->register_num_parts->return_register_border( intval( $args['register_percentage'] ) );

		$this->calendar_register_html .= '<div class="calendar-detail-box">';

		$this->calendar_register_html .= $this->title_parts->set_calendar_register_title( $args );

		$this->calendar_register_html .= $this->regsiter_date_parts->set_calendar_register_date_time( $args );

		$this->calendar_register_html .= $this->register_comment_parts->get_calendar_register_comment();

		$this->calendar_register_html .= $this->register_btn_parts->get_calendar_register_btn_box( $args );

		$this->calendar_register_html .= $this->register_hidden_field_parts->get_calendar_register_hidden_field( $args );

		$this->calendar_register_html .= $this->register_load_anime_parts->get_calendar_register_load_anime( $args );

		$this->calendar_register_html .= '</div>';
		$this->calendar_register_html .= '</div>';

		return $this->calendar_register_html;
	}



}

