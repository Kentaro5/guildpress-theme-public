<?php

class Guild_Press_Public_Payment_Form
{

    public $amount;
    public $currency_symbol;
    public $item_name;
    public $payment_period;
    public $payment_cycle_number;
    public $payment_cycle;
    public $sandbox;
    public $submit_btn_text;
    public $paypal_url;
    public $payment_unit;
    public $payment;

    public function __construct(){
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {
        $this->payment_form_template = 'templates/public/payment/paypal_form/paypal-payment-form.php';
    }

    public function paypal_payment_form( $user_arr=array(), $form_id )
    {

        if( $form_id === 0 ){

            die("ショートコードにIDが設定されていません。");
        }else{

            $post_meta_id = ( isset($form_id['id']) && $form_id['id'] !== "" ) ? intval( $form_id['id'] ) : '';

            if( $post_meta_id === '' ){

                return;
            }
        }

        //PayPalの設定を取得
        $this->set_paypal_item( $post_meta_id );

        //サンドボックスがオンの場合はサンドボックスのURLをセットする。
        $this->set_paypal_url();

        $this->set_payment_unit();

        //決済終了後にユーザーのデータ更新やメール送信に使用する
        $current_user_id = $this->wpfunc->get_current_user_id();

        $gp_data = array(
            'paypal_url' => $this->paypal_url,
            'item_name' => $this->item_name,
            'payment' => $this->payment,
            'payment_cycle_number' => $this->payment_cycle_number,
            'payment_unit' => $this->payment_unit,
            'payment_period' => $this->payment_period,
            'amount' => $this->amount,
            'currency_symbol' => $this->currency_symbol,
            'paypal_logo' => 'https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_200x51.png',
            'submit_btn_text' => $this->submit_btn_text,
            'post_meta_id' => $post_meta_id
        );

        if( ! $file_path = $this->basic->load_template( $this->payment_form_template, false ) ){
            return;
        }

        include( $file_path );
        //使用するJSを追加
        add_action( 'wp_footer',   array( $this, 'add_paypal_form_input' )  );

    }

    public function set_paypal_url()
    {
        if( $this->sandbox === 1 ){

            $this->paypal_url = PAYPAL_SAND_URL;
        }else{

            $this->paypal_url = PAYPAL_URL;
        }
    }

    public function set_payment_unit()
    {
        if( $this->payment_cycle == "Y" ){

            $this->payment_unit = "年";
        }elseif( $this->payment_cycle == "M" ){

            $this->payment_unit = "ヶ月";
        }elseif( $this->payment_cycle == "D" ){

            $this->payment_unit = "日";
        }
    }

    public function set_paypal_item( $post_meta_id )
    {
        $posts_meta = $this->wpfunc->get_post_meta( $post_meta_id );
        $this->amount                 = ( isset($posts_meta['amount'][0]) && $posts_meta['amount'][0] !== "" ) ? $posts_meta['amount'][0] : '';
        $this->currency_symbol        = ( isset($posts_meta['currency_symbol'][0]) && $posts_meta['currency_symbol'][0] !== "" ) ? $posts_meta['currency_symbol'][0] : '';
        $this->item_name              = ( isset($posts_meta['item_name'][0]) && $posts_meta['item_name'][0] !== "" ) ? $posts_meta['item_name'][0] : '';
        $this->payment_period         = ( isset($posts_meta['payment_period'][0]) && $posts_meta['payment_period'][0] !== "" ) ? $posts_meta['payment_period'][0] : '';
        $this->payment_cycle_number   = ( isset($posts_meta['payment_cycle_number'][0]) && $posts_meta['payment_cycle_number'][0] !== "" ) ? $posts_meta['payment_cycle_number'][0] : '';
        $this->payment_cycle          = ( isset($posts_meta['payment_cycle'][0]) && $posts_meta['payment_cycle'][0] !== "" ) ? $posts_meta['payment_cycle'][0] : '';
        $this->sandbox                = ( isset($posts_meta['sandbox'][0]) && $posts_meta['sandbox'][0] !== "" ) ? intval( $posts_meta['sandbox'][0] ) : '';
        $this->submit_btn_text        = ( isset($posts_meta['submit_btn_text'][0]) && $posts_meta['submit_btn_text'][0] !== "" ) ? $posts_meta['submit_btn_text'][0] : 'クリックして支払う。';

        $this->payment        = ( isset($posts_meta['payment'][0]) && $posts_meta['payment'][0] !== "" ) ? $posts_meta['payment'][0] : '';

    }

    public function add_paypal_form_input()
    {
        ?>
        <script type="text/javascript">

            let ajax_security = '<?php echo wp_create_nonce( "guild_press_set_up_paypal" ) ?>';
            public_js.register_paypal_event( ajax_security, post_meta_id );
        </script>
        <?php
    }

}