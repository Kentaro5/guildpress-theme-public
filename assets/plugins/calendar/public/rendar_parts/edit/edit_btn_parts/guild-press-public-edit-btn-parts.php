<?php
/**
 *
 */
class Guild_Press_Public_Edit_Btn
{
	public $calendar_edit_html;
	public function get_calendar_edit_box( $args )
	{
		$this->calendar_edit_html = '';

		$this->calendar_edit_html .= '<div class="row calendar-edit-box">';

		$this->calendar_edit_html .= $this->set_calendar_edit_link( $args );

		$this->calendar_edit_html .= $this->set_calendar_delete_link( $args );
		$this->calendar_edit_html .= '</div>';

		return $this->calendar_edit_html;
	}

	public function set_calendar_edit_link( $args )
	{

		$this->calendar_edit_html .= '<div class="col-md-6">';
		$this->calendar_edit_html .= '<p class="calendar-edit-text">';
		$this->calendar_edit_html .= '<a href="'.$args['edit_link'].'">編集</a>';
		$this->calendar_edit_html .= '</p>';
		$this->calendar_edit_html .= '</div>';
	}


	public function set_calendar_delete_link( $args )
	{

		$this->calendar_edit_html .= '<div class="col-md-6">';
		$this->calendar_edit_html .= '<p class="calendar-delete-text">';

		$task_id = "'".$args['schedule_task_id']."'";
		$mk_time = "'".$args['mk_time']."'";

		$this->calendar_edit_html .= '<a href="#" onclick="public_js.delete_schedule('.$mk_time.', '.$task_id.');return false;" style="color:red;">削除</a>';
		$this->calendar_edit_html .= '</p>';
		$this->calendar_edit_html .= '</div>';

	}
}