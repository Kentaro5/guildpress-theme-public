<?php
/**
*
*/
class EdTextsDocsController
{

	public function __construct()
	{

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->ed_texts_docs_model = new EdTextsDocsModel;
		$this->load();
	}


	public function load()
	{

		add_action( 'after_switch_theme', array( $this, 'create_restriction_file' ) );
		add_action( 'admin_init', array( $this, 'change_upload_dir' ) );
		add_action('wp_enqueue_scripts', array( $this, 'add_ed_texts_docs_js' ) );
		add_action('admin_enqueue_scripts', array( $this, 'admin_add_ed_texts_docs_js' ) );
		add_action('wp_enqueue_scripts', array( $this, 'add_pdf_js' ) );
		add_action('wp_enqueue_scripts', array( $this, 'add_css' ) );
		add_action( 'pre_get_posts', array( $this, 'check_dwd_query' ) );

		add_shortcode( 'guild_press_show_texts_docs', array( $this, 'show_texts_docs' ) );
		add_shortcode( 'guild_press_dwd_texts_docs', array( $this, 'show_dwd_texts_docs' ) );
		add_shortcode( 'guild_press_list_texts_docs', array( $this, 'show_list_texts_docs' ) );
	}

	public function show_list_texts_docs()
	{
		return $this->ed_texts_docs_model->show_list_texts_docs();
	}

	public function check_dwd_query( $wp_query ) {

		$this->ed_texts_docs_model->check_dwd_query( $wp_query );
	}

	//作成したフォルダにindex.phpとオリジナルのhtacesss作成
	public function create_restriction_file()
	{

		$this->ed_texts_docs_model->create_restriction_file();
	}

	public function change_upload_dir()
	{
		$this->ed_texts_docs_model->change_upload_dir();
	}


	public function show_texts_docs( $txts_docs_id )
	{
		//add_action( 'wp_footer', array( $this, 'add_js' ), 10 );
		return $this->ed_texts_docs_model->show_texts_docs( $txts_docs_id );
	}

	public function add_js()
	{
		?>
			<script>
				ed_texts_docs_js.show_pdf();
				ed_texts_docs_js.register_events();
			</script>
		<?php
	}

	public function show_dwd_texts_docs( $txts_docs_id )
	{
		return $this->ed_texts_docs_model->show_dwd_texts_docs( $txts_docs_id );
	}


	public function add_css()
	{
		$css_url = TEMP_DIR_URI.'/assets/plugins/ed_texts_documents_uploader/lib/css/style.css';

		wp_enqueue_style( 'guild_press_ed_texts_docs_css', $css_url, "", "20160608", true );
	}

	public function add_pdf_js()
	{
		$js_url = TEMP_DIR_URI.'/assets/plugins/ed_texts_documents_uploader/lib/js/pdf/pdf.js';

		wp_enqueue_script( 'guild_press_ed_texts_docs_pdf_js', $js_url, "", "20160608" );
	}

	public function add_ed_texts_docs_js()
	{
		$js_url = TEMP_DIR_URI.'/assets/plugins/ed_texts_documents_uploader/lib/js/ed_texts_documents_uploader.js';
		wp_enqueue_script( 'guild_press_ed_texts_docs_js', $js_url, "", "20160608", true );
	}

	public function admin_add_ed_texts_docs_js()
	{
		$js_url = TEMP_DIR_URI.'/assets/plugins/ed_texts_documents_uploader/lib/js/ed_texts_documents_uploader.js';
		wp_enqueue_script( 'guild_press_ed_texts_docs_js', $js_url, "", "20160608", false );
	}

}
