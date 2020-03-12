<?php
/**
 *
 */
class PayPal_Common
{

	public function getSaveItem($post_item)
	{
		$save_item = array();
		$save_item['post_title'] = ( isset($post_item['post_title']) && $post_item['post_title'] !== "" ) ? $post_item['post_title'] : '';

		$save_item['paypal_address'] = ( isset($post_item['paypal_address']) && $post_item['paypal_address'] !== "" ) ? $post_item['paypal_address'] : '';

		$save_item['amount'] = ( isset($post_item['amount']) && $post_item['amount'] !== "" ) ? $post_item['amount'] : '';

		// $save_item['thanks_url'] = ( isset($post_item['thanks_url']) && $post_item['thanks_url'] !== "" ) ? $post_item['thanks_url'] : '';

		// $save_item['cancel_url'] = ( isset($post_item['cancel_url']) && $post_item['cancel_url'] !== "" ) ? $post_item['cancel_url'] : '';

		$save_item['sandbox'] = ( isset($post_item['sandbox']) && $post_item['sandbox'] !== "" ) ? $post_item['sandbox'] : '';

		$save_item['currency'] = ( isset($post_item['currency']) && $post_item['currency'] !== "" ) ? $post_item['currency'] : '';

		$save_item['currency_symbol'] = ( isset($post_item['currency_symbol']) && $post_item['currency_symbol'] !== "" ) ? $post_item['currency_symbol'] : '';

		$save_item['paypal_lang'] = ( isset($post_item['paypal_lang']) && $post_item['paypal_lang'] !== "" ) ? $post_item['paypal_lang'] : '';

		$save_item['paypal_lang'] = ( isset($post_item['paypal_lang']) && $post_item['paypal_lang'] !== "" ) ? $post_item['paypal_lang'] : '';

		$save_item['item_name'] = ( isset($post_item['item_name']) && $post_item['item_name'] !== "" ) ? $post_item['item_name'] : '';

		$save_item['payment'] = ( isset($post_item['payment']) && $post_item['payment'] !== "" ) ? $post_item['payment'] : '';

		$save_item['member_rank'] = ( isset($post_item['member_rank']) && $post_item['member_rank'] !== "" ) ? $post_item['member_rank'] : '';

		$save_item['submit_btn_text'] = ( isset($post_item['submit_btn_text']) && $post_item['submit_btn_text'] !== "" ) ? $post_item['submit_btn_text'] : '';

		$save_item['payment_period'] = ( isset($post_item['payment_period']) && $post_item['payment_period'] !== "" ) ? $post_item['payment_period'] : '';

		$save_item['payment_cycle_number'] = ( isset($post_item['payment_cycle_number']) && $post_item['payment_cycle_number'] !== "" ) ? $post_item['payment_cycle_number'] : '';

		$save_item['payment_cycle'] = ( isset($post_item['payment_cycle']) && $post_item['payment_cycle'] !== "" ) ? $post_item['payment_cycle'] : '';


		return $save_item;
	}
}