<?php
/**
 *
 */
class Guild_Press_Public_Register_Load_Anime
{
	public $calendar_register_html;
	public function get_calendar_register_load_anime( $args )
	{
        $this->calendar_register_html = '';
		$this->calendar_register_html .= '<div id="loadingAnim'.$args['schedule_task_id'].'" class="loadingAnim" style="display:none;">';

		$this->calendar_register_html .= '<i class="loadingAnim_line"></i>';

		$this->calendar_register_html .= '</div>';

		return $this->calendar_register_html;
	}
}