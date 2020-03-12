<?php

/**
*
*/
class ErrorMessage
{

    /*
        エラーのコード
        100系:登録フィールド系のエラー
    */
    public function set_error_msg( $error_number = '' ){
            $error = new WP_Error();

            if( isset( $error_number ) && $error_number === "100" ){
                $error->add( '100', 'メッセージが入力されていません。' );
                if( $error->get_error_codes() ){
                    set_transient( 'guild_press_error', $error->get_error_messages(), 5 );
                    return $error;
                }
            }
    }
}
