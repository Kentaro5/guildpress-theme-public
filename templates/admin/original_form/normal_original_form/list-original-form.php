
    <div id="guild_press_fields_metabox">
    <h3>コンテンツ設定</h3>

        <table class="widefat" id="table1">
            <tr>
                <th scope="col">追加・削除</th>
                <th scope="col">ラベル名</th>
                <th scope="col">オプション名</th>
                <th scope="col">フィールド形式</th>
                <th scope="col">表示</th>
                <th scope="col">必須</th>
                <th scope="col">初期チェック</th>
                <th scope="col">編集</th>
            </tr>
            <?php if ( ! empty( $gp_data['options'] ) )  : ?>

                <?php for( $i=0; $i < count($gp_data['options']); $i++ ) : ?>

                    <?php //テーブルの色分け ?>
                    <?php if( $i % 2 === 0 ) : ?>
                        <tr class="alternate">
                    <?php else : ?>
                        <tr class="">
                    <?php endif; ?>

                        <td width="10%">
                            <?php
                            //項目削除のチェックを入れるかどうかを分ける。
                            $can_delete = ( $gp_data['options'][$i][2] == 'user_nicename' || $gp_data['options'][$i][2] == 'display_name' || $gp_data['options'][$i][2] == 'nickname' ) ? 'y' : 'n';

                            if ( ( $can_delete == 'y' ) || $gp_data['options'][$i][6] != 'y' ) : ?>
                                <input type="checkbox" name="<?php echo "del_".$gp_data['options'][$i][2]; ?>" value="delete" />
                                削除
                            <?php endif; ?>
                        </td>

                        <td width="10%">
                        <?php

                            //ラベルの名前を表示
                            echo $gp_data['options'][$i][1];
                            //必須の場合は赤い米印を入れる。
                            if ( $gp_data['options'][$i][5] == 'y' ){ ?><font color="red">*</font><?php }
                            ?>
                        </td>
                        <td width="10%"><?php echo  $gp_data['options'][$i][2]; ?></td>
                        <td width="10%"><?php echo $gp_data['options'][$i][3]; ?></td>

                        <?php //表示や必須項目などのチェックボックス表示 ?>
                        <?php if ( $gp_data['options'][$i][2] != 'user_email' && $gp_data['options'][$i][2] != 'password' && $gp_data['options'][$i][2] != 'last_name' && $gp_data['options'][$i][2] != 'first_name' ) : ?>
                            <td width="10%">
                                <input type="checkbox" name="<?php echo $gp_data['options'][$i][2]; ?>_display" value="y" <?php if( $gp_data['options'][$i][4] == 'y' ){ ?> checked <?php } ?> />
                            </td>

                            <td width="10%"><input type="checkbox" name="<?php echo $gp_data['options'][$i][2]; ?>_required" value="y" <?php if( $gp_data['options'][$i][5] == 'y' ){ ?> checked <?php } ?> /></td>

                      <?php else : ?>

                        <td colspan="2" width="20%"><small><i><?php echo $gp_data['not_delete_text']; ?></i></small></td>

                      <?php endif; ?>

                      <td width="10%">

                        <?php //チェックボックスの場合は、最初表示した時にチェックを入れるかどうかを表示する ?>
                        <?php if( $gp_data['options'][$i][3] == 'checkbox' ) : ?>
                            <?php echo $this->basic->guild_press_create_form( $gp_data['options'][$i][2]."_checked", 'checkbox', 'y', $gp_data['options'][$i][8] ); ?>
                        <?php endif; ?>
                        </td>
                      <td width="10%">
                        <?php //ここで編集ボタンを表示。パスワードなどには編集ボタンをつけない。 ?>
                        <?php if( $gp_data['options'][$i][6] == 'y' ) : ?>

                        <?php else : ?>
                            <a href="admin.php?page=guild_press_regsiter_item_field&tab=edit&field=<?php echo $gp_data['options'][$i][3]; ?>&id=<?php echo $gp_data['options'][$i][0]; ?>" >編集</a>
                        <?php endif; ?>

                    </tr>
                <?php endfor; ?>


            <?php endif; ?>
        </table>
            <input type="submit" name="form_submit" id="normal_button" class="button button-primary" value="変更を保存" />

      <input type="hidden" name="option_page" value="<?php echo $gp_data['option_name']; ?>" />

    </div>
    </div>
</div>