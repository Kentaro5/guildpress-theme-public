<?php

/**
 * 
 */
class Guild_Press_Register_From_Data
{
	
	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		$this->wpfunc = new WpFunc;
		add_action( 'admin_init', array( $this, 'set_register_first_user_form_item' ) );
		
	}

	//ユーザー登録フォームの初期値を設定する
	public function set_register_first_user_form_item()
	{
		$options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false);
		
		if ( $options === false ) {

			$guild_press_fields   = array(
				array( 1,  '姓',          'last_name',        'text',     'y', 'y', 'y' ),
				array( 2,  '名',         'first_name',       'text',     'y', 'y', 'y' ),
				array( 3, 'メールアドレス',              'user_email',       'text',     'y', 'y', 'y' ),
				array( 4, 'パスワード',           'password',         'password', 'y', 'y', 'y' ),
			);

			$this->wpfunc->update_option( SLUGNAME.'_regsiter_item_field', $guild_press_fields, '', 'yes' );

		}else{

			return;
		}
		
	}

}