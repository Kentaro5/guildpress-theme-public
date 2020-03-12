<?php

$general = $gp_data['general'];

 ?>
<div id="reservation-prev-next">
	<h3><?php echo $gp_data['year_month_text']; ?></h3>
	<ul class="subsubsub">
		<li><a href="<?php echo $gp_data['prev_link']; ?>"><?php echo $gp_data['prev_str']; ?></a> | </li>
		<li><a href="<?php echo $gp_data['next_link']; ?>"><?php echo $gp_data['next_str']; ?></a></li>

		<?php /*
		<li><?php echo '<a href="?page=' . SLUGNAME.'_settings' . "&gp_year=" . date('Y', $prev_month)
		. "&gp_month=" . date('n', $prev_month) . "&action=monthly\">$prev_str</a>"; ?> | </li>
		<li><?php echo '<a href="?page=' . SLUGNAME.'_settings' . "&gp_year=" . date('Y', $next_month)
		. "&gp_month=" . date('n', $next_month) . "&action=monthly\">$next_str</a>"; ?></li>
		*/?>
	</ul>
	<div class="clear"> </div>
</div>


<div class="reservation-table">
	<table class="calender_table">
		<tbody>
			<tr>
				<?php foreach( $gp_data['weeks'] as $num => $day_name ) : ?>
					<th class="calender_title calender_cell <?php echo $day_name; ?>"><?php echo $day_name; ?></th>
				<?php endforeach; ?>
			</tr>

			<?php for ($i = 0, $gp_data['day']; $i <= $gp_data['endi'] ; $i++, $gp_data['day']++) : ?>

				<?php $this_year_month_day_stamp = mktime(0, 0, 0, $gp_data['this_month'], $gp_data['day'], $gp_data['this_year']); ?>
				<?php  if ($i % 7 == 0) : ?>
					<?php echo $tr_tag = $this->basic->return_tr_tag($i); ?>
				<?php endif; ?>

				<?php  if (0 < $gp_data['day'] && $gp_data['day'] <= $days) : ?>

					<?php
						$week = strtolower($gp_data['weeks'][$i % 7]);
						$day = sprintf("%02d", $gp_data['day']);
					?>
					<td class="calendar-box calender_cell <?php echo $week; ?>">
						<div class="calendar-day <?php echo $week; ?>"><?php echo $day; ?></div>
						<div class="reservation-view">

							<?php //echo $this_year_month_day_stamp; ?>

							<?php if( isset($general[$this_year_month_day_stamp]) && count($general[$this_year_month_day_stamp]['register_task']) > 0  ) : ?>
								<?php for ($p=0; $p < count($general[$this_year_month_day_stamp]['register_task']); $p++) : ?>

									<?php $this->set_ballon_data( $general, $this_year_month_day_stamp, $p ); ?>

									<?php //ループの最後に新規登録ボタンを表示する。 ?>
									<?php if( count($general[$this_year_month_day_stamp]['register_task']) > intval($p) ) :  ?>
									<?php endif; ?>

									<?php if( $this->register_schedule_data !== false || isset( $this->schedule_title ) ) : ?>

										<?php $link = $this->get_calendar_link( $gp_data['this_month'], $gp_data['this_year'] ); ?>

										<a class="calendar-title" href="<?php echo $link; ?>" style="color:red;" id="pop_box" onmouseover="calendar_js.show_pop_up('<?php echo $this->schedule_task_id ?>')" onmouseout="calendar_js.hide_pop_up('<?php echo $this->schedule_task_id ?>')" class="p-balloon"><?php echo esc_html($this->schedule_title); ?></a>
										<div class="balloon2-top" id="pop_box_<?php echo $this->schedule_task_id ?>" style="z-index: 120000000; display:none ;">

											<p class="p-balloon">予約最大人数<?php echo esc_html($this->max_num); ?>人</p>

											<p class="p-balloon">予約人数<?php echo esc_html( $this->user_book_num ); ?>人</p>

											<?php if( !is_null($this->user_ids) && $this->user_ids !== "" ) : ?>
												<p class="p-balloon">予約ユーザー名</p>
												<?php foreach ($this->user_ids as $book_num_key => $book_user_id) : ?>

													<?php $this->set_ballon_user_data( $book_user_id ); ?>

													<p><?php echo $this->book_user_name; ?></p>
													<p class="p-balloon">予約している時間帯:<?php echo esc_html( $this->book_user_time ); ?></p>
												<?php endforeach; ?>
											<?php endif; ?>

											<p class="p-balloon">時間帯<?php echo esc_html( $this->date_times ); ?></p>
											<br/>

										</div>
									<?php endif; ?>

								<?php endfor; ?>

					<?php endif; ?>

				</div>

				<?php else : ?>
					<td class="calendar-box calender_cell no-day"></td>
				<?php endif; ?>
			<?php endfor; ?>

		</tbody>
	</table>
</div><!-- reservation-table -->
