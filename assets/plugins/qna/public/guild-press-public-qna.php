<?php
require_once( TEMP_DIR . '/assets/plugins/qna/public/qna_form/guild-press-public-qna-form.php' );
/**
*
*/
class Guild_Press_Public_Qna
{
    public function __construct()
    {
        $this->basic = new Basic;
        $this->wpfunc = new WpFunc;
        $this->qna_form = new Guild_Press_Public_Qna_Form;
        $this->load();
    }

    public function load()
    {
        add_shortcode( 'guild_press_contents', array( $this->qna_form, 'qna_form' ) );
    }
}