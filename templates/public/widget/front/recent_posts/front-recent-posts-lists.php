
<div class="container">

<div class="content-box post-lists">

	<div class="post-box">

		<h2 class="post-box-title main-color">
			<?php echo $this->wpfunc->esc_html( $gp_data['posts_title'] ); ?>
		</h2>
		<div class="row flex-eq-height">
			<?php if( $gp_data['query']->have_posts() ) : ?>
			<?php while ( $gp_data['query']->have_posts() ) : $gp_data['query']->the_post();
				?>

				<div class="col-xs-12 col-md-4 post-lists">
					<a href="<?php echo $this->wpfunc->esc_url( the_permalink() ); ?>" title="" class="posts-lists-link">
						<div class="single-post-box">
							<div class="single-post-content">
								<div class="center">

									<?php $thumbnail = get_the_post_thumbnail_url(); ?>
									<?php if( !empty( $thumbnail ) ) : ?>

										<img class="single-post-thumbnail" src="<?php echo $this->wpfunc->esc_url( $thumbnail ); ?>" style="width: 100%;">

									<?php else : ?>

										<img class="single-post-thumbnail" src="<?php echo $gp_data['no_image']; ?>" style="width: 100%;">

									<?php endif; ?>

								</div>
								<h2 class="single-post-title">
									<?php echo $this->wpfunc->esc_html(the_title() ); ?>
								</h2>
								<p class="single-post-text">
									<?php echo $this->wpfunc->esc_html( get_the_excerpt() ) ?>
								</p>

								<p class="single-post-link-text mb0">
									<?php echo $this->wpfunc->esc_html( $gp_data['posts_link_text'] ); ?>
								</p>
							</div>
						</div>
					</a>
				</div>

			<?php endwhile; ?>
			<?php endif; ?>
		</div><!-- row -->

	</div><!-- guild-press-post-box -->
</div>

	</div>