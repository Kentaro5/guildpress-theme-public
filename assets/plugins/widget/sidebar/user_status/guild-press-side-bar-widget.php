<?php

class Side_Bar_User_Status_Widget extends WP_Widget{
    /**
     * Widgetを登録する
     */
    public function __construct() {
        parent::__construct( false, 'GPSD:ユーザーステータス', array( 'description' => 'サイドバー側でユーザーのステータスを表示するウィジェットです。' ) );

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->public_user_status_template = 'templates/public/widget/sidebar/user_status/sidebar-user-status.php';

        $this->admin_user_status_template = 'templates/admin/widget/sidebar/user_status/sidebar-user-status-form.php';
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['gu_side_bar_user_status_title'] = strip_tags( $new_instance['gu_side_bar_user_status_title'] );

        return $instance;
    }

    /**
     * 表側の Widget を出力する
     *
     * @param array $args      'register_sidebar'で設定した「before_title, after_title, before_widget, after_widget」が入る
     * @param array $instance  Widgetの設定項目
     */
    public function widget( $args, $instance ) {

        //query_varからページのスラッグを取得する。
        $page_slug = $this->wpfunc->get_query_var( 'guild_press_lesson_slug', false );

        //スラッグを取得できなかった場合は、ページIDからカテゴリーを取得する。
        if( $page_slug === false ){

            $page_slug = $this->basic->guild_press_get_page_slug();
        }
        //各数字のインスタンスの値のチェックを行う。
        $user_progress_details = $this->wpfunc->apply_filters( 'guildpress_lesson_progress_details', '' );

        //現在のユーザーデータ取得
        $user = $this->wpfunc->wp_get_current_user();

        //ユーザーのアバター取得
        $user_avator = $this->wpfunc->get_avatar_url( $user->ID );

        //ユーザーの現在の会員ランクを取得
        $user_rank_num = $this->wpfunc->get_user_meta( $user->ID, 'gp_member_rank', true );
        $user_rank = '';

        if( $user_rank_num !== '' ){
            $user_rank = $this->wpfunc->get_post_meta( intval( $user_rank_num ), 'member_rank_name', true );
        }

        $title = ( isset($instance['gu_side_bar_user_status_title']) && $instance['gu_side_bar_user_status_title'] !== "" ) ? $instance['gu_side_bar_user_status_title'] : '受講ステータス';

        $gp_data = array(
            'title' => $title,
            'user_avator' => $user_avator,
            'user' => $user,
            'user_rank' => $user_rank,
            'page_slug' => $page_slug,
            'user_progress_details' => $user_progress_details,
        );

        if( ! $file_path = $this->basic->load_template( $this->public_user_status_template, false ) ){
            return;
        }

        include( $file_path );

    }

    /** Widget管理画面を出力する
     *
     * @param array $instance 設定項目
     * @return string|void
     */
    public function form( $instance ){

        $instance['gu_side_bar_user_status_title'] = ( isset($instance['gu_side_bar_user_status_title']) && $instance['gu_side_bar_user_status_title'] !== "" ) ? $instance['gu_side_bar_user_status_title'] : '';
        $gu_side_bar_user_status_title = $instance['gu_side_bar_user_status_title'];
        $gu_side_bar_user_status_title_name = $this->get_field_name('gu_side_bar_user_status_title');
        $gu_side_bar_user_status_title_id = $this->get_field_id('gu_side_bar_user_status_title');

        $gp_data = array(
            'user_status_title_id' => $gu_side_bar_user_status_title_id,
            'user_status_title_name' => $gu_side_bar_user_status_title_name,
            'user_status_title' => $gu_side_bar_user_status_title,
        );

        if( ! $file_path = $this->basic->load_template( $this->admin_user_status_template, false ) ){
            return;
        }

        include( $file_path );
    }
}

add_action( 'widgets_init', function () {
    register_widget( 'Side_Bar_User_Status_Widget' );  //WidgetをWordPressに登録する
} );