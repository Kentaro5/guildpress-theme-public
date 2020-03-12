<?php
/**
 *
 */
class Guild_Press_Public_Edit_Load_Anime
{
	public $calendar_edit_html;

	public function get_calendar_edit_load_animation( $args )
	{
        $this->calendar_edit_html = '';
		$this->calendar_edit_html .= '<div id="loadingAnim'.$args['schedule_task_id'].'" class="loadingAnim" style="display:none;">';
		$this->calendar_edit_html .= '<i class="loadingAnim_line"></i>';
		$this->calendar_edit_html .= '</div>';

		return $this->calendar_edit_html;
	}
}