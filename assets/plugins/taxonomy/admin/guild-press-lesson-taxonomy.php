<?php
/**
*
*/
class Guild_Press_Lesson_Taxnomy
{

	public function __construct()
	{
		$this->user_progress_db = new Guild_Press_User_Progress_Model();
        $this->user_progress_db_backup = new Guild_Press_User_Progress_Bk_Model();
		$this->load();
	}

	public function load()
	{
		add_action( 'init', array( $this, 'set_lesson_taxonomy' ), 0 );
		add_action( 'delete_term_taxonomy', array( $this, 'delete_lesson_taxonomy' ) );
	}

	public function delete_lesson_taxonomy( $taxonomy_id )
	{
		$taxonomy = 'guild_lesson_category';
		$term = get_term_by('term_taxonomy_id', $taxonomy_id , 'guild_lesson_category', 'ARRAY_A', 'raw' );

		if( isset( $term['slug'] ) ){
			$this->user_progress_db->delete_lesson_by_taxnomy_name( $term['slug'] );
			$this->user_progress_db_backup->delete_lesson_by_taxnomy_name( $term['slug'] );
		}

	}

	//レッスンカテゴリー一覧
	public function set_lesson_taxonomy()
	{

		$labels = array(
			'name'                       => _x( 'レッスンカテゴリー', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'レッスンカテゴリー', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'レッスンカテゴリー', 'text_domain' ),
			'all_items'                  => __( 'All Items', 'text_domain' ),
			'parent_item'                => __( 'Parent Item', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
			'new_item_name'              => __( 'New Item Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Item', 'text_domain' ),
			'edit_item'                  => __( 'Edit Item', 'text_domain' ),
			'update_item'                => __( 'Update Item', 'text_domain' ),
			'view_item'                  => __( 'View Item', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Items', 'text_domain' ),
			'search_items'               => __( 'Search Items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No items', 'text_domain' ),
			'items_list'                 => __( 'Items list', 'text_domain' ),
			'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
		);


		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'query_var' => true,
		);
		register_taxonomy( 'guild_lesson_category', array( 'guild_lesson_detail', 'guild_lesson' ), $args );

	}

}
