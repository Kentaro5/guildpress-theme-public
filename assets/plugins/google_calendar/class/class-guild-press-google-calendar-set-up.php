<?php
/**
*
*/
class GoogleCalendarSetUp
{

	public function __construct()
	{
		$this->load();
		$this->wpfunc = new WpFunc;
		$this->google_model = new GoogleCalendarModel();
	}

	public function load()
	{

		//スケジュールが登録される時に処理を行う。
		add_filter( 'rewrite_rules_array',array( $this, 'add_google_auth_query' ) );
		add_action( 'admin_init', array( $this, 'add_google_auth_url' ) );
		add_filter( 'query_vars', array( $this, 'add_original_query' ) );

	}

	public function add_original_query( $original_vars )
	{
		//$wp_query->get('guildpress_auth')で取得するために、入れる。
		$additional_vars = array( 'guildpress_auth' );
		return array_merge( $original_vars, $additional_vars );
	}

	public function add_google_auth_url()
	{
		$rules = get_option( 'rewrite_rules' );

		//まだ、オリジナルのディレクトリ構造がなかった場合は、新しく加える。
		if ( ! isset( $rules['^(guild-press-google-auth)/?$'] ) || ! isset( $rules['^(guild-press-google-auth)/([^/]+)/?$'] ) ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}

	}

	public function add_google_auth_query( $rules )
	{
		$newrules = array();
		$newrules = array(
				"^(guild-press-google-auth)/?$" => 'index.php?guildpress_auth=google_auth',
				"^(guild-press-google-auth)/([^/]+)/?$" => 'index.php?guildpress_auth=google_auth',
			);
		return $newrules + $rules;
	}

}


