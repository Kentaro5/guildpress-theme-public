<?php
/**
 *
 */
class Guild_Press_Public_Title_Parts
{
    public function __construct()
    {
        $this->wpfunc = new WpFunc();
    }

	public function set_calendar_register_title( $args )
	{
		return '<h2>'.$this->wpfunc->esc_html( $args['title'] ).'</h2>';
	}
}