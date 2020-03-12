<?php

class Front_Recent_Posts extends WP_Widget{
    /**
     * Widgetを登録する
     */
    public function __construct() {

        parent::__construct( false, 'GP:最近の投稿', array( 'description' => '投稿ページを表示するウィジェットです。' ) );

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();

    }

    public function load()
    {
        $this->public_recent_post_template = 'templates/public/widget/front/recent_posts/front-recent-posts-lists.php';

        $this->admin_recent_post_template = 'templates/admin/widget/front/recent_posts/front-recent-posts-lists-form.php';
    }


    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['gp_recent_posts_num'] = strip_tags( $new_instance['gp_recent_posts_num'] );
        $instance['gp_recent_posts_title'] = strip_tags( $new_instance['gp_recent_posts_title'] );
        $instance['gp_recent_posts_category'] = strip_tags( $new_instance['gp_recent_posts_category'] );
        $instance['gp_recent_posts_link_text'] = strip_tags( $new_instance['gp_recent_posts_link_text'] );

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
            $tax_arr = [
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $slug,
            ];
            $custom_arr = [ 'tax_query' => [ $tax_arr ], 'orderby' => 'desc' ];
            $query =  $this->basic->getNormalPostQuery( $check_int, $custom_arr );

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

        $gp_recent_posts_link_text = ( isset($instance['gp_recent_posts_link_text']) && $instance['gp_recent_posts_link_text'] !== "" ) ? $instance['gp_recent_posts_link_text'] : '続きを見る';

        $gp_data = array(
            'posts_title' => $gp_recent_posts_title,
            'posts_link_text' => $gp_recent_posts_link_text,
            'query' => $query,
            'no_image' => TEMP_DIR_URI.'/assets/img/no-image.png',
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

        $instance['gp_recent_posts_link_text'] = ( isset($instance['gp_recent_posts_link_text']) && $instance['gp_recent_posts_link_text'] !== "" ) ? $instance['gp_recent_posts_link_text'] : '';
        $gp_recent_posts_link_text = $instance['gp_recent_posts_link_text'];
        $gp_recent_posts_link_text_name = $this->get_field_name('gp_recent_posts_link_text');
        $gp_recent_posts_link_text_id = $this->get_field_id('gp_recent_posts_link_text');

        $cat_lists = $this->wpfunc->get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );

        $gp_data = array(
            'posts_title_id' => $gp_recent_posts_title_id,
            'posts_title_name' => $gp_recent_posts_title_name,
            'posts_title' => $gp_recent_posts_title,
            'posts_num_id' => $gp_recent_posts_num_id,
            'posts_num_name' => $gp_recent_posts_num_name,
            'posts_num' => $gp_recent_posts_num,
            'posts_category_name' => $gp_recent_posts_category_name,
            'cat_lists' => $cat_lists,
            'posts_category' => $gp_recent_posts_category,
            'posts_link_text_id' => $gp_recent_posts_link_text_id,
            'posts_link_text_name' => $gp_recent_posts_link_text_name,
            'posts_link_text' => $gp_recent_posts_link_text,
        );
        if( ! $file_path = $this->basic->load_template( $this->admin_recent_post_template, false ) ){
            return;
        }

        include( $file_path );
    }

}


add_action( 'widgets_init', function () {
    register_widget( 'Front_Recent_Posts' );  //WidgetをWordPressに登録する
} );