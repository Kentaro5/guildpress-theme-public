<?php
define('SLUGNAME', 'guild_press');
define('TEMP_DIR', get_template_directory());
define('TEMP_DIR_URI', get_template_directory_uri());
define('GUILD_PRESS_GET_USER_NUMBER', 20);
define( 'PAYPAL_SAND_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr' );
define( 'PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr' );

require_once( TEMP_DIR . '/assets/plugins/loader/guild-press-load-files.php' );


if ( ! function_exists( 'guild_press_delete_schedule' ) ) {

	add_action( 'wp_ajax_guild_press_delete_schedule_action', 'guild_press_delete_schedule' );
	add_action( 'wp_ajax_nopriv_guild_press_delete_schedule_action', 'guild_press_delete_schedule' );

	//Ajax処理
	function guild_press_delete_schedule(){
		//セキュリティチェック
		if( ! check_ajax_referer( 'guild_press_delete_schedule', 'security' ) ){
		 	//ajax先にerrorメッセージをjson形式で送る。
         	return;
		}

		ajax_guild_press_delete_schedule();
	}
}


if ( ! function_exists( 'guild_press_delete_user_schedule' ) ) {
	add_action( 'wp_ajax_guild_press_delete_user_schedule_action',   'guild_press_delete_user_schedule' );
	add_action( 'wp_ajax_nopriv_guild_press_delete_user_schedule_action',   'guild_press_delete_user_schedule' );

	//Ajax処理
	function guild_press_delete_user_schedule(){
		//セキュリティチェック
		if( ! check_ajax_referer( 'guild_press_delete_user_schedule', 'security' ) ){
		 	//ajax先にerrorメッセージをjson形式で送る。
			return;
		}

		ajax_guild_press_delete_user_schedule();
	}
}


if ( ! function_exists( 'guild_press_check_quiz_answer' ) ) {
	add_action( 'wp_ajax_guild_press_check_quiz_answer_action',   'guild_press_check_quiz_answer' );
	add_action( 'wp_ajax_nopriv_guild_press_check_quiz_answer_action',   'guild_press_check_quiz_answer' );

	function guild_press_check_quiz_answer(){

		if( ! check_ajax_referer( 'guild_press_save_user_lesson_progress', 'security' ) ){
		 	//ajax先にerrorメッセージをjson形式で送る。
			return;
		}
		$user_id = intval( $_POST['user_id'] );
		$user_id_check = guild_press_check_user_id($user_id);

		if( $user_id_check === false ){

			die('user_idが書き換えられています。');
		}

		$user_info = get_user_meta($user_id);

		 //user_infoの中に既に指定したスラッグがある場合
		if( isset( $user_info[$_POST['slug']] ) ){

			$result = guild_press_return_check_quiz_answer( $_POST );

			guild_press_save_lesson_data( $result, $_POST, $user_info, 'add' );
		}else{

			$result = guild_press_return_check_quiz_answer( $_POST );

			guild_press_save_lesson_data( $result, $_POST, $user_id, 'new' );
		}

		die("failed");
	}
}


if ( ! function_exists( 'guild_press_save_user_lesson_progress' ) ) {
	add_action( 'wp_ajax_guild_press_save_user_lesson_progress_action',   'guild_press_save_user_lesson_progress' );
	add_action( 'wp_ajax_nopriv_guild_press_save_user_lesson_progress_action',   'guild_press_save_user_lesson_progress' );

	//Ajax処理
	function guild_press_save_user_lesson_progress(){

		//セキュリティチェック
		if( ! check_ajax_referer( 'guild_press_save_user_lesson_progress', 'security' ) ){
		 	//ajax先にerrorメッセージをjson形式で送る。
			return;
		}

		$wpfunc = new WpFunc();

		$user_id = intval( $_POST['user_id'] );
		$user_id_check = guild_press_check_user_id($user_id);

		if( $user_id_check === false ){

			die('user_idが書き換えられています。');
		}

		$user_info = $wpfunc->get_user_meta($user_id);

		 //user_infoの中に既に指定したスラッグがある場合
		if( isset( $user_info[$_POST['slug']] ) ){

			guild_press_update_user_lesson_progress_data( $_POST, $user_info );
		}else{

			guild_press_store_new_user_lesson_progress_data( $_POST, $user_id );
		}
	}
}


if ( ! function_exists( 'guild_press_get_login_page_id' ) ) {
	function guild_press_get_login_page_id()
	{
		$guild_press_settings = get_option('guild_press_basic_setting');

		$login_page_id = ( isset($guild_press_settings['guild_press_login']) && $guild_press_settings['guild_press_login'] !== "" ) ? $guild_press_settings['guild_press_login'] : '';

		return $login_page_id;
	}
}


//オリジナルのエディタショートカットボタン
if(!function_exists("add_short_cut_btn")){
	function add_short_cut_btn() {

		if (wp_script_is('quicktags')){
			?>
			<script type="text/javascript">
				admin_shortcut_btn_js.lesson_desc();
				admin_shortcut_btn_js.add_guild_press_contents_short_code();
				admin_shortcut_btn_js.add_guild_press_lesson_list_short_code();
			</script>
			<?php
		}

	}
	add_action( 'admin_print_footer_scripts', 'add_short_cut_btn' );
}


if ( ! function_exists( 'guild_press_set_up_paypal' ) ) {

	add_action( 'wp_ajax_guild_press_set_up_paypal_action',   'guild_press_set_up_paypal' );
	add_action( 'wp_ajax_nopriv_guild_press_set_up_paypal_action',   'guild_press_set_up_paypal' );

	function guild_press_set_up_paypal()
	{
		 if( ! check_ajax_referer( 'guild_press_set_up_paypal', 'security' ) ){
		 	//ajax先にerrorメッセージをjson形式で送る。
         	return;
         }
        $wpfunc = new wpFunc();

        $basic_settings = $wpfunc->get_option(SLUGNAME.'_basic_setting');

		$cancel_url_id = ( isset($basic_settings['guild_press_payment_cancel_url']) && $basic_settings['guild_press_payment_cancel_url'] !== "" ) ? $basic_settings['guild_press_payment_cancel_url'] : '';

		$complete_payment_url_id = ( isset($basic_settings['guild_press_after_payment']) && $basic_settings['guild_press_after_payment'] !== "" ) ? $basic_settings['guild_press_after_payment'] : '';

		//決済がキャンセるされた時のURLをセットする
		$cancel_url = $wpfunc->get_page_link( $cancel_url_id );

		if( $complete_payment_url_id === '' ){

			$complete_payment_url = $wpfunc->home_url()."/";
		}else{

			//決済が終了した後のURLをセット
			$complete_payment_url = $wpfunc->get_page_link( $complete_payment_url_id );
		}

		$post_meta_id = ( isset($_POST['post_meta_id']) && $_POST['post_meta_id'] !== "" ) ? $_POST['post_meta_id'] : '';

		//現在のユーザー取得
		$current_user_id = get_current_user_id();

		//PayPalの設定を取得
		$posts_meta = get_post_meta($post_meta_id);

		$filtered_meta = array();
		foreach ($posts_meta as $posts_meta_key => $posts_meta_value) {
			$filtered_meta[$posts_meta_key] = ( isset($posts_meta[$posts_meta_key]) && $posts_meta[$posts_meta_key] !== "" ) ? $posts_meta_value : '';
		}

		$paypal_input_field = array();

		//ビジネスメールアドレスを入れる。
		$paypal_input_field[0]['item_name'] = 'business';
		$paypal_input_field[0]['item_value'] = $filtered_meta['paypal_address'];

		//キャンセルURLを入れる。
		$paypal_input_field[1]['item_name'] = 'cancel_return';
		$paypal_input_field[1]['item_value'] = $cancel_url;

		//決済完了後の遷移先を指定。
		$paypal_input_field[2]['item_name'] = 'return';
		$paypal_input_field[2]['item_value'] = $complete_payment_url;

		//リダイレクトする際の設定
		$paypal_input_field[3]['item_name'] = 'rm';
		$paypal_input_field[3]['item_value'] = '2';

		//リダイレクトする際の設定
		$paypal_input_field[4]['item_name'] = 'lc';
		$paypal_input_field[4]['item_value'] = $filtered_meta['paypal_lang'];

		//アドレス入力を求めるかどうか
		$paypal_input_field[5]['item_name'] = 'no_shipping';
		$paypal_input_field[5]['item_value'] = '1';

		//ノート表示
		$paypal_input_field[6]['item_name'] = 'no_note';
		$paypal_input_field[6]['item_value'] = '1';

		//カスタムバリュー
		$paypal_input_field[7]['item_name'] = 'custom';

		if( $filtered_meta['member_rank'][0] !== '' ){

			$paypal_input_field[7]['item_value'] = $current_user_id.':'.$filtered_meta['member_rank'][0];
		}else{

			$paypal_input_field[7]['item_value'] = $current_user_id;
		}

		//通貨指定
		$paypal_input_field[8]['item_name'] = 'currency_code';
		$paypal_input_field[8]['item_value'] = $filtered_meta['currency'];

		//決済画面のスタイル
		$paypal_input_field[9]['item_name'] = 'page_style';
		$paypal_input_field[9]['item_value'] = 'paypal';

		//文字コード
		$paypal_input_field[10]['item_name'] = 'charset';
		$paypal_input_field[10]['item_value'] = 'utf-8';

		//商品名
		$paypal_input_field[11]['item_name'] = 'item_name';
		$paypal_input_field[11]['item_value'] = $filtered_meta['item_name'];

		//決済の種類
		$paypal_input_field[12]['item_name'] = 'cmd';
		$paypal_input_field[12]['item_value'] = $filtered_meta['payment'];

		//継続課金の設定
		$paypal_input_field[13]['item_name'] = 'src';
		$paypal_input_field[13]['item_value'] = '1';

		//継続課金の回数
		$paypal_input_field[14]['item_name'] = 'srt';
		$paypal_input_field[14]['item_value'] = $filtered_meta['payment_period'];

		//継続課金の金額
		$paypal_input_field[15]['item_name'] = 'amount';
		$paypal_input_field[15]['item_value'] = $filtered_meta['amount'];

		//継続課金の金額
		$paypal_input_field[16]['item_name'] = 'a3';
		$paypal_input_field[16]['item_value'] = $filtered_meta['amount'];

		//継続課金の期間
		$paypal_input_field[17]['item_name'] = 'p3';
		$paypal_input_field[17]['item_value'] = $filtered_meta['payment_cycle_number'];

		//継続課金のサイクル
		$paypal_input_field[18]['item_name'] = 't3';
		$paypal_input_field[18]['item_value'] = $filtered_meta['payment_cycle'];

		//自動再開について
		$paypal_input_field[19]['item_name'] = 'sra';
		$paypal_input_field[19]['item_value'] = '1';

		$url = home_url();

		$paypal_input_field[20]['item_name'] = 'notify_url';
		$paypal_input_field[20]['item_value'] = $url;

		//キャンセルURLを入れる。
		echo json_encode($paypal_input_field);
		die();
	}
}


