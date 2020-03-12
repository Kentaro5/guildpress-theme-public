<?php
//session_start();
require_once( TEMP_DIR . '/assets/plugins/paypal/public/payment_form/guild-press-public-payment-form.php' );
require_once( TEMP_DIR . '/assets/plugins/paypal/public/register_form/guild-press-public-register-form.php' );
require_once( TEMP_DIR . '/assets/plugins/paypal/public/register/guild-press-public-paypal-register.php' );

class Guild_Press_User_PayPal_Register{
    public function __construct(){

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->email = new Guild_Press_Email;
        $this->payment_form = new Guild_Press_Public_Payment_Form;
        $this->register_form = new Guild_Press_Public_Register_Form;
        $this->paypal_register = new Guild_Press_Public_PayPal_Register;
        $this->load();
    }

    public function load()
    {

        add_shortcode( 'guild_press_paypal_user_register', array( $this, 'branch_paypal_user_form' ) );

        add_action( 'init', array( $this, 'get_action' ) );

        $this->basic_settings = $this->wpfunc->get_option(SLUGNAME.'_basic_setting');

    }

    //Formからアクションを受け取る
    public function get_action(){

        if( !isset( $_POST["guild_press_paypal_action"] ) || !$_POST["guild_press_paypal_action"] ){
            return;
        }

        $check_nonce = $this->wpfunc->wp_verify_nonce( $_POST['register_user_box'], 'guild_press_register_user' );

        if( !$check_nonce && !isset( $_POST["register_user_box"] ) ){
            return;
        }


        $this->action = ( isset( $_POST["guild_press_paypal_action"] ) ) ? trim( $_POST["guild_press_paypal_action"] ) : '';

        $this->action_check( $this->action );
    }

    public function action_check( $action='' ){

        if( !$action || !isset( $action ) || !is_string( $action ) ){
            return;
        }

        switch ($action) {
            case 'paypal_user_register':
                //ペイパルを使用したユーザー登録
            $this->paypal_register->register_before_payment_new_user();
            break;
        }
    }

    //ユーザーのステータスに合わせて、表示するフォームを分岐させる。
    public function branch_paypal_user_form( $form_meta_id, $user_id )
    {

        ob_start();
        $this->get_branch_paypal_user_form( $form_meta_id, $user_id );
        $paypal_form = ob_get_contents();
        ob_end_clean();

        return $paypal_form;

    }

    //ユーザーのステータスに合わせて、表示するフォームを分岐させる。
    public function get_branch_paypal_user_form( $form_meta_id, $user_id )
    {
        $form_meta_id = shortcode_atts( array(
            'id' => '',
        ), $form_meta_id, 0 );

        $user = wp_get_current_user();

        $user_status_arr = $this->wpfunc->get_user_meta( $user_id, 'status' );
        $user_status_arfefer = $this->wpfunc->get_user_meta( intval($user_id), 'status' );

        //ユーザーがログインしているかチェック。
        if( $user->exists() ){

            $user_id = $user->ID;

            //ユーザーのステータスを取得
            $user_status_arr = $this->wpfunc->get_user_meta( $user_id, 'status' );
            //中身をチェックして数値に変換。1なら決済前、""なら決済後
            $user_status = ( isset($user_status_arr[0]) && $user_status_arr[0] !== "" ) ? intval( $user_status_arr[0] ) : '';

            $user_status_arfefer = $this->wpfunc->get_user_meta( intval($user_id), 'status' );

            //ユーザーのステータスが決済前なら、決済フォーム表示する。
            $this->payment_form->paypal_payment_form( $user, $form_meta_id );

        }else{

            $this->register_form->paypal_user_register_form( $form_meta_id );
        }

    }

}