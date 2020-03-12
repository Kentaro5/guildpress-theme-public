var public_js = (function(){
	var ajaxSecurity;
	var postMetaId;
	var open_dom_window = function( class_name ){

		jQuery('.'+class_name).openDOMWindow({
			eventType:'click',
			loader:1,
			loaderHeight:16,
			loaderWidth:17
		});
	}

	var delete_schedule = function(date_id,delete_option_id){

		let ajax_success_flg = 'delete_shcedule';

		let google_event_id = 	common_js.get_element_by_id("google_event_id"+delete_option_id);
		let google_event_id_val = google_event_id.value;

		let the_month_id = 	common_js.get_element_by_id("the_month"+delete_option_id);
		let the_month = the_month_id.value;

		let load_animation = common_js.get_element_by_id("loadingAnim"+delete_option_id);
		common_js.show_block( load_animation );

		let back_url = document.location.href;

		let send_data = {
				'delete_option_id': delete_option_id,
				'date_id': date_id,
				'the_month': the_month,
				'google_event_id': google_event_id_val,
				'action' : 'guild_press_delete_user_schedule_action',
				'security': security,
			}

		common_js.ajax_post( ajaxUrl, send_data, back_url, ajax_success_flg );

		return false;
	}

	var register_paypal_event = function( ajax_security, post_meta_id ){

		 ajaxSecurity = ajax_security;
		 postMetaId = post_meta_id;
		let paypal_btn = common_js.get_element_by_id("paypal_btn");
        paypal_btn.addEventListener('click', set_up_paypal);
	}

	var set_up_paypal = function(){

		let ajax_success_flg = 'paypal_payment';

		let send_data = {
			'post_meta_id' : postMetaId,
			'action' : 'guild_press_set_up_paypal_action',
			'security': ajaxSecurity
		}

		common_js.ajax_post( ajaxUrl, send_data, '', ajax_success_flg );

		return false;
	}

	var add_paypal_input_to_form = function( response ){

		let parsed_response = common_js.parse_json(response);
		let tareget_form = common_js.get_forms_elements_by_form_name( 'frm_payment_method' );
		let input_field;

		for (var i = 0; i < parsed_response.length; i++) {

			input_field = document.createElement('input');
			input_field.type = 'hidden';
			input_field.name = parsed_response[i].item_name;
			input_field.value = parsed_response[i].item_value;

			tareget_form.appendChild(input_field);
		}

		if(tareget_form.elements['business'].value == parsed_response[0].item_value){

			tareget_form.submit();
		}else{

			alert("エラーが起きました。もう一度画面をリロードしてボタンをクリックして下さい。")
		}
	}

	var register_user_register_form_event = function(){

		let user_email = common_js.get_element_by_id( 'user_email' );
		let log = common_js.get_element_by_id( 'log' );

		user_email.addEventListener( 'change', function(){

			this.value = common_js.check_char( 'eisu', this.value );
		});

		log.addEventListener( 'change', function(){

			this.value = common_js.check_char( 'eisu', this.value );
		});

		// jQuery('#show_error').fadeOut(2000);
	}

	var open_qna_thread = function(){
		jQuery('div.area_forum.first_comment').on('click', function(e){

			jQuery(this).children('.js-acordion-target').slideToggle();

			jQuery(this).next().slideToggle();
		});
	}

	var hide_qna_thread = function( arg ){

		jQuery('div.area_forum.first_comment').next('ul.children').css('display','none');
	}

	var register_next_page_event = function( next_post_link ){

		let next_link_btn_element = common_js.get_element_by_id( 'next_link_btn' );
		if( next_link_btn_element === null ){
			return;
		}

		next_link_btn_element.addEventListener( 'click', function(){

			let load_anim_element = common_js.get_element_by_id( 'loadingAnim' );
			common_js.hide( load_anim_element );

			document.location.href = next_post_link;
		});
	}


	var check_lesson_quiz = function( next_post_link, user_id, slug, post_id, post_security ){

		let send_quiz_answer = common_js.get_element_by_id( 'send_quiz_answer_btn' );

		if( send_quiz_answer === null ){
			return;
		}
		send_quiz_answer.addEventListener( 'click', function(){

			let gp_uncorrect_answer = common_js.get_element_by_id('gp_uncorrect_answer');
			common_js.hide( gp_uncorrect_answer );

			let gp_uncorrect_answer_text = common_js.get_element_by_id('gp_uncorrect_answer_text');
			common_js.hide( gp_uncorrect_answer_text );

			let gp_user_answer_text = get_gp_user_answer_text( 'guild_press_quiz_correct_answer' );
			let ajax_success_flg = 'check_answer_quiz';

			if( gp_user_answer_text !== null ){

				let send_data = {
					'user_id': user_id,
					'next_link' : next_post_link,
					'action' : 'guild_press_check_quiz_answer_action',
					'security': post_security,
					'slug': slug,
					'post_id': post_id,
					'gp_user_answer_text': gp_user_answer_text,
				}

				common_js.ajax_post( ajaxUrl, send_data, '', ajax_success_flg );
			}else{

				alert("答えを選択してください。");
			}
		});

		return false;
	}

	var answer_action = function( response ){

		if( response === 'success' ){

			answer_correct_action();
		}else if( response === 'failed' ){

			common_js.show_log( response )
			answer_uncorrect_action();
		}else{

			common_js.show_log( response )
			alert("データが不正です。")
		}
	}

	var answer_correct_action = function(){

		let gp_correct_answer = common_js.get_element_by_id('gp_correct_answer');
		common_js.show_inline( gp_correct_answer );

		let gp_correct_answer_text = common_js.get_element_by_id('gp_correct_answer_text');
		common_js.show_inline( gp_correct_answer_text );

		let next_link_btn = common_js.get_element_by_id('next_link_btn');

		if( next_link_btn !== null ){
			if( next_link_btn.style.display !== 'inline' ){

				common_js.show_inline( next_link_btn );
			}
		}

		let send_quiz_answer_btn = common_js.get_element_by_id('send_quiz_answer_btn');
		common_js.add_class( send_quiz_answer_btn, 'btn_comp_design' );
		common_js.remove_class( send_quiz_answer_btn, 'btn_design' );

		let is_display_gp_uncorrect_answer = gp_uncorrect_answer.style.display;
		let is_display_gp_uncorrect_answer_text = gp_uncorrect_answer_text.style.display;

		if( is_display_gp_uncorrect_answer === 'none' && is_display_gp_uncorrect_answer_text === 'none'  ){

		}else{

			let gp_uncorrect_answer = common_js.get_element_by_id('gp_uncorrect_answer');
			common_js.hide( gp_uncorrect_answer );

			let gp_uncorrect_answer_text = common_js.get_element_by_id('gp_uncorrect_answer_text');
			common_js.hide( gp_uncorrect_answer_text );
		}
	}

	var answer_uncorrect_action = function(){

		let gp_uncorrect_answer = common_js.get_element_by_id('gp_uncorrect_answer');
		common_js.show_inline( gp_uncorrect_answer );

		let gp_uncorrect_answer_text = common_js.get_element_by_id('gp_uncorrect_answer_text');
		common_js.show_inline( gp_uncorrect_answer_text );
	}
	var get_gp_user_answer_text = function( radio_name ){

		let form_elements = common_js.get_elements_by_name( radio_name );

		for ( let i=0; i < form_elements.length; i++ )  {

			if (form_elements[i].type === "radio" && form_elements[i].name === radio_name && form_elements[i].checked ) {

				return form_elements[i].value;
			}
		}
		return null;
	}

	var save_lesson_schedule = function( next_post_link, user_id, slug, post_id, post_security ){

		let lesson_comp = common_js.get_element_by_id( 'lesson_comp' );

		if( lesson_comp === null ){
			return;
		}
		lesson_comp.addEventListener( 'click', function(){

			let send_data = {
				'next_link' : next_post_link,
				'action' : 'guild_press_save_user_lesson_progress_action',
				'security': post_security,
				'slug': slug,
				'user_id': user_id,
				'post_id': post_id,
			};

			let ajax_success_flg = 'save_user_lesson_schedule';

			common_js.ajax_post( ajaxUrl, send_data, '', ajax_success_flg );

		});

		return false;
	}

	var save_lesson_schedule_action = function( response ){

		if(response === 'success'){
			console.log(response)
			success_save_lesson_schedule();
		}else{

			common_js.show_log( response )
			error_save_lesson_schedule();
		}
	}

	var error_save_lesson_schedule = function(){

		let load_animation = common_js.get_element_by_id( 'loadingAnim' );
		common_js.hide( load_animation );

		alert("通信に失敗しました。画面をもう一度リロードした上でお試しください。");
	}

	var success_save_lesson_schedule = function(){

		let lesson_comp = common_js.get_element_by_id( 'lesson_comp' );

		common_js.remove_class( lesson_comp, 'btn_design' );
		common_js.add_class( lesson_comp, 'btn_comp_design' );
		common_js.add_text( lesson_comp, '完了済み' );
	}

	return {
        open_dom_window: open_dom_window,
        delete_schedule: delete_schedule,
        register_paypal_event: register_paypal_event,
        add_paypal_input_to_form: add_paypal_input_to_form,
        set_up_paypal: set_up_paypal,
        register_user_register_form_event: register_user_register_form_event,
        open_qna_thread: open_qna_thread,
        hide_qna_thread: hide_qna_thread,
        register_next_page_event: register_next_page_event,
        check_lesson_quiz: check_lesson_quiz,
        get_gp_user_answer_text: get_gp_user_answer_text,
        answer_correct_action: answer_correct_action,
        answer_uncorrect_action: answer_uncorrect_action,
        answer_action: answer_action,
        save_lesson_schedule: save_lesson_schedule,
        save_lesson_schedule_action: save_lesson_schedule_action,
        success_save_lesson_schedule: success_save_lesson_schedule,
        error_save_lesson_schedule: error_save_lesson_schedule,
    }

})();