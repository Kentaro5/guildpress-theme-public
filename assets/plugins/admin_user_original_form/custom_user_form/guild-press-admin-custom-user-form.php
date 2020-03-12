<?php
//session_start();
/**
*
ユーザーが作成した、オリジナルの登録フォーム項目を保存する項目。
*
*/
class Admin_Custom_User_Form
{

	public function __construct()
	{
		$this->load();
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
	}

	public function load()
	{
		//管理者側のページに表示。
		add_action( 'show_user_profile', array( $this, 'edit_admin_user_info' ) );
		add_action( 'edit_user_profile', array( $this, 'edit_admin_user_info' ) );
		add_action( 'profile_update',    array( $this, 'update_edit_admin_user_info' ) );
		// add_action( 'profile_update',    array( $this, 'update_admin_user_items' ) );

		$this->check_arr = array(
			'user_name',
			'user_email',
			'password',
			'first_name',
			'last_name'
		);
	}

	public function update_edit_admin_user_info()
	{
		$user_id = intval( trim( $_POST['user_id'] ) );

			//セキュリティ
		if( ! wp_verify_nonce( $_POST['user_info_box'], 'guild_press_edit_user_info' ) && !isset( $_POST['user_info_box'] ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$cusomt_user_options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

		if( $cusomt_user_options === false ){

			return;
		}

		//データのチェック。
		$items = $this->check_option_data( $cusomt_user_options, $this->check_arr );

		//データの保存
		$this->save( $cusomt_user_options, $this->check_arr, $user_id , $items);
	}

	//データの保存
	public function save( $cusomt_user_options, $check_arr, $user_id, $items )
	{
		for ( $k=0; $k < count($cusomt_user_options); $k++ ){

			if( ! $this->basic->in_array( $cusomt_user_options[$k][2], $check_arr ) ) {

				$custom_item_key = $cusomt_user_options[$k][2];

				$item_value = ( isset($items[$cusomt_user_options[$k][2]]) && $items[$cusomt_user_options[$k][2]] !== "" ) ? $items[$cusomt_user_options[$k][2]] : '';

				$custom_item_value = $item_value;

				$this->wpfunc->update_user_meta( $user_id, $custom_item_key, $custom_item_value );
			}
		}
	}

	//データのチェック。
	public function check_option_data( $cusomt_user_options, $check_arr )
	{
		for ( $i=0; $i < count($cusomt_user_options); $i++ ){

			if( ! $this->basic->in_array( $cusomt_user_options[$i][2], $check_arr ) ) {

				if ( $cusomt_user_options[$i][4] == 'y' ) {

					$items[ $cusomt_user_options[$i][2] ] = ( isset( $_POST[ $cusomt_user_options[$i][2] ] ) ) ? $_POST[ $cusomt_user_options[$i][2] ] : '';

					$_SESSION[ $cusomt_user_options[$i][2] ] = ( isset( $items[ $cusomt_user_options[$i][2] ] ) ) ? esc_html( $items[ $cusomt_user_options[$i][2] ] ) : '';
				}

				if ( $cusomt_user_options[$i][5] == 'y' ) {
					if ( ! isset( $items[$cusomt_user_options[$i][2]] ) || $items[$cusomt_user_options[$i][2]] === '' ) {

						$_SESSION['error_msg'][$cusomt_user_options[$i][2]] = sprintf( esc_html($cusomt_user_options[$i][1])."は必須入力項目です。" );
					}else{

						$_SESSION['error_msg'][$cusomt_user_options[$i][2]] = '';
					}
				}
			}
		}

		return $items;
	}

	public function edit_admin_user_info()
	{
		$cusomt_user_options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

		if( $cusomt_user_options === false ){

			return;
		}

		$error_msg = ( isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !== "" ) ? $_SESSION['error_msg'] : '';

		$html='';

		$user_id = ( isset($_GET['user_id']) && $_GET['user_id'] !== "" ) ? intval( $_GET['user_id'] ) : '';

		$result = $this->check_another_custom_options( $cusomt_user_options, $this->check_arr );

		if( $result === true ){

			$this->show_form( $cusomt_user_options, $error_msg, $user_id );
		}else{

			return;
		}
	}

	public function show_form( $cusomt_user_options, $error_msg, $user_id )
	{
		wp_nonce_field( 'guild_press_edit_user_info', 'user_info_box', false );

		$html = '';

		$html .= '<h2>GuildPressオリジナル登録フォーム項目</h2>';
		$html .= '<table class="form-table">';

		for ( $i=0; $i < count($cusomt_user_options); $i++ ){

				//check_arrに入っている項目は表示しないようにする。
			if( ! $this->basic->in_array( $cusomt_user_options[$i][2], $this->check_arr ) ) {

				$html .= '<tr>';
				if( $cusomt_user_options[$i][4] == 'y' ){

					$html .= $this->get_custom_th_contents( $cusomt_user_options, $error_msg, $i );

					$html .= $this->get_custom_td_contents( $user_id, $cusomt_user_options, $i );

				}
				$html .= '</tr>';

			}

		}
		$html .= '</table>';

		echo $html;
	}

	//デフォルトの他にカスタマイズされた登録フォーム項目があるかチェックする。
	public function check_another_custom_options( $cusomt_user_options, $check_arr )
	{
		$another_custom_check=false;
		for ( $i=0; $i < count($cusomt_user_options); $i++ ){

			//check_arrに入っている項目は表示しないようにする。
			if( ! $this->basic->in_array( $cusomt_user_options[$i][2], $check_arr ) ) {

				$another_custom_check = true;
				break;
			}

		}

		return $another_custom_check;
	}

	public function get_custom_th_contents( $cusomt_user_options, $error_msg, $loop_num )
	{
		$html = '';
		$html .= '<th>';

		if( isset($error_msg[$cusomt_user_options[$loop_num][2]]) && $error_msg[$cusomt_user_options[$loop_num][2]] !== ""  ){

			$html .= '<p style="color:red;">';
			$html .= $this->wpfunc->esc_html( $error_msg[$cusomt_user_options[$loop_num][2]] );
			$html .= '</p>';
		}

		$html .= '<p class="form-text">';

		if ( $cusomt_user_options[$loop_num][5] == 'y' ){
			$html .= '<span color="red">*</span>';
		}
		$html .= $cusomt_user_options[$loop_num][1];

		$html .= '</p>';

		$html .= '</th>';

		return $html;
	}

	public function get_custom_td_contents( $user_id, $cusomt_user_options, $loop_num )
	{
		$html='';
		$html .= '<td>';

						//値を取得する。
		$val = $this->wpfunc->get_user_meta( $user_id, $cusomt_user_options[$loop_num][2], true );

		if( $cusomt_user_options[$loop_num][3] == 'checkbox' ){

			$valtochk = $val;
			$val = $cusomt_user_options[$loop_num][7];

			if ( $cusomt_user_options[$loop_num][8] == 'y' && ! $_POST ) { $val = $valtochk = $cusomt_user_options[$loop_num][7]; }

			$html .=  $this->basic->guild_press_create_form( $cusomt_user_options[$loop_num][2], $cusomt_user_options[$loop_num][3], $val, $valtochk );

		}elseif( $cusomt_user_options[$loop_num][3] == 'select' ){

			$valtochk = $val;
			$val = $cusomt_user_options[$loop_num][7];
			$html .=  $this->basic->guild_press_create_form( $cusomt_user_options[$loop_num][2], $cusomt_user_options[$loop_num][3], $val, $valtochk );

		}else{

			$html .= $this->basic->guild_press_create_form( $cusomt_user_options[$loop_num][2], $cusomt_user_options[$loop_num][3], $val, '' );

		}

		$html .= '</td>';

		return $html;
	}



}



