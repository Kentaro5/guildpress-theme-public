 <div class="row border_black pb20 bg_lock" style="">
    <h2 class="pl20 black"><?php echo $this->wpfunc->esc_html( $gp_data['post_title'] ); ?></h2>
    <div class="col-md-8">

        <div class="lesson_desc">
            <p class="black"><?php echo $this->wpfunc->esc_html( $gp_data['post_desc'] ); ?></p>

        </div>

    </div>

    <div class="col-md-4">
        <?php if( ! empty($gp_data['post_thumb']) ) : ?>

            <div class="thumbnail mbt_-20">
                <?php echo $gp_data['post_thumb']; ?>
            </div>
        <?php else : ?>

            <div class="thumbnail mbt_-20">
                <img src="<?php echo $gp_data['no_image']; ?>" alt="">
            </div>

        <?php endif; ?>
    </div>

    <div class="icon_lock">
        <span class="font100 glyphicon glyphicon-lock" aria-hidden="true"></span>
    </div>
</div>