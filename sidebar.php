<div class="row header" id="header">
	<header>
		<div class="col-lg-12">
			<div class="row">

				<nav id="top-menu" class="navbar navbar-default border_none" role="navigation">

					<nav class="navbar navbar-default border_none mb0">

						<a class="navbar-brand title" href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(bloginfo('name')); ?></a>
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#gnavi">
								<span class="sr-only">メニュー</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div id="gnavi" class="collapse navbar-collapse">
							<!-- トップナビメニュー -->
							<?php wp_nav_menu(
								array(
									'container' => 'ul',
									'menu_class' => 'nav navbar-nav'
								)
							);
							?>
						</nav>
					</ul>

				</nav>
			</div><!-- end ヘッダーナビ -->

		</div><!-- end .col -->
	</header>
</div>
