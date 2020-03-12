<p>
    <label for="<?php echo $gp_data['new_lesson_list_title_id']; ?>">タイトル:</label>
    <input class="widefat" id="<?php echo $gp_data['new_lesson_list_title_id']; ?>" name="<?php echo $gp_data['new_lesson_list_title_name']; ?>" type="text" value="<?php echo $this->wpfunc->esc_attr( $gp_data['new_lesson_list_title'] ); ?>">
</p>

<p>
    <label for="<?php echo $gp_data['new_lesson_list_num_id']; ?>">表示する投稿数:</label>
    <input class="widefat" id="<?php echo $gp_data['new_lesson_list_num_id']; ?>" name="<?php echo $gp_data['new_lesson_list_num_name']; ?>" type="text" value="<?php echo $this->wpfunc->esc_attr( $gp_data['new_lesson_list_num'] ); ?>">
</p>
