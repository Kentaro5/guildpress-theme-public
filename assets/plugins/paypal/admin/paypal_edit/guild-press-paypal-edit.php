<?php

/**
 *
 */
class Guild_Press_PayPal_Edit
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

	public function update_paypal_settings()
	{

		if( ! $_POST["payment_register_action"] || ! isset( $_POST["payment_register_action"] ) ){
			return;
		}

		if( ! wp_verify_nonce( $_POST['setting-notification-nonce'], 'setting-notification' ) && !isset( $_POST["setting-notification-nonce"] ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$post_id = ( isset($_POST['post_id']) && $_POST['post_id'] !== "" ) ? intval(trim($_POST['post_id'])) : '';

		$post_item = $this->basic->checkPostItem( $_POST );
    	$save_item = array();

		$save_item = $this->paypal_common->getSaveItem($post_item);

		if ( $post_id ) {

			$post_id = wp_update_post( array(
				'ID' => (int) $post_id,
				'post_status' => 'publish',
				'post_title' => $save_item['post_title'],
				'post_content' => '',
			) );

			foreach ( $save_item as $post_key => $post_value ) {
				update_post_meta( $post_id, $post_key,
					$post_value);
			}
		}

	}
}