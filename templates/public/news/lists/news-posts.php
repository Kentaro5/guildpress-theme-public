
<div class="content-box post-lists">

    <div class="post-box">

        <h2 class="my-page-user-title"><?php echo $this->wpfunc->esc_html( $gp_data['title'] ); ?></h2>

        <?php while ( $gp_data['new_posts_query']->have_posts() ) : $gp_data['new_posts_query']->the_post(); ?>

            <a href="<?php echo $this->wpfunc->esc_url( the_permalink() ); ?>" title="">
                <p class="news-post-text">
                    <?php echo $this->wpfunc->esc_html( the_title() ); ?>
                </p>
            </a>
        <?php endwhile; ?>
    </div>
</div>
