<?php
/**
 *
 */
class Guild_Press_Public_Edit_Title
{
	public $calendar_register_html;

    public function __construct()
    {
        $this->wpfunc = new WpFunc();
    }
    public function get_calendar_edit_title( $args )
    {
      return '<h2>'.$this->wpfunc->esc_html( $args['title'] ).'</h2>';
  }
}