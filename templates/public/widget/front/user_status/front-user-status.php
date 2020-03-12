<div class="container">
<div class="content-box">
	<div class="user-profile-box">
		<div class="row" >

			<div class="col-md-4">
				<div class="profile-img-box">
					<img id="profile-img" class="user-profile-img-card" src="<?php echo $gp_data['user_avator']; ?>">
					<p class="profile-user-name">
						<?php echo $this->wpfunc->esc_html( $gp_data['user']->display_name ); ?>
					</p>
					<div class="center">
						<a href="<?php echo $this->wpfunc->esc_url($gp_data['my_page_url']); ?>" title="">
							<button type="button" id="" class="btn_design">マイページへ</button>
						</a></div>
					</div>
				</div>
				<?php if( count( $gp_data['user_progress_details'] ) === 0 ) : ?>

					<p class="mb0">まだ、学習履歴がありません。</p>
				<?php else : ?>
					<?php for ($i=0; $i < count($gp_data['user_progress_details']); $i++) : ?>

						<div class="col-md-4">
							<div class="row progress-box">
								<a href="<?php echo $this->wpfunc->esc_url( $gp_data['user_progress_details'][$i]['post_link'] ); ?>" class="posts-link-text">
									<div class="col-md-4">
										<p class="mb0">
											<?php echo urldecode( $this->wpfunc->esc_html( $gp_data['user_progress_details'][$i]['lesson_category'] ) ); ?>
										</p>
									</div>
								</a>
								<div class="col-md-8">
									<div class="b-radius">
										<div class="progress" style="margin-bottom: 0px;">
											<div class="progress-bar b-radius main-color" role="progressbar" style="width: <?php echo $this->wpfunc->esc_attr($gp_data['user_progress_details'][$i]['progress_bar_num'] ); ?>%;" >
												<span class="">
													<?php echo $this->wpfunc->esc_html( $gp_data['user_progress_details'][$i]['user_data_lesson_str'] ).'/'.$this->wpfunc->esc_html( $gp_data['user_progress_details'][$i]['str_lesson_posts_str'] ); ?>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endfor; ?>
				<?php endif; ?>

			</div>
		</div><!-- user-profile-box -->
	</div>

	</div>