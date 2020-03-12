<?php
/**
 *
 */
class Guild_Press_Public_Edit_Comment
{
	public $calendar_edit_html;
    public function __construct()
    {
        $this->wpfunc = new WpFunc;
    }
    public function get_calendar_edit_comments( $args )
    {
      $this->calendar_edit_html = '';
      $this->calendar_edit_html .= '<p class="calendar-border-text">記入したコメント</p>';

      $this->calendar_edit_html .= '<p class="calendar-border-text">';
      $this->calendar_edit_html .= $this->wpfunc->esc_html( $args['user_register_data']['comment'] );
      $this->calendar_edit_html .= '</p>';

      return $this->calendar_edit_html;
  }
}