<?php


/**
*
*/
class PayPal_Custom_Post
{
	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		add_action( 'init', array( $this, 'set_paypal_settings_page' ), 10 );
	}


	public function set_paypal_settings_page()
	{

		$args = array(
			'labels' => array(
				'name' => __( 'PayPal設定新規登録画面' ),
				'singular_name' => __( 'PayPal設定新規登録画面' )
			),
			'public' => true,
			'show_in_menu' => false,
			// 'show_in_menu' => true,
			'rewrite' => false,
			'query_var' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			/* ここから */
			'supports' => array('title'
			)
			/* ここまで */
		);
		register_post_type( 'guild_press_paypal', $args);

	}



}
