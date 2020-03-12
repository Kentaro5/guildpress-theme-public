<?php
/**
*
*/
class Guild_Press_User_Progress_Bk_Model
{
	public $wpfuncdb;

	public function save($args)
	{
		global $wpdb;

		$user_id = $args['user_id'];
		$taxonomy_name = urldecode( $args['taxonomy_name'] );
		$table_name = $wpdb->prefix . "guild_press_user_progress_bkup";
		$serialize_arr = serialize($args['serialize_arr']);

		$data = array(
			'user_id' => $user_id,
			'taxonomy_name' => $taxonomy_name,
			'serialize_arr' => $serialize_arr
		);

		$result = $this->check_data_exits( $user_id, $taxonomy_name, $table_name );

		if( $result !== '0' ){

			$result = $this->update_data( $table_name, $data );
		}else{

			$result = $this->insert_data( $table_name, $data );

		}

		return $result;
	}

	public function insert_data( $table_name, $insert_data )
	{
		global $wpdb;
		$user_id = $insert_data['user_id'];
		$taxonomy_name = $insert_data['taxonomy_name'];
		$serialize_arr = $insert_data['serialize_arr'];

		$result = $wpdb->insert( $table_name, array(
			'user_id' => $user_id,
			'taxonomy_name' => $taxonomy_name,
			'serialize_arr' => $serialize_arr
		),array( '%d', '%s', '%s' ) );

		return $result;

	}

	public function check_data_exits( $user_id, $taxonomy_name, $table_name )
	{
		global $wpdb;
		$query = "SELECT count(*) FROM $table_name WHERE user_id = %d and taxonomy_name = %s";
		$prepared = $wpdb->prepare($query, $user_id, $taxonomy_name);
		$result = $wpdb->get_var($prepared);

		return $result;
	}

	public function update_data( $table_name, $data )
	{
		global $wpdb;
		//更新したい行の条件
		$where = array(
			'user_id' => $data['user_id'],
			'taxonomy_name' => $data['taxonomy_name']
		);

		$update_data = array(
			'serialize_arr' => $data['serialize_arr']
		);

		return $wpdb->update($table_name, $update_data, $where);
	}

	public function delete_lesson_by_taxnomy_name( $taxonomy_name )
	{
		global $wpdb;

		$taxonomy_name = urldecode( $taxonomy_name );

		$table_name = $wpdb->prefix . "guild_press_user_progress_bkup";


		$where_query = array(
			'taxonomy_name' => $taxonomy_name
		);

		$where_format = array(
			'%s'
		);

		$result = $wpdb->delete( $table_name, $where_query, $where_format );


		return $result;

	}

	public function delete_data( $user_id, $taxonomy_name )
	{
		global $wpdb;

		$taxonomy_name = urldecode( $taxonomy_name );

		$table_name = $wpdb->prefix . "guild_press_user_progress_bkup";


		$where_query = array(
			'user_id' => $user_id,
			'taxonomy_name' => $taxonomy_name
		);

		$where_format = array(
			'%d',
			'%s'
		);

		$result = $wpdb->delete( $table_name, $where_query, $where_format );


		return $result;

	}

}