<!-- ヘッダー部分 -->

<div id="reservation-prev-next">
	<h3><?php echo $gp_data['year_month_text'] ?></h3>
	<ul class="subsubsub">
		<li><a href="<?php echo $gp_data['prev_link']; ?>"><?php echo $gp_data['prev_str']; ?></a> | </li>
		<li><a href="<?php echo $gp_data['next_link']; ?>"><?php echo $gp_data['next_str']; ?></a></li>
	</ul>
	<div class="clear"> </div>
</div>

<!-- ヘッダー部分 -->

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

				<?php if (0 < $gp_data['day'] && $gp_data['day'] <= $days) : ?>
					<?php
					$week = strtolower($weeks[$i % 7]);
					$day = sprintf("%02d", $gp_data['day']);
					?>
					<td class="calendar-box calender_cell <?php echo $week; ?>">
						<?php //echo $this_year_month_day_stamp; ?>
						<div class="calendar-day <?php echo $week; ?>"><?php echo $day; ?></div>
						<div class="reservation-view">

							<?php $link = $this->get_calendar_register_link( $this_year_month_day_stamp, $gp_data['this_month'], $gp_data['this_year'] ); ?>
							<a href="<?php echo $this->wpfunc->esc_url( $link  ); ?>">予定を登録する。</a>

						</div>

				<?php else : ?>
					<td class="calendar-box calender_cell no-day"></td>
				<?php endif; ?>
			<?php endfor; ?>

		</tbody>
	</table>

</div><!-- reservation-table -->