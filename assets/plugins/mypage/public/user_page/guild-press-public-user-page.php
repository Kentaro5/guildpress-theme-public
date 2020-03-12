<?php
//session_start();
/**
*
*/
class Guild_Press_Public_User_Page
{
    public $progress_abr_arg;
    public $lesson_posts_num;
    public $user_data_lesson_num;
    public $str_lesson_posts_str;
    public $user_data_lesson_str;
    public $my_page_top_parts_path;
    public $title;
    public $lesson_link;
    public $excerpt;
    public $thumbnail;
    public $progress_bar_num;

    public function __construct()
    {
        $this->basic = new Basic();
        $this->wpfunc = new WpFunc();
        $this->load();
    }

    public function load()
    {
        //月日をセット
        $gp_date = $this->basic->check_date();

        $gp_check_date = ( isset($gp_date) && $gp_date !== '' ) ? true : false;

        //月日をセット
        if( $gp_check_date ){

            $this->themonth = mktime(0, 0, 0, $gp_date['month'], 1, $gp_date['year'] );
        }else{

            $this->themonth = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
        }

        $this->calender_slug_name = SLUGNAME.'_register_schedule_'.$this->themonth;
        $this->my_page_top_parts_path = 'templates/public/mypage/top/top-parts.php';
        $this->my_page_bottom_parts_path = 'templates/public/mypage/bottom/bottom-parts.php';
    }


    //ユーザーの登録があるかどうかを取得する。
    public function user_my_page()
    {
        ob_start();
        $this->get_mypage();
        $this->getCalenderRegister();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function set_all_lesson_post_num( $lesson_category, $taken_lesson_ids )
    {
         //レッスンの総数を取得
        $lseeon_posts = $this->basic->getGuildDeitalQuery($lesson_category);

        $this->progress_abr_arg = $this->basic->getProgressBarArg( $lesson_category, $taken_lesson_ids );

        //各タクソノミーの総レッスン数をカウント
        $this->lesson_posts_num = count($lseeon_posts->posts);

        $this->str_lesson_posts_str = $this->progress_abr_arg['str_lesson_posts_str'];

    }

    public function set_user_taken_lesson_num( $taken_lesson_ids )
    {
        //Stringでは、カウントできないためarrayにする。
        $taken_lesson_ids = $this->basic->return_arr( $taken_lesson_ids );

        //ユーザーが受けたレッスンの数をカウント
        $this->user_data_lesson_num = count($taken_lesson_ids);
        $this->user_data_lesson_str = $this->progress_abr_arg['user_data_lesson_str'];
    }

    public function get_mypage(){

        $html = '';

        $user_info = $this->return_my_page_user_info();

        $user_info['user_avator'] = get_avatar_url( $user_info['user_id'] );

        //ユーザーの進捗状況取得
        $taken_lesson_list = $this->basic->getUserLessonProgress($user_info['user_id']);

        $edit_page_link = $this->get_edit_page_link();

        $user_progress_details = $this->wpfunc->apply_filters( 'guildpress_lesson_progress_details', '' );

        $gp_data = array(
            'user' => $user_info,
            'edit_page_link' => $edit_page_link,
            'taken_lesson_list' => $taken_lesson_list,
            'user_progress_details' => $user_progress_details,
        );

        $this->wpfunc->set_query_var( 'guild_press_mypage_top_args', $gp_data );

        if( ! $file_path = $this->basic->load_template( $this->my_page_top_parts_path, false ) ){

            return;
        }

        include( $file_path );
    }

    public function get_edit_page_link()
    {
        $options = $this->wpfunc->get_option('guild_press_basic_setting');

        if( !empty( $options['guild_press_edit_user_info'] ) ){

            $user_edit_page_id = $options['guild_press_edit_user_info'];
            $edit_page_link = $this->wpfunc->get_page_link($user_edit_page_id);
        }else{

            $edit_page_link = '';
        }

        return $edit_page_link;
    }

    public function show_success($text='')
    {
        if( isset( $_GET['suc_msg'] ) && $_GET['suc_msg'] === '1' ){

            echo '<p class="form-text center">'.$text.'</p>';

        }
    }

    public function return_my_page_user_info()
    {

        //現在のユーザーデータ取得
        $user = $this->wpfunc->wp_get_current_user();

        //ユーザーの現在の会員ランクを取得
        $user_rank_num = $this->wpfunc->get_user_meta( $user->ID, 'gp_member_rank', true );
        $user_rank = $this->get_member_rank(intval( $user_rank_num));

        $return_args = array(
            'user_name' => '',
            'user_email' => '',
            'user_rank' => '',
            'user_id' => ''
        );

        $return_args['user_name'] = $user->display_name;
        $return_args['user_email'] = $user->user_email;
        $return_args['user_rank'] = $user_rank;
        $return_args['user_id'] = $user->ID;

        return $return_args;
    }

    public function get_member_rank( $user_member_rank_num )
    {
        $wpfunc = new WpFunc();
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
                    $member_rank = get_post_meta( $member_id, 'member_rank_name', true );
                    break;
                }

            }
            $wpfunc->wp_reset_postdata();
        }

        return $member_rank;
    }

    public function getCalenderRegister()
    {

        // カレンダー対象年月
        if (isset($_GET['gp_year']) && isset($_GET['gp_month'])) {
            $this->themonth = mktime(0, 0, 0, $_GET['gp_month'], 1, $_GET['gp_year']);
        } else {
            $this->themonth = mktime(0, 0, 0, date_i18n('n'), 1, date_i18n('Y'));
        }

        // カレンダー生成パラメータ
        $theyear = date('Y', $this->themonth);
        $themonth = date('n', $this->themonth);

        // リンク
        $prev_month = mktime(0, 0, 0, $themonth - 1, 1, $theyear);
        $prev_str = date('Y-m', $prev_month);
        $next_month = mktime(0, 0, 0, $themonth + 1, 1, $theyear);
        $next_str = date('Y-m', $next_month);

        $days = (mktime(0, 0, 0, $themonth + 1, 1, $theyear) - $this->themonth) / 86400;

        $starti = date('w', $this->themonth);
        $endi = $starti + $days + 5 - date('w', mktime(0, 0, 0, $themonth, $days, $theyear));

        $day = 1 - $starti;

        $general = $this->wpfunc->get_option($this->calender_slug_name);

        $user_id = $this->wpfunc->get_current_user_id();

        //数字の日付をY-mdなどの日付の形式に変更
        $this_month = date('Y-m', $this->themonth);
        $prev_show_year = date('Y', $prev_month);
        $prev_show_month = date('n', $prev_month);

        $next_show_year = date('Y', $next_month);
        $next_show_month = date('n', $next_month);

        $mk_time = mktime(0, 0, 0, $themonth, 1, $theyear);

        $prev_link = '?gp_year='.$prev_show_year.'&gp_month='.$prev_show_month.'&action=monthly';
        $next_link = '?gp_year='.$next_show_year.'&gp_month='.$next_show_month.'&action=monthly';

        $gp_data = array(
            'this_month' => $this_month,
            'prev_link' => $prev_link,
            'prev_str' => $prev_str,
            'next_link' => $next_link,
            'next_str' => $next_str,
            'day' => $day,
            'endi' => $endi,
            'themonth' => $themonth,
            'theyear' => $theyear,
            'general' => $general,
            'month_year_time_stamp' => $this->themonth,
        );

        $this->wpfunc->set_query_var( 'guild_press_mypage_bottom_args', $gp_data );

        if( ! $file_path = $this->basic->load_template( $this->my_page_bottom_parts_path, false ) ){

            return;
        }

        include( $file_path );

    }

    public function set_post_items( $args=array() )
    {
        $this->title = get_the_title();
        $this->lesson_link = get_post_permalink();
        $this->excerpt = get_the_excerpt();
        $this->thumbnail = $args['thumbnail'];
        $this->progress_bar_num = $args['progress_bar_num'];
        $this->thumbnail = $this->check_thumbnail( $this->thumbnail );

        $this->str_lesson_posts_str = $args['str_lesson_posts_str'];
        $this->user_data_lesson_str = $args['user_data_lesson_str'];
    }
    //サムネイルが存在するかチェックする。
    public function check_thumbnail( $thumbnail )
    {
        if( ! empty($thumbnail) ){

            return $thumbnail;
        }else{

            $thumbnail = '<img src="'.TEMP_DIR_URI.'/assets/img/no-image.png" alt="">';
            return $thumbnail;

        }
    }

}
