<div class="titlewrap">
	<p class="h2size" id="title-prompt-text" for="title"><?php _e( 'タイトル' ); ?></p>
	<input type="text" name="post_title" size="30" class="title_input" value="<?php echo $gp_data['title']; ?>" id="title" spellcheck="true" autocomplete="off">
</div>

<table class="form-table">

	<tr>
		<th scope="row"><label for="paypal_address"><?php _e( 'PayPalテスト環境用管理画面URL' ); ?></label></th>
		<td><a href="https://www.sandbox.paypal.com/signin/">https://www.sandbox.paypal.com/signin/</a></td>
	</tr>
	<tr>
		<th scope="row"><label for="paypal_address"><?php _e( 'PayPal本番環境用管理画面URL' ); ?></label></th>
		<td><a href="https://www.paypal.com/signin/?country.x=JP&locale.x=ja_JP">https://www.paypal.com/signin/?country.x=JP&locale.x=ja_JP</a></td>
	</tr>
	<tr>
		<th scope="row"><label for="paypal_address"><?php _e( 'ビジネスメールアドレス(半角英数字のみ入力して下さい。)' ); ?></label></th>
		<td><input name="paypal_address" type="text" id="paypal_address" value="<?php echo $gp_data['paypal_address']; ?>" class="regular-text" style="ime-mode:disabled;" oninput="only_email('paypal_address')" /></td>
	</tr>

	<tr>
		<th scope="row"><label for="submit_btn_text"><?php _e( 'ボタンの文言' ); ?></label></th>
		<td><input name="submit_btn_text" type="text" id="submit_btn_text" value="<?php echo $gp_data['submit_btn_text']; ?>" class="regular-text" style="ime-mode:disabled;" /></td>
	</tr>


	<tr>
		<th scope="row"><label for="amount"><?php _e( '課金の金額(半角数字のみ入力して下さい。)' ); ?></label></th>
		<td><input name="amount" type="text" id="amount" value="<?php echo $gp_data['amount']; ?>" class="regular-text" oninput="only_num('amount')" /><br/>注意点：課金金額が10万円を超える場合は、paypalで決済をするユーザー側の本人確認が必要となります。</td>
	</tr>


	<tr>
		<!-- 通貨を選べば自動的に円や$が表示されるようにする。また、通貨セレクトボックスで選べるようにする。 -->
		<th scope="row"><label for="currency"><?php _e( '通貨' ); ?></label></th>
		<td>
			<select name="currency" id="currency">
				<option value="">使用する通貨を選んで下さい。</option>
				<option value="JPY" <?php echo $this->basic->check_selected($gp_data['currency'], 'JPY'); ?> >日本円</option>
				<option value="USD" <?php echo $this->basic->check_selected($gp_data['currency'], 'USD'); ?> >アメリカドル</option>
			</select>
		</td>
	</tr>
	<!-- ここはプログラム側で通貨に応じて自動で入れ替わるようにする。 -->
	<tr>
		<th scope="row"><label for="currency_symbol"><?php _e( '画面に表示する通貨' ); ?></label></th>
		<td>

			<select name="currency_symbol" id="currency_symbol">
				<option value="">使用する通貨を選んで下さい。</option>
				<option value="円" <?php echo $this->basic->check_selected($gp_data['currency_symbol'], '円'); ?> >円</option>
				<option value="$" <?php echo $this->basic->check_selected($gp_data['currency_symbol'], '$'); ?> >$</option>
			</select>
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="sandbox"><?php _e( 'テストモード' ); ?></label></th>
		<td><input name="sandbox" type="checkbox" id="sandbox" value="1" class="regular-text" style="width: auto;" <?php echo $this->basic->check_checked( $gp_data['sandbox'] ); ?> />有効</td>
	</tr>

	<tr>
		<th scope="row"><label for="paypal_lang"><?php _e( 'paypalの決済画面で表示する言語を選択して下さい。' ); ?></label></th>
		<td>
			<select name="paypal_lang" id="paypal_lang">
				<option value="">言語を選んで下さい。</option>
				<option value="ja_JP" <?php echo $this->basic->check_selected($gp_data['paypal_lang'], 'ja_JP'); ?> >日本語</option>
				<option value="US" <?php echo $this->basic->check_selected($gp_data['paypal_lang'], 'US'); ?> >英語</option>
			</select>

		</td>
	</tr>
	<tr>
		<th scope="row"><label for="item_name"><?php _e( '決済の画面で表示する商品名を決めて下さい。' ); ?></label></th>
		<td>
			<input name="item_name" type="text" id="item_name" value="<?php echo $this->wpfunc->esc_html( $gp_data['item_name'] ); ?>" class="regular-text" />
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="payment"><?php _e( '決済の種類を選択して下さい。' ); ?></label></th>
		<td>
			<select name="payment" id="payment">
				<option value="default">決済の種類を選んで下さい。</option>
				<option value="_xclick" <?php echo $this->basic->check_selected($gp_data['payment'], '_xclick'); ?> >単発決済</option>
				<option value="_xclick-subscriptions" <?php echo $this->basic->check_selected($gp_data['payment'], '_xclick-subscriptions'); ?> >継続課金</option>

			</select>
		</td>
	</tr>

	<?php if( $rank_query->have_posts() ) : ?>
		<tr>
			<th scope="row"><label for="member_rank"><?php _e( '紐づけする会員ランクを選択してください。' ); ?></label></th>
			<td>
				<select name="member_rank" id="member_rank">
					<option value="">会員ランクを選択してください。</option>
					<?php while( $rank_query->have_posts() ) : ?>

						<?php
						$rank_query->the_post();
						$member_id = get_the_ID();
						//IDを元に、会員ランク取得
						$member_rank = get_post_meta( $member_id );
						?>
						<option value="<?php echo $member_id; ?>" <?php echo $this->basic->check_selected(intval( $gp_data['selected_member_rank'] ), $member_id ); ?>>
							<?php echo $this->wpfunc->esc_html($member_rank['member_rank_name'][0]); ?>
						</option>

					<?php endwhile; ?>

				</select>
			</td>
		</tr>
	<?php endif; ?>

	<tr id="payment_period_desc" <?php if( ! $gp_data['payment'] || $gp_data['payment'] != "_xclick-subscriptions" ) : ?> style="display: none" <?php endif; ?>>
		<th scope="row" colspan="2"><label for="payment"><?php _e( '続けて継続課金をする予定がある場合は、「継続課金の回数を選択する項目」で「終了しない」を選択して下さい。例えば、１年１２ヶ月払いの決済で、ユーザーから辞めるなどの手続きを踏まないと更新される決済などの場合。' ); ?></label></th>
	</tr>
	<tr id="payment_period" <?php if( ! $gp_data['payment'] || $gp_data['payment'] != "_xclick-subscriptions" ) : ?> style="display: none" <?php endif; ?>>
		<th scope="row"><label for="payment_period"><?php _e( '継続課金の回数を選択してください。' ); ?></label></th>
		<td>
			<select name="payment_period" id="payment_period_select">
				<option value="">終了しない</option>
				<?php for ($i=1; $i < 31; $i++) : ?>
					<option value="<?php echo $i; ?>" <?php echo $this->basic->check_selected($gp_data['payment_period'], strval( $i ) ); ?> > <?php echo $i; ?> </option>
				<?php endfor; ?>
			</select>
		</td>
	</tr>

	<tr id="payment_cycle" <?php if( ! $gp_data['payment'] || $gp_data['payment'] != "_xclick-subscriptions" ) : ?> style="display: none" <?php endif; ?>>
		<th scope="row"><label for="payment_cycle"><?php _e( '継続課金のサイクルを選択してください。' ); ?></label></th>
		<td>
			<select name="payment_cycle_number" id="payment_cycle_number_select">
				<?php for ($i=0; $i < 31; $i++) : ?>
					<?php if( $i === 0 ) : ?>
						<option value="">数字を選択して下さい。</option>
						<?php else : ?>
							<option value="<?php echo $i; ?>"
								<?php echo $this->basic->check_selected( $gp_data['payment_cycle_number'], strval( $i ) ); ?> > <?php echo $i; ?>
							</option>
						<?php endif; ?>

					<?php endfor; ?>
				</select>
				<select name="payment_cycle" id="payment_cycle_select">
					<option value="">期間を選択して下さい。</option>
					<option value="D" <?php echo $this->basic->check_selected($gp_data['payment_cycle'], 'D' ); ?> >日間</option>
					<option value="W" <?php echo $this->basic->check_selected($gp_data['payment_cycle'], 'W' ); ?> >週間</option>
					<option value="M" <?php echo $this->basic->check_selected($gp_data['payment_cycle'], 'M' ); ?> >ヶ月</option>
					<option value="Y" <?php echo $this->basic->check_selected($gp_data['payment_cycle'], 'Y' ); ?> >年間</option>
				</select>
		</td>
	</tr>
	<tr id="payment_cycle_desc" <?php if( ! $gp_data['payment'] || $gp_data['payment'] != "_xclick-subscriptions" ) : ?> style="display: none" <?php endif; ?>>
		<th scope="row" colspan="2"><label for="payment"><?php _e( '例えば、１年１２ヶ月払いの決済場合は「数字の部分を12」、「期間の部分をヶ月」と選択します。' ); ?></label></th>
	</tr>

</table>


	<input type="hidden" name="payment_register_action" value="<?php echo $gp_data['post_action']; ?>" />

	<input type="hidden" name="post_id" id="post_id" value="<?php echo $gp_data['meta_id']; ?>" />


	<input type="hidden" name="action" id="action" value="save" />

				<!-- <tr>
				<th scope="row"><label for="reattempt"><?php _e( '決済が失敗した時に、手動で再開処理をする場合はチェックを入れて下さい。' ); ?></label></th>
				<td>
					<input name="reattempt" type="checkbox" id="reattempt" value="1" class="regular-text" style="width: auto;" />手動で再開処理をする。<br>
					(注意点：Paypalは決済が失敗した場合、再開処理を日にちを開けて２回行います。もし、その２回の間に決済が再開されなかった場合は手動で決済処理を再開する必要がありますのでご注意下さい。)
				</td>
			</tr> -->