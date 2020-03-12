<?php
/**
*
*/
class EdTextsDocsLists
{

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action('admin_enqueue_scripts', array( $this, 'css_js_set_up' ) );

		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;


	}


	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		add_submenu_page(
					SLUGNAME.'_basic_setting',
					'教材・資料一覧',
					'教材・資料一覧',
					'administrator',
					SLUGNAME.'_texts_docs',
					array( $this, SLUGNAME.'_texts_docs_list' )
				);

	}

	//各メタボックスの内容を分岐している処理。
	public function guild_press_texts_docs_list() {

		//リストクラスがなければ読み込み
		if( ! class_exists( 'EdTextsDocsListTable' ) ) {
			require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/class/class-guild-press-ed-texts-docs-uploader-list-table.php' );
		}

		$action_url = $this->admin_url;
		$list_table = new EdTextsDocsListTable();
		$list_table->prepare_items();
		?>

		<div class="wrap">
			<h2><?php echo esc_html( 'GuildPress教材・資料一覧' ); ?></h2>
			<a href="?page=<?php echo SLUGNAME.'_add_texts_docs'; ?>" class="add-new-h2" title="">新規追加</a>

			<?php settings_errors(); ?>
			<?php $list_table->display(); ?>
		</div>
		<?php
	}

	//cssとjsをセットアップする。
	public function css_js_set_up()
	{

		// サイト共通css
		wp_enqueue_style( '2018edtexts', get_template_directory_uri().'/assets/plugins/ed_texts_documents_uploader/lib/css/style.css' );
	}

}
