<?php
/**
*
*/
class Lesson_Overview_Custom_Post
{
	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		add_action( 'init', array( $this, 'set_lesson_over_view_page' ), 10 );
	}

	//レッスン一覧
	public function set_lesson_over_view_page()
	{

        $capabilities = array(
            // 自分の投稿を編集する権限
            'edit_posts' => 'edit_guild_lessons',
            // 他のユーザーの投稿を編集する権限
            'edit_others_posts' => 'edit_others_guild_lessons',
            // 投稿を公開する権限
            'publish_posts' => 'publish_guild_lessons',
            // プライベート投稿を閲覧する権限
            'read_private_posts' => 'read_private_guild_lessons',
            // 自分の投稿を削除する権限
            'delete_posts' => 'delete_guild_lessons',
            // プライベート投稿を削除する権限
            'delete_private_posts' => 'delete_private_guild_lessons',
            // 公開済み投稿を削除する権限
            'delete_published_posts' => 'delete_published_guild_lessons',
            // 他のユーザーの投稿を削除する権限
            'delete_others_posts' => 'delete_others_guild_lessons',
            // プライベート投稿を編集する権限
            'edit_private_posts' => 'edit_private_guild_lessons',
            // 公開済みの投稿を編集する権限
            'edit_published_posts' => 'edit_published_guild_lessons',
        );

		$args = array(
			'labels' => array(
				'name' => __( 'レッスン一覧' ),
				'singular_name' => __( 'レッスン一覧' )
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 5,
			'taxonomies'          => array( 'guild_lesson_category' ),
			/* ここから */
			'supports' => array('title',
				'editor',
				'thumbnail',
				'author',
        		// 'trackbacks',
				'hierarchical' => true,
				'has_archive' => true,
				'page-attributes',
				// 'custom-fields'
			),
            'capability_type' => 'guild_lesson',
            'capabilities'    => $capabilities,
            'map_meta_cap'    => true
			/* ここまで */
		);

		register_post_type( 'guild_lesson', $args);

        // 管理者に独自権限を付与
        $role = get_role( 'administrator' );
        foreach ( $capabilities as $cap ) {
            $role->add_cap( $cap );
        }

	}
}
