<?php

class Admin_User_Delete{
    public function __construct(){

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->user_progress_model  = new Guild_Press_User_Progress_Model;
        $this->user_progress_bkg_model  = new Guild_Press_User_Progress_Bk_Model;

        $this->load();
    }

    public function load()
    {

        add_action( 'delete_user', array( $this, 'custom_delete_action' ) );
        add_action( 'admin_head', array( $this, 'test' ) );
    }

    public function delete_user_member_rank( $user_id )
    {

        $result = $this->wpfunc->delete_user_meta( $user_id, 'gp_member_rank' );

    }

    public function delete_user_lesson_progress( $user_id )
    {
        $args = array( 'custom_taxonomy' => 'guild_lesson_category' );
        $taxonomies = $this->wpfunc->get_terms( array(
            'taxonomy' => 'guild_lesson_category',
            'hide_empty' => false,
        ) );

        for ($i=0; $i < count($taxonomies); $i++) {

            $result = $this->wpfunc->delete_user_meta( $user_id, $taxonomies[$i]->slug );

            $user_progress_model_result = $this->user_progress_model->delete_data( $user_id, $taxonomies[$i]->slug );

            $user_progress_model_result = $this->user_progress_bkg_model->delete_data( $user_id, $taxonomies[$i]->slug );

        }
    }

    public function test()
    {


        $args = array( 'custom_taxonomy' => 'guild_lesson_category' );
        $taxonomies = $this->wpfunc->get_terms( array(
            'taxonomy' => 'guild_lesson_category',
            'hide_empty' => false,
        ) );

    }

    public function custom_delete_action( $user_id )
    {

        $this->delete_user_member_rank( $user_id );
        $this->delete_user_lesson_progress( $user_id );
    }

}









