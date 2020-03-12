<?php

class Front_User_Status_Widget extends WP_Widget{
    /**
     * Widgetを登録する
     */
    public function __construct() {
        parent::__construct( false, 'GP:ユーザーステータス', array( 'description' => 'トップページで表示するユーザーの進捗状況などを表示するウィジェットです。' ) );

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->public_user_status_template = 'templates/public/widget/front/user_status/front-user-status.php';
        $this->admin_user_status_template = 'templates/admin/widget/front/user_status/front-user-status-form.php';
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['gp_my_page_url'] = strip_tags( $new_instance['gp_my_page_url'] );

        return $instance;
    }

    /**
     * 表側の Widget を出力する
     *
     * @param array $args      'register_sidebar'で設定した「before_title, after_title, before_widget, after_widget」が入る
     * @param array $instance  Widgetの設定項目
     */
    public function widget( $args, $instance ) {

        //各数字のインスタンスの値のチェックを行う。
        $user_progress_details = $this->wpfunc->apply_filters( 'guildpress_lesson_progress_details', '' );

        //現在のユーザーデータ取得
        $user = $this->wpfunc->wp_get_current_user();

        //ユーザーのアバター取得
        $user_avator = $this->wpfunc->get_avatar_url( $user->ID );

        $my_page_url = ( isset($instance['gp_my_page_url']) && $instance['gp_my_page_url'] !== "" ) ? $instance['gp_my_page_url'] : '';

        $gp_data = array(
            'user_avator' => $user_avator,
            'user' => $user,
            'my_page_url' => $my_page_url,
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

        $instance['gp_my_page_url'] = ( isset($instance['gp_my_page_url']) && $instance['gp_my_page_url'] !== "" ) ? $instance['gp_my_page_url'] : '';

        $gp_my_page_url = $instance['gp_my_page_url'];
        $gp_my_page_url_name = $this->get_field_name('gp_my_page_url');
        $gp_my_page_url_id = $this->get_field_id('gp_my_page_url');

        $gp_data = array(
            'my_page_url_id' => $gp_my_page_url_id,
            'my_page_url_name' => $gp_my_page_url_name,
            'my_page_url' => $gp_my_page_url,
        );

        if( ! $file_path = $this->basic->load_template( $this->admin_user_status_template, false ) ){
            return;
        }

        include( $file_path );
    }
}


add_action( 'widgets_init', function () {
    register_widget( 'Front_User_Status_Widget' );
} );