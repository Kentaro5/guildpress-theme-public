<?php

/**
 *
 */
class Guild_Press_Public_Edit_User_Info
{
    public $edit_user_info_page_path;
    private $user_avatar_flg;
    private $user_img_edit_url;

    public function __construct()
    {
        $this->basic = new Basic();
        $this->wpfunc = new WpFunc();
        $this->user_avatar_flg = false;
        $this->user_img_edit_url = '';
        $this->load();
    }

    public function load()
    {
        $this->edit_user_info_page_path = 'templates/public/mypage/edit/edit-user-info-parts.php';
    }

    public function edit_user_info( $args )
    {
        ob_start();
        $this->edit_user_info_page( $args );
        $edit_user_info_form = ob_get_contents();
        ob_end_clean();
        return $edit_user_info_form;
    }

    public function edit_user_info_page( $args )
    {
        global $wp;
        //現在のユーザーデータ取得
        $user_info = $this->wpfunc->wp_get_current_user();

        //ユーザーの現在の会員ランクを取得
        $user_rank_num = $this->wpfunc->get_user_meta( $user_info->ID, 'gp_member_rank', true );
        $user_rank = $this->get_member_rank(intval( $user_rank_num ) );

        if( empty( $user_rank ) ){
            $user_rank = '';
        }

        $error_msg = ( isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !== "" ) ? $_SESSION['error_msg'] : '';

        $wp_nonce = $this->wpfunc->wp_nonce_field( SLUGNAME.'_edit_user_info', 'edit_user_info', false );
        $page_id = get_the_ID();


        if( $this->is_user_avator_activate() && $args['url'] !== '' ){
            $this->user_avatar_flg = true;
            $this->user_img_edit_url = $args['url'];
        }


        $gp_data = array(
            'error_msg' => $error_msg,
            'first_name' => $user_info->first_name,
            'last_name' => $user_info->last_name,
            'user_email' => $user_info->user_email,
            'user_rank' => $user_rank,
            'wp_request' => $wp->request,
            'page_id' => $page_id,
            'wp_nonce' => $wp_nonce,
            'user_info' => $user_info,
            'user_avatar_flg' => $this->user_avatar_flg,
            'user_img_edit_url' => $this->user_img_edit_url,
        );

        if( ! $file_path = $this->basic->load_template( $this->edit_user_info_page_path, false ) ){

            return;
        }

        include( $file_path );

    }

    public function is_user_avator_activate(){

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if ( is_plugin_active( 'wp-user-avatar/wp-user-avatar.php' ) ) {
            return true;
        }

        return false;
    }

    public function rendar_edit_custom_form( $user_info )
    {
        $check_arr = array(
            'user_name',
            'user_email',
            'password',
            'first_name',
            'last_name'
        );

        $options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field' );

        $error_msg = ( isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !== "" ) ? $_SESSION['error_msg'] : '';

        $html = '';
        if( isset( $options ) && $options ){
            //必須項目は既にひょうじして
            for ( $i=0; $i < count($options); $i++ ){

                if( ! $this->basic->in_array( $options[$i][2], $check_arr ) ) {


                    $html .= '<div class="form-parts">';

                    if( $options[$i][4] == 'y' ){

                        if( isset($error_msg[$options[$i][2]]) && $error_msg[$options[$i][2]] !== ""  ){
                            $html .= '<p style="color:red;">';
                            $html .= $this->wpfunc->esc_html( $error_msg[$options[$i][2]] );
                            $html .= '</p>';
                        }


                        $html .= '<p class="form-text">';

                        if ( $options[$i][5] == 'y' ){
                            $html .= '<span color="red">*</span>';
                        }

                        $html .= $options[$i][1];

                        if ( $options[$i][2] == 'user_email' ){
                            $html .= '(半角英数字で入力して下さい。)';
                        }
                        $html .= '</p>';

                        //値を取得する。
                        $val = $this->wpfunc->get_user_meta( $user_info->ID, $options[$i][2], true );

                        if( $options[$i][3] == 'checkbox' ){

                            $valtochk = $val;
                            $val = $options[$i][7];

                            if ( $options[$i][8] == 'y' && ! $_POST ) { $val = $valtochk = $options[$i][7]; }

                            $html .=  $this->basic->guild_press_create_form( $options[$i][2], $options[$i][3], $val, $valtochk );

                        }elseif( $options[$i][3] == 'select' ){

                            $valtochk = $val;
                            $val = $options[$i][7];
                            $html .=  $this->basic->guild_press_create_form( $options[$i][2], $options[$i][3], $val, $valtochk );

                        }else{

                            $html .= $this->basic->guild_press_create_form( $options[$i][2], $options[$i][3], $val, '' );

                        }
                    }
                    $html .= '</div>';
                }

            }
        }

        return $html;
    }

    public function get_member_rank( $user_member_rank_num )
    {

        $rank_query = new WP_Query(
            array(
                'post_type' => 'guild_press_rank',
            )
        );

        $member_rank = '';
        if ( $rank_query->have_posts() ){
            while( $rank_query->have_posts() ){
                $rank_query->the_post();
                $member_id = get_the_ID();
                $member_id_arr[] = get_the_ID();

                if( $member_id === $user_member_rank_num ){

                    //IDを元に、会員ランク取得
                    $member_rank = $this->wpfunc->get_post_meta( $member_id, 'member_rank_name', true );
                    break;
                }

            }
            $this->wpfunc->wp_reset_postdata();
        }

        return $member_rank;
    }
}