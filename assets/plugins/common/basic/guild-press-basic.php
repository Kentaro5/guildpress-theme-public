<?php

class Basic
{

    protected $posts_per_page;

    public function return_tr_tag($loop_num = '')
    {
        return (0 < $loop_num ? "</tr>\n" : '') . "<tr>\n";
    }


    public function setAdminCommonJs()
    {

        add_action('admin_enqueue_scripts', array($this, 'commonJs'));

    }

    public function getNormalPostQuery( $posts_num=-1, $custom_arr = array() ){

        if( ! is_int( $posts_num ) ){

            $posts_num = -1;
        }

        $query_arr = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_num,
            'orderby' => 'post_date'
        );

        if (count($custom_arr) > 0) {

            $query_arr = array_merge($query_arr, $custom_arr);
        }

        $query =  new WP_Query( $query_arr );

        return $query;

    }

    //レッスン登録に登録されている投稿をカテゴリー別で一覧を取得
    public function getGuildDeitalQuery($slug = '', $custom_arr = array())
    {
        $query_arr = array(
            'post_type' => 'guild_lesson_detail',
            'posts_per_page' => -1,
            'orderby' => 'post_date',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'guild_lesson_category',
                    'field' => 'slug',
                    'terms' => $slug,
                )
            ),
        );

        if (count($custom_arr) > 0) {

            $query_arr = array_merge($query_arr, $custom_arr);
        }
        $query = new WP_Query($query_arr);

        return $query;
    }

    public function reset_arr_order($arr = array())
    {
        if (!is_array($arr)) {

            return;
        }
        return array_merge($arr);
    }

    //レッスン一覧に登録されている投稿をカテゴリー別で一覧を取得
    public function getGuildLessonQuery($slug = '', $custom_arr = array())
    {

        $query_arr = array(
            'post_type' => 'guild_lesson',
            'posts_per_page' => -1,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'guild_lesson_category',
                    'field' => 'slug',
                    'terms' => $slug,
                )
            )
        );

        if (count($custom_arr) > 0) {

            $query_arr = array_merge($query_arr, $custom_arr);
        }

        $query = new WP_Query( $query_arr );

        return $query;
    }

    public function get_first_key_of_array($arr = array())
    {
        reset($arr);
        $first_key = key($arr);

        return $first_key;
    }

    //各ユーザーの進捗状況を返す関数
    public function getUserLessonProgress($user_id)
    {
        $args = array('taxonomy' => 'guild_lesson_category');
        $taxonomies = get_categories($args);

        $user_info = get_user_meta($user_id);
        $taken_lesson_list = array();

        $first_key = $this->get_first_key_of_array($taxonomies);

        //タクソノミーに登録されているカテゴリー一覧取得
        //$taxonomiesの配列が0からもしくは1から始まるバグを発見したのでその対処。
        if ($first_key === 1) {

            $taken_lesson_list = $this->start_one_of_taxonomies($taxonomies, $user_info, $taken_lesson_list);
        } else {

            $taken_lesson_list = $this->start_zero_of_taxonomies($taxonomies, $user_info, $taken_lesson_list);
        }

        return $taken_lesson_list;
    }


    public function start_zero_of_taxonomies($taxonomies, $user_info, $taken_lesson_list)
    {
        for ($i = 0; $i < count($taxonomies); $i++) {
            $slug = (isset($taxonomies[$i])) ? $taxonomies[$i]->slug : '';
            //ユーザーデータの中に登録されているタクソノミーデータがあるかどうかをチェック。
            if (isset($user_info[$slug])) {

                $unserialize_result = @unserialize($user_info[$slug][0]);

                if ($unserialize_result === false) {
                    //シリアライズできなかった場合は普通にデータを入れる。
                    $taken_lesson_list[$slug] = $user_info[$slug][0];
                } else {

                    //できた場合はそのまま代入する。
                    $taken_lesson_list[$slug] = $unserialize_result;
                }
            }
        }
        return $taken_lesson_list;
    }

    public function start_one_of_taxonomies($taxonomies, $user_info, $taken_lesson_list)
    {
        for ($i = 1; $i <= count($taxonomies); $i++) {
            $slug = (isset($taxonomies[$i])) ? $taxonomies[$i]->slug : '';
            //ユーザーデータの中に登録されているタクソノミーデータがあるかどうかをチェック。
            if (isset($user_info[$slug])) {

                $unserialize_result = @unserialize($user_info[$slug][0]);

                if ($unserialize_result === false) {
                    //シリアライズできなかった場合は普通にデータを入れる。
                    $taken_lesson_list[$slug] = $user_info[$slug][0];
                } else {

                    //できた場合はそのまま代入する。
                    $taken_lesson_list[$slug] = $unserialize_result;
                }
            }
        }
        return $taken_lesson_list;
    }

    public function check_post_exists($taken_lesson_ids)
    {
        $return_taken_lesson_ids = $taken_lesson_ids;
        for ($i = 0; $i < count($taken_lesson_ids); $i++) {

            $check_result = get_post_status(intval($taken_lesson_ids[$i]));

            if ($check_result === FALSE || $check_result === "trash") {

                unset($return_taken_lesson_ids[$i]);
            }
        }

        return $return_taken_lesson_ids;
    }

    //プロレグレスバーを使用するために必要なArgumentを取得する
    public function getProgressBarArg($lesson_category = '', $taken_lesson_ids = array())
    {

        //レッスンの総数を取得
        $lseeon_posts = $this->getGuildDeitalQuery($lesson_category);

        //各タクソノミーの総レッスン数をカウント
        $lesson_posts_num = count($lseeon_posts->posts);

        //ユーザーが受けたレッスンの数をカウント
        $taken_lesson_ids = $this->return_arr($taken_lesson_ids);

        $taken_lesson_ids = $this->check_post_exists($taken_lesson_ids);

        $user_data_lesson_num = count($taken_lesson_ids);
        wp_reset_postdata();

        $str_lesson_posts_str = strval($lesson_posts_num);
        $user_data_lesson_str = strval($user_data_lesson_num);

        //万が一何らかの理由で０の場合は、１を入れておく。
        if ($lesson_posts_num === 0) {
            $lesson_posts_num = 1;
        }

        //一度総数で割る。
        $divieded_lesson_num = 100 / $lesson_posts_num;
        //そのあと、ユーザーのカウントを掛け算
        $progress_bar_num = floor($divieded_lesson_num * $user_data_lesson_num);


        $return_arr = array(
            'str_lesson_posts_str' => $str_lesson_posts_str,
            'user_data_lesson_str' => $user_data_lesson_str,
            'progress_bar_num' => $progress_bar_num,
        );
        return $return_arr;
    }

    public function getPagingNum($now_page, $total_users, $setting_page_num)
    {

        //ページング計算
        $total_pages = ceil($total_users / $setting_page_num);
        $before_page = $now_page - 1;
        $next_page = $now_page + 1;

        $return_arr = array(
            'total_pages' => $total_pages,
            'before_page' => $before_page,
            'next_page' => $next_page,
        );

        return $return_arr;
    }

    //POSTの値のIssetチェック
    public function checkPostItem($post_item = array())
    {
        $settings = array();
        foreach ($post_item as $key => $value) {
            $settings[$key] = (!$post_item[$key] || !isset($post_item[$key])) ? "" : $post_item[$key];
        }

        return $settings;
    }

    public function get_post_item($args = '')
    {

        $query = new WP_Query($args);
        return $query->posts;
    }

    public function check_selected($check_item = '', $comapre_item = '')
    {
        if ($comapre_item !== '') {

            return (isset($check_item) && $check_item === $comapre_item) ? 'selected' : '';
        } else {

            return (isset($check_item) && $check_item !== "") ? 'selected' : '';
        }

    }

    public function check_checked($check_item = '')
    {
        return (isset($check_item) && $check_item !== "") ? 'checked' : '';
    }


    public function show_error()
    {

        if ($messages = get_transient('salon_payment_error')) : ?>

            <div id="show_error" class="error">
                <ul>
                    <?php foreach ($messages as $message) : ?>
                        <li><?php echo esc_html($message); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php
        endif;
    }

    public function show_updated()
    {
        if ($messages = get_transient('salon_payment_error')) : ?>
            <div class="updated">
                <ul>
                    <?php foreach ($messages as $message) : ?>
                        <li><?php echo esc_html($message); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php
        endif;
    }

    public function guild_press_create_form($name, $type, $value, $valtochk = null, $class = 'textbox')
    {
        //一応このform-controlを採用。なお、デフォルトは$class='textbox'
        $class = "form-input";
        switch ($type) {

            case "file":
                $class = ($class == 'textbox') ? "file" : $class;
                $str = "<input name=\"$name\" type=\"$type\" id=\"$name\" value=\"$value\" class=\"$class\" />";
                break;

            case "checkbox":
                $class = ($class == 'textbox') ? "checkbox" : $class;
                $str = "<input name=\"$name\" type=\"$type\" id=\"$name\" value=\"$value\"" . $this->item_field_check($value, $valtochk, $type) . " />";
                break;

            case "text":
                $value = stripslashes(esc_attr($value));
                if ($name == "user_email") {
                    $str = "<input name=\"$name\" oninput=\"only_eisu('$name')\"  type=\"$type\" id=\"$name\" value=\"$value\" class=\"$class\" />";
                } else {
                    $str = "<input name=\"$name\" type=\"$type\" id=\"$name\" value=\"$value\" class=\"$class\" />";
                }

                break;

            case "textarea":
                $value = stripslashes(esc_textarea($value));
                $class = ($class == 'textbox') ? "textarea" : $class;
                $str = "<textarea cols=\"20\" rows=\"5\" name=\"$name\" id=\"$name\" class=\"$class\">$value</textarea>";
                break;

            case "password":
                $str = "<input name=\"$name\" type=\"$type\" id=\"$name\" class=\"$class\" />";
                break;

            case "hidden":
                $str = "<input name=\"$name\" type=\"$type\" value=\"$value\" />";
                break;

            case "option":
                $str = "<option value=\"$value\" " . $this->item_field_check($value, $valtochk, 'select') . " >$name</option>";
                break;

            case "select":


                $class = ($class == 'textbox') ? "dropdown" : $class;
                $str = "<select name=\"$name\" id=\"$name\" class=\"$class\">\n";


                foreach ($value as $option) {

                    $pieces = explode('|', $option);

                    $str = $str . "<option value=\"$pieces[1]\"" . $this->item_field_check($pieces[1], $valtochk, 'select') . ">" . __($pieces[0], 'wp-members') . "</option>\n";
                }

                $str = $str . "</select>";
                break;

        }

        return $str;

    }

    public function item_field_check($value, $valtochk, $input_type = null)
    {
        $same = ($input_type == 'select') ? ' selected' : ' checked';
        return ($value == $valtochk) ? $same : '';
    }

    public function null_check_arr($check_arr = '', $return_flg = true)
    {
        if ($return_flg === true) {

            return (empty($check_arr)) ? array() : $check_arr;
        } else {

            return (empty($check_arr)) ? "" : $check_arr;
        }

    }

    //stringをarrayにして返す。
    public function return_arr($check_str)
    {

        return (is_string($check_str)) ? array(0 => $check_str) : $check_str;
    }

    //空白削除
    public function delete_space($value = '')
    {
        return preg_replace("/( |　)/", "", $value);
    }

    //表示されている日付をチェックして、themonthに反映。
    public function check_date()
    {
        $get_month = (isset($_GET['gp_month']) && $_GET['gp_month'] !== "") ? $_GET['gp_month'] : '';
        $get_year = (isset($_GET['gp_year']) && $_GET['gp_year'] !== "") ? $_GET['gp_year'] : '';

        $post_month = (isset($_POST['gp_month']) && $_POST['gp_month'] !== "") ? $_POST['gp_month'] : '';
        $post_year = (isset($_POST['gp_year']) && $_POST['gp_year'] !== "") ? $_POST['gp_year'] : '';


        if ($get_month !== '' && $get_year !== '') {

            return array('month' => $get_month, 'year' => $get_year);
        } elseif ($post_month !== '' && $post_year) {

            return array('month' => $post_month, 'year' => $post_year);
        } else {

            return '';
        }
    }

    public function cut_except_num($value = '')
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    public function return_only_time($value = '')
    {
        return preg_replace('/[^0-9:]/', '', $value);
    }

    public function in_array($check_val = '', $check_arr = array())
    {

        return in_array($check_val, $check_arr, true);
    }

    public function trim_script($string)
    {

        $flg = true;
        while ($flg) {
            $start = stripos($string, '<script');
            $stop = stripos($string, '</script>');
            if ((is_numeric($start)) && (is_numeric($stop))) {
                $string = substr($string, 0, $start) . substr($string, ($stop + strlen('</script>')));
            } else {
                $flg = false;
            }
        }

        return trim($string);
    }

    //開業コードをbrタグに変換
    public function add_br_tag($desc = '')
    {
        return preg_replace("/\r\n|\r|\n/", '<br/>', $desc);
    }

    //改行削除
    public function delete_new_line($value = '')
    {
        return preg_replace("/\r\n|\r|\n/", '', $value);
    }

    public function insert_post($post_type)
    {
        $post_id = wp_insert_post(array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'post_title' => '',
            'post_content' => '',
        ), true);

        return $post_id;
    }

    public function update_post($taget_post_id)
    {
        $post_id = wp_update_post(array(
            'ID' => (int)$taget_post_id,
            'post_status' => 'publish',
            'post_title' => '',
            'post_content' => '',
        ));

        return $post_id;
    }

    public function update_custom_post($post_id, $save_item)
    {
        foreach ($save_item as $post_key => $post_value) {
            update_post_meta($post_id, $post_key,
                $post_value);
        }
    }

    //update_post_metaで使う値をチェックして返す。
    public function check_save_item($check_item, $save_item_arr = array())
    {

        $return_item = array();
        foreach ($save_item_arr as $key => $value) {

            $return_item[$key] = (isset($check_item[$key]) && $check_item[$key] !== "") ? $check_item[$key] : '';
        }

        return $return_item;
    }

    //get_post_metaで値を取得した時、値が何も入っていない場合はいろんな形式が考えられるためそれをチェックする関数
    public function check_value_of_post_meta($check_post_meta)
    {
        //最初は配列に値が入っていないため、初期値をセットする。
        if ($check_post_meta === false) {

            $check_post_meta[0] = '';
            return $check_post_meta;
        } else if (count($check_post_meta) === 0) {

            $check_post_meta[0] = '';
            return $check_post_meta;
        } else {

            return $check_post_meta;
        }
    }

    public function return_user_complete_lesson_lists($post_id)
    {

        $post_taxnomy_category = wp_get_post_terms($post_id, 'guild_lesson_category', array("fields" => "all"));
        $taxnomy_slug = $post_taxnomy_category[0]->slug;

        $user = wp_get_current_user();

        //ユーザーが学習を完了している値を取得
        $user_lesson_detail = get_user_meta($user->ID, $taxnomy_slug);
        $user_lesson_detail = $this->check_value_of_post_meta($user_lesson_detail);

        return $user_lesson_detail;

    }

    public function get_lock_lesson_message()
    {
        $message = "このページには鍵がかかっています。<br/>";
        $message .= "鍵を解除するには、前のコンテンツの学習を完了するか。<br/>";
        $message .= "前の学習のクイズに正解する必要があります。";

        return $message;
    }

    //値が殻の文字列なら配列を返す。
    public function check_array($arr)
    {
        if ($arr === '') {

            return array();
        } elseif (!is_array($arr)) {

            //配列じゃない場合は$arr[0]の形にして返す。
            $return_arr = array(0 => array());
            $return_arr[0] = $arr;
            return $return_arr;
        } else {

            return $arr;
        }
    }

    public function check_associative_array($arr)
    {
        return (isset($arr[0]) && $arr[0] !== "") ? $arr : array(0 => '');
    }

    //WP_erroオブジェクトのチェック
    public function check_wp_error($check_value)
    {
        if (is_wp_error($check_value)) {

            //エラーを表示
            echo $check_value->get_error_message();
            die();
        }
    }

    public function load_template($target_file_path = '', $load = false, $require_once = true)
    {
        $file_path = locate_template($target_file_path, $load, $require_once);

        if (!file_exists($file_path)) {
            return false;
        } else {

            return $file_path;
        }
    }

    public function guild_press_get_page_slug()
    {
        $post_id = get_the_ID();

        //複数ある場合は、最初のカテゴリーを取得
        $page_slug = wp_get_post_terms($post_id, 'guild_lesson_category', array("fields" => "all"));

        if (!is_wp_error($page_slug)) {

            $page_slug = (isset($page_slug[0]) && $page_slug[0] !== "") ? $page_slug[0]->slug : '';
        }

        return $page_slug;
    }

    public function guild_press_get_post_type()
    {
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);

        return $post_type;
    }

    public function check_email_format($check_val)
    {
        return preg_match('/@(?!\[)(.+)\z/', $check_val);
    }

    public function get_member_rank_query()
    {
        $rank_query = new WP_Query(
            array(
                'post_type' => 'guild_press_rank',
            )
        );

        return $rank_query;
    }

    public function set_posts_per_page()
    {
        $this->posts_per_page = get_option('posts_per_page', false);
        if ($this->posts_per_page === false) {
            $this->posts_per_page = 4;
        }

    }

    public function get_posts_per_page()
    {
        return $this->posts_per_page;
    }

    public function get_pagination_num()
    {
        return get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    }

}

