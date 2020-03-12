<?php
if( ! class_exists( 'WP_List_Table' ) ) {
	//何かしらの変更でクラスがなくなったりした場合は、下記のバックアップを使用する。
    require_once( TEMP_DIR . '/lib/wp-list-table/class-wp-list-table.php' );
   	//require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class EdTextsDocsListTable extends WP_List_Table {


	public static function define_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'guild_press' ),
			'shortcode' => __( 'Shortcode', 'guild_press' ),
			'author' => __( 'Author', 'guild_press' ),
			'date' => __( 'Date', 'guild_press' ),
		);

		return $columns;
	}

	public function __construct() {

		parent::__construct( array(
			'singular' => 'post',
			'plural' => 'posts',
			'ajax' => false,
		) );
	}

	//テーブルのラベル定義
	public function get_columns(){
		$columns = array(
			'id' => 'ID',
			'title' => 'タイトル',
			'show_short_code' => '表示用ショートコード',
			'dwd_short_code' => 'ダウンロード用ショートコード',
			'texts_docs_date'    => '作成した日付',
		);
		return $columns;
	}

	//テーブルに表示するアイテム準備
	public function prepare_items() {

		//ページネーション数指定
		$per_page = 14;
		$current_page = $this->get_pagenum();

		$current_screen = get_current_screen();

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$args = array(
			'posts_per_page' => $per_page,
			'post_status' => 'any',
			'post_type' => 'guild_press_text_doc',
			'offset' => ( $current_page - 1 ) * $per_page,
			'orderby' => 'ID',
		);

		//各値でソート
		if ( ! empty( $_GET['orderby'] ) ) {
			switch ( strtolower( $_GET['orderby'] ) ) {

				case 'id':
					$args['orderby'] = 'id';
				break;
			}
		}

		//order順変更
		if ( ! empty( $_GET['order'] ) ) {
			if ( 'asc' == strtolower( $_GET['order'] ) ) {

				$args['order'] = 'ASC';
			} elseif ( 'desc' == strtolower( $_GET['order'] ) ) {

				$args['order'] = 'DESC';
			}
		}

		//テーブルに表示するアイテムをセット
		$this->items = $this->get_column_item( $args );


		//全てのポストのナンバーなどをセット
		$total_items = $this->count_all_posts($args);
		$total_pages = ceil( $total_items / $per_page );

		$this->set_pagination_args( array(
			'total_pages' => $total_pages,
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );

	}

	public function column_default( $item, $column_name ) {
		return '';
	}

	public function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'guild_press' ),
		);

		return $actions;
	}

	public function column_id( $item ) {

		return sprintf(
            $item->ID
        );

	}

	public function column_title( $item ) {

		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&action=%s&gp_text_doc_id=%s">Edit</a>','guild_press_add_texts_docs','edit', $item->ID ),
			'delete'    => sprintf('<a href="?page=%s&action=%s&gp_text_doc_id=%s">Delete</a>','guild_press_add_texts_docs','delete', $item->ID ),
		);

		return sprintf('%1$s %2$s', $item->gp_post_title, $this->row_actions($actions) );

	}

	public function column_show_short_code( $item ) {

		return sprintf( '[guild_press_show_texts_docs txts_docs_id="%1$s"]', $item->ID );
	}

	public function column_dwd_short_code( $item ) {

		return sprintf( '[guild_press_dwd_texts_docs txts_docs_id="%1$s"]', $item->ID );
	}



	public function column_texts_docs_date( $item ) {

		return $item->post_date;
	}

	public static function get_column_item( $args = '' ) {

		$query = new WP_Query( $args );
		return $query->posts;
	}

	public function count_all_posts($args)
	{
		$count_posts = wp_count_posts($args['post_type']);

		$draft_posts = (Int) $count_posts->draft;
		$public_posts = (Int) $count_posts->publish;

		$all_posts = $draft_posts +  $public_posts;

		return $all_posts;
	}

	//ソート用の関数
	public function get_sortable_columns() {
		$sortable_columns = array(
			'id'  => array('id',false),
		);
		return $sortable_columns;
	}

}