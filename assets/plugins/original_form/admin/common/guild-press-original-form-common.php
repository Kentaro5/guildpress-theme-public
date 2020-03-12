<?php

/**
 *
 */
class Guild_Press_Original_Form_Common
{

	public $original_form_item;
	public $add_field_err_msg;
	public $cut_option;
	public $action;
	public $admin_url;

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_regsiter_item_field';
		$this->original_form_item = array();
		$this->add_field_err_msg = '';
		$this->cut_option = '';
		$this->action = '';
	}

	public function get_original_form_item()
	{
		return $this->original_form_item;
	}

	public function get_add_field_err_msg()
	{
		return $this->add_field_err_msg;
	}

	public function get_cut_option()
	{
		return $this->cut_option;
	}

	public function set_action($action)
	{
		$this->action = $action;
	}

	public function check_save_field_item()
	{
		$add_flag = ( isset($_POST['add_flag']) && $_POST['add_flag'] !== "" ) ? $_POST['add_flag'] : '';
		$this->action = ( isset( $_POST['admin_action'] ) ) ? trim( $_POST['admin_action'] ) : '';

		if( $add_flag === 'guildpressnormal' ){

			return $this->get_update_original_form_item();

		}elseif( $add_flag === 'guildpressaddfield' ){


			return $this->get_new_field_item();
		}

	}

	public function save_field_settings(){

		register_setting( 'guild_press_regsiter_item_field', 'guild_press_regsiter_item_field', array( $this, 'check_save_field_item' ) );
	}

	public function check_is_same_input_name( $options )
	{

		//アクションが新しく追加の場合のみ、行う。
		if ( $this->action == 'guildpressaddfield' ) {
			$add_input_name = ( isset($_POST['add_option']) && $_POST['add_option'] !== "" ) ? $_POST['add_option'] : '';

			for ($i=0; $i < count( $options ); $i++) {

				if( $add_input_name === $options[$i][2] ){
				//同じ名前の場合はリダイレクト
					$this->wpfunc->wp_redirect( $this->admin_url );
					exit;
				}
			}
		}
	}

	public function set_original_form_item( $options )
	{

		$this->original_form_item[0] = ( $this->action == 'guildpressaddfield' ) ? ( count( $options ) ) + 1  : false;
		$this->original_form_item[1] = stripslashes( $_POST['add_label'] );
		$this->original_form_item[2] = $this->cut_option;
		$this->original_form_item[3] = $_POST['add_type'];
		$this->original_form_item[4] = ( isset( $_POST['add_display'] ) )  ? $_POST['add_display']  : 'n';
		$this->original_form_item[5] = ( isset( $_POST['add_required'] ) ) ? $_POST['add_required'] : 'n';
		$this->original_form_item[6] = ( $this->cut_option == 'user_nicename' || $this->cut_option == 'display_name' || $this->cut_option == 'nickname' ) ? 'y' : 'n';

		//表示がNOで必須項目にチェックがついているのはおかしいので、チェックを外す。
		$this->original_form_item[5] = ( $this->original_form_item[4] != 'y' && $this->original_form_item[5] == 'y' ) ? 'n' : $this->original_form_item[5];
	}

	public function set_check_box_form_item()
	{
		$this->add_field_err_msg = ( ! $_POST['add_checked_value'] ) ? __( 'Checked value is required for checkboxes. Nothing was updated.', 'wp-members' ) : $this->add_field_err_msg;

		$this->original_form_item[7] = ( isset( $_POST['add_checked_value'] ) )   ? $_POST['add_checked_value']   : false;
		$this->original_form_item[8] = ( isset( $_POST['add_checked_default'] ) ) ? $_POST['add_checked_default'] : 'n';
	}

	public function set_select_box_form_item()
	{
		// Get the values.
		$str = stripslashes( $_POST['add_dropdown_value'] );
			// Remove linebreaks.
		$str = trim( str_replace( array("\r", "\r\n", "\n"), '', $str ) );

			// Create array.
		if ( ! function_exists( 'str_getcsv' ) ) {
			$this->original_form_item[7] = explode( ',', $str );
		} else {
			$this->original_form_item[7] = str_getcsv( $str, ',', '"' );
		}

	}

	public function get_update_original_form_item()
	{

		$options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false  );

		$arr = ( isset( $_POST['salon_payment_u_field'] ) ) ? $_POST['salon_payment_u_field'] : '';
				//update_option( 'salon_payment_utfields', $arr );

		$k = 0;
		for ($row=0; $row < count($options) ; $row++) {
			$delete_field = "del_" . $options[$row][2];

			$delete_field = ( isset( $_POST[$delete_field] ) ) ? $_POST[$delete_field] : false;

				//フィールド処理は更新があるたびに、登録されている全ての項目を取得して、もう一度保存する。
				//削除のチェックが入っているやつだけ除外する。
			if ( $delete_field != "delete" ) {

				for ( $i = 0; $i < 4; $i++ ) {
					$guild_press_newfields[$k][$i] = $options[$row][$i];
				}

				$guild_press_newfields[$k][0] = $k + 1;

				$display_field = $options[$row][2] . "_display";
				$require_field = $options[$row][2] . "_required";
				$checked_field = $options[$row][2] . "_checked";


				//ユーザーメールとパスワードは削除を飛ばす。
				if ( $options[$row][2] != 'user_email' && $options[$row][2] != 'password' && $options[$row][2] != 'last_name' && $options[$row][2] != 'first_name' ){
					$guild_press_newfields[$k][4] = ( isset( $_POST[$display_field] ) ) ? $_POST[$display_field] : '';
					$guild_press_newfields[$k][5] = ( isset( $_POST[$require_field] ) ) ? $_POST[$require_field] : '';

				} else {
					$guild_press_newfields[$k][4] = 'y';
					$guild_press_newfields[$k][5] = 'y';
				}

					//表示がNOで必須項目にチェックがついているのはおかしいので、エラーをだす。
				$guild_press_newfields[$k][5] = ( $guild_press_newfields[$k][4] != 'y' && $guild_press_newfields[$k][5] == 'y' ) ? 'n' : $guild_press_newfields[$k][5];

				$guild_press_newfields[$k][6] = $options[$row][6];
				$guild_press_newfields[$k][7] = ( isset( $options[$row][7] ) ) ? $options[$row][7] : '';

				if ( $options[$row][3] == 'checkbox' ) {
					if ( isset( $_POST[$checked_field] ) && $_POST[$checked_field] == 'y' ) {
						$guild_press_newfields[$k][8] = 'y';
					} else {
						$guild_press_newfields[$k][8] = 'n';
					}
				}

				$k = $k + 1;
			}

		}

		return $guild_press_newfields;
	}

	public function add_new_original_form_item( $options )
	{
		array_push( $options, $this->original_form_item );

		//SESIONを破棄
		session_destroy();
		return $options;
	}

	public function edit_original_form_item( $options, $field_id )
	{
		//フィールド編集処理。
		for ($i=0; $i < count($options); $i++) {
			if( $options[$i][0] === $field_id ){

				$this->original_form_item[0] = $field_id;

				$x = ( $this->original_form_item[3] == 'checkbox' ) ? 8 : ( ( $this->original_form_item[3] == 'select' || $this->original_form_item[3] == 'file' ) ? 7 : 6 );

				for ($k=0; $k <$x+1; $k++) {
					$options[$i][$k] = $this->original_form_item[$k];
				}
			}
		}
				//SESIONを破棄
		session_destroy();
		return $options;
	}

	public function set_item_to_session()
	{
		$add_session_items = array(
			'add_label',
			'add_option',
			'add_type',
			'add_display',
			'add_required',
			'add_dropdown_value',
			'add_checked_default',
			'add_checked_value',
		);

		for ($i=0; $i < count( $add_session_items ); $i++) {
			$_SESSION[$add_session_items[$i]] = ( isset( $_POST[$add_session_items[$i]] ) && $_POST[$add_session_items[$i]] != '' ) ? $_POST[$add_session_items[$i]] : '';
		}
	}

	public function set_cut_option()
	{

		$this->cut_option = $_POST['add_option'];

		//全角から半角の空白に戻す
		$this->cut_option = mb_convert_kana($this->cut_option, 's');
		//空白をアンダースコアに直す
		$this->cut_option = preg_replace('/[\s]+/', '_', $this->cut_option);
	}

	public function check_add_option_item()
	{
		$_POST['add_option'] = ( isset( $_POST['add_option'] ) && $_POST['add_option'] != '' ) ? $_POST['add_option'] : '';
		if ( ! preg_match("/^[a-zA-Z0-9_-]+$/", $_POST['add_option'])) {

			$this->wpfunc->wp_redirect( $this->admin_url );
			exit;
		}
	}

	public function get_new_field_item()
	{

		$options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

		$this->check_is_same_input_name( $options );

		$this->set_item_to_session();

		$this->check_add_option_item();

		$this->set_cut_option();

		$this->set_original_form_item( $options );

		if ( $_POST['add_type'] == 'checkbox' ) {

			$this->set_check_box_form_item();
		}

		if ( $_POST['add_type'] == 'select' ) {
			$this->set_select_box_form_item();
		}

		$field_id = (int) sanitize_text_field( $_POST['field_id'] );

		//フィールド追加処理。
		if ( $this->action== 'guildpressaddfield' ) {

			return $this->add_new_original_form_item( $options );
		}else{

			return $this->edit_original_form_item( $options, $field_id );
		}


	}


}