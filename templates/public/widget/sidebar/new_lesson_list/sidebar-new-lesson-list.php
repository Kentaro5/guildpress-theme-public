<div class="widget-box">
    <h2 class="widget-title"><?php echo $this->wpfunc->esc_html( $gp_data['new_lesson_list_title'] ); ?></h2>
    <?php if( $gp_data['query']->have_posts() ) : ?>
        <?php while ( $gp_data['query']->have_posts() ) : $gp_data['query']->the_post(); ?>

            <a href="<?php echo $this->wpfunc->esc_url( get_the_permalink() ); ?>" class="lesson_link" >
                <div class="widget-detail-box widget-detail-lesson">
                    <div class="lesson-list-detail-desc-box">
                        <h2><?php echo $this->wpfunc->esc_html( get_the_title() ); ?></h2>
                        <p><?php echo  $this->wpfunc->esc_html( get_the_excerpt() ); ?></p>
                    </div>
                </div>
            </a>

        <?php endwhile; ?>
    <?php endif; ?>
</div>