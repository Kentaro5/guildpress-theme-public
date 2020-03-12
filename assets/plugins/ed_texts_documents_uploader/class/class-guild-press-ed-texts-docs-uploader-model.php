<?php
require_once( TEMP_DIR . '/assets/plugins/pagination/public/guild-press-public-pagination.php' );
/**
*
*/
class EdTextsDocsModel
{

	public function __construct()
	{
		// $this->load();
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;
		$this->pagination = new Pagination;
		$this->admin_url='';
	}


	public function get_upload_dir() {

		$wp_upload_dir = wp_upload_dir();
		wp_mkdir_p( $wp_upload_dir['basedir'] . '/gp_texts_docs' );
		$path = $wp_upload_dir['basedir'] . '/gp_texts_docs';

		return apply_filters( 'guild_press_get_upload_dir', $path );
	}

	public function return_upload_dir( $upload )
	{

		if ( get_option( 'uploads_use_yearmonth_folders' ) ) {
		// Generate the yearly and monthly dirs
			$time = current_time( 'mysql' );
			$y = substr( $time, 0, 4 );
			$m = substr( $time, 5, 2 );
			$upload['subdir'] = "/$y/$m";
		}

		$upload['subdir'] = '/gp_texts_docs' . $upload['subdir'];
		$upload['path']   = $upload['basedir'] . $upload['subdir'];
		$upload['url']    = $upload['baseurl'] . $upload['subdir'];

		return $upload;
	}

	public function return_member_rank_query()
	{
		$rank_query = new WP_Query(
				array(
				'post_type' => 'guild_press_rank',
				)
			);

		return $rank_query;
	}

	public function show_list_texts_docs()
	{
		//ページネーションの値を取得
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

		//ページネーションに沿ったクエリーを返す。
		$texts_docs_query = $this->get_cusom_query('guild_press_text_doc', $paged);
		$max_num_pages = $texts_docs_query->max_num_pages;

		//会員ランク一覧のwp_query取得
    	$member_rank_cat_query = $this->get_member_rank_cat_query();

    	//key->valueの形の会員ランク一覧を取得
    	$member_rank_cat_arr = $this->get_member_rank_lists($member_rank_cat_query);

    	//各カテゴリーごとに整理した教材データのリストを取得
    	$texts_docs_by_cat = $this->return_texts_docs_items( $texts_docs_query );

    	$html = '';
    	foreach ($texts_docs_by_cat as $lesson_cat_name => $lesson_cats) {

    		for ($k=0; $k < count($lesson_cats); $k++) {

				//表示するファイル名を取得
    			$gp_texts_docs_title = $this->return_file_name( $lesson_cats[$k]['txts_docs_id'] );

    			if( $k === 0 ) {

    				$lesson_cat_name = ( $lesson_cat_name === 'none' ) ? 'その他' : $lesson_cat_name;
    				$html .= '<h2 class="lesson_docs_title">'.urldecode( $lesson_cat_name ).'</h2>';
    			}

    			if( $lesson_cats[$k]['guild_press_rank_check'][0] === "2" ) {

    				$serialized_lesson_cats = $lesson_cats[$k]['guild_press_block_texts_docs_rank'][0];

    				$current_user_id = $this->wpfunc->get_current_user_id();
    				$gp_member_rank = $this->wpfunc->get_user_meta( $current_user_id, 'gp_member_rank' );
    				$gp_member_rank = $this->basic->check_associative_array($gp_member_rank);

    				$member_rank_result = $this->member_rank_check( $gp_member_rank[0], $serialized_lesson_cats );

    				if( $member_rank_result ){

    					$html .= '<div class="dwd_background mb24">';
    					$html .= '教材ダウンロード： <a href="'.$this->wpfunc->home_url().'/guild-press-ed-texts-docs-dwd/'.$lesson_cats[$k]['txts_docs_id'].'" title="">';
    					$html .= $this->wpfunc->esc_html($gp_texts_docs_title);

    					$html .= '</a>';
    					$html .= '</div>';

    				}else{

							//表示用のhtml取得
    					$html .= $this->return_block_text_docs( $serialized_lesson_cats, $member_rank_cat_arr );
    				}
    			}else{

    				$html .= '<div class="dwd_background mb24">';
    				$html .= '教材ダウンロード： <a href="'.$this->wpfunc->home_url().'/guild-press-ed-texts-docs-dwd/'.$lesson_cats[$k]['txts_docs_id'].'" title="">';
    				$html .= $this->wpfunc->esc_html($gp_texts_docs_title);
    				$html .= '</a>';
    				$html .= '</div>';
    			}
    		}

		}

		//ページネーション表示。
		$html .= $this->pagination->show_pagination($paged, $max_num_pages, $range = 20);

		return $html;
	}

	public function member_rank_check( $gp_member_rank, $serialized_lesson_cats )
	{
		$texts_docs_rank_arr = unserialize( $serialized_lesson_cats );

		if( $this->wpfunc->current_user_can( 'administrator' ) ){

			return true;
		}else{

			$result = $this->basic->in_array( $gp_member_rank, $texts_docs_rank_arr );
			return $result;
		}

	}

	//会員ランクを[191]=>valueの値で返す。
	public function return_each_rank_arr( $texts_docs_rank_arr, $member_rank_cat_arr = array() )
	{

		for ($y=0; $y < count($texts_docs_rank_arr); $y++) {

			if( $y === 0 ){

				$rank_text = $member_rank_cat_arr[$texts_docs_rank_arr[$y]];
			}else{

				$rank_text .= ','.$member_rank_cat_arr[$texts_docs_rank_arr[$y]];
			}
		}

		return $rank_text;
	}

	//unserilizeされたtexts_docs_rank_arrが配列か文字列か判断して、表示用のhtmlを返す。
	public function return_block_text_docs( $serialized_lesson_cats, $member_rank_cat_arr )
	{
		$texts_docs_rank_arr = unserialize( $serialized_lesson_cats );
		if( is_array( $texts_docs_rank_arr ) ){

			$rank_text = $this->return_each_rank_arr( $texts_docs_rank_arr, $member_rank_cat_arr );

			//表示用のHTML取得
			$html = $this->return_block_text_docs_html($rank_text);
		}else{

			$rank_text = $member_rank_cat_arr[$texts_docs_rank_arr];
			$html = $this->return_block_text_docs_html($rank_text);
		}
		return $html;
	}

	public function return_block_text_docs_html($rank_text='')
	{
		$html = '<div class="dwd_background mb24">';
		$html .= '<span>このコンテンツは'.$this->wpfunc->esc_html($rank_text).'限定コンテンツです。</span>';
		$html .= '</div>';

		return $html;
	}

	public function get_member_rank_cat_query()
	{
		$member_rank_query = new WP_Query(
				array(
				'post_type' => 'guild_press_rank',
				)
			);

		return $member_rank_query;
	}

	//各カテゴリーごとに整理した教材データのリストを返す。
	public function return_texts_docs_items( $texts_docs_query )
	{
		$args = array(
        	'taxonomy'=> 'guild_lesson_category',
    	);

		$leson_categories = $this->wpfunc->get_categories($args);
    	$leson_categories_arr = array();

		for ($i=0; $i < count($leson_categories); $i++) {

    		$leson_categories_arr[$i] = $leson_categories[$i]->slug;
    		//表示する側は名前にする。
    		$texts_docs_by_cat[$leson_categories[$i]->name] = array();
    	}


		while ( $texts_docs_query->have_posts() ) {

			$texts_docs_query->the_post();

			$txts_docs_id = get_the_ID();

			$texts_docs_meta = get_post_meta($txts_docs_id);

			//カテゴリーの中でどのキーに当てはまるかを取得する。
			$lesson_key = array_search( $texts_docs_meta['gp_lesson_category'][0], $leson_categories_arr);

			if( $lesson_key === false ){

				$texts_docs_meta['txts_docs_id'] = $txts_docs_id;
				$texts_docs_by_cat['none'][] = $texts_docs_meta;
			}else{

				$texts_docs_meta['txts_docs_id'] = $txts_docs_id;
				$texts_docs_by_cat[$leson_categories_arr[$lesson_key]][] = $texts_docs_meta;
			}

		}
		$this->wpfunc->wp_reset_postdata();

		return $texts_docs_by_cat;
	}

	//員ランクをkey->valueの形にして返す。
	public function get_member_rank_lists( $rank_cat_query )
	{
		$member_rank_arr=array();
		while( $rank_cat_query->have_posts() ) {

    		$rank_cat_query->the_post();
			$member_id = get_the_ID();
			//IDを元に、会員ランク取得
			$member_rank = get_post_meta($member_id);

			$member_rank_arr[$member_id] = $member_rank['member_rank_name'][0];
    	}

    	$this->wpfunc->wp_reset_postdata();

    	return $member_rank_arr;
	}


	//レッスン一覧に登録されている投稿をカテゴリー別で一覧を取得
	public function get_cusom_query( $post_type, $paged,$slug='' )
	{

		$query =  new WP_Query( array(
			'post_type' => $post_type,
			'posts_per_page' => 29,
			'paged' => $paged
			)
		);

		return $query;
	}

	public function check_dwd_query( $wp_query ) {

		$auth_check = $wp_query->get( 'gp_ed' );

		//google_authの場合は、auth処理を行う。
		if( $auth_check === 'texs_docs_dwd' ){

			//リクエストのチェック
			$request_method = ( ! empty( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : 'GET' );
			$request_method_result = $this->basic->in_array( $request_method, array('GET', 'POST') );

			if( $request_method_result !== true ){
				//リクエストが異常な場合は、ホームへ返す。
				wp_redirect( $this->wpfunc->home_url() );
				exit;
			}else{

				//リファラーのチェック。
				$this->check_referer();

				//ユーザーがログインしているかチェック
				$this->check_user_login();

				//ブラウザにキャッシュを持たせない。
				$this->nochache_to_headers();

				$url_arr = explode( '/', $_SERVER['REQUEST_URI']);

				$filtered_url_arr = $this->delete_white_space_from_arr($url_arr);

				//からの配列を削除して、最後のカギを返す。
				$last_key = $this->return_last_key_of_arr($filtered_url_arr);

				//数字のみ抽出
				$dwd_texts_docs_id = $this->basic->cut_except_num($filtered_url_arr[$last_key]);

				//取得したIDから教材資料の詳細を取得
				$texts_docs_meta = $this->wpfunc->get_post_meta( $dwd_texts_docs_id );

				//ファイル名から拡張子を取得
				$gp_texts_docs_title = $this->return_file_name( $dwd_texts_docs_id );

				//絶対パスではなくて、URL形式のパスかどうかをチェックする。
				$file_path = $this->return_url_path($texts_docs_meta['gp_texts_docs_url'][0]);

				$wp_uploads     = wp_upload_dir();
				$wp_uploads_dir = $wp_uploads['basedir'];
				$wp_uploads_url = $wp_uploads['baseurl'];

				$parsed_file_path = parse_url( $file_path );
				$file_path_scheme = $parsed_file_path['scheme'];
				$file_path_path = $parsed_file_path['path'];

				$check_http = array(
					'http',
					'https',
					'ftp'
				);

				if ( ( ! isset( $file_path_scheme ) || ! in_array( $file_path_scheme,$check_http  ) ) && isset( $file_path_path ) && file_exists( $file_path_path ) ) {

					/** This is an absolute path */
					$remote_file = false;

				} elseif ( strpos( $file_path, $wp_uploads_url ) !== false ) {

					$remote_file = false;
					$file_path   = trim( str_replace( $wp_uploads_url, $wp_uploads_dir, $file_path ) );
					$file_path   = realpath( $file_path );
				}

				//ユーザーにファイルをダウンロードさせる。
				$this->dwd_texts_docs( $file_path, $gp_texts_docs_title );

			}


		}
	}

	//絶対パスではなく、URL形式のパスを返す
	public function return_url_path($target_url)
	{
		return str_replace( ABSPATH, site_url( '/', 'http' ), $target_url );
	}

	//渡された絶対パスを元にダウンロードさせる。
	public function dwd_texts_docs($file_path='', $file_name)
	{
		header('Content-Type: application/force-download');
		header('Content-Length: '.filesize($file_path));
		header('Content-disposition: attachment; filename='.$file_name);
		readfile($file_path);
		exit();
	}

	public function nochache_to_headers()
	{
		global $is_IE;

		if ( $is_IE && is_ssl() ) {
			// IE bug prevents download via SSL when Cache Control and Pragma no-cache headers set.
			header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
			header( 'Cache-Control: private' );
		} else {
			$this->wpfunc->nocache_headers();
		}
	}

	public function check_referer()
	{
		$referer = ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';

		if( $referer === '' ){

			echo '<a href="'.$this->wpfunc->home_url().'" >不正なアクセスです。ダウンロードリンクをクリックして、ダウンロードしてください。</a>';
			die();
		}
	}

	public function check_user_login()
	{
		if( ! $this->wpfunc->is_user_logged_in() ){

			echo '<a href="'.$this->wpfunc->home_url().'" >ページからログアウトしています。もう一度ログインし直して頂くようお願い致します。</a>';
			die();
		}
	}

	public function create_restriction_file($value='')
	{
		$upload_path = $this->get_upload_dir();

		$this->wpfunc->wp_mkdir_p( $upload_path );

		$htaccess_rules = $this->return_htaccess_rules();

		if ( $this->check_htaccess_exists() ) {

			$contents = @file_get_contents( $upload_path . '/.htaccess' );
			if ( $contents !== $htaccess_rules || ! $contents ) {
				// Update the .htaccess htaccess_rules if they don't match
				@file_put_contents( $upload_path . '/.htaccess', $htaccess_rules );
			}

		} elseif( $this->wpfunc->wp_is_writable( $upload_path ) ) {
			// Create the file if it doesn't exist
			@file_put_contents( $upload_path . '/.htaccess', $htaccess_rules );
		}

		// Top level blank index.php
		if ( ! file_exists( $upload_path . '/index.php' ) && $this->wpfunc->wp_is_writable( $upload_path ) ) {
			@file_put_contents( $upload_path . '/index.php', '<?php' . PHP_EOL );
		}
	}

	//stringチェック。
	public function is_string_check($value='')
	{
		return !is_string( $value ) || strlen( $value ) ;
	}

	//配列の最後のキーを返す。
	public function return_last_key_of_arr($arr)
	{
			end($arr);
			return $last_key = key($arr);
	}

	//配列の中にあるから文字を削除する。
	public function delete_white_space_from_arr($arr)
	{
		return array_filter( $arr, array( $this, 'is_string_check' ) );
	}

	public function return_htaccess_rules()
	{

		$allowed_filetypes = apply_filters( 'edd_protected_directory_allowed_filetypes', array( 'jpg', 'jpeg', 'png', 'doc', 'docm', 'docx', 'dot', 'dotm', 'dotx', 'pdf', 'txt', 'csv', 'dbf', 'xls', 'xlsb', 'xlsm', 'xlsx', 'pptx', 'pptx', 'zip' ) );

		$rules = "Options -Indexes\n";
		$rules .= "deny from all\n";
		$rules .= "<FilesMatch '\.(" . implode( '|', $allowed_filetypes ) . ")$'>\n";
		$rules .= "Order Allow,Deny\n";
		$rules .= "Allow from all\n";
		$rules .= "</FilesMatch>\n";
		return $rules;

	}

	public function change_upload_dir()
	{
		 global $pagenow;
            $referrer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
            if( $referrer != '' ) {
                $explode_1 = explode( 'page=' , $referrer );
                if( isset( $explode_1[1] ) ) {
                    $referring_page = explode( '&id=' , $explode_1[1] );
                    if( isset( $referring_page[0] ) && $referring_page[0] == 'guild_press_add_new_texts_docs' && ( 'async-upload.php' == $pagenow || 'media-upload.php' == $pagenow ) ) {
                        add_filter( 'upload_dir', array( $this, 'return_upload_dir' ) );
                    }
                }
            }
	}

	public function check_htaccess_exists() {

		$upload_path = $this->get_upload_dir();

		return file_exists( $upload_path . '/.htaccess' );

	}


	//スライドショー形式で表示するためにPDFかどうかをチェックする。
	public function check_pdf_to_show( $texts_docs_metas=array() )
	{
		if( !isset( $texts_docs_metas['gp_texts_docs_url'] ) ){

			return false;
		}
		$texts_docs_url = explode('/', $texts_docs_metas['gp_texts_docs_url'][0]);

		end($texts_docs_url);
		$last_key = key($texts_docs_url);

		if (strpos($texts_docs_url[$last_key],'pdf') !== false){

			return true;
		}else{

			return false;
		}
	}

	public function show_texts_docs( $txts_docs_id )
	{
		//教材のポストメタ取得
		$texts_docs_meta = $this->wpfunc->get_post_meta($txts_docs_id['txts_docs_id']);


		$texts_docs_metas = $this->basic->checkPostItem($texts_docs_meta);

		//拡張子がPDFかどうかをチェックする。
		$check_pdf_result = $this->check_pdf_to_show( $texts_docs_metas );

		if( isset( $texts_docs_metas['guild_press_rank_check'][0] ) && $texts_docs_metas['guild_press_rank_check'][0] === "2" ){

			//会員ランク一覧のwp_query取得
    		$member_rank_cat_query = $this->get_member_rank_cat_query();

    		//key->valueの形の会員ランク一覧を取得
    		$member_rank_cat_arr = $this->get_member_rank_lists($member_rank_cat_query);

    		//会員ランクリストをunserializeする。
    		$serialized_lesson_cats = $texts_docs_metas['guild_press_block_texts_docs_rank'][0];
    		$texts_docs_rank_arr = unserialize( $serialized_lesson_cats );

    		$current_user_id = $this->wpfunc->get_current_user_id();
    		$gp_member_rank = $this->wpfunc->get_user_meta( $current_user_id, 'gp_member_rank' );

    		$gp_member_rank = $this->basic->check_associative_array( $gp_member_rank );

    		$member_rank_result = $this->member_rank_check( $gp_member_rank[0], $serialized_lesson_cats );

    		if( $member_rank_result ){

				//wp_enqueue_scriptで登録したJSに、オブジェクトを渡す。
    			wp_localize_script( 'guild_press_ed_texts_docs_js', 'ed_texts_docs', $this->return_pdf_js_obj( $texts_docs_metas['gp_texts_docs_url'][0] ) );
    			return $this->show_pdf_html();
    		}else{

    			if( is_array( $texts_docs_rank_arr ) ){

    				$rank_text = $this->return_each_rank_arr( $texts_docs_rank_arr, $member_rank_cat_arr );
    			}else{

    				$rank_text = $serialized_lesson_cats;
    			}

    			return '<p>このコンテンツは'.$rank_text.'専用コンテンツです。</p>';
    		}
		}
		//結果がPDFだった場合は、PDFを表示する。
		else if( $check_pdf_result === true ){

			//wp_enqueue_scriptで登録したJSに、オブジェクトを渡す。
			wp_localize_script( 'guild_press_ed_texts_docs_js', 'ed_texts_docs', $this->return_pdf_js_obj( $texts_docs_metas['gp_texts_docs_url'][0] ) );

			return $this->show_pdf_html();
		}else{

			return '<p>添付されているファイルの形式がPDFではありません。</p>';
		}
	}


	//PDF出力用のhtmlを表示。
	public function show_pdf_html()
	{

		$html = '<span>Page: <span id="gp-page-num"></span> / <span id="gp-page-count"></span></span>

			<div id="gp-texts-docs-canvas-box">
				<a href="#" id="gp-prev" class="gp-nav" onclick="return false">
					<div class="gp-texts-docs-left gp-back-black-left">

						<span class="gp-arrow-position-left  glyphicon glyphicon-chevron-left"></span>

					</div>
				</a>
				<div class="" id="gp-canvas-body">
					<canvas id="gp-the-canvas"></canvas>
				</div>
				<a href="#" id="gp-next" class="gp-nav" onclick="return false">
					<div class="gp-texts-docs-right gp-back-black-right">

						<span class="go-arrow-position-right  glyphicon glyphicon-chevron-right"></span>

					</div>
				</a>
			</div>';
		return $html;
	}

	public function show_dwd_texts_docs( $txts_docs_id )
	{
		//教材のポストメタ取得
		$texts_docs_meta = $this->wpfunc->get_post_meta($txts_docs_id['txts_docs_id']);

		$texts_docs_metas = $this->basic->checkPostItem($texts_docs_meta);

		if( $texts_docs_metas['guild_press_rank_check'][0] === "2" ){

			//会員ランク一覧のwp_query取得
    		$member_rank_cat_query = $this->get_member_rank_cat_query();

    		//key->valueの形の会員ランク一覧を取得
    		$member_rank_cat_arr = $this->get_member_rank_lists($member_rank_cat_query);

    		//会員ランクリストをunserializeする。
    		$serialized_lesson_cats = $texts_docs_metas['guild_press_block_texts_docs_rank'][0];
    		$texts_docs_rank_arr = unserialize( $serialized_lesson_cats );

    		$current_user_id = $this->wpfunc->get_current_user_id();
    		$gp_member_rank = $this->wpfunc->get_user_meta( $current_user_id, 'gp_member_rank' );
    		$gp_member_rank = $this->basic->check_associative_array( $gp_member_rank );
    		$member_rank_result = $this->member_rank_check( $gp_member_rank[0], $serialized_lesson_cats );

    		if( $member_rank_result ){

    			//表示するファイル名を取得
				$gp_texts_docs_title = $this->return_file_name( $txts_docs_id['txts_docs_id'] );

				$html =	'<a href="'.$this->wpfunc->home_url().'/guild-press-ed-texts-docs-dwd/'.$txts_docs_id['txts_docs_id'].'" title="">';
				$html .=	'<div class="dwd_background">';

				$html .=	'教材ダウンロード：';
				$html .= $this->wpfunc->esc_html($gp_texts_docs_title);

				$html .='</div>';
				$html .='</a>';
				return $html;

    		}else{
    			if( is_array( $texts_docs_rank_arr ) ){

    				$rank_text = $this->return_each_rank_arr( $texts_docs_rank_arr, $member_rank_cat_arr );
    				$rank_text = $this->return_block_text_docs_html($rank_text);
    			}else{

    				$rank_text = $serialized_lesson_cats;
    				$rank_text = $this->return_block_text_docs_html($rank_text);
    			}
    		}


			return $rank_text;
		}else{

			//表示するファイル名を取得

			$gp_texts_docs_title = $this->return_file_name( $txts_docs_id['txts_docs_id'] );

			$html = '<a href="'.$this->wpfunc->home_url().'/guild-press-ed-texts-docs-dwd/'.$txts_docs_id['txts_docs_id'].'" title="">';
			$html .= '<div class="dwd_background">';

			$html .= '教材ダウンロード：';
			$html .= $this->wpfunc->esc_html($gp_texts_docs_title);
			$html .= '</div>';
			$html .= '</a>';

			return $html;

		}
	}


	//拡張子付きのファイル名を返す。
	public function return_file_name( $texts_docs_id )
	{
		$texts_docs_meta = $this->wpfunc->get_post_meta( $texts_docs_id );

		$texts_docs_metas = $this->basic->checkPostItem($texts_docs_meta);

		$gp_texts_docs_title = ( isset($texts_docs_metas['gp_texts_docs_title'][0]) && $texts_docs_metas['gp_texts_docs_title'][0] !== "" ) ? $texts_docs_metas['gp_texts_docs_title'][0] : '';

		//URLから拡張を取得
		$url_arr = explode( '/', $texts_docs_metas['gp_texts_docs_url'][0] );

		$filtered_url_arr = $this->delete_white_space_from_arr($url_arr);

			//からの配列を削除して、最後のカギを返す。
		$last_key = $this->return_last_key_of_arr($filtered_url_arr);

		//gp_texts_docs_titleがからの場合は、URLの名前を変わりに代入
		if( $gp_texts_docs_title === '' ){

			$gp_texts_docs_title = $filtered_url_arr[$last_key];
		}else{

			//文字列から拡張子を取得
			$file_extension = $this->return_extension_of_file( $filtered_url_arr[$last_key] );

			//既に拡張子が含まれているか念のためにチェックする。
			if( strpos( $gp_texts_docs_title, $file_extension ) !== false ){

				$gp_texts_docs_title = $gp_texts_docs_title;
			}else{

				$gp_texts_docs_title = $gp_texts_docs_title.$file_extension;
			}

		}

		return $gp_texts_docs_title;
	}

	//ファイルの拡張子を返す
	public function return_extension_of_file( $file )
	{
		$file_extensions = explode( '.', $file );

		$last_key = $this->return_last_key_of_arr($file_extensions);

		return '.'.$file_extensions[$last_key];
	}

	//jsにオブジェクトとして値を渡す。
	public function return_pdf_js_obj( $texts_docs_url )
	{
		return array(
			'worker_src' => TEMP_DIR_URI.'/assets/plugins/ed_texts_documents_uploader/lib/js/pdf/pdf.worker.js',
			'cmap_url' => TEMP_DIR_URI.'/assets/plugins/ed_texts_documents_uploader/lib/js/pdf/cmap/',
			'texts_docs_url' => $this->wpfunc->esc_url( $texts_docs_url )
		);

	}

}
