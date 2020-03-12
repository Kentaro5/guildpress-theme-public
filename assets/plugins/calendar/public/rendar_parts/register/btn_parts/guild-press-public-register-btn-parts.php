<?php
/**
 *
 */
class Guild_Press_Public_Register_Btn
{
	public $calendar_register_html = '';
	public function get_calendar_register_btn_box( $args )
	{
		$this->calendar_register_html = '';

		$this->calendar_register_html .= '<div class="row calendar-edit-box">';
		$this->calendar_register_html .= '<div class="col-md-12">';
		$this->calendar_register_html .= '<p class="calendar-register-text">';
		$this->calendar_register_html .= '<a href="'.$args['register_link'].'">登録する</a>';
		$this->calendar_register_html .= '</p>';
		$this->calendar_register_html .= '</div>';
		$this->calendar_register_html .= '</div>';

		return $this->calendar_register_html;
	}
}