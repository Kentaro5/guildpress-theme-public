<div class="form-box mb0">
            <form class="form-signin" name="form" action="<?php echo $this->wpfunc->esc_url($this->wpfunc->home_url( $gp_data['wp_request'] )); ?>" method="post" id="<?php echo $gp_data['form_id_name']; ?>">

                <div id="<?php echo $gp_data['id_name']; ?>">
                    <div class="wrap">

                        <h2 class="mb40">予約カレンダー編集ページ</h2>

                        <div class="form-parts">

                            <p class="form-text">時間帯指定</p>
                            <input type="text" class="form-input" id="date_time1" name="date_time1" value="<?php echo $this->wpfunc->esc_html( $gp_data['user_data']['date_time1'] ); ?>">
                            <input type="text" class="form-input" id="date_time2" name="date_time2" value="<?php echo $this->wpfunc->esc_html( $gp_data['user_data']['date_time2'] ); ?>">

                        </div>

                        <div class="form-parts">
                            <label class="align_top">コメント</label>
                            <textarea name="comment"  id="comment" class="form_textarea" rows="10" cols="30"><?php echo $this->wpfunc->esc_html( $gp_data['user_data']['comment'] ); ?></textarea>
                        </div>

                        <input type="hidden" name="guild_press_schedule_action" value="user_data_update" />

                        <input type="hidden" name="update_schedule_id" value="<?php echo $this->wpfunc->esc_html( $gp_data['user_option_id'] ); ?>" />

                        <input type="hidden" name="s_id" value="<?php echo $this->wpfunc->esc_html( $gp_data['schedule_task_id'] ); ?>" />

                        <input type="hidden" name="add_flag" value="salonpaymentfieldadd" />

                        <input type="hidden" name="the_month" value="<?php echo $this->wpfunc->esc_html( $gp_data['the_month'] ); ?>" />

                        <input type="hidden" name="date_id" value="<?php echo $this->wpfunc->esc_html( $gp_data['d_id'] ); ?>" />

                        <div class="center">
                            <input type="submit" name="" id="submit" class="form-btn btn_design" value="スケジュール編集">
                        </div>

                        <input type="hidden" name="google_event_id" value="<?php echo $gp_data['google_event_id']; ?>" />

                        <input type="hidden" name="gp_month" value="<?php echo $this->wpfunc->esc_html( $gp_data['gp_month'] ); ?>" />

                        <input type="hidden" name="gp_year" value="<?php echo $this->wpfunc->esc_html( $gp_data['gp_year'] ); ?>" />

                        <?php echo $gp_data['wp_nonce']; ?>
                    </form>
                </div>
            </div>
        </div>
