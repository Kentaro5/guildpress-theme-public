<?php

/**
 *
 */
class Guild_Press_Public_Lesson_Common
{
    protected $post_args = array(
        array(
            'post_link' => '',
            'post_title' => '',
            'post_desc' => '',
            'post_thumb' => '',
            'post_id' => 0,
            'prev_post_id' => 0,
            'is_lock_page' => 0,
        )
    );

    protected $locked_lesson_path='';
    protected $normal_lesson_path='';

    public function __construct()
    {
        $this->basic = new Basic();
        $this->wpfunc = new Wpfunc();
        $this->locked_lesson_path = 'templates/public/lesson/locked_lesson/locked-lesson.php';
        $this->normal_lesson_path = 'templates/public/lesson/normal_lesson/normal-lesson.php';
    }

    public function set_post_args($count)
    {

        $this->post_args[$count]['post_link'] = get_post_permalink();
        $this->post_args[$count]['post_title'] = get_the_title();
        $this->post_args[$count]['post_desc'] = get_the_excerpt();
        $this->post_args[$count]['post_thumb'] = get_the_post_thumbnail();
        //IDを取得して、ページに鍵を掛けるか切り分ける。
        $this->post_args[$count]['post_id'] = get_the_ID();

    }

    public function set_prev_post_id($count, $prev_post)
    {
        if( $prev_post === '' || is_null( $prev_post ) ){

            $this->post_args[$count]['prev_post_id'] = 0;
        }else{
            $this->post_args[$count]['prev_post_id'] = $prev_post->ID;
        }

    }

    public function show_content_page( $count )
    {
        //ポストIDを文字列化
        $str_now_post_id = strval($this->post_args[$count]['post_id']);
        $str_prev_post_id = strval($this->post_args[$count]['prev_post_id']);

        $user_lesson_detail = $this->basic->return_user_complete_lesson_lists( $this->post_args[$count]['post_id'] );
        $user_lesson_detail[0] = $this->basic->check_array( $user_lesson_detail[0] );

        $is_now_post_comp = $this->basic->in_array( $str_now_post_id , $user_lesson_detail[0]);
        $is_prev_post_comp = $this->basic->in_array( $str_prev_post_id , $user_lesson_detail[0]);

        $guild_press_lock_page = $this->wpfunc->get_post_meta( $this->post_args[$count]['post_id'], 'guild_press_lock_page', true );

        $gp_data = array(
            'post_id' => 0,
            'post_title' => $this->post_args[$count]['post_title'],
            'post_desc' => $this->post_args[$count]['post_desc'],
            'post_thumb' => $this->post_args[$count]['post_thumb'],
            'no_image' => TEMP_DIR_URI.'/assets/img/no-image.png',
            'post_link' => $this->post_args[$count]['post_link'],
            'count' => $count,
            'is_now_post_comp' => $is_now_post_comp,
            'is_prev_post_comp' => $is_prev_post_comp,
        );

        if( $guild_press_lock_page === '2' ){

            if( $is_prev_post_comp || $is_now_post_comp ){

                //元のコンテンツを返す。
                if( ! $file_path = $this->basic->load_template( $this->normal_lesson_path, false ) ){

                    return;
                }
                include( $file_path );
            }else{

                if( ! $file_path = $this->basic->load_template( $this->locked_lesson_path, false ) ){

                    return;
                }
                include( $file_path );
            }
        }else{

            //元のコンテンツを返す。
            if( ! $file_path = $this->basic->load_template( $this->normal_lesson_path, false ) ){

                return;
            }
            include( $file_path );
        }
    }

    public function set_lock_page_num( $count )
    {
        $user_lesson_detail = $this->basic->return_user_complete_lesson_lists( $this->post_args[$count]['post_id'] );
        $user_lesson_detail[0] = $this->basic->check_array( $user_lesson_detail[0] );

        //ポストIDを文字列化
        $str_now_post_id = strval($this->post_args[$count]['post_id']);
        $str_prev_post_id = strval($this->post_args[$count]['prev_post_id']);

        $guild_press_lock_page = $this->wpfunc->get_post_meta( $this->post_args[$count]['post_id'], 'guild_press_lock_page', true );

        if( $guild_press_lock_page === '2' ){

            $is_now_post_comp = $this->basic->in_array( $str_now_post_id , $user_lesson_detail[0]);
            $is_prev_post_comp = $this->basic->in_array( $str_prev_post_id , $user_lesson_detail[0]);

            if( $is_prev_post_comp || $is_now_post_comp ){

                //元のコンテンツを返す。
                $post_args[$count]['is_lock_page'] = '1';
            }else{
                $post_args[$count]['is_lock_page'] = '2';
            }
        }else{
            //元のコンテンツを返す。
            $this->post_args[$count]['is_lock_page'] = '1';
        }
    }

    public function get_post_args(){

        return $this->post_args;
    }

}
