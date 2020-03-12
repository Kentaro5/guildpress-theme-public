<?php while ( $gp_data['query']->have_posts() ) : $gp_data['query']->the_post();  ?>
	<?php
		$gp_data['post_link'] = get_permalink();
		$gp_data['post_thumb'] = get_the_post_thumbnail();
		$gp_data['post_desc'] = get_the_excerpt();
		$gp_data['post_title'] = get_the_title();
	?>
	<div class="row border_black pb20 lesson_lists">

		<a href="<?php echo $this->wpfunc->esc_url( $gp_data['post_link'] ); ?>">

			<h2 class="pl20 black">
				<?php echo $this->wpfunc->esc_html( $gp_data['post_title'] ); ?>
			</h2>
			<div class="col-md-4">

				<?php if( ! empty($gp_data['post_thumb']) ) : ?>
					<div class="thumbnail">

						<?php echo $gp_data['post_thumb']; ?>
					</div>
				<?php else : ?>
						<div class="thumbnail">
							<img src="<?php echo $gp_data['no_image']; ?>" alt="">
						</div>

				<?php endif; ?>
			</div>
			<div class="col-md-8 mt30">

				<div class="lesson_desc">
					<p class="black"><?php echo $this->wpfunc->esc_html( $gp_data['post_desc'] ); ?></p>
				</div>
			</div>
		</a>
	</div>

<?php endwhile; ?>
<div class="center">
<?php $this->pagination( $gp_data['query'] ); ?>
</div>