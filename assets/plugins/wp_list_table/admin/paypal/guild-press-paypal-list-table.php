<?php

if( ! class_exists( 'WP_List_Table' ) ) {
	//何かしらの変更でクラスがなくなったりした場合は、下記のバックアップを使用する。
	require_once( TEMP_DIR . '/lib/wp-list-table/class-wp-list-table.php' );
   	//require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 *
 */
class Guild_Press_PayPal_List_Table extends WP_List_Table
{

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
			'cb' => '<input type="checkbox" />',
			'id' => 'id',
			'title' => 'タイトル',
			'shortcode'    => 'ショートコード',
			'paypal_date'      => '日付'
		);
		return $columns;
	}

	//テーブルに表示するアイテム準備
	public function prepare_items() {

		$this->process_bulk_action();

		//ページネーション数指定
		$per_page = 14;
		$current_page = $this->get_pagenum();

		$current_screen = get_current_screen();

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$args = array(
			'posts_per_page' => $per_page,
			'post_status' => 'any',
			'post_type' => 'guild_press_paypal',
			'offset' => ( $current_page - 1 ) * $per_page,
			'orderby' => 'ID',
		);

		//各値でソート
		if ( ! empty( $_GET['orderby'] ) ) {
			switch ( strtolower( $_GET['orderby'] ) ) {

				case 'id':
				$args['orderby'] = 'id';
				break;

				case 'shortcode':
				$args['orderby'] = 'shortcode';
				break;

				case 'paypal_date':
				$args['orderby'] = 'paypal_date';
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

		switch ($column_name) {
			case 'id':
			case 'title':
			case 'shortcode':
			case 'paypal_date':
			return $item[$column_name];
			break;

			default:
			return 'データがありません';
			break;
		}


	}

	public function process_bulk_action()
	{

		$form_action = ( isset($_POST['action']) && $_POST['action'] !== "" ) ? $_POST['action'] : '';
		if( $form_action === 'delete' ){

			$paypal_form = ( isset($_POST['paypal_form']) && $_POST['paypal_form'] !== "" ) ? $_POST['paypal_form'] : '';

			foreach ( $paypal_form as $target_key => $target_id ) {

				wp_delete_post( intval( $target_id ), true );
			}

		}
	}

	public function get_bulk_actions() {
		$actions = array(
			'delete' => __( '削除', 'guild_press' ),
		);

		return $actions;
	}
	public function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="paypal_form[]" value="%s" />', $item->ID );

	}
	public function column_id( $item ) {

		return sprintf(
			'<span>%s</span>',
			$item->ID );

	}


	public function column_title( $item ) {

		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&action=%s&form_id=%s">Edit</a>','guild_press_edit_paypal_form','edit', $item->ID ),
			'delete'    => sprintf('<a href="?page=%s&action=%s&form_id=%s">Delete</a>','guild_press_delete_paypal_form','delete', $item->ID ),
		);

		return sprintf('%1$s %2$s', $item->post_title, $this->row_actions($actions) );

	}

	public function column_shortcode( $item ) {
		$test = get_post_meta($item->ID);

		$shortcode = sprintf( '[guild_press_paypal_user_register id="%1$d"]',
			$item->ID );


		return $shortcode;

	}

	public function column_paypal_date( $item ) {

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
			'title' => array('title',false),
			'date'   => array('date',false)
		);
		return $sortable_columns;

	}

	//テーブルの中で、隠したい項目を指定する
	public function get_hidden_columns()
	{

		return array();
	}
}

