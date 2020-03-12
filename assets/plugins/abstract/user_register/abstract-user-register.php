<?php
session_start();
/**
 *
 */
abstract class Basic_User_Register
{

    protected $item_field = array();
    protected $error_msg_list = array();
    protected $guild_press_new_user_data;
    protected $login_info = array();
    protected $is_ssl;

    public function __construct()
    {
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->email = new Guild_Press_Email;
    }

    public function set_ssl()
    {
        //SSLを使用している場合はwp_signonでセキュアクッキーをオンにする。
        $this->is_ssl = $this->wpfunc->is_ssl();
    }

    public function set_login_info()
    {
        //wp_signonに渡すパラーメータ用変数
        $this->login_info['user_login'] = $this->items['user_name'];
        $this->login_info['user_password'] = $this->items['password'];
        $this->login_info['remember'] = true;
    }

    public function set_register_info()
    {
        $this->items['last_name'] = ( ! isset( $_POST['last_name'] ) && ! $_POST['last_name'] ) ? '' : $_POST['last_name'];
        $this->items['first_name'] = ( ! isset( $_POST['first_name'] ) && ! $_POST['first_name'] ) ? '' : $_POST['first_name'];
        $this->items['user_name'] = ( ! isset( $_POST['log'] ) || ! $_POST['log'] ) ? '' : sanitize_user( $_POST['log'] );
        $this->items['user_email'] = ( ! isset( $_POST['user_email'] ) && ! $_POST['user_email'] ) ? '' : $_POST['user_email'];

        $this->items['password'] = ( ! isset( $_POST['password'] ) ) ? wp_generate_password() : $_POST['password'];

        $this->items['global_post_id'] = ( isset($_POST['global_post_id']) && $_POST['global_post_id'] !== "" ) ? intval( $_POST['global_post_id'] ) : '';

        $this->items['user_registered'] = date( 'Y-m-d H:i:s' );
        $this->items['user_role']       = $this->wpfunc->get_option( 'default_role' );
        $this->items['guild_press_user_ip']    = $_SERVER['REMOTE_ADDR'];

    }

    public function set_register_item_info_to_session()
    {
        $_SESSION['last_name'] = ( ! isset( $this->items['last_name'] ) || ! $this->items['last_name'] ) ? '' : $this->items['last_name'];

        $_SESSION['first_name'] = ( ! isset( $this->items['first_name'] ) && ! $this->items['first_name'] ) ? '' : $this->items['first_name'];

        $_SESSION['user_name'] = ( ! isset( $this->items['user_name'] ) || ! $this->items['user_name'] ) ? '' : sanitize_user( $this->items['user_name'] );

        $_SESSION['user_email'] = ( ! isset( $this->items['user_email'] ) && ! $this->items['user_email'] ) ? '' : $this->items['user_email'];

        $_SESSION['password'] = ( ! isset( $this->items['password'] ) && ! $this->items['password'] ) ? '' : $this->items['password'];

        $_SESSION['error_msg'] = array();
    }

    public function set_error_msg_list()
    {
        $this->error_msg_list = [
            'last_name' => '姓は必須入力項目です。',
            'first_name' => '名は必須入力項目です。',
            'user_email_exists' => '指定されたemailアドレスは既に存在しています。',
            'user_email_format_error' => 'メールアドレスの形式が正しくありません',
            'user_email' => 'メールアドレスは必須入力項目です。',
            'user_name_exists' => '指定されたユーザー名は既に存在しています。',
            'user_name_validate' => 'ユーザーネームにはローマ字を入力してください。',
            'user_name' => 'ユーザーネームは必須入力項目です。',
            'password' => 'パスワードは必須入力項目です。'
        ];
    }

    public function set_original_form_item( $original_form_item_key, $original_form_item_value )
    {
        $this->items[ $original_form_item_key ] = ( isset( $_POST[ $original_form_item_value ] ) ) ? $_POST[ $original_form_item_value ] : '';
    }

    public function set_original_form_item_to_session( $original_form_item_key, $original_form_item_value )
    {
        $_SESSION[ $original_form_item_key ] = ( isset( $this->items[ $original_form_item_value ] ) ) ? $this->wpfunc->esc_html( $this->items[ $original_form_item_value ] ) : '';
    }

    public function set_guild_press_new_user_data()
    {
        $this->items['user_nicename'] = ( isset($this->items['user_nicename']) && $this->items['user_nicename'] !== "" ) ? $this->items['user_nicename'] : '';

        $this->items['display_name'] = ( isset($this->items['display_name']) && $this->items['display_name'] !== "" ) ? $this->items['display_name'] : '';

        $this->items['nickname'] = ( isset($this->items['nickname']) && $this->items['nickname'] !== "" ) ? $this->items['nickname'] : '';

        $this->items['salon_user_ip'] = ( isset($this->items['salon_user_ip']) && $this->items['salon_user_ip'] !== "" ) ? $this->items['salon_user_ip'] : '';

        $this->guild_press_new_user_data = array(
            'user_pass'       => $this->items['password'],
            'user_login'      => $this->items['user_name'],
            'user_nicename'   => $this->items['user_nicename'],
            'user_email'      => $this->items['user_email'],
            'display_name'    => $this->items['display_name'],
            'nickname'        => $this->items['nickname'],
            'user_registered' => $this->items['user_registered'],
            'role'            => $this->items['user_role'],
            'user_payment_id' => ''
        );
    }

    public function email_error_check( $item_key, $check_val )
    {
        $result = $this->basic->check_email_format( $check_val );

        if( $result === 0 ){

            $erromsg = "指定されたemailアドレスは既に存在しています。";
            $_SESSION['error_msg'][$item_key] = $this->error_msg_list['user_email_format_error'];
        }elseif( email_exists( $check_val ) ){

            $erromsg = "指定されたemailアドレスは既に存在しています。";
            $_SESSION['error_msg'][$item_key] = $this->error_msg_list['user_email_exists'];
        }else{

            $this->normal_error_check( $item_key, $check_val );
        }
    }

    public function user_name_error_check( $item_key, $check_val )
    {

        if( username_exists( $check_val ) ){

            $erromsg = "指定されたユーザー名は既に存在しています。";
            $_SESSION['error_msg'][$item_key] = $this->error_msg_list['user_name_exists'];
        }elseif( $check_val !== '' && ! validate_username( $check_val ) ){

            $erromsg = "ユーザーネームには英数字を入力してください。";
            $_SESSION['error_msg'][$item_key] = $this->error_msg_list['user_name_validate'];
        }else{

            $this->normal_error_check( $item_key, $check_val );
        }
    }

    public function normal_error_check( $item_key, $check_val )
    {
        if( ! $this->items[$item_key] || $check_val === '' ){

            $_SESSION['error_msg'][$item_key] = $this->error_msg_list[$item_key];
        }else{

            $_SESSION['error_msg'][$item_key] = '';
        }
    }

    public function check_items_error()
    {
        $this->set_error_msg_list();
        foreach ($this->items as $item_key => $item_value) {

            $check_val = preg_replace("/( |　)/", "", $item_value );
            switch ( $item_key ) {
                case 'user_email':
                    //エラーチェック
                    $this->email_error_check( $item_key, $check_val );
                break;

                case 'user_name':
                    //エラーチェック
                    $this->user_name_error_check( $item_key, $check_val );
                break;

                default:
                    $this->normal_error_check( $item_key, $check_val );
                break;
            }

        }

    }
    public function error_check_original_form_item( $original_form_item_key, $original_form_item_name )
    {
        if ( ! isset( $this->items[$original_form_item_key] ) || $this->items[$original_form_item_key] == '' ) {

            $_SESSION['error_msg'][$original_form_item_key] = sprintf( $this->wpfunc->esc_html($original_form_item_name)."は必須入力項目です。" );
        }else{

            $_SESSION['error_msg'][$original_form_item_key] = '';
        }
    }

    public function check_original_form_item( $original_form_item )
    {
        for ($i=4; $i < count($original_form_item); $i++) {

            if ( $original_form_item[$i][4] == 'y' ) {

                $this->set_original_form_item( $original_form_item[$i][2],  $original_form_item[$i][2] );

                $this->set_original_form_item_to_session( $original_form_item[$i][2],  $original_form_item[$i][2] );
            }

            if ( $original_form_item[$i][5] == 'y' ) {

                $this->error_check_original_form_item( $original_form_item[$i][2], $original_form_item[$i][1] );
            }
        }
    }

    public function update_original_form_items( $original_form_item )
    {
        //カスタムのユーザー項目も保存
        foreach ( $original_form_item as $original_form_meta ) {
            if ( $original_form_meta[4] == 'y' ) {
                if ( $original_form_meta[2] != 'password' && $original_form_meta[2] != 'user_email' ) {
                    $this->wpfunc->update_user_meta( $this->items['user_id'], $original_form_meta[2], $this->items[$original_form_meta[2]] );
                }
            }
        }
    }


}