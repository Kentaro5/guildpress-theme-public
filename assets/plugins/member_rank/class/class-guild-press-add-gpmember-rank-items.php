<?php
/**
*
*/
class AddGpMemberRankItem
{

	public function __construct()
	{
		$this->load();
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->model = new MemberRankModel;
	}

	public function load()
	{
		add_action( 'show_user_profile', array( $this, 'add_admin_user_items' ) );
		add_action( 'edit_user_profile', array( $this, 'add_admin_user_items' ) );
		add_action( 'profile_update',    array( $this, 'update_admin_user_items' ) );
	}

	public function add_admin_user_items()
	{
		$user_id = ( isset($_GET['user_id']) && $_GET['user_id'] !== "" ) ? intval( $_GET['user_id'] ) : '';

		if( $user_id === '' && ! current_user_can( 'edit_posts' ) ){

			return;
		}

		//新しく会員ランクが作られているかどうかをチェックする。
		$counts = $this->wpfunc->wp_count_posts('guild_press_rank');
		$member_rank_post_counts = $counts->publish;

		if( $member_rank_post_counts > 0 ){
			$gp_member_rank = $this->wpfunc->get_user_meta( $user_id, 'gp_member_rank' );

			$user_gp_member_rank = ( isset($gp_member_rank[0]) && $gp_member_rank[0] !== "" ) ? intval( $gp_member_rank[0] ) : '';

			wp_nonce_field( 'guild_press_edit_user_rank_item', 'edit_rank_box', false );

			$rank_query = $this->model->return_member_rank_query();
			$member_id_arr = array();
			?>
			<h2>GuildPress会員ランク項目</h2>
			<table class="form-table">
					<tr>
						<th>会員ランク</th>
						<td>
							<select name="gp_user_rank" id="gp_user_rank" style="width: 300px;">
							<option value="">ユーザーの会員ランク</option>

								<?php while( $rank_query->have_posts() ) : ?>

									<?php
										$rank_query->the_post();
										$member_id = get_the_ID();
										$member_id_arr[] = get_the_ID();
										//IDを元に、会員ランク取得
										$member_rank = get_post_meta( $member_id );
									?>

									<option value="<?php echo $member_id; ?>" <?php echo $this->basic->check_selected($user_gp_member_rank, $member_id ); ?>>
										<?php echo $this->wpfunc->esc_html($member_rank['member_rank_name'][0]); ?>
									</option>

								<?php endwhile; ?>

							</select>
							</td>
					</tr>
					<?php for ($i=0; $i < count( $member_id_arr ); $i++) : ?>
						<?php

							$user_rank_meta = get_post_meta( intval( $member_id_arr[$i] ) );
							$p_s_id_meta = $this->wpfunc->get_user_meta( $user_id, 'p_s_id_'.$member_id_arr[$i] );
							$member_rank = $user_rank_meta['member_rank_name'][0];

							if( $p_s_id_meta === false ){

								$p_s_id = '';
							}elseif( count( $p_s_id_meta ) === 0 ){

								$p_s_id = '';
							}else{

								$p_s_id = $p_s_id_meta[0];
							}
						?>
						<tr>
							<th><?php echo $this->wpfunc->esc_html( $member_rank ).'PayPalID'; ?></th>
							<td>
								<input type="text" name="paypal_id[<?php echo $member_id_arr[$i]; ?>]" value="<?php echo $this->wpfunc->esc_html( $p_s_id ); ?>">
							</td>
						</tr>
					<?php endfor; ?>

				</table>

			<?php
			$this->wpfunc->wp_reset_postdata();
		}
	}

	public function update_admin_user_items(){
			$user_id = ( isset($_POST['user_id']) && $_POST['user_id'] !== "" ) ? intval( trim( $_POST['user_id'] ) ) : '';


			$edit_rank_box = ( isset($_POST['edit_rank_box']) && $_POST['edit_rank_box'] !== "" ) ? $_POST['edit_rank_box'] : '';
			//セキュリティ
			if( ! wp_verify_nonce( $edit_rank_box, 'guild_press_edit_user_rank_item' ) && !isset( $_POST["edit_rank_box"] ) ) {
				return;
			}
			if ( ! current_user_can( 'edit_posts' ) ) {
				return;
			}

			$gp_user_rank = ( isset($_POST['gp_user_rank']) && $_POST['gp_user_rank'] !== "" ) ? intval( $_POST['gp_user_rank'] ) : 0;


			update_user_meta( $user_id, 'gp_member_rank', $gp_user_rank );


			foreach ($_POST['paypal_id'] as $p_s_id_key => $p_s_id_value) {

				$p_s_id = ( isset($_POST['paypal_id'][$p_s_id_key]) && $_POST['paypal_id'][$p_s_id_key] !== "" ) ? $_POST['paypal_id'][$i] : '';

				if( $p_s_id !== '' ){
					//中身があるPSIDだけ更新する。
					update_user_meta( $user_id, 'p_s_id_'.$p_s_id_key, $p_s_id_value );
				}else{

					update_user_meta( $user_id, 'p_s_id_'.$p_s_id_key, '' );
				}


			}

			return;
		}

}