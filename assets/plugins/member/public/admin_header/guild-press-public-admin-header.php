<?php
class Guild_Press_Public_Admin_Header
{
    //管理画面のhtml margintop32pxを削除する
    public function delete_html_32px()
    {
        remove_action('wp_head', '_admin_bar_bump_cb');
    }

    public function delete_admin_bar($content)
    {
        return ( current_user_can( 'administrator' ) ) ? $content : false;
    }
}


