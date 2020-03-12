<?php

class Side_Bar_New_Lesson_List_Widget extends WP_Widget{
    /**
     * Widgetを登録する
     */
    public function __construct() {
        parent::__construct( false, 'GPSD:レッスン一覧', array( 'description' => 'サイドバーに追加したレッスン一覧を表示するウィジェットです。' ) );

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->public_sidebar_new_lesson_template = 'templates/public/widget/sidebar/new_lesson_list/sidebar-new-lesson-list.php';

        $this->admin_sidebar_new_lesson_template = 'templates/admin/widget/sidebar/new_lesson_list/sidebar-new-lesson-list-form.php';
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['gp_new_lesson_list_num'] = strip_tags( $new_instance['gp_new_lesson_list_num'] );
        $instance['gp_new_lesson_list_title'] = strip_tags( $new_instance['gp_new_lesson_list_title'] );

        return $instance;
    }

    public function getGuildLessonQuery($slug='', $posts_num=-1)
    {
        $check_int;
        //０からスタートするため、マイナス１する。
        $check_int = intval( $posts_num );

        if( $check_int === 0 || $check_int < 0  ){

            $check_int = -1;
        }

        if( $slug === '' || empty($slug) ){

            $custom_query = array('posts_per_page' => $check_int, 'tax_query' => array());
            $query = $this->basic->getGuildLessonQuery($slug, $custom_query);
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

        //各数字のインスタンスの値のチェックを行う。
        $query = $this->getGuildLessonQuery( '', $instance['gp_new_lesson_list_num'] );

        $gp_new_lesson_list_title = ( isset($instance['gp_new_lesson_list_title']) && $instance['gp_new_lesson_list_title'] !== "" ) ? $instance['gp_new_lesson_list_title'] : 'レッスン一覧';

        $gp_data = array(
            'new_lesson_list_title' => $gp_new_lesson_list_title,
            'query' => $query,
        );

        if( ! $file_path = $this->basic->load_template( $this->public_sidebar_new_lesson_template, false ) ){

            return;
        }

        include( $file_path );

        $this->wpfunc->wp_reset_postdata();

    }

    /** Widget管理画面を出力する
     *
     * @param array $instance 設定項目
     * @return string|void
     */
    public function form( $instance ){

        $instance['gp_new_lesson_list_title'] = ( isset($instance['gp_new_lesson_list_title']) && $instance['gp_new_lesson_list_title'] !== "" ) ? $instance['gp_new_lesson_list_title'] : '';
        $gp_new_lesson_list_title = $instance['gp_new_lesson_list_title'];
        $gp_new_lesson_list_title_name = $this->get_field_name('gp_new_lesson_list_title');
        $gp_new_lesson_list_title_id = $this->get_field_id('gp_new_lesson_list_title');

        $instance['gp_new_lesson_list_num'] = ( isset($instance['gp_new_lesson_list_num']) && $instance['gp_new_lesson_list_num'] !== "" ) ? $instance['gp_new_lesson_list_num'] : '';
        $gp_new_lesson_list_num = $instance['gp_new_lesson_list_num'];
        $gp_new_lesson_list_num_name = $this->get_field_name('gp_new_lesson_list_num');
        $gp_new_lesson_list_num_id = $this->get_field_id('gp_new_lesson_list_num');

        $cat_lists = $this->wpfunc->get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );

        $gp_data = array(
            'new_lesson_list_title_id' => $gp_new_lesson_list_title_id,
            'new_lesson_list_title_name' => $gp_new_lesson_list_title_name,
            'new_lesson_list_title' => $gp_new_lesson_list_title,
            'new_lesson_list_num_id' => $gp_new_lesson_list_num_id,
            'new_lesson_list_num_name' => $gp_new_lesson_list_num_name,
            'new_lesson_list_num' => $gp_new_lesson_list_num,
        );

        if( ! $file_path = $this->basic->load_template( $this->admin_sidebar_new_lesson_template, false ) ){

            return;
        }

        include( $file_path );

    }

}

add_action( 'widgets_init', function () {
    register_widget( 'Side_Bar_New_Lesson_List_Widget' );  //WidgetをWordPressに登録する
} );