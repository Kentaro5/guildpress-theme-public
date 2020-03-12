<?php

/**
 *
 */
class Guild_Press_Public_Qna_Form
{
    public $comment_form_setting = array();
    public $comment_setting;
    public $comments;
    public $complete_next_btn_parts_path='';
    public $normal_next_btn_parts_path='';
    public $single_complete_btn_parts_path='';
    public $single_normal_btn_parts_path='';
    public $quiz_btn_parts_path='';
    public $text_area_parts_path='';
    public $first_comment_parts_path='';
    public $normal_comment_parts_path='';
    public $normal_comment_header_path='';
    public $comment_header_path='';

    public function __construct()
    {
        $this->load();
    }

    public function load()
    {

        $this->basic = new Basic;
        $this->wpfunc = new WpFunc;
        $this->comment_form_setting = array(
            'label_submit'=>'',
            'comment_notes_after' => '',
            'title_reply'=>'',
            'comment_field' => '',
            'title_reply_to' =>  '',
            'logged_in_as' => '',
            'cancel_reply_link' => ' ',
            'class_submit' => ''
        );

        $this->complete_next_btn_parts_path = 'templates/public/qna/btn_parts/complete_next/complete-next-btn.php';
        $this->normal_next_btn_parts_path = 'templates/public/qna/btn_parts/normal_next/normal-next-btn.php';
        $this->quiz_btn_parts_path = 'templates/public/qna/btn_parts/quiz/quiz-btn.php';

        $this->single_complete_btn_parts_path = 'templates/public/qna/btn_parts/single_complete/single-complete-btn.php';
        $this->single_normal_btn_parts_path = 'templates/public/qna/btn_parts/single_normal/single-normal-btn.php';

        $this->text_area_parts_path = 'templates/public/qna/text_area/qna-text-area.php';
        $this->first_comment_parts_path = 'templates/public/qna/comments/first_comment/first-comment.php';
        $this->normal_comment_parts_path = 'templates/public/qna/comments/normal_comment/normal-comment.php';

        $this->first_comment_header_path = 'templates/public/qna/comments/comment_parts/first_comment_header/first-comment-header.php';
        $this->normal_comment_header_path = 'templates/public/qna/comments/comment_parts/normal_comment_header/normal-comment-header.php';

    }

    public function qna_form()
    {
        $post_id = get_the_ID();
        $this->set_qna_form( $post_id );

        ob_start();

        echo $this->next_btn_parts( $post_id );

        comment_form($this->comment_form_setting);
        wp_list_comments($this->comment_setting, $this->comments);

        $cform = ob_get_contents();
        ob_end_clean();

        add_action( 'wp_footer',   array( $this, 'qna_toggle_js' )  );
        return apply_filters( 'guild_press_qna_form', $cform );
    }

    public function set_comment_form_setting()
    {
        $this->comment_form_setting['comment_notes_after'] = '';

        $this->comment_form_setting['title_reply'] = '質問フォーム';
        $current_comment = get_comment();
        $this->comment_form_setting['comment_field'] = $this->qna_form_html();
        $this->comment_form_setting['title_reply_to'] = __( '%sへの質問に返信する。' );
        $this->comment_form_setting['logged_in_as'] = '';
        $this->comment_form_setting['cancel_reply_link'] = ' ';
        $this->comment_form_setting['class_submit'] = 'btn_design';
    }

    public function set_comment_setting()
    {

        $this->comment_setting = array(
                'reverse_top_level' => false, //最古のコメントをリストの先頭に表示
                'callback' => array( $this, 'qna_forum' ),
            );
    }

    public function set_submit_label()
    {
        $replytocom = ( isset($_GET['replytocom']) && $_GET['replytocom'] !== "" ) ? $_GET['replytocom'] : '';

        if( $replytocom !== '' ){

            $this->comment_form_setting['label_submit'] = __( '返信する。' );
        }else{

            $this->comment_form_setting['label_submit'] = '質問する';
        }
    }

    public function get_current_user_progress_lists( $post_id )
    {
         //現在のユーザーの情報取得
        $user = $this->wpfunc->wp_get_current_user();

        //現在のユーザーの進捗状況取得
        $current_user_progress_arr = $this->basic->getUserLessonProgress( $user->ID );

        //現在の投稿のタクソノミー取得
        $this_post_taxonomy = $this->wpfunc->wp_get_post_terms( $post_id, 'guild_lesson_category', array() );

        $current_user_progress_lists = ( isset($current_user_progress_arr[$this_post_taxonomy[0]->slug]) && $current_user_progress_arr[$this_post_taxonomy[0]->slug] !== "" ) ? $current_user_progress_arr[$this_post_taxonomy[0]->slug] : array();

        //nullチェック
        if( empty($current_user_progress_lists) ){

            $current_user_progress_lists = array();
        }elseif( is_string( $current_user_progress_lists ) ) {

            $unserialize_result = @unserialize( $current_user_progress_lists );

            if( $unserialize_result === false ){

                $current_user_progress_lists = array( 0 => $current_user_progress_lists );
            }else{

                $current_user_progress_lists = $unserialize_result;
            }

        }

        return $current_user_progress_lists;
    }

    public function get_next_btn_parts( $post_id, $current_user_progress_lists )
    {

        $post_type = $this->wpfunc->get_post_type();

        if( $post_type === 'guild_lesson' ){

            return;
        }

        //同じタクソノミー内のページで、次のポストのリンクを取得。
        $next_post_info = get_next_post(true, '', 'guild_lesson_category');

        //文字列にしてから検索
        $post_id_str = strval($post_id);

        $guild_press_quiz_check = $this->wpfunc->get_post_meta( $post_id, 'guild_press_quiz_check', true );

        if( $next_post_info !== "" ){

            if( $guild_press_quiz_check === '2' ){

                $this->load_quiz_html( $post_id_str, $post_id, $current_user_progress_lists, $next_post_info );
            }elseif( $this->basic->in_array( $post_id_str, $current_user_progress_lists, true ) ){

                if( ! $file_path = $this->basic->load_template( $this->complete_next_btn_parts_path, false ) ){
                    return;
                }
                include( $file_path );
            }else{

                if( ! $file_path = $this->basic->load_template( $this->normal_next_btn_parts_path, false ) ){
                    return;
                }
                include( $file_path );
            }
        }else{

            if( $guild_press_quiz_check === '2' ){

                $this->load_quiz_html( $post_id_str, $post_id, $current_user_progress_lists, $next_post_info );
            }elseif( $this->basic->in_array( $post_id_str, $current_user_progress_lists, true ) ){

                //$this->load_single_complete_btn_parts();
                if( ! $file_path = $this->basic->load_template( $this->single_complete_btn_parts_path, false ) ){
                    return;
                }
                include( $file_path );
            }else{


                if( ! $file_path = $this->basic->load_template( $this->single_normal_btn_parts_path, false ) ){
                    return;
                }
                include( $file_path );
            }
        }
    }

    public function next_btn_parts( $post_id )
    {
        $current_user_progress_lists = $this->get_current_user_progress_lists( $post_id );

        $html = $this->get_next_btn_parts( $post_id, $current_user_progress_lists );

        return $html;
    }

         //QandAフォーム生成
    public function set_qna_form( $post_id )
    {
        $this->comments = get_comments(array( 'post_id' => $post_id));

        $this->set_comment_form_setting();

        $this->set_comment_setting();

        $this->set_submit_label();

    }
    public function qna_form_html()
    {

        ob_start();
        $this->load_text_area_parts();
        $cform = ob_get_contents();
        ob_end_clean();
        return $cform;
    }

    public function return_quiz_radio_input( $guild_press_quiz_answer_text_arr )
    {
        $html='';
        $loop_counts = count( $guild_press_quiz_answer_text_arr );
        for ($loop_num=0; $loop_num < $loop_counts; $loop_num++) {

            if( $loop_num === 0 ){

                $html .= '<div class="mb10"><label class="flexed"><input type="radio" checked id="gp_user_answer_text" class="input_design" name="guild_press_quiz_correct_answer" value="'.$loop_num.'">'.$guild_press_quiz_answer_text_arr[$loop_num].'</label></div>';
            }elseif( $loop_num === ( $loop_counts - 1 ) ){
                $html .= '<div class=""><label class="flexed"><input type="radio" id="gp_user_answer_text" class="input_design" name="guild_press_quiz_correct_answer" value="'.$loop_num.'">'.$guild_press_quiz_answer_text_arr[$loop_num].'</label></div>';
            }else{

                $html .= '<div class="mb10"><label class="flexed"><input type="radio" id="gp_user_answer_text" class="input_design" name="guild_press_quiz_correct_answer" value="'.$loop_num.'">'.$guild_press_quiz_answer_text_arr[$loop_num].'</label></div>';
            }
        }

        return $html;
    }

    public function qna_forum($comment, $args, $depth) {

        if ( 'div' === $args['style'] ) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }

        /* translators: 1: date, 2: time */
        $comment_date = get_comment_date();

        $comment_user = get_comment_author_link();
        $current_comment = get_comment();

        $user = $this->wpfunc->wp_get_current_user();

        $gp_data = array(
                'comment_date' => $comment_date,
                'comment_user' => $comment_user,
                'current_comment' => $current_comment,
                'user' => $user,
                'add_below' => $add_below,
                'depth' => $depth,
                'args' => $args,
            );

        //初めての質問の場合は、初めての質問とわかるようにブロックを作る。
        if( '0' == $current_comment->comment_parent ) {

            //コメントのタイトルとして、文字をカットして抜粋を表示する。
            $comment_title = $current_comment->comment_content;
            $comment_args = array(
                'parent' => $current_comment->comment_ID
            );
            $coment_replay = get_comments($comment_args);


            if( count($coment_replay) > 0 ){
                $reply_flg = "返信あり";
            }else{
                $reply_flg = "返信なし";
            }

            $gp_data['reply_flg'] = $reply_flg;
            $gp_data['comment_title'] = $comment_title;

            if( ! $file_path = $this->basic->load_template( $this->first_comment_parts_path, false ) ){
                return;
            }
            include( $file_path );
        }

        if( ! $file_path = $this->basic->load_template( $this->normal_comment_parts_path, false ) ){
            return;
        }
        include( $file_path );


    }

    public function show_title( $first_char = 'Q.', $comment, $num = 30, $after_char = '...' )
    {

        return $this->wpfunc->esc_html( $first_char.mb_strimwidth( $comment, 0, $num, $after_char, "UTF-8" ) );
    }

    public function qna_toggle_js()
    {
        $post_id = get_the_ID();
        //ポストのタクソノミー(カテゴリー取得)
        $post_taxonomy = $this->wpfunc->wp_get_post_terms( $post_id, 'guild_lesson_category', array() );
        $user = $this->wpfunc->wp_get_current_user();

        //同じタクソノミー内のページで、次のポストのリンクを取得。
        $next_post_info = get_next_post(true, '', 'guild_lesson_category');

        //IDからリンク取得
        if( $next_post_info !== "" ){

            $next_post_link = get_permalink($next_post_info->ID);
        }else{
            $next_post_link = home_url();
        }

        ?>
        <?php //画面ロード中に表示するアニメーション ?>
        <div id="loadingAnim" class="loadingAnim" style='display:none;'>
            <i class="loadingAnim_line"></i>
        </div>
        <script>
            let ss = '<?php echo wp_create_nonce( "guild_press_save_user_lesson_progress" ) ?>';
            let n = '<?php echo $next_post_link; ?>';
            let i = <?php echo $user->ID; ?>;
            let s = '<?php echo $post_taxonomy[0]->slug; ?>';
            let p = '<?php echo $post_id ?>';

            public_js.hide_qna_thread();
            public_js.open_qna_thread();
            public_js.register_next_page_event( n );
            public_js.check_lesson_quiz( n, i, s, p, ss );
            public_js.save_lesson_schedule( n, i, s, p, ss );
        </script>
        <?php
    }

    //クイズ形式のHTMLを返す。
    public function load_quiz_html( $post_id_str, $post_id, $current_user_progress_lists, $next_post_info )
    {
        $guild_press_quiz_text = $this->wpfunc->get_post_meta( $post_id, 'guild_press_quiz_text', true );

        $guild_press_quiz_answer_text = $this->wpfunc->get_post_meta( $post_id, 'guild_press_quiz_answer_text', true );
        $guild_press_quiz_answer_text_arr = unserialize( $guild_press_quiz_answer_text );

        $gp_data = array(

            'guild_press_quiz_text' => $guild_press_quiz_text,
            'guild_press_quiz_answer_text_arr' => $guild_press_quiz_answer_text_arr,
            'next_post_info' => $next_post_info,
            'post_id_str' => $post_id_str,
            'current_user_progress_lists' => $current_user_progress_lists,
        );
        if( ! $file_path = $this->basic->load_template( $this->quiz_btn_parts_path, false ) ){

            return;
        }

        include( $file_path );
    }

    public function load_comment_header( $current_comment )
    {
        if( '0' === $current_comment->comment_parent ) {

            //初めての質問の詳細を隠す、
            $this->load_first_comment_header();
        }else {

            //既に質問の詳細が隠されているので、返信の場合はdisplay:noneを付けない。
            $this->load_normal_comment_header();
        }
    }

    public function load_first_comment_header()
    {
        if( ! $file_path = $this->basic->load_template( $this->first_comment_header_path, false ) ){

            return;
        }

        include( $file_path );
    }

    public function load_normal_comment_header()
    {
        if( ! $file_path = $this->basic->load_template( $this->normal_comment_header_path, false ) ){

            return;
        }

        include( $file_path );
    }

    public function load_text_area_parts()
    {
        if( ! $file_path = $this->basic->load_template( $this->text_area_parts_path, false ) ){
            return;
        }
        include( $file_path );
    }
}