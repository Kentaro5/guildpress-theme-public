<div class="widget-box">
    <h2 class="widget-title"><?php echo $this->wpfunc->esc_html( $gp_data['lesson_list_title'] ); ?></h2>
    <?php if( $gp_data['query']->have_posts() ) : ?>
        <?php while ( $gp_data['query']->have_posts() ) : $gp_data['query']->the_post(); ?>

            <?php $this->load_sidebar_template(); ?>

        <?php endwhile; ?>
    <?php endif; ?>
</div>