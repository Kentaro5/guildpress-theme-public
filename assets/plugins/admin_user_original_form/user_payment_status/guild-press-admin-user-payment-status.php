<?php
/**
*
*/
class User_Payment_Status
{

	public function __construct()
	{
		$this->load();
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_settings';

		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->email = new Guild_Press_Email;
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

		$p_s_id = $this->wpfunc->get_user_meta( $user_id, 'p_s_id' );
		$status = $this->wpfunc->get_user_meta( $user_id, 'status' );

		$user_p_s_id = ( isset($p_s_id[0]) && $p_s_id[0] !== "" ) ? $p_s_id[0] : '';
		$user_status = ( isset($status[0]) && $status[0] !== "" ) ? intval( $status[0] ) : '';

		wp_nonce_field( 'guild_press_edit_user_item', 'editbox', false );
		?>
		<h2>GuildPressユーザーステータス</h2>
		<table class="form-table">
			<tr>
				<th>PayPalID</th>
				<td>
					<?php if( $user_p_s_id !== '' ) : ?>
						<p><?php echo $user_p_s_id; ?></p>
						<input type="hidden" name="gp_p_sid" value="<?php echo $user_p_s_id; ?>" >
						<?php else : ?>
							<input type="text" name="gp_p_sid" value="" >
						<?php endif; ?>
					</td>
				</tr>

				<tr>
					<th>ユーザーステータス</th>
					<td>
						<select name="gp_user_status" id="user_status" style="width: 300px;">
						<option value="">ユーザーの決済ステータス</option>
							<option value="0" <?php echo $this->basic->check_selected($user_status, 0 ); ?>>本登録</option>
							<option value="1" <?php echo $this->basic->check_selected($user_status, 1 ); ?>>仮登録</option>
						</select>
						</td>
				</tr>

			</table>

		<?php
	}

	public function update_admin_user_items(){
			$user_id = intval( trim( $_POST['user_id'] ) );
			//$options = get_option("salon_payment_field");

			//セキュリティ
			if( ! wp_verify_nonce( $_POST['editbox'], 'guild_press_edit_user_item' ) && !isset( $_POST["editbox"] ) ) {
				return;
			}
			if ( ! current_user_can( 'edit_posts' ) ) {
				return;
			}

			$gp_user_status = ( isset($_POST['gp_user_status']) && $_POST['gp_user_status'] !== "" ) ? intval( $_POST['gp_user_status'] ) : 0;

			$gp_p_sid = ( isset($_POST['gp_p_sid']) && $_POST['gp_p_sid'] !== "" ) ? $_POST['gp_p_sid'] : '';

			update_user_meta( $user_id, 'status', $gp_user_status );
			update_user_meta( $user_id, 'p_s_id', $gp_p_sid );

			return;
		}

}



