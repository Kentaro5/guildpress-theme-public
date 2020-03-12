<?php get_header(); ?>
<?php get_sidebar(); ?>
<div class="container">
	<?php if( have_posts()) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="row bg-white mb40 pt40 pb40 pr20 pl20">
				<div class="col-md-4">
					<div class="img_width100">
						<?php $thumbnail = get_the_post_thumbnail(); ?>
						<?php if( $thumbnail !== "" ) : ?>

							<?php echo $thumbnail; ?>
						<?php else : ?>

								<img src="<?php echo TEMP_DIR_URI.'/assets/img/no-image.png'; ?>" alt="">
						<?php endif; ?>
					</div>
				</div>
				<div class="col-md-8">
						<h2 class="page_title mt0 font20 black bold pt0 mb0"><?php the_title(); ?></h2>

						<p><?php echo get_the_excerpt(); ?></p>

						<a class="" href="<?php echo esc_url(get_page_link()); ?>">
							<button type="" class="link_design">続きを読む</button>
						</a>
				</div>
			</div>
		<?php endwhile; ?>
	<?php else : ?>
		<p>投稿がまだありません。</p>
	<?php endif; ?>

	<?php wp_reset_postdata(); ?>
	<div class="center posts_pagination">
		<?php the_posts_pagination(); ?>
	</div>
</div>
<?php get_footer(); ?>









