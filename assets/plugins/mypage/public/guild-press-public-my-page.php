<?php
//session_start();
require_once( TEMP_DIR . '/assets/plugins/mypage/public/user_page/guild-press-public-user-page.php' );
require_once( TEMP_DIR . '/assets/plugins/mypage/public/edit_user_info/guild-press-public-edit-user-info.php' );
require_once( TEMP_DIR . '/assets/plugins/mypage/public/lesson/guild-press-public-user-progress-details.php' );
require_once( TEMP_DIR . '/assets/plugins/mypage/public/lesson/guild-press-public-user-taken-list.php' );

/**
*
*/
class Guild_Press_Public_My_Page
{

    public function __construct()
    {

        $this->basic = new Basic();
        $this->wpfunc = new WpFunc();
        $this->user_page = new Guild_Press_Public_User_Page();
        $this->edit_user_info = new Guild_Press_Public_Edit_User_Info();
        $this->user_progress_details = new Guild_Press_Public_User_Progress_Details();
        $this->user_taken_list = new Guild_Press_Public_User_Taken_List();
        $this->load();
    }

    public function load()
    {

        add_shortcode( 'guild_press_my_page', array( $this->user_page, 'user_my_page' ) );
        add_shortcode( 'guild_press_edit_user_info', array( $this->edit_user_info, 'edit_user_info' ) );

        add_action( 'init', array( $this, 'get_action' ) );


        add_filter( 'guildpress_rendar_edit_custom_form',   array( $this->edit_user_info, 'rendar_edit_custom_form' )  );
        add_filter( 'guildpress_taken_lesson_list',   array( $this->user_taken_list, 'get_user_taken_lesson_list' )  );
        add_filter( 'guildpress_lesson_progress_details',   array( $this->user_progress_details, 'get_user_lesson_progress_details' )  );

    }

    //Formからアクションを受け取る
    public function get_action(){

        if( !isset( $_POST["guild_press_user_info_action"] ) || !$_POST["guild_press_user_info_action"]  ){
            return;
        }

        $this->action = ( isset( $_POST["guild_press_user_info_action"] ) ) ? trim( $_POST["guild_press_user_info_action"] ) : '';
        $this->action_check( $this->action );
    }

    //受け取ったアクションによって、処理を分岐させる。
    public function action_check( $action='' ){

        if( !$action || !isset( $action ) || !is_string( $action ) ){
            return;
        }

        switch ($action) {
            case ("user_data_update"):
            $this->update_user_info();
            break;
        }

    }

    public function update_user_info()
    {
        if( ! wp_verify_nonce( $_POST['edit_user_info'], SLUGNAME.'_edit_user_info' ) && !isset( $_POST["edit_user_info"] ) ){
            return;
        }


        $items['last_name'] = ( ! isset( $_POST['last_name'] ) && ! $_POST['last_name'] ) ? '' : $_POST['last_name'];

        $items['first_name'] = ( ! isset( $_POST['first_name'] ) && ! $_POST['first_name'] ) ? '' : $_POST['first_name'];
        $items['user_email'] = ( ! isset( $_POST['user_email'] ) && ! $_POST['user_email'] ) ? '' : $_POST['user_email'];

        $items['check_back_redirect_url'] = ( ! isset( $_POST['guild_press_check_back_url'] ) && ! $_POST['guild_press_check_back_url'] ) ? '' : $_POST['guild_press_check_back_url'];

        //各値をセッションにセットする。
        $this->session_set( $items );

        //エラーのチェック。
        $error_msg = $this->session_error_check();

        //ユーザー定義のオリジナルの登録フォーム項目を取得する。
        $item_field = get_option(SLUGNAME.'_regsiter_item_field');

        //ユーザー定義のオリジナルのアイテムのエラーをチェックする。
        $items['custom_user_items'] = $this->custom_item_session_error_check( $item_field );

        $error_flg=false;

        //エラーがないかチェックする。
        foreach ($_SESSION['error_msg'] as $key => $value) {

            if( isset($_SESSION['error_msg'][$key]) && $_SESSION['error_msg'][$key] !== "" ){

                $_SESSION['error_msg'][$key] = $value;
                $error_flg=true;
            }

        }

        //エラーがあればGETで知らせる。
        if ( $error_flg ) {

            $this->redirect_user_info_page_with_error( $items['check_back_redirect_url'] );
        }

        $user = wp_get_current_user();

        //それぞれのデータを保存
        foreach ($items as $items_key => $items_value) {

            if( $items_key === 'custom_user_items' ){

                foreach ($items_value as $custom_item_key => $custom_item_value) {

                    update_user_meta( $user->ID, $custom_item_key, $custom_item_value );
                }
            }elseif( $items_key !== 'check_back_redirect_url' ){

                //update_user_meta( $user->ID, $items_key, $items_value );
                wp_update_user( array( 'ID' => $user->ID, $items_key => $items_value ) );
            }

        }

        session_destroy();

        $page_options = $this->wpfunc->get_option(SLUGNAME.'_basic_setting');
        $user_my_page_id = $page_options['guild_press_mypage'];
        $my_page_link = $this->wpfunc->get_page_link($user_my_page_id);

        $link = $my_page_link.'?suc_msg=1';
        wp_redirect($link);
        exit();
    }

    public function redirect_user_info_page_with_error( $check_back_redirect_url_id )
    {
        //エラーの場合はグローバルポストIDからリターン用のリンクを取得して、返す。
        $guild_press_register_page_link = get_page_link($check_back_redirect_url_id);
        $link = $guild_press_register_page_link.'?mypage_edit=edit_user_info';

        wp_redirect($link);
        exit();
    }

    public function custom_item_session_error_check( $item_field )
    {
        $check_arr = array(
            'user_name',
            'user_email',
            'password',
            'first_name',
            'last_name'
        );

        //フィールド入力チェック。
        if( isset( $item_field ) && $item_field ){

            for ( $i=0; $i < count($item_field); $i++ ){

                if( ! $this->basic->in_array( $item_field[$i][2], $check_arr ) ) {
                    if ( $item_field[$i][4] == 'y' ) {

                        $items[ $item_field[$i][2] ] = ( isset( $_POST[ $item_field[$i][2] ] ) ) ? $_POST[ $item_field[$i][2] ] : '';

                        $_SESSION[ $item_field[$i][2] ] = ( isset( $items[ $item_field[$i][2] ] ) ) ? esc_html( $items[ $item_field[$i][2] ] ) : '';
                    }
                }
            }

            for ( $k=0; $k < count($item_field); $k++ ){

                if( ! $this->basic->in_array( $item_field[$k][2], $check_arr ) ) {

                    if ( $item_field[$k][5] == 'y' ) {
                        if ( ! isset( $items[$item_field[$k][2]] ) || $items[$item_field[$k][2]] === '' ) {

                            $_SESSION['error_msg'][$item_field[$k][2]] = sprintf( esc_html($item_field[$k][1])."は必須入力項目です。" );
                        }else{

                            $_SESSION['error_msg'][$item_field[$k][2]] = '';
                        }
                    }
                }
            }
        }

        return $items;
    }

    public function session_set( $items )
    {
        $_SESSION['error_msg'] = array();

        $_SESSION['last_name'] = ( ! isset( $items['last_name'] ) || ! $items['last_name'] ) ? '' : $items['last_name'];

        $_SESSION['first_name'] = ( ! isset( $items['first_name'] ) && ! $items['first_name'] ) ? '' : $items['first_name'];

        $_SESSION['user_email'] = ( ! isset( $items['user_email'] ) && ! $items['user_email'] ) ? '' : $items['user_email'];
    }

    public function session_error_check()
    {
        $error_msg="";
        //エラーチェック
        if( ! $_SESSION['last_name'] ){

            $error_msg = "姓は必須入力項目です。";
            $_SESSION['error_msg']['last_name'] = "姓は必須入力項目です。";
        }else{

            $_SESSION['error_msg']['last_name'] = '';
        }

        //エラーチェック
        if( ! $_SESSION['first_name'] ){

            $error_msg = "名は必須入力項目です。";
            $_SESSION['error_msg']['first_name'] = "名は必須入力項目です。";
        }else{

            $_SESSION['error_msg']['first_name'] = '';
        }

        //エラーチェック
        if( ! is_email( $_SESSION['user_email'] ) ){

            $error_msg = "不正なemailアドレスです。";
            $_SESSION['error_msg']['user_email'] = "不正なemailアドレスです。";
        }elseif( ! $_SESSION['user_email'] ){

            $error_msg = "メールアドレスは必須入力項目です。";
            $_SESSION['error_msg']['user_email'] = "メールアドレスは必須入力項目です。";
        }else{

            $_SESSION['error_msg']['user_email'] = '';
        }

        return $error_msg;
    }

}





