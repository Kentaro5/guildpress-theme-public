<div class="form-box mb0">
    <form class="form-signin" name="form"
          action="<?php echo $this->wpfunc->esc_url($this->wpfunc->home_url($gp_data['wp_request'])); ?>" method="post"
          id="<?php echo SLUGNAME . '_calender_form'; ?>">
        <div id="user-edit-info">
            <div class="wrap">

                <h2 class="form-title center">ユーザー情報編集ページ</h2>

                <?php if ($gp_data['user_avatar_flg']) : ?>
                    <div class="form-parts">
                        <div class="center">
                            <a href="<?php echo $this->wpfunc->esc_url($gp_data['user_img_edit_url']); ?>"
                               class="form-btn btn_design">アイコンの更新はこちら</a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-parts">

                    <?php if (isset($gp_data['error_msg']['last_name']) && $gp_data['error_msg']['last_name'] !== "") : ?>
                        <p style="color:red;"><?php echo $this->wpfunc->esc_html($gp_data['error_msg']['last_name']); ?></p>
                    <?php endif; ?>

                    <p class="form-text">姓</p>
                    <input type="text" class="form-input" id="first_name" name="first_name"
                           value="<?php echo $this->wpfunc->esc_html($gp_data['first_name']); ?>">

                </div>

                <div class="form-parts">

                    <?php if (isset($gp_data['error_msg']['first_name']) && $gp_data['error_msg']['first_name'] !== "") : ?>
                        <p style="color:red;"><?php echo $this->wpfunc->esc_html($gp_data['error_msg']['first_name']); ?></p>
                    <?php endif; ?>

                    <p class="form-text">名</p>
                    <input type="text" class="form-input" id="last_name" name="last_name"
                           value="<?php echo $this->wpfunc->esc_html($gp_data['last_name']); ?>">

                </div>

                <div class="form-parts">

                    <?php if (isset($gp_data['error_msg']['user_email']) && $gp_data['error_msg']['user_email'] !== "") : ?>
                        <p style="color:red;"><?php echo $this->wpfunc->esc_html($gp_data['error_msg']['user_email']); ?></p>
                    <?php endif; ?>

                    <p class="form-text">メールアドレス</p>
                    <input type="text" class="form-input" id="user_email" name="user_email"
                           value="<?php echo $this->wpfunc->esc_html($gp_data['user_email']); ?>">

                </div>

                <div class="form-parts">

                    <p class="form-text">会員ランク
                        <span><?php echo $this->wpfunc->esc_html($gp_data['user_rank']); ?></span></p>

                </div>

                <?php echo $this->wpfunc->apply_filters('guildpress_rendar_edit_custom_form', $gp_data['user_info']); ?>

                <div class="center">
                    <input type="submit" name="" id="submit" class="form-btn btn_design" value="ユーザー情報更新">
                </div>

                <input type="hidden" name="guild_press_user_info_action" value="user_data_update"/>
                <input type="hidden" name="guild_press_check_back_url" value="<?php echo $gp_data['page_id']; ?>"/>


            </div>
        </div>
        <?php echo $gp_data['wp_nonce']; ?>
    </form>
</div>