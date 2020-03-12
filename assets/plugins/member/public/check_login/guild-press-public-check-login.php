<?php
require_once( TEMP_DIR . '/assets/plugins/member/public/update_user_status/guild-press-public-update-user-status.php' );
/**
*
*/
class Guild_Press_Public_Check_Login
{
    public $current_user_info;
    public $user_payment_status;
    public $login_page_id;
    public $register_page_id;

    public function __construct(){
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->update_user_status = new Guild_Press_Public_Update_User_Status;
        $this->load();
    }

    public function load()
    {
        $this->basic_settings = $this->wpfunc->get_option( SLUGNAME.'_basic_setting', false );
        if( $this->basic_settings === false ){
            return;
        }
    }

    //ログインをチェック。
    public function check_login()
    {
        if( !isset($this->basic_settings['guild_press_register']) ){
            return;
        }

        if( !isset($this->basic_settings['guild_press_login']) ){
            return;
        }

        $paypal_ipn_flg = ( isset($_POST['custom']) && $_POST['custom'] !== "" ) ? $_POST['custom'] : '';
        $get_paypal_result = ( isset($_GET['st']) && $_GET['st'] !== "" ) ? $_GET['st'] : '';

        $this->check_paypal_result( $get_paypal_result );

        $this->check_paypal_ipn( $paypal_ipn_flg );

        global $post;

        if( empty($post->ID) ){
            return;
        }
        //登録ページフラグにチェックが入っているかチェックする。
        $registe_page_flg = $this->wpfunc->get_post_meta( $post->ID, '_guild_press_register_page_check', true );

        $this->set_current_user_info();

        $this->set_user_payment_status();

        $this->set_login_page_id();

        $this->set_register_page_id();

        if( $registe_page_flg === '1' ){
            return;
        }

        if( $this->is_register_page() ){
            return;
        }

        if( $this->is_after_payment_page() ){

            $this->is_user_logged_in();
            return;
        }

        if( $this->is_cancel_page() ){
            return;
        }

        $this->is_user_logged_in();
    }

    public function is_user_logged_in()
    {
        //ユーザーがログインしていなかったら、ログインページに飛ばす。
        if( ! $this->wpfunc->is_user_logged_in() && ! $this->wpfunc->is_page( $this->login_page_id ) ){

            $this->go_to_login_page();
        }elseif( $this->user_payment_status === '1' ){

            $this->go_to_payment_page();
        }
    }

    public function check_paypal_result( $get_paypal_result='' )
    {
        if( $get_paypal_result === "Completed" ){

            $this->update_user_status->update_user_payment_status();
            return;
        }
    }

    public function check_paypal_ipn( $paypal_ipn_flg='' )
    {
        if( $paypal_ipn_flg !== "" ){
            $this->update_user_status->update_user_payment_status();
            $this->update_user_status->add_user_paypal_payment_id();
            return;
        }
    }

    public function is_register_page()
    {
        $register_page_id = $this->basic_settings['guild_press_register'];
        if( $register_page_id !== "" || !empty( $register_page_id ) ){
            if( $this->wpfunc->is_page( $register_page_id ) ){
                return true;
            }
        }
        return false;
    }

    public function is_after_payment_page()
    {
        $after_payment_page_id = $this->basic_settings['guild_press_after_payment'];
        if( $after_payment_page_id !== "" || !empty( $after_payment_page_id ) ){
            if( $this->wpfunc->is_page( $after_payment_page_id ) ){

                return true;
            }
        }
        return false;
    }

    public function is_cancel_page()
    {
        $cancel_page_id = $this->basic_settings['guild_press_payment_cancel_url'];
        if( $cancel_page_id !== "" || !empty( $cancel_page_id ) ){
            if( $this->wpfunc->is_page( $cancel_page_id )  ){

                return true;
            }
        }
        return false;
    }

    public function set_current_user_info()
    {
        $this->current_user_info = $this->wpfunc->wp_get_current_user();
    }

    public function set_user_payment_status()
    {
        //ユーザーの決済ステータスも取得。1なら決済前なので、決済ページに飛ばす
        $this->user_payment_status = $this->wpfunc->get_user_meta( $this->current_user_info->ID , 'status', true );
    }

    public function set_login_page_id()
    {
        //ログインのページをタイトルで指定して、取得。
        $this->login_page_id = intval( $this->basic_settings['guild_press_login'] );
    }

    public function set_register_page_id()
    {
        $this->register_page_id = intval( $this->basic_settings['guild_press_register'] );
    }

    public function go_to_login_page()
    {
        //ログインページURL取得
        $redirect_url = $this->wpfunc->get_page_link( $this->login_page_id );
        $this->wpfunc->wp_redirect($redirect_url);
        exit();
    }

    public function go_to_payment_page()
    {
        //指定のPayPal支払いのURLを取得する。
        $user_paypal_page_id = $this->wpfunc->get_user_meta( $this->current_user_info->ID, 'register_url_id', true );

        //もし、register_url_idが登録されたいなかった場合は、デフォルトの登録ページのURLを取得。
        if( $user_paypal_page_id === '' ){

            $redirect_url = $this->wpfunc->get_page_link( $this->register_page_id );
        }else{

            $paypal_page_url_id = intval($user_paypal_page_id);
            //ユーザー決済ページURL取得
            $redirect_url = $this->wpfunc->get_page_link( $paypal_page_url_id );
        }

        $this->wpfunc->wp_redirect($redirect_url);
        exit();
    }

}