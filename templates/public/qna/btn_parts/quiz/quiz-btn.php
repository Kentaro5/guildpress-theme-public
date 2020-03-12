
<div id="respond" class="comment-respond">
    <h3 id="reply-title" class="comment-reply-title">※次の教材へ進むには下記の問題に回答して正解する必要があります。</h3>
    <h3 id="reply-title" class="comment-reply-title">問題</h3>
    <p><?php echo $this->wpfunc->esc_html( $gp_data['guild_press_quiz_text'] );  ?></p>
</div>

<div id="respond" class="comment-respond">
    <h3 id="reply-title" class="comment-reply-title">あなたの回答</h3>

    <div class="row">
        <section class="col-lg-12">
            <?php echo $this->return_quiz_radio_input( $gp_data['guild_press_quiz_answer_text_arr'] );  ?>
        </section>
    </div>

    <span id="gp_correct_answer" style="display:none; color:green;">正解！</span>

    <?php if( $gp_data['next_post_info'] === '' ) : ?>

        <span id="gp_correct_answer_text" style="display:none;">この章の学習はすべて完了です。</span>
    <?php else : ?>

        <span id="gp_correct_answer_text" style="display:none;">次のページへ進んでください！</span>
    <?php endif; ?>

<span id="gp_uncorrect_answer" style="display:none; color:red;">不正解！</span>
<span id="gp_uncorrect_answer_text" style="display:none;">学習を復習して、正しい答えを入力しましょう！</span>

<p class="form-submit">
    <button type="button" id="send_quiz_answer_btn" class="btn_design" >回答を送信する</button>

    <?php if( $this->basic->in_array( $gp_data['post_id_str'], $gp_data['current_user_progress_lists'], true ) ) :  ?>

        <button type="button" id="next_link_btn" class="btn_design ml30" style="display:inline;">次のページへ</button>
    <?php elseif( $gp_data['next_post_info'] !== '' ) :  ?>

        <button type="button" id="next_link_btn" class="btn_design ml30" style="display:none;">次のページへ</button>

    <?php endif; ?>
<input type="hidden" name="comment_parent" id="comment_parent" value="0">
</p>
</div>