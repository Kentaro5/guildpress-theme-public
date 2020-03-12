<?php
class Guild_Press_Public_Update_User_Status
{
    public function __construct(){
        $this->basic = new Basic;
        $this->email = new Guild_Press_Email;
    }

    //ユーザーの決済ステータスを更新
    public function update_user_payment_status()
    {
        //送られてきた値のチェック
        $post_items = $this->basic->checkPostItem( $_GET );

        //ユーザーのIDInt化
        $custom_items = explode(':', $post_items['cm']);
        $current_user_id = intval( $custom_items[0] );

        $user_payment_status = get_user_meta( $current_user_id , 'status', true );

        //ステータス更新は一度のみ行う。
        if( $user_payment_status !== '' && intval( $user_payment_status ) !== 0 ){

            //決済を行ったユーザーのステータスを更新。
            update_user_meta( $current_user_id, 'status', 0 );

            //ユーザーと管理者に決済完了のお知らせメールを飛ばす。
            $this->email->send_mail( $current_user_id, 'user_payment_complete' );
        }

        $args = array(
            'custom_items' => $custom_items,
            'user_payment_status' => intval( $user_payment_status ),
            'current_user_id' => $current_user_id,
            'post_items' => $post_items
        );

        do_action( SLUGNAME.'_after_update_user_payment_status', $args );

    }

    //ユーザーのペイメントIDステータスを更新
    public function add_user_paypal_payment_id()
    {
        //送られてきた値のチェック
        $post_items = $this->basic->checkPostItem( $_POST );

        //ユーザーのIDInt化
        $custom_items = explode(':', $post_items['custom']);
        $current_user_id = intval( $custom_items[0] );

        $user_payment_status = get_user_meta( $current_user_id , 'p_s_id', true );

        $user_payment_status = ( ! empty( $user_payment_status ) ) ? $user_payment_status : '';

        //ステータス更新は一度のみ行う。
        if( $user_payment_status === '' ){

            //ユーザーのPayPal定期決済のIDを保存
            update_user_meta( $current_user_id, 'p_s_id', $post_items['subscr_id'] );
        }

        $args = array(
            'custom_items' => $custom_items,
            'user_payment_status' => intval( $user_payment_status ),
            'current_user_id' => $current_user_id,
            'post_items' => $post_items
        );

        do_action( SLUGNAME.'_after_add_user_paypal_payment_id', $args );
    }
}


