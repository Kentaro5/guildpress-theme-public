<?php
require_once( TEMP_DIR . '/assets/plugins/lesson/public/common/guild-press-public-lesson-common.php' );

/**
 *
 */
class Guild_Press_Public_All_Lesson
{
    protected $post_args = array(
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

    public function __construct()
    {
        $this->basic = new Basic();
        $this->wpfunc = new Wpfunc();
        $this->lesson_common = new Guild_Press_Public_Lesson_Common;

    }
    public function get_all_lesson( $slug )
    {

        $this->set_all_lesson( $slug );

        $all_lesson_posts_items = $this->lesson_common->get_post_args();

        return $all_lesson_posts_items;
    }

    public function set_all_lesson( $taxonomy_slug )
    {

        $custom_query = array( 'order' => 'ASC' );

        $query = $this->basic->getGuildDeitalQuery( $taxonomy_slug, $custom_query );

        $count=0;
        while ( $query->have_posts() ) {

            $query->the_post();

            $this->lesson_common->set_post_args( $count );

            $prev_post = get_previous_post( true, '', 'guild_lesson_category' );

            $this->lesson_common->set_prev_post_id( $count, $prev_post );

            $this->lesson_common->set_lock_page_num( $count );
            $count++;

        }

        $this->wpfunc->wp_reset_postdata();

    }

}