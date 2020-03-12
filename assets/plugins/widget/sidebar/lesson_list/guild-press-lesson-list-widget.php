<?php

class Side_Bar_Lesson_List_Widget extends WP_Widget{
    protected $sidebar_template = '';
    protected $normal_sidebar_template = '';
    protected $locked_sidebar_template = '';
    /**
     * Widgetを登録する
     */
    public function __construct() {
        parent::__construct( false, 'GPSD:関連するレッスン', array( 'description' => 'サイドバーに各レッスンに対応する投稿を表示するウィジェットです。' ) );

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();

    }

    public function load()
    {
        $this->sidebar_template = 'templates/public/widget/sidebar/lesson_list/sidebar-lesson-list.php';
        $this->admin_form_template = 'templates/admin/widget/sidebar/lesson_list/sidebar-lesson-list-form.php';

        $this->normal_sidebar_template = 'templates/public/widget/sidebar/lesson_list/normal_lesson/normal-lesson.php';
        $this->locked_sidebar_template = 'templates/public/widget/sidebar/lesson_list/locked_lesson/locked-lesson.php';


    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['gp_lesson_list_title'] = strip_tags( $new_instance['gp_lesson_list_title'] );

        return $instance;
    }

    public function getGuildDeitalQuery($slug='', $posts_num=-1)
    {
        $check_int;
        //０からスタートするため、マイナス１する。
        $check_int = intval( $posts_num );

        if( $check_int === 0 || $check_int < 0  ){

            $check_int = -1;
        }

        if( $slug === '' || empty($slug) ){

            $custom_query = array( 'posts_per_page' => $check_int, 'orderby' => 'ID', 'tax_query' => array() );
            $query = $this->basic->getGuildDeitalQuery( $slug, $custom_query );
        }else{

            $custom_query = array( 'posts_per_page' => $check_int );
            $query = $this->basic->getGuildDeitalQuery( $slug, $custom_query );
        }

        return $query;

    }

    /**
     * 表側の Widget を出力する
     *
     * @param array $args      'register_sidebar'で設定した「before_title, after_title, before_widget, after_widget」が入る
     * @param array $instance  Widgetの設定項目
     */
    public function widget( $args, $instance ) {

        $post_type = $this->basic->guild_press_get_post_type();

        if( $post_type !== 'guild_lesson_detail' ){
            return;
        }

        $page_slug = $this->basic->guild_press_get_page_slug();

        $query = $this->getGuildDeitalQuery( $page_slug );

        $gp_lesson_list_title = ( isset($instance['gp_lesson_list_title']) && $instance['gp_lesson_list_title'] !== "" ) ? $instance['gp_lesson_list_title'] : '関連するコンテンツ';

        $gp_data = array(
            'lesson_list_title' => $gp_lesson_list_title,
            'query' => $query,
        );

        if( ! $file_path = $this->basic->load_template( $this->sidebar_template, false ) ){

            return;
        }

        include( $file_path );

        $this->wpfunc->wp_reset_postdata();

    }

    public function load_sidebar_template()
    {
        $post_id = get_the_ID();
        $str_now_post_id = strval($post_id);

        $prev_post = get_previous_post( true, '', 'guild_lesson_category' );

        $str_prev_post_id = ( isset($prev_post) && $prev_post !== "" ) ? strval($prev_post->ID) : '';


        $guild_press_lock_page = $this->wpfunc->get_post_meta( get_the_ID(), 'guild_press_lock_page', true );

        $post_link = get_the_permalink();
        $post_title = get_the_title();
        $post_excerpt = get_the_excerpt();

        $gp_data = array(
            'post_link' => $post_link,
            'post_title' => $post_title,
            'post_desc' => $post_excerpt,
            'post_id' => 0,
            'post_thumb' => '',
            'post_overview_desc' => '',
        );

        if( $guild_press_lock_page === '2' ){

            $user_lesson_detail = $this->basic->return_user_complete_lesson_lists( get_the_ID() );
            $user_lesson_detail[0] = $this->basic->check_array( $user_lesson_detail[0] );

            $is_now_post_comp = $this->basic->in_array( $str_now_post_id , $user_lesson_detail[0]);
            $is_prev_post_comp = $this->basic->in_array( $str_prev_post_id , $user_lesson_detail[0]);


            if( $is_prev_post_comp || $is_now_post_comp ){


                if( ! $file_path = $this->basic->load_template( $this->normal_sidebar_template, false ) ){

                    return;
                }

                include( $file_path );

            }else{

                if( ! $file_path = $this->basic->load_template( $this->locked_sidebar_template, false ) ){
                    return;
                }

                include( $file_path );

            }
        }else{

            if( ! $file_path = $this->basic->load_template( $this->normal_sidebar_template, false ) ){
                return;
            }

            include( $file_path );
        }
    }

    /** Widget管理画面を出力する
     *
     * @param array $instance 設定項目
     * @return string|void
     */
    public function form( $instance ){

        $instance['gp_lesson_list_title'] = ( isset($instance['gp_lesson_list_title']) && $instance['gp_lesson_list_title'] !== "" ) ? $instance['gp_lesson_list_title'] : '';
        $gp_lesson_list_title = $instance['gp_lesson_list_title'];
        $gp_lesson_list_title_name = $this->get_field_name('gp_lesson_list_title');
        $gp_lesson_list_title_id = $this->get_field_id('gp_lesson_list_title');

        $gp_data = array(
            'lesson_list_title_id' => $gp_lesson_list_title_id,
            'lesson_list_title_name' => $gp_lesson_list_title_name,
            'lesson_list_title' => $gp_lesson_list_title,
        );

        if( ! $file_path = $this->basic->load_template( $this->admin_form_template, false ) ){

            return;
        }

        include( $file_path );
    }

}

add_action( 'widgets_init', function () {
    register_widget( 'Side_Bar_Lesson_List_Widget' );  //WidgetをWordPressに登録する
} );