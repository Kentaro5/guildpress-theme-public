<?php
/**
*
*/
class AddMemberRankPostTypes
{
	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		add_action( 'init', array( $this, 'add_member_rank_post_types' ), 10 );

	}

	public function add_member_rank_post_types()
	{

		$args = array(
			'labels' => array(
				'name' => __( '会員ランク新規登録画面' ),
				'singular_name' => __( '会員ランク新規登録画面' )
			),
			'public' => true,
			'show_in_menu' => false,
			// 'show_in_menu' => true,
			'rewrite' => false,
			'query_var' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			/* ここから */

			/* ここまで */
		);
		register_post_type( 'guild_press_rank', $args);

	}



}
