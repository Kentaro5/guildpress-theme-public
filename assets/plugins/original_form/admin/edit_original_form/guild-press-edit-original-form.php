<?php

/**
 *
*/
class Guild_Press_Edit_Original_Form
{
	public $edit_original_form_metabox_path;
	public function __construct()
	{
		$this->wpfunc = new WpFunc;
		$this->basic = new Basic;
		$this->load();

	}

	public function load()
	{
		$this->admin_url = admin_url().'admin.php?page='.SLUGNAME.'_regsiter_item_field';
		$this->edit_original_form_metabox_path = 'templates/admin/original_form/edit_original_form/edit-original-form.php';
	}

	public function edit_field_item_form( $options )
	{
		$options = $this->wpfunc->get_option( SLUGNAME.'_regsiter_item_field', false );

		for ($i=0; $i < count( $options ); $i++) {
			if( in_array( $_GET['id'], $options[$i] ) ){
				$edit_field = $options[$i];
			}
		}

		$field_id = ( isset($_GET['id']) && $_GET['id'] !== "" ) ? $_GET['id'] : '';

		$gp_data = array(
			'edit_field' => $edit_field,
			'field_id' => $field_id,
			'option_page_val' => SLUGNAME.'_regsiter_item_field',
		);

		if( ! $file_path = $this->basic->load_template( $this->edit_original_form_metabox_path, false ) ){

            return;
        }

        include( $file_path );

		add_action( 'admin_footer', array( $this, 'add_js' ) );
	}

	public function show_text( $edit_field )
	{

		$text_val = '';

		for ($i=0; $i <count($edit_field[7]) ; $i++) {

			if( strstr( $edit_field[7][$i], ',' ) ){

				$text_val .= '"'.  $edit_field[7][$i] .'",&#13;';
			}elseif( strstr( $edit_field[7][$i], ',' ) && $i == count($edit_field[7]) - 1 ){

				$text_val .= '"'.  $edit_field[7][$i] .'"';
			}elseif( $i == count($edit_field[7]) - 1 ){

				$text_val .=  $edit_field[7][$i];
			}else{

				$text_val .=  $edit_field[7][$i] .',&#13;';
			}
		}

		return $text_val;
	}

	public function add_js()
	{
		?>
		<script type="text/javascript" charset="utf-8">

			admin_js.redirect_user_after_update( '<?php echo $this->admin_url; ?>' )
		</script>
		<?php
	}
}