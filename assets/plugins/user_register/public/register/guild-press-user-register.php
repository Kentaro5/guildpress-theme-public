<?php
//session_start();
/**
 *
 */
class User_Register_Func extends Basic_User_Register
{

    public function __construct()
    {
        parent::__construct();
    }

    public function register_new_user()
    {

        $original_form_item = $this->wpfunc->get_option(SLUGNAME.'_regsiter_item_field', false );

        $this->check_post_items( $original_form_item );

        $this->insert_user_info( $original_form_item );

        //SESIONを破棄
        session_destroy();

        $this->redirect_user_to_page();

    }

    public function check_post_items( $original_form_item )
    {
        $this->set_register_info();

        //セッションに値を保存
        $this->set_register_item_info_to_session();

        $erromsg="";

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

        $this->wpfunc->update_user_meta( $this->items['user_id'], 'status', 0 );

        //成功したらメールを送る。
        $this->email->send_mail($this->items['user_id'], 'user_register');
    }

    public function redirect_user_to_page()
    {

        $this->set_login_info();

        $this->set_ssl();

        //成功すればユーザー情報取得
        $user = $this->wpfunc->wp_signon( $this->login_info, $this->is_ssl );

        if ( ! $this->wpfunc->is_wp_error( $user ) ) {
            $redirect_url = $this->wpfunc->home_url();
            wp_redirect($redirect_url);
            exit();
        }else{
            $user_error = ( isset($user->errors) && $user->errors !== "" ) ? $user->errors : '';
            foreach ($user_error as $key => $value) {

                $_SESSION['error_msg']['user_error'][] = $value[0];
            }

            $link = $guild_press_register_page_link;
            wp_redirect($link);
            exit();
        }
    }


}