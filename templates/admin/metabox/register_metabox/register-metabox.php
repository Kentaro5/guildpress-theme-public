<p>
	<input type="checkbox" id="guild_press_register_page_check" name="guild_press_register_page_check" value="1" <?php checked( get_post_meta( $gp_data['post_id'], $gp_data['meta'], true ), "1" ); ?> />
	<label for="guild_press_register_page_check"><?php echo $gp_data['text']; ?></label>
</p>