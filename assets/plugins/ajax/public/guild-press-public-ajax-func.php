<?php

/**
 * Ajax関係の処理を記載。
 */
if ( ! function_exists( 'guild_press_update_user_lesson_progress_data' ) ) {
    function guild_press_update_user_lesson_progress_data( $post_item, $user_info )
    {

        $wpfunc = new WpFunc();
        $user_progress_db = new Guild_Press_User_Progress_Model();
        $user_progress_db_backup = new Guild_Press_User_Progress_Bk_Model();

        $user_id = intval( $post_item['user_id'] );

        $user_id_check = guild_press_check_user_id( $user_id );

        if( $user_id_check === false ){

            die('user_idが書き換えられています。');
        }

        $post_id = $wpfunc->esc_html( $post_item['post_id'] );
        $post_id = preg_replace("/( |　)/", "", $post_id );

        //Wordpressで最初の値はシリアライズされないので、チェックする。
        $unserialize_result = @unserialize($user_info[$post_item['slug']][0]);

        $check_flg = guild_press_check_serialize( $post_id, $unserialize_result, $user_info, $post_item );

        //同じ値がない場合はデータを追加して更新。
        if( $check_flg === true ){

            if( $unserialize_result === false ){

                $new_user_info = $user_info[$post_item['slug']];
            }else{

                $new_user_info = $unserialize_result;
            }

            $add_slug_num = count($new_user_info);

            //その上で、新しく保存するものを配列に加える。
            $new_user_info[$add_slug_num] = $post_id;

            $insert_args = array(
                'user_id' => $user_id,
                'taxonomy_name' => $post_item['slug'],
                'count_num' => count($new_user_info),
                'serialize_arr' => $new_user_info
            );

            $bkup_result = $user_progress_db_backup->save( $insert_args );
            $db_result = $user_progress_db->save( $insert_args );

            //ユーザー情報を更新。
            $wpfunc->update_user_meta( $user_id, $post_item['slug'], $new_user_info );

            die("success");

        }else{

            //既にデータがある場合は処理を何もせず画面遷移させる。
            die("success");
        }
    }
}

if ( ! function_exists( 'guild_press_check_user_id' ) ) {
    function guild_press_check_user_id( $user_id )
    {
        $wpfunc = new WpFunc();

        $current_user_id = $wpfunc->get_current_user_id();

        return ( $current_user_id === $user_id ) ? true : false;
    }
}


if ( ! function_exists( 'guild_press_check_serialize' ) ) {
    function guild_press_check_serialize( $post_id, $unserialize_result, $user_info, $post_item )
    {
        $check_flg = true;
        //unserialize_resultでfalseの場合は、シリアライズが失敗しているので、単体の値しか入っていない。
        if( $unserialize_result === false ){

            //同じ値がないかどうかチェックする。比較する時は、user_infoで比較。
            if( in_array( $post_id, $user_info[$post_item['slug']], true ) ){

                $check_flg = false;
            }
        }else{

            //同じ値がないかどうかチェックする。
            //slugが複数入っている場合は、シリアライズされるので、unserializeで比較。
            if( in_array( $post_id, $unserialize_result, true ) ){
                $check_flg = false;
            }
        }

        return $check_flg;
    }
}


if ( ! function_exists( 'guild_press_store_new_user_lesson_progress_data' ) ) {
    //ユーザーが初めてデータを登録するときは各データをinsertする処理
    function guild_press_store_new_user_lesson_progress_data( $post_item, $user_id )
    {

        $wpfunc = new WpFunc();
        $user_progress_db = new Guild_Press_User_Progress_Model();
        $user_progress_db_backup = new Guild_Press_User_Progress_Bk_Model();

        $post_id = $wpfunc->esc_html( $post_item['post_id'] );
        $post_id = preg_replace("/( |　)/", "", $post_id );

        $insert_args = array(
            'user_id' => $user_id,
            'taxonomy_name' => $post_item['slug'],
            'count_num' => 1,
            'serialize_arr' => $post_id
        );

        $result = $user_progress_db->save( $insert_args );

        $bkup_result = $user_progress_db_backup->save( $insert_args );

        //ない場合は新しく値を追加。
        $wpfunc->add_user_meta( $user_id, $post_item['slug'], $post_id);

        die("success");
    }
}

if ( ! function_exists( 'guild_press_return_check_quiz_answer' ) ) {
    function guild_press_return_check_quiz_answer( $post_item )
    {
        $basic = new Basic();
        $wpfunc = new WpFunc();

        $check_arr = array(
            'post_id' => '',
            'gp_user_answer_text' => '',
            'slug' => '',
            'next_link' => ''
        );

        //ユーザーからポストされた値をフィルタリング
        $checked_post_item = $basic->check_save_item( $post_item, $check_arr );

        $post_id = intval( $checked_post_item['post_id'] );

        $guild_press_quiz_correct_answer = $wpfunc->get_post_meta( $post_id, 'guild_press_quiz_correct_answer' );
        $guild_press_quiz_answer = $basic->check_array( $guild_press_quiz_correct_answer[0] );

        //ユーザー側から送られてきたデータのスペースと改行を削除
        $user_answer = $basic->delete_space( $checked_post_item['gp_user_answer_text'] );
        $user_answer = $basic->delete_new_line( $user_answer );

        //スペースと改行を削除
        $answer = $basic->delete_space( $guild_press_quiz_answer[0] );
        $answer = $basic->delete_new_line( $answer );

        //ユーザーの答えと問題の答えを比較する。
        if( $user_answer === $answer ){

            return true;
        }else{

            return false;
        }

    }
}

if ( ! function_exists( 'guild_press_save_lesson_data' ) ) {
    function guild_press_save_lesson_data( $result, $post_item, $user_data, $flg )
    {

        if( $result !== true ){

            die("failed");
        }

        if( $flg === 'add' ){

            guild_press_update_user_lesson_progress_data( $post_item, $user_data );
        }else if( $flg === 'new' ){

            guild_press_store_new_user_lesson_progress_data( $post_item, $user_data );
        }
    }
}


if ( ! function_exists( 'ajax_guild_press_delete_schedule' ) ) {

    function ajax_guild_press_delete_schedule()
    {

        $basic = New Basic();
        $this_month = ( isset($_POST['gp_month']) && $_POST['gp_month'] !== "" ) ? $_POST['gp_month'] : '';
        $this_year = ( isset($_POST['gp_year']) && $_POST['gp_year'] !== "" ) ? $_POST['gp_year'] : '';

        //月日をセット
        $themonth = mktime(0, 0, 0, $this_month, 1, $this_year);

        //カレンダーのスラッグセット
        $calender_slug_name = 'guild_press_register_schedule_'.$themonth;

        $delete_month_data = get_option($calender_slug_name);
        $delete_options = $delete_month_data[$_POST['date_id']]['register_task'];

        //メールを送る準備。
        $email = new Guild_Press_Email();

        //ここで送られてきたIDとoptionのIDが一致しているかどうかをチェックする。
        foreach ($delete_month_data[$_POST['date_id']]['register_task'] as $key => $value) {

            if( $value === $_POST['delete_option_id']){

                //指定されたデータを削除
                delete_option( $delete_options[$key] );
                $delete_user_id_arr = $delete_month_data[$_POST['date_id']]['user_id'][$delete_options[$key]];

                $delete_user_id_arr = $basic->null_check_arr( $delete_user_id_arr, true );

                if( count( $delete_user_id_arr ) > 0 ){

                    for ($p=0; $p < count($delete_month_data[$_POST['date_id']]['user_id'][$delete_options[$key]]); $p++) {

                        //配列の中からIDを取得して、紐付いているユーザー全てを削除する。
                        delete_option( $delete_options[$key].'_'.$delete_user_id_arr[$p] );

                        $email->send_mail_to_user( intval($delete_user_id_arr[$p]), "admin_delete_schedule" );
                    }
                    unset($delete_month_data[$_POST['date_id']]['user_id'][$_POST['delete_option_id']]);
                }

                //削除したあと、optionの配列から取り除く。　
                unset($delete_month_data[$_POST['date_id']]['register_task'][$key]);
            }
        }

        $email->send_mail_to_admin( "admin_delete_schedule" );

        //ユーザーID取得
        $user_id = $delete_month_data[$_POST['date_id']]['user_id'];

        $user_id = $basic->null_check_arr( $user_id, true );

        $register_task = $basic->null_check_arr( $delete_month_data[$_POST['date_id']]['register_task'], true );

        //バラバラになった配列を元に戻す。　
        $delete_month_data[$_POST['date_id']]['register_task'] = array_merge($register_task);

        update_option( $calender_slug_name, $delete_month_data );

        //削除される予定に登録されているユーザーを配列で入れる。
        $delete_user_id = $delete_month_data[$_POST['date_id']]['user_id'][$_POST['delete_option_id']];
        $delete_user_id = $basic->null_check_arr( $delete_user_id, true );

        $google_event_id = ( isset($_POST['google_event_id']) && $_POST['google_event_id'] !== "" ) ? $_POST['google_event_id'] : '';

        do_action( 'admin_'.SLUGNAME.'_after_delete_schedule', $google_event_id );

        die("success");

    }

}

if ( ! function_exists( 'ajax_guild_press_delete_user_schedule' ) ) {
    function ajax_guild_press_delete_user_schedule(){
        $basic = New Basic();
        $current_user_id = strval(get_current_user_id());

        //月日をセット
        $themonth = ( isset($_POST['the_month']) && $_POST['the_month'] !== "" ) ? $_POST['the_month'] : '';

        $google_event_id = ( isset($_POST['google_event_id']) && $_POST['google_event_id'] !== "" ) ? $_POST['google_event_id'] : '';

        //カレンダーのスラッグセット
        $calender_slug_name = 'guild_press_register_schedule_'.$themonth;

        $delete_month_data = get_option($calender_slug_name);
        $delete_options = $delete_month_data[$_POST['date_id']]['register_task'];

        //ここで送られてきたIDとoptionのIDが一致しているかどうかをチェックする。
        foreach ($delete_month_data[$_POST['date_id']]['user_id'] as $schedule_id => $user_data ) {

            if( $schedule_id === $_POST['delete_option_id'] ){

                foreach ( $user_data as $num_key => $user_id) {

                    if( $user_id === $current_user_id ){

                        $delete_id = $schedule_id.'_'.$user_id;

                        //指定されたデータを削除
                        delete_option( $delete_id );
                        //削除したあと、optionの配列から取り除く。　
                        unset($delete_month_data[$_POST['date_id']]['user_id'][$schedule_id][$num_key]);

                    }
                }

            }

        }

        //バラバラになった配列を元に戻す。　
        $delete_month_data[$_POST['date_id']]['register_task'] = array_merge($delete_month_data[$_POST['date_id']]['register_task']);

        //メールで送るため、数字に戻す。
        $current_user_id = intval($current_user_id);
        //メールを送る準備。
        $email = new Guild_Press_Email();
        $email->send_mail( $current_user_id,"delete_user_shedule" );

        $result = update_option( $calender_slug_name, $delete_month_data );

        $google_event_id = ( isset($_POST['google_event_id']) && $_POST['google_event_id'] !== "" ) ? $_POST['google_event_id'] : '';

        $args = array(
            'date_id' => $_POST['date_id'],
            'user_key' => 'user_id',
            'schedule_id' => $_POST['delete_option_id'],
            'google_event_id' => $google_event_id,
            'the_month' => $themonth
        );

        do_action( 'public_'.SLUGNAME.'_after_delete_schedule', $args );

        if( $result === true ){
            die("success");
        }else{
            die("fail");
        }
    }
}



