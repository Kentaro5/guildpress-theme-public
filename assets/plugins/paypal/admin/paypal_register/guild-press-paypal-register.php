<?php

/**
 *
 */
class Guild_Press_PayPal_Register
{
	public $paypal_common;
	public $wpfunc;
	public $basic;

	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->paypal_common = new PayPal_Common;
	}

	public function save_paypal_settings() {


    	// Save logic goes here. Don't forget to include nonce checks!
    	//ポストが新規か編集家をチェックする。
    	$check_post_status = get_current_screen();

    	$post_item = $this->basic->checkPostItem( $_POST );
    	$save_item = array();

    	if( ! $_POST["payment_register_action"] || ! isset( $_POST["payment_register_action"] ) ){
			return;
		}

		if( ! wp_verify_nonce( $_POST['setting-notification-nonce'], 'setting-notification' ) && !isset( $_POST["setting-notification-nonce"] ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$save_item = $this->paypal_common->getSaveItem($post_item);


		if( $post_item['action'] === 'save' ){
			$post_id = wp_insert_post( array(
				'post_type' => 'guild_press_paypal',
				'post_status' => 'publish',
				'post_title' => $_POST['post_title'],
				'post_content' => '',
			) );
		}

		if ( $post_id ) {
			foreach ( $save_item as $post_key => $post_value ) {
				update_post_meta( $post_id, $post_key,
					$post_value);
			}
		}

	}



}