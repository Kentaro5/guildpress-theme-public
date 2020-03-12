<?php
/**
*
*/

class Guild_Press_Public_Calendar extends Gp_Calender
{
	public $rendar_calendar_parts;
	public $calendar_html;

	public function __construct(){
		$this->load();
	}

	public function load()
	{
		parent::__construct();

		$this->rendar_calendar_parts = new Rendar_Calendar_Parts();
		add_action( 'init', array( $this, 'get_action' ) );
		add_shortcode( 'guild_press_calender', array( $this, 'get_page' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'create_ajax_url' ), 10 );
		add_filter( 'get_calender_data', array( $this, 'filter_calender_page' ) );

	}

	//ユーザーの登録があるかどうかを取得する。
	public function get_page()
	{
		if( isset( $_GET["salon_tab"] ) && $_GET["salon_tab"] !== ""  ){

			$page = $_GET["salon_tab"];

		}else{

			$page = 'case';
		}

		switch ($page) {
			case ("register_schedule"):
			ob_start();
			$this->public_calendar_register->register_shcedule_page();
			$register_shcedule_page = ob_get_contents();
			ob_end_clean();
			return $register_shcedule_page;
			break;

			case ("edit_schedule"):

			ob_start();
			$this->public_calendar_edit->edit_shcedule_page();
			$edit_shcedule_page = ob_get_contents();
			ob_end_clean();
			return $edit_shcedule_page;
			break;

			default:

			add_action( 'wp_footer', array( $this, 'add_js' ), 10 );
			return $this->new_calender_page();
			break;
		}

	}

	public function add_js()
	{
		?>
		<script type="text/javascript">
			public_js.open_dom_window( 'dom-box' );
		</script>
		<?php
	}


	//Formからアクションを受け取る
	public function get_action(){

		if( !isset( $_POST["guild_press_schedule_action"] ) || !$_POST["guild_press_schedule_action"] ){
			return;
		}

		$this->action = ( isset( $_POST["guild_press_schedule_action"] ) ) ? trim( $_POST["guild_press_schedule_action"] ) : '';
		$this->action_check( $this->action );
	}

	//受け取ったアクションによって、処理を分岐させる。
	public function action_check( $action='' ){

		if( !$action || !isset( $action ) || !is_string( $action ) ){
			return;
		}

		switch ($action) {

			case ("register_schedule"):
			$this->public_calendar_register->register_shcedule_page();
			break;

			case ("register_new_schedule"):
			$this->public_calendar_register->register_new_schedule();
			break;

			case ("user_data_update"):
			$this->public_calendar_edit->update_user_schedule();
			break;
		}

	}

	public function create_ajax_url(){
		?>
		<script>
			let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
			let security = '<?php echo wp_create_nonce( "guild_press_delete_user_schedule" ) ?>';
		</script>
		<?php

	}

	public function set_default_calendar_args()
	{
		if (isset($_GET['gp_year']) && isset($_GET['gp_month'])) {

			$this->default_year_month_stamp = mktime(0, 0, 0, $_GET['gp_month'], 1, $_GET['gp_year']);
			$this->calender_slug_name = SLUGNAME.'_register_schedule_'.$this->default_year_month_stamp;

		} else {
			$this->default_year_month_stamp = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
			$this->calender_slug_name = SLUGNAME.'_register_schedule_'.$this->default_year_month_stamp;
		}
	}

	public function filter_calender_page(){

			// カレンダー対象年月
		$this->set_default_calendar_args();

			//隔月の現在表示されている部分を取得。
		$this_month = ( isset($_GET['gp_month']) && $_GET['gp_month'] !== "" ) ? $_GET['gp_month'] : date_i18n('n');
		$this_year = ( isset($_GET['gp_year']) && $_GET['gp_year'] !== "" ) ? $_GET['gp_year'] : date_i18n('Y');

		$weeks = array(
			'Sun' => '日',
			'Mon' => '月',
			'Tue' => '火',
			'Wed' => '水',
			'Thu' => '木',
			'Fri' => '金',
			'Sat' => '土'
		);

			// カレンダー生成パラメータ今の年と月を取得2018,8などの形式で取得
		$theyear = date('Y', $this->default_year_month_stamp);
		$themonth = date('n', $this->default_year_month_stamp);

			// リンク
		$prev_month = mktime(0, 0, 0, $themonth - 1, 1, $theyear);
		$prev_str = date('Y-m', $prev_month);
		$next_month = mktime(0, 0, 0, $themonth + 1, 1, $theyear);
		$next_str = date('Y-m', $next_month);

		$this_month_num = mktime(0, 0, 0, $this_month, 1, $this_year);
		$this_month_str = date('Y-m', $this_month_num);

			//今月のマックスの日付を取得
		$days = (mktime(0, 0, 0, $themonth + 1, 1, $theyear) - $this->default_year_month_stamp) / 86400;

			//今月は何曜日から始まるかを取得。
		$starti = intval( date('w', $this->default_year_month_stamp) );

		$endi = $starti + $days + 5 - date('w', mktime(0, 0, 0, $themonth, $days, $theyear));

			//$day = 1 - $starti;
		$day = 0;

			//カレンダーのデータを取得する。
		$general = $this->wpfunc->get_option( $this->calender_slug_name, false );

		$user_id = $this->wpfunc->get_current_user_id();
		$user_id_str = strval( $user_id );

		$return_args = array(
			'themonth' => $themonth,
			'theyear' => $theyear,
			'prev_month' => $prev_month,
			'prev_str' => $prev_str,
			'next_month' => $next_month,
			'next_str' => $next_str,
			'starti' => $starti,
			'endi' => $endi,
			'day' => $day,
			'days' => $days,
			'weeks' => $weeks,
			'general' => $general,
			'user_id' => $user_id,
			'user_id_str' => $user_id_str,
			'this_month' => $this_month,
			'this_year' => $this_year,
			'this_month_str' => $this_month_str,
			'the_month' => $this->default_year_month_stamp
		);

		return $return_args;
	}

	public function set_calendar_header_menu( $calendar_data ){

		$this->calendar_html .= '<a href="'.$this->rendar_calendar_parts->return_calendar_prev_month_link($calendar_data['prev_month']).'" >';

		$this->calendar_html .= '<button type="button" class="calendar-btn calendar-move-day">';
		$this->calendar_html .= $calendar_data['prev_str'];
		$this->calendar_html .= '</button>';

		$this->calendar_html .= '</a>';

		$this->calendar_html .= '<span class="calendar-render-range">';

		$this->calendar_html .=  $calendar_data['this_month_str'];
		$this->calendar_html .= '</span>';

		$this->calendar_html .= '<a href="'.$this->rendar_calendar_parts->return_calendar_next_month_link($calendar_data['next_month']).'" title="">';
		$this->calendar_html .= '<button type="button" class="calendar-btn calendar-move-day">';
		$this->calendar_html .= $calendar_data['next_str'];
		$this->calendar_html .= '</button>';
		$this->calendar_html .= '</a>';
	}

	public function set_calendar_header_desc()
	{
		$this->calendar_html .= '<span class="calendar-colors-green"></span>';
		$this->calendar_html .= '<span class="calendar-colors-text">多い</span>';
		$this->calendar_html .= '<span class="calendar-colors-orange"></span>';
		$this->calendar_html .= '<span class="calendar-colors-text">少ない</span>';
		$this->calendar_html .= '<span class="calendar-colors-red"></span>';
		$this->calendar_html .= '<span class="calendar-colors-text">残りわずか</span>';
		$this->calendar_html .= '<span class="calendar-colors-gray"></span>';
		$this->calendar_html .= '<span class="calendar-colors-text">満席</span>';
	}

	public function set_calendar_header( $calendar_data )
	{
		$this->calendar_html .= '<div id="calendarMenu">';

		$this->calendar_html .= '<span id="menu-navi">';

		$this->set_calendar_header_menu( $calendar_data );

		$this->set_calendar_header_desc();

		$this->calendar_html .= '</span>';
		$this->calendar_html .= '</div>';
	}

	public function set_calendar_thead( $calendar_data )
	{
		$this->calendar_html .= '<thead class="calendar-day-of-the-week">';
		$this->calendar_html .= '<tr>';

		foreach( $calendar_data['weeks'] as $week_name => $week_jp_name ){

			$this->calendar_html .= $this->rendar_calendar_parts->return_week_name_th( $week_name, $week_jp_name );

		}

		$this->calendar_html .= '</tr>';
		$this->calendar_html .= '</thead>';

	}

	public function set_calendar_tbody( $calendar_data )
	{
		$this->calendar_html .= '<tbody>';

		for ($i = 0, $calendar_data['day'] = 1 - $calendar_data['starti']; $i <= $calendar_data['endi'] ; $i++, $calendar_data['day']++){

			$mk_time = mktime( 0, 0, 0, $calendar_data['themonth'], $calendar_data['day'], $calendar_data['theyear'] );

			if ($i % 7 == 0){

				$this->calendar_html .= $this->rendar_calendar_parts->return_calnedar_tr_tag($i);
			}

				//日付が正しい日付かどうかチェックする。
			if (0 < $calendar_data['day'] && $calendar_data['day'] <= $calendar_data['days']) {

				$this->set_calendar_detail( $calendar_data, $mk_time );
			}else{

				$this->set_empty_calendar_detail();
			}

		}

		$this->calendar_html .= '</tbody>';

	}

	public function set_empty_calendar_detail()
	{
		$this->calendar_html .= '<td class="calendar-each-day-of-the-week-items calender_cell no-day"></td>';
	}

	public function set_calendar_detail( $calendar_data, $mk_time )
	{
		$day = sprintf("%02d", $calendar_data['day']);

		$this->calendar_html .= '<td class="calendar-each-day-of-the-week-items calender_cell tue">';

		$this->calendar_html .= '<div class="calendar-day tue">';
		$this->calendar_html .= $day;
		$this->calendar_html .= '</div>';

		$this->calendar_html .= '<div class="reservation-view">';

		$this->calendar_html .= $this->rendar_calendar_parts->return_calendar_detail( $calendar_data, $mk_time, $this->calender_slug_name );

		$this->calendar_html .= '</div>';

		$this->calendar_html .= '</td>';
	}

	public function set_calendar_box( $calendar_data )
	{
		$this->calendar_html .= '<div class="calendar-box">';

		$this->calendar_html .= '<table class="calender_table">';

		$this->set_calendar_thead( $calendar_data );

		$this->set_calendar_tbody( $calendar_data );

		$this->calendar_html .= '</table>';
		$this->calendar_html .= '</div>';
	}

	public function set_calendar_html( $calendar_data )
	{
		$this->calendar_html .= '<div class="relative">';

		$this->calendar_html .= '<div class="content-box">';

		$this->set_calendar_header( $calendar_data );

		$this->set_calendar_box( $calendar_data );

		$this->calendar_html .= '</div>';
		$this->calendar_html .= '</div>';

		return $this->calendar_html;
	}

	public function new_calender_page()
	{

		$calendar = $this->wpfunc->apply_filters( 'get_calender_data', '' );

		return $this->set_calendar_html( $calendar );

	}

}



