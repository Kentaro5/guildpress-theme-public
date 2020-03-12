<div class="footer_bkg">

	<div class="footer_wiget">

		<div class="flex_gorw2">

			<?php if ( ! is_active_sidebar('gp-footer-widget-area') ) : ?>
				<?php dynamic_sidebar('gp-footer-widget-area'); ?>
			<?php endif; ?>


			<?php dynamic_sidebar('my_sidebar'); ?>
		</div>
	</div>

	<p class="footer_text mb0">Copyright © <?php echo esc_html(bloginfo('name')); ?> All Rights Reserved</p>


</div>
<?php wp_footer(); ?>
</div><!-- wrap -->
</body>
</html>

