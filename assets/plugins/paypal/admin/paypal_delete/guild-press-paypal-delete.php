<?php

/**
 * 
 */
class Guild_Press_PayPal_Delete
{
	
	public function __construct()
	{
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_paypal_form';
	}
	//リクエストされたポストを削除する。
	public function delete_post( $args = array() ){

		//削除するポストのIDを数値化して変数に入れる。
		$post_id = intval($args['form_id_request']);

		$args = array(
			'p' => $post_id,
			'post_status' => 'any',
			'post_type' => 'guild_press_paypal',
		);

		$custom_fileds_items = get_post_meta( $post_id );

		//ちなみにユーザーが１年間１２払いを終えた後はどうやって続きするの？？
		foreach ($custom_fileds_items as $delete_meta_key => $delete_meta_value) {

			$meta_delete_result = delete_post_meta( $post_id, $delete_meta_key, $delete_meta_value[0] );
			if( $meta_delete_result !== true ){

				$meta_result = false;
			}
		}

		if( $meta_result !== false ){
			$result = wp_delete_post( $post_id , true );	
		}

		wp_safe_redirect($this->admin_url);
		exit();

	}
}