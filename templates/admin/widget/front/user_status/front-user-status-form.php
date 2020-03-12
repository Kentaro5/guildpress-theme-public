<p>
    <label for="<?php echo $gp_data['my_page_url_id']; ?>">マイページのURL:</label>
    <input class="widefat" id="<?php echo $gp_data['my_page_url_id']; ?>" name="<?php echo $gp_data['my_page_url_name']; ?>" type="text" value="<?php echo $this->wpfunc->esc_attr( $gp_data['my_page_url'] ); ?>">
</p>