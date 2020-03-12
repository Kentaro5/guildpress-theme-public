<?php
/**
*
*/
class EdTextsDocsSetUpUrl
{

	public function __construct()
	{
		$this->load();
		$this->wpfunc = new WpFunc;
	}

	public function load()
	{

		//スケジュールが登録される時に処理を行う。
		add_filter( 'rewrite_rules_array',array( $this, 'add_ed_texts_dwd_query' ) );
		add_action( 'admin_init', array( $this, 'add_ed_texts_dwd_url' ) );
		add_filter( 'query_vars', array( $this, 'add_original_query' ) );

	}

	public function add_original_query( $original_vars )
	{
		//$wp_query->get('gp_ed')で取得するために、入れる。
		$additional_vars = array( 'gp_ed' );
		return array_merge( $original_vars, $additional_vars );
	}

	public function add_ed_texts_dwd_url()
	{
		$rules = get_option( 'rewrite_rules' );

		//まだ、オリジナルのディレクトリ構造がなかった場合は、新しく加える。
		if ( ! isset( $rules['^(guild-press-ed-texts-docs-dwd)/?$'] ) || ! isset( $rules['^(guild-press-ed-texts-docs-dwd)/([^/]+)/?$'] ) ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}

	}

	public function add_ed_texts_dwd_query( $rules )
	{
		$newrules = array();
		$newrules = array(
				"^(guild-press-ed-texts-docs-dwd)/?$" => 'index.php?gp_ed=texs_docs_dwd',
				"^(guild-press-ed-texts-docs-dwd)/([^/]+)/?$" => 'index.php?gp_ed=texs_docs_dwd',
			);
		return $newrules + $rules;
	}

}


