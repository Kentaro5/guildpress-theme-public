<?php

/**
*
*/
class Guild_Press_Original_Form_Nomal
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

        }
    }

    public function save_field_settings(){

        register_setting( 'guild_press_regsiter_item_field', 'guild_press_regsiter_item_field', array( $this, 'check_save_field_item' ) );
    }

    public function get_update_original_form_item()
    {

        $options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false  );

        $k = 0;
        for ($row=0; $row < count($options) ; $row++) {
            $delete_field = "del_" . $options[$row][2];

            $delete_field = ( isset( $_POST[$delete_field] ) ) ? $_POST[$delete_field] : false;

            //フィールド処理は更新があるたびに、登録されている全ての項目を取得して、もう一度保存する。
            //削除のチェックが入っているやつだけ除外する。
            if ( $delete_field != "delete" ) {

                for ( $i = 0; $i < 4; $i++ ) {
                    $guild_press_newfields[$k][$i] = $options[$row][$i];
                }

                $guild_press_newfields[$k][0] = $k + 1;

                $display_field = $options[$row][2] . "_display";
                $require_field = $options[$row][2] . "_required";
                $checked_field = $options[$row][2] . "_checked";


                //ユーザーメールとパスワードは削除を飛ばす。
                if ( $options[$row][2] != 'user_email' && $options[$row][2] != 'password' && $options[$row][2] != 'last_name' && $options[$row][2] != 'first_name' ){
                    $guild_press_newfields[$k][4] = ( isset( $_POST[$display_field] ) ) ? $_POST[$display_field] : '';
                    $guild_press_newfields[$k][5] = ( isset( $_POST[$require_field] ) ) ? $_POST[$require_field] : '';

                } else {
                    $guild_press_newfields[$k][4] = 'y';
                    $guild_press_newfields[$k][5] = 'y';
                }

                //表示がNOで必須項目にチェックがついているのはおかしいので、エラーをだす。
                $guild_press_newfields[$k][5] = ( $guild_press_newfields[$k][4] != 'y' && $guild_press_newfields[$k][5] == 'y' ) ? 'n' : $guild_press_newfields[$k][5];

                $guild_press_newfields[$k][6] = $options[$row][6];
                $guild_press_newfields[$k][7] = ( isset( $options[$row][7] ) ) ? $options[$row][7] : '';

                if ( $options[$row][3] == 'checkbox' ) {
                    if ( isset( $_POST[$checked_field] ) && $_POST[$checked_field] == 'y' ) {
                        $guild_press_newfields[$k][8] = 'y';
                    } else {
                        $guild_press_newfields[$k][8] = 'n';
                    }
                }

                $k = $k + 1;
            }

        }

        return $guild_press_newfields;
    }


}