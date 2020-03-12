<p>
    <label for="<?php echo $gp_data['recent_posts_title_id']; ?>">タイトル:</label>
    <input class="widefat" id="<?php echo $gp_data['recent_posts_title_id']; ?>" name="<?php echo $gp_data['recent_posts_title_name']; ?>" type="text" value="<?php echo $this->wpfunc->esc_attr( $gp_data['recent_posts_title'] ); ?>">
</p>

<p>
    <label for="<?php echo $gp_data['recent_posts_num_id']; ?>">表示する投稿数:</label>
    <input class="widefat" id="<?php echo $gp_data['recent_posts_num_id']; ?>" name="<?php echo $gp_data['recent_posts_num_name']; ?>" type="text" value="<?php echo $this->wpfunc->esc_attr( $gp_data['recent_posts_num'] ); ?>">
</p>

<p>

    <select name="<?php echo $gp_data['recent_posts_category_name']; ?>" style="width: 100%;">

        <option value="">表示するカテゴリーを選択してください。</option>

        <?php for ($k=0; $k < count($gp_data['cat_lists']); $k++) : ?>

           <option value="<?php echo $this->wpfunc->esc_attr( $gp_data['cat_lists'][$k]->slug ); ?>" <?php selected( $gp_data['recent_posts_category'],  $gp_data['cat_lists'][$k]->slug ); ?> >
            <?php echo $this->wpfunc->esc_attr( $gp_data['cat_lists'][$k]->name ); ?>
            </option>

       <?php endfor; ?>

   </select>
</p>
