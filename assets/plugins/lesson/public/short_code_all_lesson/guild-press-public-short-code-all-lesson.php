<?php
/**
*
*/
class Guild_Press_Public_Short_Code_All_Lesson
{

    public function __construct()
    {
        $this->basic = new Basic;
        $this->wpfunc = new WpFunc;
        $this->load();
    }

    public function load()
    {
        $this->all_lesson_lists_path = 'templates/public/lesson/all_lesson/all-lesson-lists.php';
    }

    //ユーザーの登録があるかどうかを取得する。
    public function get_page()
    {

        ob_start();
        $this->lesson_list();
        $all_lesson_lists = ob_get_contents();
        ob_end_clean();

        return $all_lesson_lists;
    }

    public function pagination( $query )
    {
        $GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;
        the_posts_pagination();
    }

    public function lesson_list(){

        $this->basic->set_posts_per_page();
        $posts_per_page = $this->basic->get_posts_per_page();
        $pagenation_num = $this->basic->get_pagination_num();

        $taxonomy_query = array( 'taxonomy' => 'guild_lesson_category', 'field' => array( 'slug' => '' ), 'operator'=>'NOT IN', 'relation' => 'AND');
        $custom_query = array (
            'posts_per_page' => $posts_per_page,
            'paged' => $pagenation_num,
            'tax_query' => array( $taxonomy_query )
        );
        $query =  $this->basic->getGuildLessonQuery( '', $custom_query );

        $gp_data = array(
            'query' => $query,
            'no_image' => TEMP_DIR_URI.'/assets/img/no-image.png',
            'post_desc' => '',
            'post_overview_desc' => '',
            'post_title' => '',
            'post_link' => '',
            'post_thumb' => '',
            'post_id' => 0,
        );

        if( ! $file_path = $this->basic->load_template( $this->all_lesson_lists_path, false ) ){
            return;
        }

        include( $file_path );
        $this->wpfunc->wp_reset_postdata();

    }

}
