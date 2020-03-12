<?php

class Front_Lesson_Overview_Widget extends WP_Widget{
    protected $public_lesson_overview_template;
    protected $admin_lesson_overview_template;
    /**
     * Widgetを登録する
     */
    public function __construct() {
        parent::__construct( false, 'GP:レッスン概要ポスト', array( 'description' => 'レッスン概要ページを表示するためのウィジェットです。' ) );

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {

        $this->public_lesson_overview_template = 'templates/public/widget/front/lesson_overview_lists/front-lesson-overview.php';

        $this->admin_lesson_overview_template = 'templates/admin/widget/front/lesson_overview_lists/front-lesson-overview-form.php';

        add_action( 'admin_footer', array( $this, 'add_lesson_overview_box_js' ) );

    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['gp_lesson_overview_title'] = strip_tags( $new_instance['gp_lesson_overview_title'] );
        $instance['overview_cat_name'] = strip_tags( $new_instance['overview_cat_name'] );
        $instance['gp_overview_col_num'] = strip_tags( $new_instance['gp_overview_col_num'] );
        $instance['gp_overview_lesson_link_text'] = strip_tags( $new_instance['gp_overview_lesson_link_text'] );

        $skip_arr = array( 'gp_lesson_overview_title' );

        foreach( $new_instance as $key => $value ){

            if ( ! $this->basic->in_array( $value, $skip_arr ) && isset ( $new_instance[$key] ) )
            {

                if ( '' !== trim( $value ) ){
                    $instance[$key] = $value;
                }
            }
        }

        return $instance;
    }

    /**
     * 表側の Widget を出力する
     *
     * @param array $args      'register_sidebar'で設定した「before_title, after_title, before_widget, after_widget」が入る
     * @param array $instance  Widgetの設定項目
     */
    public function widget( $args, $instance ) {

        $gp_lesson_overview_title = ( isset($instance['gp_lesson_overview_title']) && $instance['gp_lesson_overview_title'] !== "" ) ? $instance['gp_lesson_overview_title'] : '最近追加されたレッスン';

        $gp_overview_col_num = array();
        for ($i=0; $i < count( $instance['gp_overview_col_num'] ); $i++) {

            $gp_overview_col_num[$i] = ( isset($instance['gp_overview_col_num'][$i]) && $instance['gp_overview_col_num'][$i] !== "" ) ? $instance['gp_overview_col_num'][$i] : '4';
        }

        $gp_lesson_link_text = array();
        for ($i=0; $i < count( $instance['gp_overview_lesson_link_text'] ); $i++) {

            $gp_lesson_link_text[$i] = ( isset($instance['gp_overview_lesson_link_text'][$i]) && $instance['gp_overview_lesson_link_text'][$i] !== "" ) ? $instance['gp_overview_lesson_link_text'][$i] : '続きはこちら';
        }

        //各数字のインスタンスの値のチェックを行う。
        $overview_query = array();
        for ($o=0; $o < count($instance['overview_cat_name']); $o++) {

            $overview_query[$o] = $this->basic->getGuildLessonQuery( $instance['overview_cat_name'][$o] );
        }

        $post_items = array();

        for ($i=0; $i < count( $overview_query ); $i++) {
            $post_items[$i] = $this->return_all_posts_by_slug( $overview_query[$i] );
        }

        $gp_data = array(
            'overview_title' => $gp_lesson_overview_title,
            'post_items' => $post_items,
            'link_text' => $gp_lesson_link_text,
            'col_num' => $gp_overview_col_num,
            'no_image' => TEMP_DIR_URI.'/assets/img/no-image.png',
            'post_id' => 0,
            'post_desc' => '',
            'post_link' => '',
            'post_thumb' => '',
            'post_overview_desc' => '',
        );

        if( ! $file_path = $this->basic->load_template( $this->public_lesson_overview_template, false ) ){

            return;
        }

        include( $file_path );

        $this->wpfunc->wp_reset_postdata();
    }

    public function return_all_posts_by_slug($query)
    {

        $post_args = array(
                'post_link' => '',
                'post_title' => '',
                'post_desc' => '',
                'post_thumb' => '',
                'post_id' => 0,
                'prev_post_id' => 0,
                'is_lock_page' => 0,
        );

        while ( $query->have_posts() ) {
            $query->the_post();
            $post_args['post_link'] = get_post_permalink();
            $post_args['post_title'] = get_the_title();
            $post_args['post_desc'] = get_the_excerpt();
            $post_args['post_thumb'] = get_the_post_thumbnail_url();
            //IDを取得して、ページに鍵を掛けるか切り分ける。
            $post_args['post_id'] = get_the_ID();

            $prev_post = get_previous_post( true, '', 'guild_lesson_category' );

            if( $prev_post === '' || empty( $prev_post ) ){

                $post_args['prev_post_id'] = 0;
            }else{
                $post_args['prev_post_id'] = $prev_post->ID;
            }

            $guild_press_lock_page = $this->wpfunc->get_post_meta( $post_args['post_id'], 'guild_press_lock_page' );

            $guild_press_lock_page = $this->basic->check_value_of_post_meta( $guild_press_lock_page );

            $user_lesson_detail = $this->basic->return_user_complete_lesson_lists( $post_args['post_id'] );
            $user_lesson_detail[0] = $this->basic->check_array( $user_lesson_detail[0] );


            //ポストIDを文字列化
            $str_now_post_id = strval($post_args['post_id']);
            $str_prev_post_id = strval($post_args['prev_post_id']);

            if( $guild_press_lock_page[0] === '2' ){

                $is_now_post_comp = $this->basic->in_array( $str_now_post_id , $user_lesson_detail[0]);
                $is_prev_post_comp = $this->basic->in_array( $str_prev_post_id , $user_lesson_detail[0]);

                if( $is_prev_post_comp || $is_now_post_comp ){

                    //元のコンテンツを返す。
                    $post_args['is_lock_page'] = '1';
                }else{

                    $post_args['is_lock_page'] = '2';
                }
            }else{

                    //元のコンテンツを返す。
                $post_args['is_lock_page'] = '1';
            }

        }


        $this->wpfunc->wp_reset_postdata();
        return $post_args;
    }

    /** Widget管理画面を出力する
     *
     * @param array $instance 設定項目
     * @return string|void
     */
    public function form( $instance ){

        $instance['gp_lesson_overview_title'] = ( isset($instance['gp_lesson_overview_title']) && $instance['gp_lesson_overview_title'] !== "" ) ? $instance['gp_lesson_overview_title'] : '';
        $gp_lesson_overview_title = $instance['gp_lesson_overview_title'];
        $gp_lesson_overview_title_name = $this->get_field_name('gp_lesson_overview_title');
        $gp_lesson_overview_title_id = $this->get_field_id('gp_lesson_overview_title');

        $instance['overview_cat_name'] = ( isset($instance['overview_cat_name']) && $instance['overview_cat_name'] !== "" ) ? $instance['overview_cat_name'] : array(  0 => ''  );
        $gp_lesson_overview_category = $instance['overview_cat_name'];
        $gp_overview_cat_num_name = $this->get_field_name('overview_cat_name');
        $gp_overview_cat_num_id = $this->get_field_id('overview_cat_name');

        $instance['gp_overview_col_num'] = ( isset($instance['gp_overview_col_num']) && $instance['gp_overview_col_num'] !== "" ) ? $instance['gp_overview_col_num'] : array(  0 => 0 );
        $gp_overview_col_num = $instance['gp_overview_col_num'];
        $gp_overview_col_name = $this->get_field_name('gp_overview_col_num');
        $gp_overview_col_id = $this->get_field_id('gp_overview_col_num');

        $instance['gp_overview_lesson_link_text'] = ( isset($instance['gp_overview_lesson_link_text']) && $instance['gp_overview_lesson_link_text'] !== "" ) ? $instance['gp_overview_lesson_link_text'] : array( 0 => '' );
        $gp_overview_lesson_link_text = $instance['gp_overview_lesson_link_text'];
        $gp_overview_lesson_link_text_name = $this->get_field_name('gp_overview_lesson_link_text');
        $gp_overview_lesson_link_text_id = $this->get_field_id('gp_overview_lesson_link_text');

        $cat_lists = $this->wpfunc->get_terms( array( 'taxonomy' => 'guild_lesson_category', 'hide_empty' => false ) );

        $loop_count = count( $gp_lesson_overview_category );

        if( $loop_count === 0 ){
            $loop_count = 1;
        }

        $gp_data = array(
            'loop_count' => $loop_count,
            'overview_title_id' => $gp_lesson_overview_title_id,
            'overview_title_name' => $gp_lesson_overview_title_name,
            'overview_title' => $gp_lesson_overview_title,
            'col_num_id' => $gp_overview_col_id,
            'col_num_name' => $gp_overview_col_name,
            'col_num' => $gp_overview_col_num,
            'overview_cat' => $gp_lesson_overview_category,
            'overview_cat_id' => $gp_overview_cat_num_id,
            'overview_cat_name' => $gp_overview_cat_num_name,
            'cat_lists' => $cat_lists,
            'overview_link_text_id' => $gp_overview_lesson_link_text_id,
            'overview_link_text_name' => $gp_overview_lesson_link_text_name,
            'overview_link_text' => $gp_overview_lesson_link_text,
        );

        if( ! $file_path = $this->basic->load_template( $this->admin_lesson_overview_template, false ) ){

            return;
        }

        include( $file_path );
    }

    public function add_lesson_overview_box_js()
    {
        ?>
            <script type="text/javascript">
                widget_lesson_overview_js.set_up();
                widget_lesson_overview_js.after_add_widget_action();
            </script>
        <?php
    }

}

add_action( 'widgets_init', function () {
    register_widget( 'Front_Lesson_Overview_Widget' );
} );