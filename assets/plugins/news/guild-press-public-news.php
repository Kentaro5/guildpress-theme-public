<?php

/**
 * 最新の投稿を返すクラス
 */
class Guild_Press_Public_News
{

    public function __construct()
    {
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->new_post_template = 'templates/public/news/lists/news-posts.php';
        add_shortcode( 'guild_press_news', array( $this, 'get_new_posts' ) );

    }

    public function get_new_posts($args)
    {

        ob_start();
        $this->return_new_posts( $args );
        $new_posts = ob_get_contents();
        ob_end_clean();
        return $new_posts;
    }

    public function return_new_posts($args)
    {

        if( $args['posts_num'] === '' ){

            $posts_num = 4;
        }else{

            $posts_num = intval( $args['posts_num'] );
        }

        if( $args['title'] === '' ){

            $title = '最新ニュース';
        }else{

            $title = $args['title'];
        }

        $new_posts_query = $this->basic->getNormalPostQuery( $posts_num, [ 'orderby' => 'desc' ]);

        $gp_data = array(
            'title' => $title,
            'new_posts_query' => $new_posts_query,
        );

        if( ! $file_path = $this->basic->load_template( $this->new_post_template, false ) ){

            return;
        }

        include( $file_path );

        $this->wpfunc->wp_reset_postdata();
    }

}