<?php
/**
*
*/
class Guild_Press_Public_Login
{
    public $login_form_path;
    public function __construct()
    {
        $this->load();
        $this->login_form_path = 'templates/public/login/login_form.php';
        $this->wpfunc = new WpFunc();
        $this->basic = new Basic();
    }

    public function load()
    {
        add_shortcode( 'guild_press_login_page', array( $this, 'login_page' ) );
    }

    public function login_page()
    {
        ob_start();
        $this->login_form();
        $login_form = ob_get_contents();
            ob_end_clean();
        return $login_form;
    }

    public function login_form()
    {
        if( ! $file_path = $this->basic->load_template( $this->login_form_path, false ) ){
            return;
        }

        include( $file_path );
    }

    public function check_error()
    {
        $error = ( isset($_GET['err']) && $_GET['err'] !== "" ) ? $_GET['err'] : '';
        ?>
        <?php if( $error !== '' ) : ?>
            <div class="error">
                <ul>
                    <li style="padding: 5px 10px; background-color:#FADBDA; list-style: none;"><?php echo $this->wpfunc->esc_html( $error ); ?></li>
                </ul>
            </div>
        <?php endif; ?>
        <?php
    }

    public function login_nonce()
    {
        $this->wpfunc->wp_nonce_field( 'guild_press_login', 'login_user', false );
    }

}