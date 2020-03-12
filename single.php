<?php get_header(); ?>
<?php get_sidebar(); ?>
<div class="post">
	<div class="bg-white">
		<div class="container">
			<div class="row mt40 mb45">
				<div class="col-md-8 main_content">
					<?php if (have_posts()) : ?>
						<?php while (have_posts()) : the_post();
							/* ループ開始 */ ?>
							<h1 class="center mt0"><?php the_title(); ?></h1>
							<?php the_content(); ?>
						<?php endwhile; ?>
					<?php else : ?>
						<p>コンテンツがありません</p>
					<?php endif; ?>
				</div>
				<div class="col-md-4">
						<?php dynamic_sidebar( 'gp-side-bar-widget' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>