<?php

/**
 *
 */
class Guild_Press_Delete_Default_Category
{


	public function __construct()
	{
		$this->load();
	}


	public function load()
	{
		$this->wpfunc = new WpFunc;
		add_action( 'admin_enqueue_scripts', array( $this, 'delete_taxnomy_dropdown_box' ) );
	}


	//カテゴリーの親カテゴリーを選ぶドロップダウンを削除するもの
	public function delete_taxnomy_dropdown_box()
	{

		//現在のポストタイプ取得
		$post_type = $this->wpfunc->get_post_type();

		if( $post_type === 'guild_lesson' || $post_type === 'guild_lesson_detail' ){
			//フッター部部にドロップダウン消すJSを入れる。
			add_action( 'admin_footer', array( $this, 'delete_taxnomy_dropdown_script' ) );
		}else{
			return;
		}

	}

	public function delete_taxnomy_dropdown_script()
	{
		?>
		<script type="text/javascript">
			admin_js.delete_taxnomy_dropdown();
		</script>
		<?php
	}

}