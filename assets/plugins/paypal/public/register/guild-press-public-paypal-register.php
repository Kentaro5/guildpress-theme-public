<?php
//session_start();
/**
 *
 */
class Guild_Press_Public_PayPal_Register extends Basic_User_Register
{
    // public $items;
    // public $erromsg;
    // public $item_field;
    // public $login_info;

    public function __construct(){
        parent::__construct();
    }

    public function register_before_payment_new_user()
    {
        //セッションのIDを更新する処理。
        if( ! $field_setting = $this->wpfunc->get_option( SLUGNAME.'_basic_setting', false ) ) {
            return;
        }

        $guild_press_register_page_link = $this->wpfunc->get_page_link($field_setting['guild_press_register']);

        $original_form_item = $this->wpfunc->get_option(SLUGNAME.'_regsiter_item_field', false );

        $this->check_post_items( $original_form_item );

        $this->set_user_payment_id();

        $this->insert_user_info( $original_form_item );

        //SESIONを破棄
        session_destroy();

        $this->redirect_user_to_page( $guild_press_register_page_link );
    }

    public function redirect_user_to_page( $guild_press_register_page_link )
    {

        $this->set_login_info();

        $this->set_ssl();

        //成功すればユーザー情報取得
        $user = $this->wpfunc->wp_signon( $this->login_info, $this->is_ssl );

        if ( ! $this->wpfunc->is_wp_error( $user ) ) {
            //指定のPayPal支払いのURLを取得する。
            $user_paypal_page_id = $this->wpfunc->get_user_meta( $this->items['user_id'], 'register_url_id', true );

            //もし、register_url_idが登録されたいなかった場合は、デフォルトの登録ページのURLを取得。
            if( $user_paypal_page_id === '' ){

                $register_page_id = intval( $this->basic_settings['guild_press_register'] );
                $redirect_url = $this->wpfunc->get_page_link( $register_page_id );
            }else{

                $paypal_page_url_id = intval($user_paypal_page_id);

                //ユーザー決済ページURL取得
                $redirect_url = $this->wpfunc->get_page_link( $paypal_page_url_id );
            }
        }else{
            $user_error = ( isset($user->errors) && $user->errors !== "" ) ? $user->errors : '';
            foreach ($user_error as $key => $value) {

                $_SESSION['error_msg']['user_error'][] = $value[0];
            }

            $redirect_url = $guild_press_register_page_link;

        }

        $this->wpfunc->wp_redirect($redirect_url);
        exit();
    }

    public function insert_user_info( $original_form_item )
    {
        $this->set_guild_press_new_user_data();

        $this->items['user_id'] = $this->wpfunc->wp_insert_user( $this->guild_press_new_user_data );

        if( is_wp_error( $this->items['user_id']  ) ) {

            $_SESSION['error_msg']['user_error'][] = $this->items['user_id']->get_error_message();
            //エラーの場合はグローバルポストIDからリターン用のリンクを取得して、返す。
            $guild_press_register_page_link = $this->wpfunc->get_page_link($this->items['global_post_id']);
            $link = $guild_press_register_page_link;
            $this->wpfunc->wp_redirect($link);
            exit();
        }

        if( $original_form_item !== false ){

            $this->update_original_form_items( $original_form_item );
        }

        //デフォルトにはipはないので、アップデートで追加する。
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'salon_user_ip', $this->items['salon_user_ip'] );

        //ユーザーデータのところにペイパルのフォームIDを表示する。
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'payment_id', $this->items['paypal_setting_id'] );

        //ユーザー認証をペイパルを支払う前にステータスを支払い前に更新
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'status', 1 );

        //ユーザーが支払いをする際のURLのIDを登録。
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'register_url_id', $this->items['global_post_id'] );

        //成功したらメールを送る。
        $this->email->send_mail($this->items['user_id'], 'user_register');
    }

    public function check_post_items( $original_form_item )
    {
        $this->set_register_info();

        $this->set_paypal_info();

        //セッションに値を保存
        $this->set_register_item_info_to_session();

        $erromsg="";

        $this->set_paypal_error();

        //エラーチェック
        $this->check_items_error();
        //フィールド入力チェック。
        if( $original_form_item !== false ){

            $this->check_original_form_item( $original_form_item );
        }

        $error_flg=false;
        foreach ($_SESSION['error_msg'] as $session_key => $session_value) {

            if( isset($_SESSION['error_msg'][$session_key]) && $_SESSION['error_msg'][$session_key] !== "" ){

                $_SESSION['error_msg'][$session_key] = $session_value;
                $error_flg=true;
            }
        }

        //エラーがあればGETで知らせる。
        if ( $error_flg ) {

            //エラーの場合はグローバルポストIDからリターン用のリンクを取得して、返す。
            $guild_press_register_page_link = $this->wpfunc->get_page_link($this->items['global_post_id']);
            $link = $guild_press_register_page_link;
            $this->wpfunc->wp_redirect($link);
            exit();
        }
    }


    public function set_user_payment_id()
    {
        $this->guild_press_new_user_data['user_payment_id'] = $this->items['paypal_setting_id'];
    }

    public function set_paypal_info()
    {
        //post_idは数値化する。
        $this->items['paypal_setting_id'] = ( ! isset( $_POST['paypal_setting_id'] ) ) ? '' : intval($_POST['paypal_setting_id']);
    }

    public function set_paypal_error()
    {
        $this->error_msg_list['paypal_setting_id'] = 'paypalの設定IDが指定されていません。';
    }




}