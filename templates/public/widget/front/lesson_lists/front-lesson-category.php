<div class="container">

<div class="content-box post-lists">

    <div class="post-box">

        <h2 class="post-box-title main-color">
            <?php echo $this->wpfunc->esc_html( $gp_data['category_title'] ); ?>
        </h2>
        <div class="row flex-eq-height">

            <?php if( $gp_data['query']->have_posts() ) : ?>
                <?php while ( $gp_data['query']->have_posts() ) : $gp_data['query']->the_post(); ?>

                    <?php $this->load_main_template(); ?>

                <?php endwhile; ?>
            <?php endif; ?>
        </div><!-- row -->

    </div><!-- guild-press-post-box -->
</div>

</div>








