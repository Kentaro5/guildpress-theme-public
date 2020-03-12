<?php
/**
 *
 */
class Guild_Press_Public_Register_Hidden_Field
{
	public $calendar_register_html;
	public function get_calendar_register_hidden_field( $args )
	{
        $this->calendar_register_html = '';
		$this->calendar_register_html .= '<input type="hidden" name="google_event_id" value="'.$args['google_event_id'].'" id="google_event_id'.$args['schedule_task_id'].'">';

		$this->calendar_register_html .= '<input type="hidden" name="the_month" value="'.$args['the_month'].'" id="the_month'.$args['schedule_task_id'].'">';

		return $this->calendar_register_html;
	}
}