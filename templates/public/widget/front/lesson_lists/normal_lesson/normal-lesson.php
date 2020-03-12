<?php

$gp_data['post_link'] = get_the_permalink();
$gp_data['post_title'] = get_the_title();
$gp_data['post_excerpt'] = get_the_excerpt();
$gp_data['post_thumb'] = get_the_post_thumbnail_url();
$guild_press_desc_name = 'guild_press_lesson_overview_desc';
$gp_data['post_desc'] = $this->wpfunc->get_post_meta( $gp_data['post_id'], $guild_press_desc_name, true );


?>

<div class="col-xs-12 col-md-4 post-lists">
    <a href="<?php echo $this->wpfunc->esc_url( $gp_data['post_link'] ) ?>" title="" class="posts-lists-link">
        <div class="single-post-box">
            <div class="single-post-content">
                <div class="center">

                    <?php if( !empty( $gp_data['post_thumb'] ) ) : ?>

                        <img class="single-post-thumbnail" src="<?php echo $this->wpfunc->esc_url( $gp_data['post_thumb'] ); ?>" style="width: 100%;">

                    <?php else : ?>

                        <img  class="single-post-thumbnail" src="<?php echo $gp_data['no_image']; ?>" alt=""  style="width: 100%;">
                    <?php endif; ?>

                </div>
                <h2 class="single-post-title">
                    <?php echo $this->wpfunc->esc_html( $gp_data['post_title'] ) ?>
                </h2>
                <p class="single-post-text">
                    <?php echo $this->wpfunc->esc_html( $gp_data['post_desc'] ) ?>
                </p>

                <p class="single-post-link-text mb0">
                    <?php echo $this->wpfunc->esc_html( $gp_data['link_text'] ); ?>
                </p>
            </div>
        </div>
    </a>
</div>