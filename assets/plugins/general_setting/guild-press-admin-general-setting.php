<?php
require_once( TEMP_DIR . '/assets/plugins/general_setting/admin/delete_default_category/guild-press-delete-default-category.php' );
require_once( TEMP_DIR . '/assets/plugins/general_setting/admin/register_form_data/guild-press-register-form-data.php' );
require_once( TEMP_DIR . '/assets/plugins/general_setting/admin/settings/guild-press-setting.php' );

/**
 *
 */
class Guild_Press_Admin_General_Setting
{

	public function __construct()
	{
		new Guild_Press_Setting();
		new Guild_Press_Delete_Default_Category();
		new Guild_Press_Register_From_Data();
	}

}