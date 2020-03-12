<?php
/**
 *
 */
class Guild_Press_Public_Edit_Date_Time
{
	public $calendar_edit_html;
	public function get_calendar_edit_registered_date( $args )
	{
		$this->calendar_edit_html = '';
		$this->calendar_edit_html .= '<p class="calendar-border-text">登録した時間帯 :';
		$this->calendar_edit_html .= $args['user_register_data']['date_time1'];
		$this->calendar_edit_html .= '~';
		$this->calendar_edit_html .= $args['user_register_data']['date_time2'];
		$this->calendar_edit_html .= '</p>';

		return $this->calendar_edit_html;
	}
}