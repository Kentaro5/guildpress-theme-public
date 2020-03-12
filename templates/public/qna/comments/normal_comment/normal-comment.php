<?php if( '0' !== $gp_data['current_comment']->comment_parent ) : ?>
    <?php //返信用としてブロックを分ける。 ?>
    <div class="area_forum">
    <?php endif; ?>

    <?php $this->load_comment_header( $gp_data['current_comment'] ); ?>
    <?php //コメント表示 ?>

    <span><?php echo $gp_data['current_comment']->comment_content; ?>
    <p>
        <span style="font-size:0.8em; float: right;padding-left: 15px;"><?php echo $gp_data['comment_date']; ?></span>
        <span style="font-size:0.8em; float: right;">コメントしたユーザー：<?php echo $gp_data['comment_user']; ?></span>
    </p></span>

    <?php //返信用のリンク表示。 ?>

    <?php comment_reply_link( array_merge( $args, array(
        'add_below' => $gp_data['add_below'],
        'depth' => $gp_data['depth'],
        'max_depth' => $gp_data['args']['max_depth']
    ) ) ); ?>

</div><!-- list_body js-acordion-target p30 -->
</div><!-- area_forum -->
