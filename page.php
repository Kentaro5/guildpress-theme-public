<?php get_header(); ?>

<?php //ログインページのID取得 ?>
<?php $login_page_id = guild_press_get_login_page_id(); ?>

<?php //ログインページの場合はサイドバーをオフにする。 ?>
<?php if( !is_page( $login_page_id ) ) : ?>
	<?php get_sidebar(); ?>
<?php endif; ?>


<?php if(have_posts()) : ?>
	<?php while(have_posts()):the_post();?>
		<div class="row">
			<div class="col-xs-1 col-sm-1">
			</div>
			<div class="col-xs-10 col-sm-10 background-white" style="">
				<h1 class="page_title mb40"><?php the_title(); ?></h1>

				<?php the_content(); ?>
			</div>
			<div class="col-xs-1 col-sm-1">
			</div>
		</div>
	<?php endwhile; ?>
<?php else : ?>
	<p>投稿がまだありません。</p>
<?php endif; ?>
<?php get_footer(); ?>