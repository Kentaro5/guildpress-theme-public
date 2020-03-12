<?php
/**
 *
 */
class Guild_Press_Public_Register_Date_Time
{
	public $calendar_register_html;
	public function __construct()
	{
		$this->wpfunc = new WpFunc();
	}

	public function set_calendar_register_date_time( $args )
	{
		$this->calendar_register_html = '';
		$this->calendar_register_html .= '<p class="calendar-border-text">時間帯 : ';
		$this->calendar_register_html .= $this->wpfunc->esc_html( $args['date_time1'] );
		$this->calendar_register_html .= '~';
		$this->calendar_register_html .= $this->wpfunc->esc_html( $args['date_time2'] );
		$this->calendar_register_html .= '</p>';

		return $this->calendar_register_html;
	}
}