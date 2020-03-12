<div class="container">

<div class="content-box post-lists">

    <div class="post-box">

        <h2 class="post-box-title main-color">
            <?php echo $this->wpfunc->esc_html( $gp_data['overview_title'] ); ?>
        </h2>
        <div class="row flex-eq-height">
            <?php for ($i=0; $i < count($gp_data['post_items']); $i++) : ?>
                <?php if( $gp_data['post_items'][$i]['post_link'] !== '' ) : ?>

                    <?php
                        $gp_data['post_id'] = $gp_data['post_items'][$i]['post_id'];

                        //inputの名前を設定。
                        $guild_press_desc_name = 'guild_press_overview_page_desc';
                        $guild_press_desc_value = $this->wpfunc->get_post_meta( $gp_data['post_id'], $guild_press_desc_name, true );
                        $gp_data['post_desc'] = $gp_data['post_items'][$i]['post_desc'];
                        $gp_data['post_overview_desc'] = $guild_press_desc_value;
                        $gp_data['post_link'] = $gp_data['post_items'][$i]['post_link'];
                        $gp_data['post_thumb'] = $gp_data['post_items'][$i]['post_thumb'];
                        $gp_data['post_title'] = $gp_data['post_items'][$i]['post_title'];

                    ?>

                    <div class="col-xs-12 col-md-<?php echo $gp_data['col_num'][$i] ?> post-lists">
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
                                        <?php echo $this->wpfunc->esc_html( $gp_data['post_overview_desc'] ) ?>
                                    </p>

                                    <p class="single-post-link-text mb0">
                                        <?php echo $this->wpfunc->esc_html( $gp_data['link_text'][$i] ); ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="col-xs-12 col-md-<?php echo $gp_data['col_num'][$i] ?> post-lists">
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div><!-- row -->

    </div><!-- guild-press-post-box -->
</div>

</div>








