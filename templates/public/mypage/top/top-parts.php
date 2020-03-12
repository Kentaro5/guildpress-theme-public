<div class="row pb20">
    <h2 class="">完了レッスン一覧</h2>
    <p class="border_black mb30 mt30"></p>

    <?php foreach ( $gp_data['taken_lesson_list'] as $lesson_category => $taken_lesson_ids ) : ?>
        <?php
        $this->set_all_lesson_post_num( $lesson_category, $taken_lesson_ids );

        $this->set_user_taken_lesson_num( $taken_lesson_ids );

        $this->wpfunc->wp_reset_postdata();

        $progress_bar_num = $this->progress_abr_arg['progress_bar_num'];

        //レッスンの紹介ページデータを取得
        $lesson_sum = $this->basic->getGuildLessonQuery($lesson_category);
        ?>
        <?php while ( $lesson_sum->have_posts() ) : ?>
            <?php
            $lesson_sum->the_post();
            $thumbnail = get_the_post_thumbnail();
            $html_args = array(
                'str_lesson_posts_str' => $this->str_lesson_posts_str,
                'user_data_lesson_str' => $this->user_data_lesson_str,
                'progress_bar_num' => $progress_bar_num,
                'thumbnail' => $thumbnail
            );
            $this->set_post_items( $html_args );
            ?>
            <div class="col-md-12 mb30">
                <div class="bg_white">
                    <a class="decoration_none" href="<?php echo $this->wpfunc->esc_url($this->lesson_link) ?>">
                        <div class="row mb30">

                            <div class="col-md-4 mt30">
                                <div class="thumbnail">
                                    <?php echo $this->thumbnail; ?>
                                </div>

                            </div>
                            <div class="col-md-8 mt30">

                                <h2 class="pl20 black mt0"><?php echo $this->wpfunc->esc_html($this->title); ?></h2>

                                <p class="black mb0"><?php echo $this->excerpt; ?></p>

                                <p class="black mb0">全<?php echo $this->str_lesson_posts_str; ?>回</p>

                                <p class="black mb0">達成度<?php echo $this->user_data_lesson_str; ?>/<?php echo $this->str_lesson_posts_str ?></p>

                                <div class="progress progress-striped" style="margin-bottom: 0px;">
                                    <div class="progress-bar progress-bar-success" style="width:<?php echo $this->progress_bar_num ?>%">
                                        <span class="white font16 align_left"><?php echo $this->progress_bar_num; ?>%</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
        <?php $this->wpfunc->wp_reset_postdata(); ?>
    <?php endforeach; ?>
</div>