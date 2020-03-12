<?php
/**
*
*/
class RankLists
{

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;


	}


	//各管理画面タブの内容の設定。settings_api使用。
	public function admin_menu() {

		add_submenu_page(
					SLUGNAME.'_basic_setting',
					'会員ランク一覧',
					'会員ランク一覧',
					'administrator',
					SLUGNAME.'_member_rank',
					array( $this, SLUGNAME.'_member_rank_list' )
				);

	}

	//各メタボックスの内容を分岐している処理。
	public function guild_press_member_rank_list() {

		//リストクラスがなければ読み込み
		if( ! class_exists( 'MemberRankListTable' ) ) {
			require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-member-rank-list-table.php' );
		}

		$action_url = $this->admin_url;
		$list_table = new MemberRankListTable();
		$list_table->prepare_items();
		?>

		<div class="wrap">
			<h2><?php echo esc_html( 'GuildPress会員ランク一覧' ); ?></h2>
			<a href="?page=<?php echo SLUGNAME.'_add_new_member_rank'; ?>" class="add-new-h2" title="">新規追加</a>

			<?php settings_errors(); ?>
			<?php $list_table->display(); ?>
		</div>
		<?php
	}


}
