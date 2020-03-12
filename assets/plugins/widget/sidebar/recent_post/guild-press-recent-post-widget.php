<?php

class Side_Bar_Recent_Post_Widget extends WP_Widget{
    /**
     * Widgetを登録する
     */
    public function __construct() {
        parent::__construct( false, 'GPSD:最近の投稿', array( 'description' => 'サイドバーに投稿ページを表示するウィジェットです。' ) );

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->public_recent_post_template = 'templates/public/widget/sidebar/recent_post/sidebar-recent-post.php';

        $this->admin_recent_post_template = 'templates/admin/widget/sidebar/sidebar-recent-post-lists-form.php';
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['gp_recent_posts_num'] = strip_tags( $new_instance['gp_recent_posts_num'] );
        $instance['gp_recent_posts_title'] = strip_tags( $new_instance['gp_recent_posts_title'] );
        $instance['gp_recent_posts_category'] = strip_tags( $new_instance['gp_recent_posts_category'] );

        return $instance;
    }

    public function getGuildDeitalQuery($slug='', $posts_num=-1)
    {
        $check_int;
        //０からスタートするため、マイナス１する。
        $check_int = intval( $posts_num ) - 1;

        if( $check_int === 0 || $check_int < 0  ){

            $check_int = -1;
        }

        if( $slug === '' || empty($slug) ){

            $query =  $this->basic->getNormalPostQuery( $check_int, [ 'orderby' => 'desc' ] );
        }else{

            $query =  new WP_Query( array(
                'post_type' => 'post',
                'posts_per_page' => $check_int,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $slug,
                    )
                ),
                'orderby' => 'desc',
            )
        );
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
        $query = $this->getGuildDeitalQuery( $instance['gp_recent_posts_category'], $instance['gp_recent_posts_num'] );

        $gp_recent_posts_title = ( isset($instance['gp_recent_posts_title']) && $instance['gp_recent_posts_title'] !== "" ) ? $instance['gp_recent_posts_title'] : '最近の投稿';

        $gp_data = array(
            'recent_posts_title' => $gp_recent_posts_title,
            'query' => $query,
        );

        if( ! $file_path = $this->basic->load_template( $this->public_recent_post_template, false ) ){

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

        $instance['gp_recent_posts_title'] = ( isset($instance['gp_recent_posts_title']) && $instance['gp_recent_posts_title'] !== "" ) ? $instance['gp_recent_posts_title'] : '';
        $gp_recent_posts_title = $instance['gp_recent_posts_title'];
        $gp_recent_posts_title_name = $this->get_field_name('gp_recent_posts_title');
        $gp_recent_posts_title_id = $this->get_field_id('gp_recent_posts_title');

        $instance['gp_recent_posts_num'] = ( isset($instance['gp_recent_posts_num']) && $instance['gp_recent_posts_num'] !== "" ) ? $instance['gp_recent_posts_num'] : '';
        $gp_recent_posts_num = $instance['gp_recent_posts_num'];
        $gp_recent_posts_num_name = $this->get_field_name('gp_recent_posts_num');
        $gp_recent_posts_num_id = $this->get_field_id('gp_recent_posts_num');

        $instance['gp_recent_posts_category'] = ( isset($instance['gp_recent_posts_category']) && $instance['gp_recent_posts_category'] !== "" ) ? $instance['gp_recent_posts_category'] : '';
        $gp_recent_posts_category = $instance['gp_recent_posts_category'];
        $gp_recent_posts_category_name = $this->get_field_name('gp_recent_posts_category');
        $gp_recent_posts_category_id = $this->get_field_id('gp_recent_posts_category');

        $cat_lists = $this->wpfunc->get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );

        $gp_data = array(
            'recent_posts_title_id' => $gp_recent_posts_title_id,
            'recent_posts_title_name' => $gp_recent_posts_title_name,
            'recent_posts_title' => $gp_recent_posts_title,
            'recent_posts_num_id' => $gp_recent_posts_num_id,
            'recent_posts_num_name' => $gp_recent_posts_num_name,
            'recent_posts_num' => $gp_recent_posts_num,
            'recent_posts_category_name' => $gp_recent_posts_category_name,
            'recent_posts_category' => $gp_recent_posts_category,
            'cat_lists' => $cat_lists,
        );

        if( ! $file_path = $this->basic->load_template( $this->admin_recent_post_template, false ) ){

            return;
        }

        include( $file_path );

    }

}


add_action( 'widgets_init', function () {
    register_widget( 'Side_Bar_Recent_Post_Widget' );  //WidgetをWordPressに登録する
} );