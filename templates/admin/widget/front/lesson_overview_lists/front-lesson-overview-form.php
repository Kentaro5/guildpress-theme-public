<p class="widget-overview">
    <label for="<?php echo $gp_data['overview_title_id']; ?>">タイトル:</label>
    <input class="widefat" id="<?php echo $gp_data['overview_title_id']; ?>" name="<?php echo $gp_data['overview_title_name']; ?>" type="text" value="<?php echo $this->wpfunc->esc_attr( $gp_data['overview_title'] ); ?>">
</p>

<div id="guild_press_widget_overview_block">

    <?php for( $i = 0; $i < $gp_data['loop_count']; $i++ ) : ?>
        <div id="guild_press_widget_overview_box">
            <p style="font-weight: bold;">
                レッスン概要コンテンツ1
            </p>
            <p>
                <label for="">表示するカテゴリー。</label>

                <select name="<?php echo $gp_data['overview_cat_name']; ?>[]" style="width: 100%;">

                    <option value="">表示するカテゴリーを選択してください。</option>

                    <?php for ($k=0; $k < count($gp_data['cat_lists']); $k++) : ?>

                        <option value="<?php echo $this->wpfunc->esc_attr( $gp_data['cat_lists'][$k]->slug ); ?>" <?php selected( $gp_data['overview_cat'][$i],  $gp_data['cat_lists'][$k]->slug ); ?> ><?php echo $this->wpfunc->esc_attr( $gp_data['cat_lists'][$k]->name ); ?></option>

                    <?php endfor; ?>

                </select>
            </p>

            <p>
                <label for="<?php echo $gp_data['col_num_id']; ?>">col数</label>
                <select name="<?php echo $gp_data['col_num_name']; ?>[]" style="width: 100%;">

                    <option value="">col数を選んでください。</option>

                    <?php for ($k=1; $k < 13; $k++) : ?>

                        <option value="<?php echo $k; ?>" <?php selected( $k,  $gp_data['col_num'][$i] ); ?> ><?php echo $k; ?></option>

                    <?php endfor; ?>

                </select>
            </p>

            <p>
                <label for="<?php echo $gp_data['overview_link_text_id']; ?>">リンクテキスト</label>
                <input class="widefat" id="<?php echo $gp_data['overview_link_text_id']; ?>" name="<?php echo $gp_data['overview_link_text_name']; ?>[]" type="text" value="<?php echo $this->wpfunc->esc_attr( $gp_data['overview_link_text'][$i] ); ?>">
            </p>

            <button class="button delete_guild_press_widget_overview_box">削除</button>

        </div>
    <?php endfor; ?>

</div>



<div class="add-overview-btn">
    <button type="button" class="button add_guild_press_widget_overview_box">ボックスを追加</button>
</div>















