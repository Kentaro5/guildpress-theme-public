<div class="widget-box">
    <h2 class="widget-title"><?php echo $this->wpfunc->esc_html( $gp_data['new_lesson_list_title'] ); ?></h2>
    <?php if( $gp_data['query']->have_posts() ) : ?>
        <?php while ( $gp_data['query']->have_posts() ) : $gp_data['query']->the_post(); ?>
            <?php
                $gp_data['post_id'] = get_the_ID();
                $guild_press_desc_name = 'guild_press_overview_page_desc';
                $gp_data['post_title'] = get_the_title();
                $gp_data['post_desc'] = get_the_excerpt();
                $gp_data['post_overview_desc'] = $this->wpfunc->get_post_meta( $gp_data['post_id'], $guild_press_desc_name, true );
            ?>
            <a href="<?php echo $this->wpfunc->esc_url( get_the_permalink() ); ?>" class="lesson_link" >
                <div class="widget-detail-box widget-detail-lesson">
                    <div class="lesson-list-detail-desc-box">
                        <h2><?php echo $this->wpfunc->esc_html( $gp_data['post_title'] ); ?></h2>
                        <p><?php echo  $this->wpfunc->esc_html( $gp_data['post_overview_desc'] ); ?></p>
                    </div>
                </div>
            </a>

        <?php endwhile; ?>
    <?php endif; ?>
</div>