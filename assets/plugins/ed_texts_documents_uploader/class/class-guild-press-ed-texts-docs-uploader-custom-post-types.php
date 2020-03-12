<?php
/**
*
*/
class AddEdTextsDocsPostTypes
{
	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		add_action( 'init', array( $this, 'add_ed_texts_docs_post_types' ), 10 );

	}

	public function add_ed_texts_docs_post_types()
	{

		$args = array(
			'labels' => array(
				'name' => __( '教材資料アップロード画面' ),
				'singular_name' => __( '教材資料アップロード画面' )
			),
			'public' => true,
			'show_in_menu' => false,
			// 'show_in_menu' => true,
			'rewrite' => false,
			'query_var' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'supports' => array(
			)
			/* ここから */

			/* ここまで */
		);
		register_post_type( 'guild_press_text_doc', $args);

	}





}
