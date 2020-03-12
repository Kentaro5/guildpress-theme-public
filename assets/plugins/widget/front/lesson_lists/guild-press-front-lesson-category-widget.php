<?php

class Front_Lesson_Category_Widget extends WP_Widget
{
    protected $public_lesson_category_template;
    protected $admin_lesson_category_template;
    protected $normal_category_template;
    protected $locked_category_template;
    protected $gp_lesson_category_title;
    protected $gp_lesson_link_text;

    /**
     * Widgetを登録する
     */
    public function __construct()
    {
        parent::__construct(false, 'GP:レッスンポスト', array('description' => 'レッスンカテゴリー別に表示するためのウィジェットです。'));

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {

        $this->public_lesson_category_template = 'templates/public/widget/front/lesson_lists/front-lesson-category.php';

        $this->admin_lesson_category_template = 'templates/admin/widget/front/lesson_lists/front-lesson-category-form.php';

        $this->locked_category_template = 'templates/public/widget/front/lesson_lists/locked_lesson/locked-lesson.php';
        $this->normal_category_template = 'templates/public/widget/front/lesson_lists/normal_lesson/normal-lesson.php';



    }

    public function getGuildDeitalQuery($slug = '', $posts_num = -1)
    {
        $check_int;
        $check_int = intval($posts_num);

        if ($check_int === 0 || $check_int < 0) {

            $check_int = -1;
        }

        if ($slug === '') {

            $custom_query = array('posts_per_page' => $check_int, 'orderby' => 'ID', 'tax_query' => array());
            $query = $this->basic->getGuildDeitalQuery($slug, $custom_query);
        } else {

            $custom_query = array('posts_per_page' => $check_int);
            $query = $this->basic->getGuildDeitalQuery($slug, $custom_query);

        }

        return $query;

    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['gp_post_num'] = strip_tags($new_instance['gp_post_num']);
        $instance['gp_lesson_category_title'] = strip_tags($new_instance['gp_lesson_category_title']);
        $instance['gp_lesson_category'] = strip_tags($new_instance['gp_lesson_category']);

        $instance['gp_lesson_link_text'] = strip_tags($new_instance['gp_lesson_link_text']);

        return $instance;
    }

    /**
     * 表側の Widget を出力する
     *
     * @param array $args 'register_sidebar'で設定した「before_title, after_title, before_widget, after_widget」が入る
     * @param array $instance Widgetの設定項目
     */
    public function widget($args, $instance)
    {

        //各数字のインスタンスの値のチェックを行う。
        $query = $this->getGuildDeitalQuery($instance['gp_lesson_category'], $instance['gp_post_num']);

        $this->gp_lesson_category_title = (isset($instance['gp_lesson_category_title']) && $instance['gp_lesson_category_title'] !== "") ? $instance['gp_lesson_category_title'] : '最近追加されたレッスン';

        $this->gp_lesson_link_text = (isset($instance['gp_lesson_link_text']) && $instance['gp_lesson_link_text'] !== "") ? $instance['gp_lesson_link_text'] : '続きはこちら';

        $gp_data = array(
            'category_title' => $this->gp_lesson_category_title,
            'query' => $query,
        );

        if (!$file_path = $this->basic->load_template($this->public_lesson_category_template, false)) {

            return;
        }

        include($file_path);


        $this->wpfunc->wp_reset_postdata();

    }


    public function load_main_template()
    {
        $post_id = get_the_ID();
        $str_now_post_id = strval($post_id);

        $prev_post = get_previous_post(true, '', 'guild_lesson_category');

        $str_prev_post_id = (isset($prev_post) && $prev_post !== "") ? strval($prev_post->ID) : '';


        $guild_press_lock_page = $this->wpfunc->get_post_meta(get_the_ID(), 'guild_press_lock_page', true);

        $gp_data = array(
            'link_text' => $this->gp_lesson_link_text,
            'no_image' => TEMP_DIR_URI . '/assets/img/no-image.png',
            'post_link' => $post_link,
            'post_title' => $post_title,
            'post_desc' => $post_excerpt,
            'post_id' => $post_id,
            'post_thumb' => '',
            'post_overview_desc' => '',
        );

        if ($guild_press_lock_page === '2') {

            $user_lesson_detail = $this->basic->return_user_complete_lesson_lists(get_the_ID());
            $user_lesson_detail[0] = $this->basic->check_array($user_lesson_detail[0]);

            $is_now_post_comp = $this->basic->in_array($str_now_post_id, $user_lesson_detail[0]);
            $is_prev_post_comp = $this->basic->in_array($str_prev_post_id, $user_lesson_detail[0]);


            if ($is_prev_post_comp || $is_now_post_comp) {


                if (!$file_path = $this->basic->load_template($this->normal_category_template, false)) {

                    return;
                }

                include($file_path);

            } else {

                if (!$file_path = $this->basic->load_template($this->locked_category_template, false)) {
                    return;
                }

                include($file_path);

            }
        } else {

            if (!$file_path = $this->basic->load_template($this->normal_category_template, false)) {

                return;
            }

            include($file_path);
        }
    }

    /** Widget管理画面を出力する
     *
     * @param array $instance 設定項目
     * @return string|void
     */
    public function form($instance)
    {

        $instance['gp_lesson_category_title'] = (isset($instance['gp_lesson_category_title']) && $instance['gp_lesson_category_title'] !== "") ? $instance['gp_lesson_category_title'] : '';
        $gp_lesson_category_title = $instance['gp_lesson_category_title'];
        $gp_lesson_category_title_name = $this->get_field_name('gp_lesson_category_title');
        $gp_lesson_category_title_id = $this->get_field_id('gp_lesson_category_title');

        $instance['gp_post_num'] = (isset($instance['gp_post_num']) && $instance['gp_post_num'] !== "") ? $instance['gp_post_num'] : '';
        $gp_post_num = $instance['gp_post_num'];
        $gp_post_num_name = $this->get_field_name('gp_post_num');
        $gp_post_num_id = $this->get_field_id('gp_post_num');

        $instance['gp_lesson_category'] = (isset($instance['gp_lesson_category']) && $instance['gp_lesson_category'] !== "") ? $instance['gp_lesson_category'] : '';
        $gp_lesson_category = $instance['gp_lesson_category'];
        $gp_lesson_category_name = $this->get_field_name('gp_lesson_category');
        $gp_lesson_category_id = $this->get_field_id('gp_lesson_category');

        $instance['gp_lesson_link_text'] = (isset($instance['gp_lesson_link_text']) && $instance['gp_lesson_link_text'] !== "") ? $instance['gp_lesson_link_text'] : '';
        $gp_lesson_link_text = $instance['gp_lesson_link_text'];
        $gp_lesson_link_text_name = $this->get_field_name('gp_lesson_link_text');
        $gp_lesson_link_text_id = $this->get_field_id('gp_lesson_link_text');

        $cat_lists = $this->wpfunc->get_terms(array('taxonomy' => 'guild_lesson_category', 'hide_empty' => false));

        $gp_data = array(
            'category_title_id' => $gp_lesson_category_title_id,
            'category_title_name' => $gp_lesson_category_title_name,
            'category_title' => $gp_lesson_category_title,
            'post_num_id' => $gp_post_num_id,
            'post_num_name' => $gp_post_num_name,
            'post_num' => $gp_post_num,
            'category_id' => $gp_lesson_category_id,
            'category_name' => $gp_lesson_category_name,
            'cat_lists' => $cat_lists,
            'link_text_id' => $gp_lesson_link_text_id,
            'link_text_name' => $gp_lesson_link_text_name,
            'link_text' => $gp_lesson_link_text,
        );

        if (!$file_path = $this->basic->load_template($this->admin_lesson_category_template, false)) {

            return;
        }

        include($file_path);

    }

}


add_action('widgets_init', function () {
    register_widget('Front_Lesson_Category_Widget');
});