<?php
require_once( TEMP_DIR . '/assets/plugins/calendar/public/calendar_edit/guild-press-public-calendar-edit.php' );
require_once( TEMP_DIR . '/assets/plugins/calendar/public/calendar_register/guild-press-public-calendar-register.php' );
/**
*
*/

abstract class Gp_Calender
{
	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->public_calendar_edit = new Guild_Press_Public_Calendar_Edit();
		$this->public_calendar_register = new Guild_Press_Public_Calendar_Register();

		//月日をセット
		$gp_date = $this->basic->check_date();

		$gp_check_date = ( isset($gp_date) && $gp_date !== '' ) ? true : false;

		//月日をセット
		if( $gp_check_date ){

			$this->default_year_month_stamp = mktime(0, 0, 0, $gp_date['month'], 1, $gp_date['year'] );
		}else{

			$this->default_year_month_stamp = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
		}
	}




}