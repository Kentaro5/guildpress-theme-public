<?php

require_once( TEMP_DIR . '/assets/plugins/custom_post/lesson_detail/lesson-detail-custom-post.php' );
require_once( TEMP_DIR . '/assets/plugins/custom_post/lesson_overview/lesson-overview-custom-post.php' );
require_once( TEMP_DIR . '/assets/plugins/custom_post/paypal/paypal-custom-post.php' );

/**
 *
 */
class Guild_Press_Custom_Post
{

	public function __construct()
	{
		new Lesson_Detail_Custom_Post();
		new Lesson_Overview_Custom_Post();
		new PayPal_Custom_Post();
	}
}