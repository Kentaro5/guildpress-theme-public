<table class="wp-list-table widefat fixed striped users">
	<thead>
		<tr>
			<th scope="col" id="username" class="manage-column column-username column-primary sortable desc">
				<span>ユーザー名</span><span class="sorting-indicator"></span>
			</th>
			<th scope="col" id="email" class="manage-column column-email sortable desc">
				<span>メールアドレス</span><span class="sorting-indicator"></span>
			</th>
			<th scope="col" id="name" class="manage-column column-name">
				進捗状況
			</th>
		</tr>
	</thead>

	<tbody id="the-list" data-wp-lists="list:user">

		<?php foreach ($users as $users_arr_num => $users_obj) : ?>

			<?php $this->set_user_progress_info( $users_obj ); ?>

			<tr id="user-1">
				<td class="username column-username has-row-actions column-primary" data-colname="ユーザー名">
					<img alt="" src="http://2.gravatar.com/avatar/25445794ae041fcc1672d719b75b37e3?s=32&amp;d=mm&amp;r=g" srcset="http://2.gravatar.com/avatar/25445794ae041fcc1672d719b75b37e3?s=64&amp;d=mm&amp;r=g 2x" class="avatar avatar-32 photo" height="32" width="32">
					<strong>
						<a href="<?php echo $this->profile_link; ?>">
							<?php echo $users_obj->display_name; ?>
						</a>
					</strong><br>
				</td>
				<td class="email column-email" data-colname="メールアドレス">

					<?php echo $users_obj->user_email; ?>
				</td>

				<td class="email column-email" data-colname="メールアドレス">

					<?php foreach ( $this->user_lesson_progress as $taxnomy_name => $taxnomy_arr ) : ?>

						<?php $this->set_progress_bar_info( $taxnomy_name, $taxnomy_arr ); ?>

						<p><?php echo urldecode($taxnomy_name); ?></p>
						<div class="progress progress-striped" style="margin-bottom: 0px;">
							<div class="progress-bar progress-bar-success" style="width:<?php echo $this->progress_bar_num; ?>%;">
								<span class="white font16 align_left"><?php echo $this->progress_bar_num; ?> %</span>
							</div>
						</div>

					<?php endforeach; ?>

				</td>

			</tr>
		<?php endforeach; ?>

	</tbody>

	<tfoot>
	</tfoot>

</table>
<?php if( $gp_data['pagination'] == 1 ) : ?>
	<span class="pagination" aria-hidden="true"><<</span>
	<span class="pagination" aria-hidden="true"><</span>
<?php else : ?>

	<a href="<?php echo $gp_data['first_page_link']; ?>">

		<span class="pagination" aria-hidden="true"><<</span>
	</a>
	<a href="<?php echo $gp_data['prev_link']; ?>">

		<span class="pagination" aria-hidden="true"><</span>
	</a>

<?php endif; ?>
<span class="pagin-input">
	<?php echo $gp_data['now_page']; ?>
	/
	<?php echo $gp_data['total_pages']; ?>
</span>

<?php if( $gp_data['total_pages'] != $gp_data['now_page'] ) : ?>

	<a href="<?php echo $gp_data['next_link']; ?>">

		<span class="pagination abled">></span>
	</a>
	<a href="<?php echo $gp_data['last_page_link']; ?>">

		<span class="pagination abled" aria-hidden="true">>></span>
	</a>

<?php else : ?>

	<span class="pagination abled">></span>
	<span class="pagination abled" aria-hidden="true">>></span>

<?php endif; ?>