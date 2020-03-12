<?php
session_start();
/**
 *
 */
class Guild_Press_Public_PayPal_Register
{
    public $items;
    public $erromsg;
    public $item_field;
    public $login_info;

    public function __construct(){
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->email = new Guild_Press_Email;
        $this->load();
    }

    public function load()
    {
        $this->items = array();
    }

    public function register_before_payment_new_user()
    {
        //セッションのIDを更新する処理。
        if( ! $field_setting = $this->wpfunc->get_option( SLUGNAME.'_basic_setting', false ) ) {
            return;
        }

        $guild_press_register_page_link = $this->wpfunc->get_page_link($field_setting['guild_press_register']);

        $this->set_item_field();

        $this->set_regsiter_items();

        $_SESSION['error_msg'] = array();

        //エラーチェック
        $this->error_check();

        //フィールド入力チェック。
        $this->check_item_field();

        $error_flg = $this->get_error_flg();
        //エラーがあればGETで知らせる。
        $this->redirect_with_error_message( $error_flg );

        $guild_press_new_user_data = array (
            'user_pass'       => $this->items['password'],
            'user_login'      => $this->items['user_name'],
            'user_nicename'   => $this->items['user_nicename'],
            'user_email'      => $this->items['user_email'],
            'display_name'    => $this->items['display_name'],
            'nickname'        => $this->items['nickname'],
            'user_registered' => $this->items['user_registered'],
            'role'            => $this->items['user_role'],
            'user_payment_id' => $this->items['paypal_setting_id'],
        );

        $this->items['user_id'] = $this->wpfunc->wp_insert_user( $guild_press_new_user_data );

        //カスタムのユーザー項目も保存
        $this->update_custom_user_items();

        $this->update_user_data();

        //ユーザーに仮登録のメールを送信
        $this->email->send_mail($this->items['user_id'], 'user_register');

        //wp_signonに渡すパラーメータ用変数
        $this->set_sign_on_items();

        //SSLを使用している場合はwp_signonでセキュアクッキーをオンにする。
        $is_ssl = $this->wpfunc->is_ssl();

        //成功すればユーザー情報取得
        $user = $this->wpfunc->wp_signon( $this->login_info, $is_ssl );

        $this->redirect_user( $user );
    }

    public function update_user_data()
    {
         //デフォルトにはipはないので、アップデートで追加する。
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'salon_user_ip', $this->items['salon_user_ip'] );

        //ユーザーデータのところにペイパルのフォームIDを表示する。
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'payment_id', $this->items['paypal_setting_id'] );

        //ユーザー認証をペイパルを支払う前にステータスを支払い前に更新
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'status', 1 );

        //ユーザーが支払いをする際のURLのIDを登録。
        $this->wpfunc->update_user_meta( $this->items['user_id'], 'register_url_id', $this->items['global_post_id'] );

    }

    public function set_regsiter_items()
    {
        $this->items['last_name'] = ( ! isset( $_POST['last_name'] ) && ! $_POST['last_name'] ) ? '' : $_POST['last_name'];
        $this->items['first_name'] = ( ! isset( $_POST['first_name'] ) && ! $_POST['first_name'] ) ? '' : $_POST['first_name'];
        $this->items['user_name'] = ( ! isset( $_POST['log'] ) || ! $_POST['log'] ) ? '' : sanitize_user( $_POST['log'] );
        $this->items['user_email'] = ( ! isset( $_POST['user_email'] ) && ! $_POST['user_email'] ) ? '' : $_POST['user_email'];
        $this->items['password'] = ( ! isset( $_POST['password'] ) ) ? wp_generate_password() : $_POST['password'];
        $this->items['global_post_id'] = ( isset($_POST['global_post_id']) && $_POST['global_post_id'] !== "" ) ? intval( $_POST['global_post_id'] ) : '';

        //post_idは数値化する。
        $this->items['paypal_setting_id'] = ( ! isset( $_POST['paypal_setting_id'] ) ) ? '' : intval($_POST['paypal_setting_id']);
        $this->items['user_registered'] = date( 'Y-m-d H:i:s' );
        $this->items['user_role']       = $this->wpfunc->get_option( 'default_role', false );
        $this->items['guild_press_user_ip']    = $_SERVER['REMOTE_ADDR'];

    }

    public function set_regsiter_items_to_session()
    {
        $_SESSION['last_name'] = ( ! isset( $this->items['last_name'] ) || ! $this->items['last_name'] ) ? '' : $this->items['last_name'];

        $_SESSION['first_name'] = ( ! isset( $this->items['first_name'] ) && ! $this->items['first_name'] ) ? '' : $this->items['first_name'];

        $_SESSION['user_name'] = ( ! isset( $this->items['user_name'] ) || ! $this->items['user_name'] ) ? '' : sanitize_user( $this->items['user_name'] );

        $_SESSION['user_email'] = ( ! isset( $this->items['user_email'] ) && ! $this->items['user_email'] ) ? '' : $this->items['user_email'];

        $_SESSION['password'] = ( ! isset( $this->items['password'] ) && ! $this->items['password'] ) ? '' : $this->items['password'];
    }

    public function set_register_item_field()
    {
        //配列番号0-3には、必須項目が入っているため、４からスタートする。
        for ($i=4; $i < count($this->item_field); $i++) {
            if ( $this->item_field[$i][4] == 'y' ) {

                $this->items[ $this->item_field[$i][2] ] = ( isset( $_POST[ $this->item_field[$i][2] ] ) ) ? $_POST[ $this->item_field[$i][2] ] : '';

                $_SESSION[ $this->item_field[$i][2] ] = ( isset( $this->items[ $this->item_field[$i][2] ] ) ) ? esc_html( $this->items[ $this->item_field[$i][2] ] ) : '';
            }
        }
    }

    public function set_sign_on_items()
    {
        $this->login_info = array();
        $this->login_info['user_login'] = $this->items['user_name'];
        $this->login_info['user_password'] = $this->items['password'];
        $this->login_info['remember'] = true;
    }

    public function check_errors()
    {
        if( $this->items['paypal_setting_id'] === '' ){

            $this->erromsg = "paypalの設定IDが指定されていません。";
            $_SESSION['error_msg']['paypal_setting_id'] = "paypalの設定IDが指定されていません。";
        }else{

            $_SESSION['error_msg']['paypal_setting_id'] = '';
        }
    }

    public function check_last_name()
    {
        if( ! $this->items['last_name'] ){

            $this->erromsg = "姓は必須入力項目です。";
            $_SESSION['error_msg']['last_name'] = "姓は必須入力項目です。";
        }else{

            $_SESSION['error_msg']['last_name'] = '';
        }
    }

    public function check_first_name()
    {
        if( ! $this->items['first_name'] ){

            $this->erromsg = "名は必須入力項目です。";
            $_SESSION['error_msg']['first_name'] = "名は必須入力項目です。";
        }else{

            $_SESSION['error_msg']['first_name'] = '';
        }
    }

    public function check_user_email()
    {
        $result = $this->basic->check_email_format( $check_val );
        if( $result === 0 ){

            $this->erromsg = "メールアドレスの形式が正しくありません";
            $_SESSION['error_msg']['user_email'] = "メールアドレスの形式が正しくありません";
        }elseif( email_exists( $this->items['user_email'] ) ){

            $this->erromsg = "指定されたemailアドレスは既に存在しています。";
            $_SESSION['error_msg']['user_email'] = "指定されたemailアドレスは既に存在しています。";
        }elseif( ! $this->items['user_email'] ){

            $this->erromsg = "メールアドレスは必須入力項目です。";
            $_SESSION['error_msg']['user_email'] = "メールアドレスは必須入力項目です。";
        }else{

            $_SESSION['error_msg']['user_email'] = '';
        }
    }

    public function check_user_name($value='')
    {
        if( username_exists( $this->items['user_name'] ) ){

            $this->erromsg = "指定されたユーザー名は既に存在しています。";
            $_SESSION['error_msg']['user_name'] = "指定されたユーザー名は既に存在しています。";
        }elseif( $this->items['user_name'] === '' ){

            $this->erromsg = "ユーザーネームは必須入力項目です。";
            $_SESSION['error_msg']['user_name'] = "ユーザーネームは必須入力項目です。";
        }else{

            $_SESSION['error_msg']['user_name'] = '';
        }
    }

    public function check_password()
    {
        if( $this->items['password'] === '' || ! $this->items['password'] ){

            $_SESSION['error_msg']['password'] = "パスワードは必須入力項目です。";
        }else{

            $_SESSION['error_msg']['password'] = '';
        }
    }

    public function set_item_field()
    {
        $this->item_field = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );
    }

    public function error_check()
    {
        $this->check_errors();

        $this->set_regsiter_items_to_session();

        $this->erromsg="";

        $this->check_last_name();

        $this->check_first_name();
        //エラーチェック
        $this->check_user_email();

        //エラーチェック
        $this->check_user_name();

        $this->check_password();
    }

    public function check_item_field_error()
    {
        for ($k=4; $k < count($this->item_field); $k++) {

            if ( $this->item_field[$k][5] == 'y' ) {
                if ( ! isset( $this->items[$this->item_field[$k][2]] ) || $this->items[$this->item_field[$k][2]] == '' ) {

                    $_SESSION['error_msg'][$this->item_field[$k][2]] = sprintf( esc_html($this->item_field[$k][1])."は必須入力項目です。" );
                }else{

                    $_SESSION['error_msg'][$this->item_field[$k][2]] = '';
                }
            }
        }
    }

    public function check_item_field()
    {
        if( isset( $this->item_field ) && $this->item_field ){

            $this->set_register_item_field();

            $this->check_item_field_error();
        }
    }

    public function get_error_flg()
    {
        $error_flg = false;
        foreach ($_SESSION['error_msg'] as $key => $value) {

            if( isset($_SESSION['error_msg'][$key]) && $_SESSION['error_msg'][$key] !== "" ){

                $_SESSION['error_msg'][$key] = $value;
                $error_flg = true;
            }
        }

        return $error_flg;
    }

    public function redirect_with_error_message( $error_flg )
    {
        if ( $this->erromsg || $error_flg ) {

            //エラーの場合はグローバルポストIDからリターン用のリンクを取得して、返す。
            $guild_press_register_page_link = $this->wpfunc->get_page_link($this->items['global_post_id']);
            $link = $guild_press_register_page_link;
            $this->wpfunc->wp_redirect($link);
            exit();
        }
    }

    public function update_custom_user_items()
    {
        foreach ( $this->item_field as $meta ) {
            if ( $meta[4] == 'y' ) {
                if ( $meta[2] != 'password' && $meta[2] != 'user_email' ) {
                    $this->wpfunc->update_user_meta( $this->items['user_id'], $meta[2], $this->items[$meta[2]] );
                }
            }
        }
    }

    public function redirect_user($user)
    {
        if ( ! $this->wpfunc->is_wp_error( $user ) ) {

            $this->redirect_user_with_success();
        }else{

            $this->redirect_user_with_error( $user );
        }
    }

    public function redirect_user_with_error( $user )
    {
        $user_error = ( isset($user->errors) && $user->errors !== "" ) ? $user->errors : '';
        foreach ($user_error as $key => $value) {

            $_SESSION['error_msg']['user_error'][] = $value[0];
        }

        $link = $guild_press_register_page_link;
        wp_redirect($link);
        exit();
    }

    public function redirect_user_with_success()
    {
        session_destroy();

        //指定のPayPal支払いのURLを取得する。
        $user_paypal_page_id = $this->wpfunc->get_user_meta( $this->items['user_id'], 'register_url_id', true );

        //もし、register_url_idが登録されたいなかった場合は、デフォルトの登録ページのURLを取得。
        if( $user_paypal_page_id === '' ){

            $register_page_id = intval( $this->basic_settings['guild_press_register'] );
            $redirect_url = $this->wpfunc->get_page_link( $register_page_id );
        }else{

            $paypal_page_url_id = intval($user_paypal_page_id[0]);

            //ユーザー決済ページURL取得
            $redirect_url = $this->wpfunc->get_page_link( $paypal_page_url_id );
        }

        $this->wpfunc->wp_redirect($redirect_url);
        exit();
    }
}