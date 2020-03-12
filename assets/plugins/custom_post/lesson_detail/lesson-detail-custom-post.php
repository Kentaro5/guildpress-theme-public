<?php

/**
 *
 */
class Lesson_Detail_Custom_Post
{
    public function __construct()
    {
        $this->load();
    }

    public function load()
    {

        add_action('init', array($this, 'set_lesson_detail_page'), 10);
    }

    //レッスン詳細一覧
    public function set_lesson_detail_page()
    {

        $capabilities = array(
            // 自分の投稿を編集する権限
            'edit_posts' => 'edit_guild_lesson_details',
            // 他のユーザーの投稿を編集する権限
            'edit_others_posts' => 'edit_others_guild_lesson_details',
            // 投稿を公開する権限
            'publish_posts' => 'publish_guild_lesson_details',
            // プライベート投稿を閲覧する権限
            'read_private_posts' => 'read_private_guild_lesson_details',
            // 自分の投稿を削除する権限
            'delete_posts' => 'delete_guild_lesson_details',
            // プライベート投稿を削除する権限
            'delete_private_posts' => 'delete_private_guild_lesson_details',
            // 公開済み投稿を削除する権限
            'delete_published_posts' => 'delete_published_guild_lesson_details',
            // 他のユーザーの投稿を削除する権限
            'delete_others_posts' => 'delete_others_guild_lesson_details',
            // プライベート投稿を編集する権限
            'edit_private_posts' => 'edit_private_guild_lesson_details',
            // 公開済みの投稿を編集する権限
            'edit_published_posts' => 'edit_published_guild_lesson_details',
        );

        $args = array(
            'labels' => array(
                'name' => __('各レッスン登録'),
                'singular_name' => __('各レッスン登録')
            ),
            'public' => true,
            'has_archive' => true,
            'menu_position' => 5,
            'taxonomies' => array('guild_lesson_category'),
            /* ここから */
            'supports' => array('title',
                'editor',
                'thumbnail',
                'author',
                // 'trackbacks',
                'capability_type' => 'guild_lesson_detail',
                'hierarchical' => true,
                'has_archive' => true,
                'comments',
                'page-attributes',

                // 'custom-fields'
            ),
            'capability_type' => 'guild_lesson_detail',
            'capabilities'    => $capabilities,
            'map_meta_cap'    => true
            /* ここまで */
        );

        register_post_type('guild_lesson_detail', $args);

        // 管理者に独自権限を付与
        $role = get_role( 'administrator' );
        foreach ( $capabilities as $cap ) {
            $role->add_cap( $cap );
        }
    }

}
