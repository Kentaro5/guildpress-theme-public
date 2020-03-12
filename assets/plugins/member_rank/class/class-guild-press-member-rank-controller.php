<?php
/**
*
*/
class MemberRankController
{

	public function __construct()
	{

		$this->wpfunc = new WpFunc;
		$this->member_model = new MemberRankModel;
		$this->load();
	}


	public function load()
	{

		add_action( SLUGNAME.'_after_update_user_payment_status', array( $this, 'add_user_member_rank' ) );
		add_action( SLUGNAME.'_after_add_user_paypal_payment_id', array( $this, 'add_user_member_rank_payment_id' ) );

		if( !$this->wpfunc->is_admin()) {

			add_filter( 'the_content', array( $this, 'check_login' ) );
		}
	}

	public function check_login($the_content){

		return $this->member_model->check_login($the_content);
	}

	public function add_user_member_rank_payment_id( $args )
	{
		//NULLチェック
		$args['custom_items'][1] = ( empty( $args['custom_items'][1] ) ) ? '' : $args['custom_items'][1];

		if( $args['custom_items'][1] !== '' ){

			$this->member_model->add_user_member_rank_payment_id( $args );
		}
	}

	//ユーザーに設定された会員のランクをつける。
	public function add_user_member_rank( $args ) {

		//NULLチェック
		$args['custom_items'][1] = ( empty( $args['custom_items'][1] ) ) ? '' : $args['custom_items'][1];

		if( $args['custom_items'][1] !== '' ){

			$this->member_model->update_user_rank_info( $args );
		}


	}


}
