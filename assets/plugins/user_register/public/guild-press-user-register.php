<?php
require_once( TEMP_DIR . '/assets/plugins/user_register/public/register/guild-press-user-register.php' );
require_once( TEMP_DIR . '/assets/plugins/user_register/public/register_form/guild-press-regsiter-form.php' );

class Public_User_Register{
    public function __construct(){

        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;

        $this->user_register_func = new User_Register_Func;
        $this->user_register_form = new User_Register_Form;

        $this->load();
    }

    public function load()
    {
        add_shortcode( 'guild_press_user_register', array( $this->user_register_form, 'user_register_form' ) );
        add_action( 'init', array( $this, 'get_action' ) );
    }

    //Formからアクションを受け取る
    public function get_action(){

        if( !isset( $_POST["guild_press_action"] ) || !$_POST["guild_press_action"] ){
            return;
        }

        $register_user_box = ( isset($_POST['register_user_box']) && $_POST['register_user_box'] !== "" ) ? $_POST['register_user_box'] : '';

        if( $register_user_box === '' ){

            return;
        }

        $check_nonce = $this->wpfunc->wp_verify_nonce( $_POST['register_user_box'], 'guild_press_register_user' );


        if( !$check_nonce && !isset( $_POST["register_user_box"] ) ){
            return;
        }

        $this->action = ( isset( $_POST["guild_press_action"] ) ) ? trim( $_POST["guild_press_action"] ) : '';
        $this->action_check( $this->action );
    }

    public function action_check( $action='' ){

        if( !$action || !isset( $action ) || !is_string( $action ) ){
            return;
        }

        switch ($action) {
            case 'user_register':

                $this->user_register_func->register_new_user();
            break;
        }

    }

}









