<?php

/**
 *
*/
class Guild_Press_New_Original_Form
{

	public $new_original_form_metabox_path;

	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->new_original_form_metabox_path = 'templates/admin/original_form/new_original_form/new-original-form.php';
		$this->original_normal_form_metabox_path = 'templates/admin/original_form/normal_original_form/list-original-form.php';
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_regsiter_item_field';
	}

	public function original_normal_form( $options )
	{
		$not_delete_text = "メール、パスワード、姓、名は削除できません。";

		$gp_data = array(
			'options' => $options,
			'not_delete_text' => $not_delete_text,
			'option_name' => SLUGNAME.'_regsiter_item_field',
		);
		if( ! $file_path = $this->basic->load_template( $this->original_normal_form_metabox_path, false ) ){
            return;
        }

		include( $file_path );
	}

	public function new_field_item_form( $options )
	{

		$add_label = ( isset($_SESSION['add_label']) && $_SESSION['add_label'] !== "" ) ? $_SESSION['add_label'] : '';

		$add_option = ( isset($_SESSION['add_option']) && $_SESSION['add_option'] !== "" ) ? $_SESSION['add_option'] : '';

		$add_display = ( isset($_SESSION['add_display']) && $_SESSION['add_display'] !== "" ) ? 'checked="checked"' : '';

		$add_required = ( isset($_SESSION['add_required']) && $_SESSION['add_required'] !== "" ) ? 'checked="checked"' : '';

		$add_checked_default = ( isset($_SESSION['add_checked_default']) && $_SESSION['add_checked_default'] !== "" ) ? 'checked="checked"' : '';

		$add_checked_value = ( isset($_SESSION['add_checked_value']) && $_SESSION['add_checked_value'] !== "" ) ? $_SESSION['add_checked_value'] : '1';

		$gp_data = array(
			'add_label' => $add_label,
			'add_option' => $add_option,
			'add_display' => $add_display,
			'add_required' => $add_required,
			'add_checked_default' => $add_checked_default,
			'add_checked_value' => $add_checked_value,
		);

		if( ! $file_path = $this->basic->load_template( $this->new_original_form_metabox_path, false ) ){
            return;
        }

		include( $file_path );

		add_action( 'admin_footer', array( $this, 'add_js' ) );
	}

	public function show_text()
	{

		$text = '';
		if( isset( $_SESSION['add_dropdown_value'] ) && $_SESSION['add_dropdown_value'] != '' ){

			$text .= $this->wpfunc->esc_html($_SESSION['add_dropdown_value']);
		} elseif (version_compare(PHP_VERSION, '5.3.0') >= 0) {

			//&#13;は改行コード
			$text .= 'ここに最初に表示するものを記入してください。|,&#13;';
			$text .= '"こちら側は自由に|こちら側は半角英数字で記入してください",&#13;';
			$text .= '"1,000|one_thousand",&#13;';
			$text .= '"1,000-10,000|1,000-10,000",&#13;';
			$text .= '"最後の選択部分|last_row"';
		} else {

			$text .= 'ここに最初に表示するものを記入してください。|,&#13;';
			$text .= 'こちら側は自由に|こちら側は半角英数字で記入してください,&#13;';
			$text .= '1,000-10,000|1,000-10,000,&#13;';
			$text .= '最後の選択部分|last_row';
		}

		return $text;
	}

	public function add_js()
	{
		?>
		<script type="text/javascript">
			admin_js.delete_wp_footer_content();
			admin_js.regsiter_original_form_event_listener();
		</script>
		<?php
	}
}