<?php $this->show_user_error( $gp_data['error_msg'] ); ?>
<div id="guild_press_register_form">

    <form action="" name="f1" id="payment-form" method="post" >

        <?php echo $gp_data['nounce_form']; ?>

        <div class="form-group">

            <?php if( isset($gp_data['error_msg']['last_name']) && $gp_data['error_msg']['last_name'] !== ""  ) : ?>
                <p style="color:red;"><?php echo $this->wpfunc->esc_html( $gp_data['error_msg']['last_name'] ); ?></p>
            <?php endif; ?>

            <label for="product-title">姓 </label>
            <font color="red">*</font>
            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $this->wpfunc->esc_html( $gp_data['last_name'] ); ?>">

        </div>

        <div class="form-group">

            <?php if( isset($gp_data['error_msg']['first_name']) && $gp_data['error_msg']['first_name'] !== ""  ) : ?>
                <p style="color:red;"><?php echo $this->wpfunc->esc_html( $gp_data['error_msg']['first_name'] ); ?></p>
            <?php endif; ?>

            <label for="product-title">名 </label>
            <font color="red">*</font>
            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $this->wpfunc->esc_html( $gp_data['first_name'] ); ?>">

        </div>

        <div class="form-group">

            <?php if( isset($gp_data['error_msg']['user_name']) && $gp_data['error_msg']['user_name'] !== ""  ) : ?>
                <p style="color:red;"><?php echo $this->wpfunc->esc_html( $gp_data['error_msg']['user_name'] ); ?></p>
            <?php endif; ?>

            <label for="product-title">ユーザーネーム(半角英数字で入力して下さい。) </label>
            <font color="red">*</font>
            <input type="text" name="log" id="log" class="form-control" value="<?php echo $gp_data['user_name']; ?>">

        </div>

        <div class="form-group">

            <?php if( isset($gp_data['error_msg']['user_email']) && $gp_data['error_msg']['user_email'] !== ""  ) : ?>
                <p style="color:red;"><?php echo $this->wpfunc->esc_html( $gp_data['error_msg']['user_email'] ); ?></p>
            <?php endif; ?>

            <label for="product-title">メールアドレス(半角英数字で入力して下さい。) </label>
            <font color="red">*</font>
            <input type="text" name="user_email" id="user_email" class="form-control" value="<?php echo $gp_data['user_email']; ?>">

        </div>

        <div class="form-group">

            <?php if( isset($gp_data['error_msg']['password']) && $gp_data['error_msg']['password'] !== ""  ) : ?>
                <p style="color:red;"><?php echo $this->wpfunc->esc_html( $gp_data['error_msg']['password'] ); ?></p>
            <?php endif; ?>

            <label for="product-title">パスワード(半角英数字で入力して下さい。) </label>
            <font color="red">*</font>
            <input type="password" name="password" id="password" class="form-control" value="">

        </div>

        <?php if( isset( $gp_data['options'] ) && $gp_data['options'] ) : ?>
            <?php //必須項目は既にひょうじして ?>
            <?php for ( $i=4; $i < count($gp_data['options']); $i++ ) : ?>

                <div class="form-group">

                    <?php if( $gp_data['options'][$i][4] == 'y' ) : ?>

                        <?php if( isset($gp_data['error_msg'][$gp_data['options'][$i][2]]) && $gp_data['error_msg'][$gp_data['options'][$i][2]] !== ""  ) : ?>
                            <p style="color:red;"><?php echo $this->wpfunc->esc_html( $gp_data['error_msg'][$gp_data['options'][$i][2]] ); ?></p>
                        <?php endif; ?>

                        <label for="<?php echo $gp_data['options'][$i][2] ?>">

                            <?php echo $gp_data['options'][$i][1]; ?>

                            <?php if ( $gp_data['options'][$i][2] == 'user_email' ) : ?>
                                (半角英数字で入力して下さい。)
                            <?php endif; ?>

                            <?php if ( $gp_data['options'][$i][5] == 'y' ) : ?>
                                <font color="red">*</font>
                            <?php endif; ?>

                        </label>

                        <?php $val = ( isset( $_SESSION[ $gp_data['options'][$i][2] ] ) ) ? $_SESSION[ $gp_data['options'][$i][2] ] : ''; ?>

                        <?php
                        if( $gp_data['options'][$i][3] == 'checkbox' ){

                            $valtochk = $val;
                            $val = $gp_data['options'][$i][7];

                            if ( $gp_data['options'][$i][8] == 'y' && ! $_POST ) { $val = $valtochk = $gp_data['options'][$i][7]; }

                            echo $this->basic->guild_press_create_form( $gp_data['options'][$i][2], $gp_data['options'][$i][3], $val, $valtochk );

                        }elseif( $gp_data['options'][$i][3] == 'select' ){

                            $valtochk = $val;
                            $val = $gp_data['options'][$i][7];
                            echo $this->basic->guild_press_create_form( $gp_data['options'][$i][2], $gp_data['options'][$i][3], $val, $valtochk );

                        }else{

                            echo $this->basic->guild_press_create_form( $gp_data['options'][$i][2], $gp_data['options'][$i][3], $val );

                        }
                        ?>
                    <?php endif; ?>

                </div>
            <?php endfor; ?>
        <?php endif; ?>

        <div class="form-group">

            <input type="hidden" name="guild_press_action" value="user_register" class="form-control">

            <input type="hidden" name="global_post_id" value="<?php echo $gp_data['global_post_id']; ?>" class="form-control">

            <input type="submit" class="btn_design" value="登録" />
        </div>
    </form>
</div>

