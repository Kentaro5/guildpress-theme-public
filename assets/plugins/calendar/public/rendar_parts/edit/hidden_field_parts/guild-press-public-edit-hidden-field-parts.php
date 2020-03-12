<?php
/**
 *
 */
class Guild_Press_Public_Edit_Hidden_Field
{
	public $calendar_edit_html;
	public function get_calendar_edit_hidden_field( $args )
	{
        $this->calendar_edit_html = '';
		$this->calendar_edit_html .= '<input type="hidden" name="google_event_id" value="'.$args['google_event_id'].'" id="google_event_id'.$args['schedule_task_id'].'">';

		$this->calendar_edit_html .= '<input type="hidden" name="the_month" value="'.$args['the_month'].'" id="the_month'.$args['schedule_task_id'].'">';

		return $this->calendar_edit_html;
	}
}