<?php
require_once( TEMP_DIR . '/assets/plugins/member/public/admin_header/guild-press-public-admin-header.php' );
require_once( TEMP_DIR . '/assets/plugins/member/public/check_login/guild-press-public-check-login.php' );
require_once( TEMP_DIR . '/assets/plugins/member/public/user_login/guild-press-public-user-login.php' );

class Guild_Press_Public_Member
{
    public function __construct(){

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->email = new Guild_Press_Email;
        $this->admin_header = new Guild_Press_Public_Admin_Header;
        $this->check_login = new Guild_Press_Public_Check_Login;
        $this->user_login = new Guild_Press_Public_User_Login;
        $this->load();
    }

    public function load()
    {
        if( !$this->wpfunc->is_admin()) {

            add_action( 'get_header', array( $this->check_login, 'check_login' ) );
        }

         $this->basic_settings = $this->wpfunc->get_option( SLUGNAME.'_basic_setting', false );
         if( $this->basic_settings === false ){
            return;
        }

        //チェックが入っていたら管理画面のバーを削除する
        $admin_bar_check = ( isset($this->basic_settings['guild_press_check_admin_bar']) && $this->basic_settings['guild_press_check_admin_bar'] !== "" ) ? $this->basic_settings['guild_press_check_admin_bar'] : '';

        if( isset($admin_bar_check) && $admin_bar_check !== "" ){

            add_action('get_header',  array( $this->admin_header, 'delete_html_32px' ) );
            add_filter( 'show_admin_bar' , array( $this->admin_header, 'delete_admin_bar' ) );
        }

        add_action( 'init', array( $this, 'get_action' ) );

    }

    //Formからアクションを受け取る
    public function get_action(){

        if( !isset( $_POST["guild_press_action"] ) || !$_POST["guild_press_action"] ){
            return;
        }
        $this->action = ( isset( $_POST["guild_press_action"] ) ) ? trim( $_POST["guild_press_action"] ) : '';
        $this->action_check( $this->action );
    }

    //受け取ったアクションによって、処理を分岐させる。
    public function action_check( $action='' ){
        /*
            change_user_pwd
            user_logout
        */
        if( !$action || !isset( $action ) || !is_string( $action ) ){
            return;
        }

        switch ($action) {
            case 'user_login':
                $this->user_login->user_login();
            break;
        }

    }
}


