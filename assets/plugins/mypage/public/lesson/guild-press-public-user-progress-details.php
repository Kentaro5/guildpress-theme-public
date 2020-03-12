<?php
require_once( TEMP_DIR . '/assets/plugins/mypage/public/lesson/guild-press-public-user-taken-list.php' );

class Guild_Press_Public_User_Progress_Details
{
    public function __construct()
    {

        $this->basic = new Basic();
        $this->wpfunc = new WpFunc();
        $this->user_taken_list = new Guild_Press_Public_User_Taken_List();
    }
    //ユーザーが現在進めているレッスンの進捗度合いを返す関数。
    public function get_user_lesson_progress_details()
    {

        $taken_lesson_list = $this->user_taken_list->get_user_taken_lesson_list();
        $lesson_progress_items = array();
        $i=0;
        foreach ($taken_lesson_list as $lesson_category => $taken_lesson_ids ) {

            //レッスンの総数を取得
            $lseeon_posts = $this->basic->getGuildDeitalQuery($lesson_category);

            $progress_abr_arg = $this->basic->getProgressBarArg( $lesson_category, $taken_lesson_ids );

            //各タクソノミーの総レッスン数をカウント
            $lesson_posts_num = count($lseeon_posts->posts);

            //Stringでは、カウントできないためarrayにする。
            $taken_lesson_ids = $this->basic->return_arr( $taken_lesson_ids );
            $taken_lesson_ids = $this->basic->check_post_exists( $taken_lesson_ids );

            //ユーザーが受けたレッスンの数をカウント
            $user_data_lesson_num = count($taken_lesson_ids);
            wp_reset_postdata();

            $str_lesson_posts_str = strval($lesson_posts_num);
            $user_data_lesson_str = strval($user_data_lesson_num);

            //万が一何らかの理由で０の場合は、１を入れておく。
            if( $lesson_posts_num === 0 ) {
                $lesson_posts_num = 1;
            }

            //0で割るとエラーが出るので条件分岐
            if( $user_data_lesson_num === 0 ){
            /*
                ここuser_taken_lesson_numじゃなくて、$user_data_lesson_numじゃね？？
            */
                //ユーザーが全てのレッスンを受けている場合は100を入れる。
                $progress_bar_num = 100;
            }else{
                //一度総数で割る。
                $divieded_lesson_num = 100/$lesson_posts_num;
            //そのあと、ユーザーのカウントを掛け算
                $progress_bar_num = floor($divieded_lesson_num * $user_data_lesson_num);
            }

            $str_lesson_posts_str = $progress_abr_arg['str_lesson_posts_str'];
            $user_data_lesson_str = $progress_abr_arg['user_data_lesson_str'];
            $progress_bar_num = $progress_abr_arg['progress_bar_num'];

            //レッスンの紹介ページデータを取得
            $lesson_sum = $this->basic->getGuildLessonQuery($lesson_category);

            if( $user_data_lesson_str !== '0' ){
                while ( $lesson_sum->have_posts() ) {

                    $lesson_sum->the_post();
                    $thumbnail = get_the_post_thumbnail();
                    $posts_id = get_the_ID();
                    $post_link = $this->wpfunc->get_permalink( $posts_id );

                    $lesson_progress_items[$i] = array(
                        'str_lesson_posts_str' => $str_lesson_posts_str,
                        'user_data_lesson_str' => $user_data_lesson_str,
                        'progress_bar_num' => $progress_bar_num,
                        'thumbnail' => $thumbnail,
                        'lesson_category' => $lesson_category,
                        'posts_id' => $posts_id,
                        'post_link' => $post_link
                    );
                }
                $i++;
            }
            wp_reset_postdata();
        }

        return $lesson_progress_items;
    }
}