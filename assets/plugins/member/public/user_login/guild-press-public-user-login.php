<?php

class Guild_Press_Public_User_Login
{
    public $login_page_id;
    public $redirect_url;
    public $rememberme;
    public $login_info;
    public function __construct(){
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
         $this->basic_settings = $this->wpfunc->get_option(SLUGNAME.'_basic_setting');
         if( $this->basic_settings === false ){
            return;
        }
    }

    public function user_login(){

        //セキュリティ
        if( ! $this->wpfunc->wp_verify_nonce( $_POST['login_user'], 'guild_press_login' ) && !isset( $_POST["login_user"] ) ) {
            return;
        }

        $this->set_default_redirect_link();

        if ( isset( $_POST['user_email'] ) && $_POST['user_email'] !== "" &&  $_POST['user_pass'] !== "" && isset( $_POST['user_pass'] ) ) {

            //送られてきたPOSTの値をサニタイズ
            $user_name = sanitize_user( $_POST['user_email'] );
            $user_pass = trim( $_POST['user_pass'] );

            $this->set_remember_me();

            $this->set_login_info( $user_name, $user_pass );

            //SSLを使用している場合はwp_signonでセキュアクッキーをオンにする。
            $is_ssl = $this->wpfunc->is_ssl();

            //成功すればユーザー情報取得
            $user = $this->wpfunc->wp_signon( $this->login_info, $is_ssl );

            if ( ! $this->wpfunc->is_wp_error( $user ) ) {

                $this->redirect_user();
            }else{

                $this->redirect_user_with_uncorrect_info();
            }
        }else{

            $this->redirect_user_with_required_msg();
        }
    }

    public function set_default_redirect_link()
    {
        //エラーがあった場合はログインページに飛ばすためリンク取得。
        $this->login_page_id = $this->basic_settings['guild_press_login'];
        $this->redirect_url = $this->wpfunc->get_permalink( $this->login_page_id );
    }

    public function set_remember_me()
    {
        //ログイン状態を維持するかどうかチェック。
        if( isset( $_POST['rememberme'] ) && $_POST['rememberme'] !== "" ){
            $this->rememberme = true;
        }else{
            $this->rememberme = false;
        }
    }

    public function set_login_info( $user_name, $user_pass )
    {
        //wp_signonに渡すパラーメータ用変数
        $this->login_info = array();
        $this->login_info['user_login'] = $user_name;
        $this->login_info['user_password'] = $user_pass;
        $this->login_info['remember'] = $this->rememberme;
    }

    public function redirect_user()
    {
        //もし、ログイン後のリダイレクトページが指定されていた場合は、そのページに飛ばす。
        $is_admin = $this->wpfunc->current_user_can('administrator');

        $after_login_page_id = $this->basic_settings['guild_press_after_login'];

         //管理者の場合は管理画面へ飛ばす。
        if( $is_admin ){

            $this->redirect_url = $this->wpfunc->admin_url();
        }elseif( $after_login_page_id !== "" ){

            $this->redirect_url = $this->wpfunc->get_permalink( $after_login_page_id );
        }else{

            //指定されていない場合は、ホームに飛ばす。
            $this->redirect_url = $this->wpfunc->home_url();
        }

        //各ページにリダイレクト
        $this->wpfunc->wp_redirect( $this->redirect_url );
        exit();
    }

    public function redirect_user_with_uncorrect_info()
    {
        $erromsg = "ユーザー名、もしくは、パスワードが間違っています。";
        $this->wpfunc->wp_redirect($this->redirect_url."?err=".$erromsg);
        exit();
    }

    public function redirect_user_with_required_msg()
    {
        $erromsg = "ユーザー名、もしくは、パスワードを入力して下さい。";
        $this->wpfunc->wp_redirect($this->redirect_url."?err=".$erromsg);
        exit();
    }

}


