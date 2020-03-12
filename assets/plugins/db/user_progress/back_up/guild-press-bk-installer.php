<?php 
/**
* 
*/
class Guild_Press_User_Progress_Bk_Installer
{	
	public $wpfuncdb;

	public function __construct()
	{
		$this->load();
	}

	public function load()
	{
		add_action( 'after_switch_theme', array( $this, 'installer' ) );
	}

	public static function installer() {


		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$table_name = $wpdb->prefix.'guild_press_user_progress_bkup';

		$query = $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name );
		if($wpdb->get_var( $query ) != $table_name) {

			$charset_collate = '';
			if (!empty( $wpdb->charset ) ) {
				$charset_collate = "DEFAULT CHARACTER SET ".$wpdb->charset;
			} else {
				$charset_collate = "DEFAULT CHARSET=utf8";
			}
			if (!empty($wpdb->collate)) {
				$charset_collate .= " COLLATE ".$wpdb->collate;
			}

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE " . $wpdb->prefix . "guild_press_user_progress_bkup(
			id bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			user_id bigint(20) NOT NULL,
			taxonomy_name varchar(32) NOT NULL,
			serialize_arr longtext
			)" . $charset_collate . ";";
			dbDelta( $sql );
		}

	}
}