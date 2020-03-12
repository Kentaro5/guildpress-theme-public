<?php

/**
 *
 */
class Guild_Press_Original_Form_New
{
    public $original_form_item;
    public $add_field_err_msg;
    public $cut_option;
    public $action;
    public $admin_url;

    public function __construct()
    {
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->common = new Guild_Press_Original_Form_Common;
        $this->load();
    }

    public function load()
    {

        $this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_regsiter_item_field';
        $this->original_form_item = array();
        $this->add_field_err_msg = '';
        $this->cut_option = '';
        $this->action = '';
    }

    public function check_save_field_item()
    {
        $this->action = ( isset( $_POST['admin_action'] ) ) ? trim( $_POST['admin_action'] ) : '';
        $this->common->set_action( $this->action );

        if( $this->action === 'guildpressnormal' ){

            return $this->get_update_original_form_item();

        }elseif( $this->action === 'guildpressaddfield' ){


            return $this->get_new_field_item();
        }

    }

    public function save_field_settings(){

        register_setting( 'guild_press_regsiter_item_field', 'guild_press_regsiter_item_field', array( $this, 'check_save_field_item' ) );
    }

    public function add_new_original_form_item( $options )
    {
        $original_form_item = $this->common->get_original_form_item();
        array_push( $options, $original_form_item );

        //SESIONを破棄
        session_destroy();
        return $options;
    }

    public function get_new_field_item()
    {

        $options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

        $this->common->check_is_same_input_name( $options );

        $this->common->set_item_to_session();

        $this->common->check_add_option_item();

        $this->common->set_cut_option();

        $this->common->set_original_form_item( $options );

        if ( $_POST['add_type'] == 'checkbox' ) {

            $this->common->set_check_box_form_item();
        }

        if ( $_POST['add_type'] == 'select' ) {
            $this->common->set_select_box_form_item();
        }

        $_POST['field_id'] = ( isset($_POST['field_id']) && $_POST['field_id'] !== "" ) ? $_POST['field_id'] : '';

        $field_id = (int) sanitize_text_field( $_POST['field_id'] );

        return $this->add_new_original_form_item( $options );

    }


}