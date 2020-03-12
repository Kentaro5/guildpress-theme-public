<?php


class User_Register_Form
{
    public function __construct()
    {
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->user_register_form_template = 'templates/public/register/normal/normal-user-register-form.php';
    }
    public function user_register_form()
    {
        ob_start();
        $this->get_user_register_form();
        $user_regsiter_form = ob_get_contents();
        ob_end_clean();

        return $user_regsiter_form;
    }
    public function get_user_register_form()
    {

        $nounce_form = $this->wpfunc->wp_nonce_field( 'guild_press_register_user', 'register_user_box', false, false );
        $options = $this->wpfunc->get_option(SLUGNAME.'_regsiter_item_field');

        //セッションから値を取得
        $last_name = ( isset($_SESSION['last_name']) && $_SESSION['last_name'] !== "" ) ? $_SESSION['last_name'] : '';

        $first_name = ( isset($_SESSION['first_name']) && $_SESSION['first_name'] !== "" ) ? $_SESSION['first_name'] : '';

        $user_name = ( isset($_SESSION['user_name']) && $_SESSION['user_name'] !== "" ) ? $_SESSION['user_name'] : '';

        $user_email = ( isset($_SESSION['user_email']) && $_SESSION['user_email'] !== "" ) ? $_SESSION['user_email'] : '';

        $error_msg = ( isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !== "" ) ? $_SESSION['error_msg'] : '';

        global $post;
        $global_post_id = $post->ID;

        $gp_data = array(
            'error_msg' => $error_msg,
            'nounce_form' => $nounce_form,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_name' => $user_name,
            'user_email' => $user_email,
            'options' => $options,
            'global_post_id' => $global_post_id,
        );

        if( ! $file_path = $this->basic->load_template( $this->user_register_form_template, false ) ){

            return;
        }

        include( $file_path );

        add_action( 'wp_footer',   array( $this, 'addJs' )  );
    }

    public function show_user_error($error_msg='')
    {

         if( isset($error_msg['user_error']) && count($error_msg['user_error']) >= 1 ){

            for ($i=0; $i < count($error_msg['user_error']); $i++) {

                echo '<p style="color:red;">'.$error_msg['user_error'][$i] .'</p>';
            }
        }
    }

     public function addJs()
    {
        ?>
        <script type="text/javascript">
            public_js.register_user_register_form_event();
        </script>
        <?php
    }
}