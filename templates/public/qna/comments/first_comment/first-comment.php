
<div class="area_forum first_comment">
    <div class="row">
        <div class="col-lg-12">
            <div class="list_head js-acordion-action">
                <p  class="qna-thumb-title">
                    <?php echo $this->show_title( 'Q.', $gp_data['comment_title'], 30, '...' ); ?>
                    <span  class="qna-thumb-reply-flg">
                        <?php echo $gp_data['reply_flg']; ?>
                    </span>
                    <span  class="qna-thumb-dates">
                        <?php echo $gp_data['comment_date']; ?>
                    </span>
                    <span  class="qna-thumb-user">
                        コメントしたユーザー：<?php echo $this->wpfunc->esc_html( $gp_data['comment_user'] ); ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
