<?php
require_once( TEMP_DIR . '/assets/plugins/lesson/public/common/guild-press-public-lesson-common.php' );
/**
*
*/
class Guild_Press_Public_Lesson_Content
{
    protected $post_args;

    public function __construct()
    {
        $this->load();
    }

    public function load()
    {
        $this->basic = new Basic;
        $this->wpfunc = new WpFunc;
        $this->lesson_common = new Guild_Press_Public_Lesson_Common;

        $this->post_args = array(
            array(
                'post_link' => '',
                'post_title' => '',
                'post_desc' => '',
                'post_thumb' => '',
                'post_id' => 0,
                'prev_post_id' => 0,
                'is_lock_page' => 0,
            )
        );
    }

    //ユーザーの登録があるかどうかを取得する。
    public function get_page($slug)
    {

        ob_start();
        $this->lesson_list($slug);
        $lesson_lists = ob_get_contents();
        ob_end_clean();
        return $lesson_lists;
    }

    public function lesson_list($slug){

        $slug = get_term_by( 'slug', $slug['slug'], 'guild_lesson_category', 'ARRAY_A', 'raw' );

        $custom_query = array( 'order' => 'ASC' );

        $query = $this->basic->getGuildDeitalQuery( $slug['slug'], $custom_query );

        $count = 0;
        while ( $query->have_posts() ) {
            $query->the_post();

            $this->lesson_common->set_post_args( $count );

            $prev_post = get_previous_post( true, '', 'guild_lesson_category' );

            $this->lesson_common->set_prev_post_id( $count, $prev_post );

            $this->lesson_common->show_content_page($count);

            $count++;

        }

        $this->wpfunc->wp_reset_postdata();

    }
}


