<h2 class="">予約一覧</h2>

<p class="border_black mb30 mt30"></p>
<p><?php echo $gp_data['this_month']; ?></p>
<a href="<?php echo $gp_data['prev_link']; ?>">
    <?php echo $gp_data['prev_str']; ?>
</a>/
<a href="<?php echo $gp_data['next_link']; ?>">
    <?php echo $gp_data['next_str']; ?>
</a>
<table class="width100 bg_white">

    <thead>
        <tr>
            <th class="bg_black"><p class="p2010 white mb0">予約タイトル</p></th>
            <th class="bg_black"><p class="p2010 white bg_black mb0">予約した日付</p></th>
            <th class="bg_black"><p class="p2010 white bg_black mb0">予約した時間帯</p></th>

            <th class="bg_black"><p class="p2010 white bg_black mb0">　</p></th>
        </tr>
    </thead>

    <tbody class="border_black">
        <?php for ($i = 0; $i <= $gp_data['endi']; $i++, $gp_data['day']++)  : ?>
            <?php $mk_time = mktime(0, 0, 0, $gp_data['themonth'], $gp_data['day'], $gp_data['theyear']); ?>

            <?php if( isset($gp_data['general'][$mk_time]) && count($gp_data['general'][$mk_time]['register_task']) > 0  ) : ?>
                <?php for ($p=0; $p < count($gp_data['general'][$mk_time]['register_task']); $p++) : ?>

                    <?php $schedule_task_id = $gp_data['general'][$mk_time]['register_task'][$p];  ?>
                    <?php $register_schedule_data = $this->wpfunc->get_option($schedule_task_id); ?>

                    <?php $google_event_id = ( isset($register_schedule_data['google_event_id']) && $register_schedule_data['google_event_id'] !== "" ) ? $register_schedule_data['google_event_id'] : ''; ?>

                    <?php $user_register_data = $this->wpfunc->get_option($schedule_task_id."_".$user_id, false); ?>

                    <?php if( $user_register_data !== false ) : ?>

                        <?php
                        $register_title = $this->wpfunc->esc_html($register_schedule_data['title']);

                        $register_time = $this->wpfunc->esc_html($user_register_data['date_time1']).'〜'.$this->wpfunc->esc_html($user_register_data['date_time2']);


                        $register_date = date('Y-m-d', $user_register_data['date_id']);
                        ?>
                        <tr>
                            <td><p class="p2010 mb0"><?php echo $register_title; ?></p></td>

                            <td><p class="p2010 mb0"><?php echo $register_date; ?></p></td>

                            <td><p class="p2010 mb0"><?php echo $register_time; ?></p></td>

                            <td class="center"><a href="#" onclick="public_js.delete_schedule('<?php echo $mk_time ?>','<?php echo $schedule_task_id; ?>');return false;" class="btn btn-danger">削除</a></td>
                        </tr>
                        <input type="hidden" name="google_event_id" value="<?php echo $google_event_id; ?>" id="google_event_id<?php echo $schedule_task_id; ?>">
                        <input type="hidden" name="the_month" value="<?php echo $gp_data['month_year_time_stamp']; ?>" id="the_month<?php echo $schedule_task_id; ?>">

                        <div id="loadingAnim<?php echo $schedule_task_id; ?>" class="loadingAnim" style="display:none;">
                            <i class="loadingAnim_line"></i>
                        </div>

                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
        <?php endfor; ?>
    </tbody>
</table>