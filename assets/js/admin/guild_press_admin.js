var admin_js = (function(){

	//guild-press-calendar-edit.php
	var calendar_form_check = function(){
		jQuery(document).ready(function(){

			calendar_js.set_time_picker('date_time1');
			calendar_js.set_time_picker('date_time2');

			calendar_js.change_zen_num_to_han_num('max_num');
			calendar_js.change_zen_num_to_han_num('date_time1');
			calendar_js.change_zen_num_to_han_num('date_time2');

			let id_list = [
				'title',
				'max_num',
				'date_time1',
				'date_time2',
			]

			let name = [
				'タイトル',
				'人数',
				"時間指定1",
				"時間指定2",
			];

			calendar_js.submit_date_val( 'submit', id_list, name );

		});
	}

	var delete_schedule = function(date_id,delete_option_id, back_url){

		let form_tag = common_js.get_form_elements_by_tag_name( "input" );

		let google_event_id = common_js.get_form_val( form_tag, 'google_event_id' );
		let gp_month_val = common_js.get_form_val( form_tag, 'gp_month' );
		let gp_year_val = common_js.get_form_val( form_tag, 'gp_year' );

		let ajax_success_flg = 'admin_delete_shcedule';

		let load_animation = common_js.get_element_by_id('loadingAnim');

		common_js.show_block( load_animation );

		let send_data = {
				'delete_option_id': delete_option_id,
				'date_id': date_id,
				'gp_month': gp_month_val,
				'gp_year': gp_year_val,
				'google_event_id': google_event_id,
				'action' : 'guild_press_delete_schedule_action',
				'security': security,
			}

		common_js.ajax_post( ajaxUrl, send_data, back_url, ajax_success_flg );

		return false;

	}

	var redirect_user_after_update = function( admin_url ){
		let update_flg = common_js.get_element_by_id('setting-error-settings_updated');

		if( update_flg ){

			common_js.redirect( admin_url );
		}
	}

	var delete_wp_footer_content = function(){
		//WPFooterを削除できるタイミングで発火するようにする。
		document.addEventListener("DOMContentLoaded", function(event) {

			let wp_footer = common_js.get_element_by_id("wpfooter");
			common_js.hide( wp_footer )

		});
	}

	var regsiter_paypal_event_listener = function(){

		let payment_state = common_js.get_element_by_id("payment")
    	payment_state.addEventListener('change', show_sub_scription_form);
	}

	var show_sub_scription_form = function(){
		let payment_state = common_js.get_element_by_id("payment").value;
		let payment_period_state = common_js.get_element_by_id("payment_period");
		let payment_cycle_state = common_js.get_element_by_id("payment_cycle");

		let payment_period_desc_state = common_js.get_element_by_id("payment_period_desc");
		let payment_cycle_desc_state = common_js.get_element_by_id("payment_cycle_desc");

		if( payment_state === '_xclick-subscriptions' ){

			common_js.show( payment_period_state );
			common_js.show( payment_period_desc_state );

			common_js.show( payment_cycle_state );
			common_js.show( payment_cycle_desc_state );

		}else{

			common_js.hide( payment_period_state );
			common_js.hide( payment_period_desc_state );

			common_js.hide( payment_cycle_state );
			common_js.hide( payment_cycle_desc_state );

			let payment_period_select_state = common_js.get_element_by_id("payment_period_select");
			let payment_cycle_num_select_state = common_js.get_element_by_id("payment_cycle_number_select");
			let payment_cycle_select_state = common_js.get_element_by_id("payment_cycle_select");

			common_js.reset_index( payment_period_select_state );

			common_js.reset_index( payment_cycle_num_select_state );
			common_js.reset_index( payment_cycle_select_state );
		}
	}

	var regsiter_original_form_event_listener = function(){

		jQuery(document).ready(function(){
			let field_type_state = common_js.get_element_by_id( "guild_press_field_type_select" );
			field_type_state.addEventListener('change', show_item_field);

			let add_update_field_state = common_js.get_element_by_id( "add_field" );
			add_update_field_state.addEventListener('click', add_update_field_flag);

			let add_normal_field_state = common_js.get_element_by_id( "normal_button" );
			add_normal_field_state.addEventListener('click', add_normal_field_flag);
		});

	}

	var add_normal_field_flag = function(){

		let field_form = common_js.get_forms_elements_by_form_name( 'field_form' );

		field_form.admin_action.value = "guildpressnormal";
		common_js.submit( field_form );
	}

	var add_update_field_flag = function(){

		let field_form = common_js.get_forms_elements_by_form_name( 'field_form' );

		field_form.admin_action.value = "guildpressaddfield";
		common_js.submit( field_form );
	}


	var show_item_field = function(){
		let field_type_state = common_js.get_value_by_id( "guild_press_field_type_select" );

		let checkbox_field = common_js.get_element_by_id("guild_press_checkbox_info");
		let checkbox_field2 = common_js.get_element_by_id("guild_press_checkbox_info2");

		if( field_type_state === "checkbox" ){

			common_js.remove_none_class(checkbox_field);
			common_js.remove_none_class(checkbox_field2);
		}else{

			common_js.add_none_class(checkbox_field);
			common_js.add_none_class(checkbox_field2);

		}

		let select_box_drop_down_field = common_js.get_element_by_id("guild_press_dropdown_info");
		if( field_type_state === "select" ){

			common_js.remove_none_class(select_box_drop_down_field);

		}else{

			common_js.add_none_class(select_box_drop_down_field);
		}
	}

	var register_quiz_event = function( arg ){

		let guild_press_add_quiz_answer = common_js.get_element_by_id('guild_press_add_quiz_answer');
		let guild_press_page_quiz = common_js.get_element_by_id('guild_press_page_quiz');
		let guild_press_page_non_quiz = common_js.get_element_by_id('guild_press_page_non_quiz');
		let guild_press_quiz_answer_box = common_js.get_element_by_id('guild_press_quiz_answer_box');

		guild_press_add_quiz_answer.addEventListener('click', gp_add_quiz_answer);
		guild_press_quiz_answer_box.addEventListener('click', guild_press_remove_meta_box, false);

		guild_press_page_non_quiz.addEventListener( 'change', function(){

			if(this.checked) {
				guild_press_quiz_box.style.display = 'none';
			}
		});
		guild_press_page_quiz.addEventListener( 'change', function(){

			if(this.checked) {
				guild_press_quiz_box.style.display = 'block';
			}
		});
	}

	var check_quiz_check_box = function(){

		let guild_press_quiz_box = common_js.get_element_by_id('guild_press_quiz_box');

		let guild_press_page_quiz = common_js.get_element_by_id('guild_press_page_quiz');
		let guild_press_page_non_quiz = common_js.get_element_by_id('guild_press_page_non_quiz');

		if( common_js.is_checked( guild_press_page_non_quiz ) ){

			//guild_press_quiz_box.style.display = 'none';
			common_js.hide( guild_press_quiz_box );
		}else if( common_js.is_checked( guild_press_page_quiz ) ){

			//guild_press_quiz_box.style.display = 'block';
			common_js.show_block( guild_press_quiz_box );
		}
	}

	var gp_add_quiz_answer = function(){

		let id_no;

		let guild_press_quiz_answer_texts = common_js.get_element_by_id('guild_press_quiz_answer_texts');

		if( guild_press_quiz_answer_texts === null ){

			id_no = 0;
		}else{

			let guild_press_quiz_answer_box_nodes = guild_press_quiz_answer_texts.parentNode;
			id_no = guild_press_quiz_answer_box_nodes.children.length;
		}

		let new_guild_press_quiz_answer_field = document.createElement('div');
		new_guild_press_quiz_answer_field.className = 'relative mb18';
		new_guild_press_quiz_answer_field.id = 'guild_press_quiz_answer_texts';

		let set_items = [
			'<label>解答',
			'<input type="text" class="width100 input_design" name="guild_press_quiz_answer_text[]" id="guild_press_quiz_answer_text" value="">',
			'</label>',
			'<label>正しい答えにチェックを入れて下さい。',
			'<input type="radio" class="width100 input_design" name="guild_press_quiz_correct_answer" id="guild_press_quiz_correct_answer" value="'+id_no+'">',
			'</label>',
			'<div class="position_right"><a href="#" id="guild_press_remove_box" class="gp_remove_element" style="color: #555;" >☓</a></div>'
		];

		for (let i=0; i < set_items.length; i++) {

			new_guild_press_quiz_answer_field.innerHTML += set_items[i];
		}

		let guild_press_quiz_answer_box = common_js.get_element_by_id('guild_press_quiz_answer_box');

		guild_press_quiz_answer_box.appendChild(new_guild_press_quiz_answer_field);
	}


	var guild_press_remove_meta_box = function( e ){

		if(e.target.matches('.gp_remove_element')){
			e.preventDefault();
			let target_remove_node = e.target.parentNode.parentNode;
			target_remove_node.remove();
		}

	}

	var register_member_block_event = function(){

		let guild_press_member_list_box = common_js.get_element_by_id('guild_press_member_list_box');
		let guild_press_page_block = common_js.get_element_by_id('guild_press_page_block');
		let guild_press_page_non_block = common_js.get_element_by_id('guild_press_page_non_block');

		guild_press_page_non_block.addEventListener( 'change', function(){

			if(this.checked) {
				//guild_press_member_list_box.style.display = 'none';
				common_js.hide(guild_press_member_list_box);
			}
		});

		guild_press_page_block.addEventListener( 'change', function(){

			if(this.checked) {
				//guild_press_member_list_box.style.display = 'block';
				common_js.show_block( guild_press_member_list_box );
			}
		});
	}

	var check_member_block_state = function(){

		let guild_press_member_list_box = common_js.get_element_by_id('guild_press_member_list_box');
		let guild_press_page_block = common_js.get_element_by_id('guild_press_page_block');
		let guild_press_page_non_block = common_js.get_element_by_id('guild_press_page_non_block');

		if( guild_press_page_non_block.checked ){

			//guild_press_member_list_box.style.display = 'none';
			common_js.hide( guild_press_member_list_box );
		}else if( guild_press_page_block.checked ){

			//guild_press_member_list_box.style.display = 'block';
			common_js.show_block( guild_press_member_list_box );
		}
	}

	var delete_taxnomy_dropdown = function(){

		let taxnomy_dorpdown_box = common_js.get_element_by_id('newguild_lesson_category_parent');

		if ( typeof(taxnomy_dorpdown_box) !== "undefined" && taxnomy_dorpdown_box !== null ) {
			//カテゴリーを２重構造にさせない。
			taxnomy_dorpdown_box.remove()
		}
	}

	return {
		calendar_form_check: calendar_form_check,
		delete_schedule: delete_schedule,
		redirect_user_after_update: redirect_user_after_update,
		delete_wp_footer_content: delete_wp_footer_content,
		show_item_field: show_item_field,
		regsiter_original_form_event_listener: regsiter_original_form_event_listener,
		add_update_field_flag: add_update_field_flag,
		add_normal_field_flag: add_normal_field_flag,
		show_sub_scription_form: show_sub_scription_form,
		regsiter_paypal_event_listener: regsiter_paypal_event_listener,
		register_quiz_event: register_quiz_event,
		check_quiz_check_box: check_quiz_check_box,
		register_member_block_event: register_member_block_event,
		check_member_block_state: check_member_block_state,
		delete_taxnomy_dropdown: delete_taxnomy_dropdown,
	}


})();