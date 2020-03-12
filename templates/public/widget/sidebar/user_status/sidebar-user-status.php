<div class="widget-box">
    <h2 class="widget-title"><?php echo $this->wpfunc->esc_html( $gp_data['title'] ); ?></h2>
    <div class="widget-detail-box prfoile-widget">
        <div class="center">
            <img id="profile-img" class="user-profile-img-card" src="<?php echo $this->wpfunc->esc_url( $gp_data['user_avator'] ); ?>">
        </div>

        <?php if( $gp_data['user_rank'] !== '' ) :  ?>

            <p class="mb0">お名前：<?php echo $this->wpfunc->esc_html( $gp_data['user']->display_name ); ?></p>

            <p>会員ランク：<?php echo $this->wpfunc->esc_html( $gp_data['user_rank'] ); ?></p>
        <?php else : ?>
                <p>お名前：<?php echo $this->wpfunc->esc_html( $gp_data['user']->display_name ) ?></p>
        <?php endif; ?>

        <?php if( ! is_wp_error( $gp_data['page_slug'] ) ) : ?>

            <?php if( ! empty( $gp_data['user_progress_details'] ) ) : ?>

                <?php for ($i=0; $i < count( $gp_data['user_progress_details'] ); $i++) : ?>

                    <?php if( $gp_data['user_progress_details'][$i]['lesson_category'] === $gp_data['page_slug'] ) : ?>

                        <div class="user-progress-box">
                            <p class="mb0">進捗状況</p>
                            <div class="b-radius">
                                <div class="progress" style="margin-bottom: 0px;">
                                    <div class="progress-bar b-radius main-color" role="progressbar" style="width: <?php echo$gp_data['user_progress_details'][$i]['progress_bar_num']; ?>%" >
                                        <p class="mb0">　</p>
                                    </div>
                                </div>
                            </div>
                            <p class="mb0">
                                完了レッスン :<?php echo $this->wpfunc->esc_html( $gp_data['user_progress_details'][$i]['user_data_lesson_str'] ) ?>/<?php echo $this->wpfunc->esc_html( $gp_data['user_progress_details'][$i]['str_lesson_posts_str'] ); ?> 回
                            </p>
                            <p class="mb0">
                                完了率 : <?php echo $gp_data['user_progress_details'][$i]['progress_bar_num']; ?>%
                            </p>
                        </div>

                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>