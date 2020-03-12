<?php
//session_start();
class Guild_Press_Public_Register_Form
{
    public $last_name;
    public $first_name;
    public $user_name;
    public $user_email;
    public $error_msg;
    public $register_form_path;

    public function __construct(){
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->register_form_path = 'templates/public/register/paypal/paypal-user-register-form.php';
    }

    public function paypal_user_register_form( $form_meta_id )
    {
        $paypal_id = ( isset($form_meta_id['id']) && $form_meta_id['id'] !== "" ) ? $form_meta_id['id'] : '';

        if( ! $this->short_code_check( $paypal_id ) ){
            return;
        }

        global $post;

        $global_post_id = $post->ID;

        $options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

        $paypal_setting_items = $this->wpfunc->get_post_meta($paypal_id);

        $nounce_form = $this->wpfunc->wp_nonce_field( 'guild_press_register_user', 'register_user_box', false, false );

        //セッションから値を取得
        $this->set_register_user_item();

        $gp_data = array(
            'nounce_form' => $nounce_form,
            'error_msg' => $this->error_msg,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'options' => $options,
            'paypal_id' => $paypal_id,
            'global_post_id' => $global_post_id,
        );

        if( ! $file_path = $this->basic->load_template( $this->register_form_path, false ) ){
            return;
        }

        include( $file_path );

        //使用するJSを追加
        add_action( 'wp_footer',   array( $this, 'addJs' )  );
    }

    public function short_code_check( $form_meta_id )
    {
        if( $form_meta_id === 0 ){

            die("ショートコードにIDが設定されていません。");
        }else{

            if( $form_meta_id === '' ){

                return false;
            }
        }
        return true;
    }

    public function set_register_user_item()
    {
        $this->last_name = ( isset($_SESSION['last_name']) && $_SESSION['last_name'] !== "" ) ? $_SESSION['last_name'] : '';
        $this->first_name = ( isset($_SESSION['first_name']) && $_SESSION['first_name'] !== "" ) ? $_SESSION['first_name'] : '';
        $this->user_name = ( isset($_SESSION['user_name']) && $_SESSION['user_name'] !== "" ) ? $_SESSION['user_name'] : '';
        $this->user_email = ( isset($_SESSION['user_email']) && $_SESSION['user_email'] !== "" ) ? $_SESSION['user_email'] : '';
        $this->error_msg = ( isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !== "" ) ? $_SESSION['error_msg'] : '';
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