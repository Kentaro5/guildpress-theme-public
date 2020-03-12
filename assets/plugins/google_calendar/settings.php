<?php

require_once( TEMP_DIR . '/assets/plugins/google_calendar/class/class-guild-press-google-calendar-model.php' );

require_once( TEMP_DIR . '/assets/plugins/google_calendar/class/class-guild-press-google-calendar-set-up.php' );
if( class_exists('GoogleCalendarSetUp') ){
		new GoogleCalendarSetUp();
	}


if( is_admin() ){

	require_once( TEMP_DIR . '/assets/plugins/google_calendar/class/class-guild-press-google-calendar-settings.php' );
	if( class_exists('GoogleCalendarSettings') ){
		new GoogleCalendarSettings();
	}



}

require_once( TEMP_DIR . '/assets/plugins/google_calendar/class/class-guild-press-google-calendar-auth.php' );
	if( class_exists('GoogleCalendarAuth') ){
		new GoogleCalendarAuth();
	}
