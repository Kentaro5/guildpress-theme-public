<?php
require_once( TEMP_DIR . '/assets/plugins/lesson/public/short_code_all_lesson/guild-press-public-short-code-all-lesson.php' );
require_once( TEMP_DIR . '/assets/plugins/lesson/public/all_lesson/guild-press-public-all-lesson.php' );
require_once( TEMP_DIR . '/assets/plugins/lesson/public/lesson_content/guild-press-public-lesson-content.php' );
/**
*
*/
class Guild_Press_Public_Lesson
{
    public function __construct()
    {
        $this->basic = new Basic;
        $this->wpfunc = new WpFunc;
        $this->all_lesson = new Guild_Press_Public_All_Lesson;
        $this->short_code_all_lesson = new Guild_Press_Public_Short_Code_All_Lesson;
        $this->lesson_content = new Guild_Press_Public_Lesson_Content;
        $this->load();
    }

    public function load()
    {

        add_shortcode( 'guild_press_all_lesson', array( $this->short_code_all_lesson, 'get_page' ) );

        add_shortcode( 'guild_press_lesson', array( $this->lesson_content, 'get_page' ) );

        add_filter( 'guild_press_get_taxonomy_lesson_lists' , array( $this->all_lesson, 'get_all_lesson' ) );
    }

}
