<?php

/**
 *
 */
class Guild_Press_Original_Form_Edit
{
    public $original_form_item;
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
        $this->cut_option = '';
        $this->action = '';
    }

    public function check_save_field_item()
    {
        $this->action = ( isset( $_POST['admin_action'] ) ) ? trim( $_POST['admin_action'] ) : '';

        if( $this->action === 'guildpresseditfield' ){

            return $this->get_edit_field_item();
        }

    }
    public function save_field_settings(){

        register_setting( 'guild_press_regsiter_item_field', 'guild_press_regsiter_item_field', array( $this, 'check_save_field_item' ) );

    }

    public function edit_original_form_item( $options, $field_id )
    {
        $original_form_item = $this->common->get_original_form_item();

        //フィールド編集処理。
        for ($i=0; $i < count($options); $i++) {
            if( $options[$i][0] === $field_id ){

                $original_form_item[0] = $field_id;

                $x = ( $original_form_item[3] == 'checkbox' ) ? 8 : ( ( $original_form_item[3] == 'select' || $original_form_item[3] == 'file' ) ? 7 : 6 );

                for ($k=0; $k <$x+1; $k++) {
                    $options[$i][$k] = $original_form_item[$k];
                }
            }
        }
                //SESIONを破棄
        session_destroy();
        return $options;
    }


    public function get_edit_field_item()
    {
        $options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

        $this->common->set_item_to_session();

        $original_form_item = $this->common->get_original_form_item();

        $this->common->check_add_option_item();

        $this->common->set_cut_option();

        $this->common->set_original_form_item( $options );

        if ( $_POST['add_type'] == 'checkbox' ) {

            $this->common->set_check_box_form_item();
        }

        if ( $_POST['add_type'] == 'select' ) {
            $this->common->set_select_box_form_item();
        }

        $field_id = (int) sanitize_text_field( $_POST['field_id'] );

        return $this->edit_original_form_item( $options, $field_id );
    }


}