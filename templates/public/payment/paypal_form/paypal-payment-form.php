<form name="frm_payment_method" action= "<?php echo $gp_data['paypal_url'] ?>" method="post" class="mbt40 mb40">

    <table class="width100">
        <thead>
            <th></th>
        </thead>
        <tbody class="tbody_default">

            <tr class="border_gray">
                <th class="p2010">商品名</th>
                <td class="p2010"><?php echo $this->wpfunc->esc_html( $gp_data['item_name'] ); ?></td>
            </tr>
            <?php if( isset( $gp_data['payment'] ) && $gp_data['payment'] == "_xclick-subscriptions" ) : ?>
                <tr class="border_gray">
                    <th class="p2010">支払い間隔</th>
                    <td class="p2010">
                        <?php echo $this->wpfunc->esc_html($gp_data['payment_cycle_number']).$gp_data['payment_unit']."ごとに全".$this->wpfunc->esc_html($gp_data['payment_period'])."回払い"; ?>
                    </td>
                </tr>
            <?php endif; ?>
            <tr class="border_gray">
                <th class="p2010">金額</th>
                <td class="p2010"><?php echo $this->wpfunc->esc_html($gp_data['amount'].$gp_data['currency_symbol']); ?></td>
            </tr>
            <tr class="border_gray">
                <th class="p2010">決済手段</th>
                <td class="p2010"><img src="<?php echo $gp_data['paypal_logo']; ?>" alt="PayPal Logo"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr><th colspan="2" class="border_gray"></th></tr>
        </tfoot>

    </table>
    <p><button type="button" class="btn_desgin p2010" id="paypal_btn"><?php echo $this->wpfunc->esc_html($gp_data['submit_btn_text']); ?></button></p>

</form>
<script type="text/javascript">
    let post_meta_id = '<?php echo $gp_data['post_meta_id']; ?>';
</script>