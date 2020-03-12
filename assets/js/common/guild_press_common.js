var common_js = (function(){

	var check_void_value = function(argument){

		for (let i = 0 - 1; i <= argument.length; i++) {
			if(argument[i] === ""){
				return {result: false, num: i};
			}
		}

		return true;
	}

	var check_val_is_num = function( check_val ){

		return Boolean(check_val.match(/[0-9:]/));
	}

	var get_form_elements_by_tag_name = function( tag_name ){
		let form = document.form.getElementsByTagName( tag_name );
		return form;
	}

	var get_particular_form_elements_by_tag_name = function( taget_element, tag_name ){
		let form = taget_element.getElementsByTagName( tag_name );
		return form;
	}

	var get_forms_elements_by_form_name = function( target_name ){
		let form = document.forms[target_name];
		return form;
	}

	var get_form_val = function( form_element, target_id_name ){
		return form_element[target_id_name].value;
	}

	var submit = function( target_element ){
		target_element.submit();
	}

	var only_eisu = function( item_id ){

		let target_item = get_element_by_id( item_id );

		let target_str = target_item.value;

		while(target_str.match(/[^A-Z^a-z\d\-_]/))
		{
			target_str = target_str.replace(/[^A-Z^a-z\d\-_]/,"");
		}

		target_item.value = target_str;
	}

	var change_zen_num_to_han_num = function( ZenNum ){
		return ZenNum.replace(/[\uFF10-\uFF19]/g, function(e) {
			return String.fromCharCode(e.charCodeAt(0) - 0xfee0);
		});
	}

	var change_full_char_to_half_char = function( fullChar ){
		return fullChar.replace(/[\uff01-\uff5e]/g, function(e) {
			return String.fromCharCode(e.charCodeAt(0) - 0xfee0);
		});
	}

	var get_element_by_id = function( id ){
		return document.getElementById(id);
	}

	var get_value_by_id = function( id ){
		return document.getElementById(id).value;
	}

	var get_element_by_class_name = function( class_name ){
		return document.getElementsByClassName( class_name );
	}

	var remove_none_class = function( target_element ){

		target_element.classList.remove("display_none");

	}

	var reset_index = function( target_element ){
		target_element.selectedIndex = 0;
	}


	var add_none_class = function( target_element ){

		target_element.classList.add("display_none");
	}

	var hide = function( target_element ){
		target_element.style.display = 'none';
	}

	var show = function( target_element ){

		target_element.style.display = '';
	}

	var show_block = function( target_element ){

		target_element.style.display = 'block';
	}

	var show_inline = function( target_element ){

		target_element.style.display = 'inline';
	}

	var ajax_post = function( target_url, post_data, original_item, func_flg ){

		jQuery.ajax({
			type: 'POST',
			url: target_url,
			data: post_data,
		})
		.then(
			function (response) {

				if( func_flg === 'delete_shcedule' ){
					calendar_js.delete_shcedule_success( response, original_item, post_data );
				}

				if( func_flg === 'admin_delete_shcedule' ){

					calendar_js.delete_admin_shcedule_success( response, original_item );
				}

				if( func_flg === 'paypal_payment' ){

					public_js.add_paypal_input_to_form( response );
				}

				if( func_flg === 'check_answer_quiz' ){

					public_js.answer_action( response );
				}

				if( func_flg === 'save_user_lesson_schedule' ){

					public_js.save_lesson_schedule_action( response );
				}

			},
			function (response) {
				if( func_flg === 'delete_shcedule' ){

					calendar_js.delete_shcedule_error( response, post_data );
				}else if( func_flg === 'admin_delete_shcedule' ){

					calendar_js.delete_admin_shcedule_error( response );
				}else{

					ajax_error( response );
				}

				return false;
			}
		);
	}

	var ajax_error = function( response ){
		console.log(response)
		alert("不具合がおきました。");
	}

	var redirect = function(target_url){

		document.location.href = target_url;
	}



	var is_checked = function( target_element ){

		return target_element.checked
	}

	var parse_json = function( json ){

		return JSON.parse( json );
	}

	var check_char = function( lang, string ){

		let val = string.split("");
		for(let i=0;i<val.length;i++){
			switch(lang){
				case "eisu":
				if(val[i].match(/^[a-zA-Z0-9!-/:-@¥[-`{-~]+$/)==null){
					val[i] = "";
				}
				break;
				case "number":
				if(val[i].match(/^[0-9,.]+$/)==null){
					val[i] = "";
				}
				break;
			}
		}
		return val.join("");
	}

	var get_elements_by_name = function( element_name ){

		return window.document.getElementsByName( element_name );
	}

	var add_class = function( target_element, class_name ){

		return target_element.classList.add( class_name )
	}

	var remove_class = function( target_element, class_name ){

		return target_element.classList.remove( class_name )
	}

	var show_log = function( log ){

		console.log( log )
	}

	var add_text = function( target_element, text ){

		target_element.textContent = text;

	}

	return {
		check_void_value: check_void_value,
		get_form_val: get_form_val,
		get_form_elements_by_tag_name: get_form_elements_by_tag_name,
		get_particular_form_elements_by_tag_name: get_particular_form_elements_by_tag_name,
		get_element_by_class_name: get_element_by_class_name,
		change_zen_num_to_han_num: change_zen_num_to_han_num,
		change_full_char_to_half_char: change_full_char_to_half_char,
		get_element_by_id: get_element_by_id,
		check_val_is_num: check_val_is_num,
		hide: hide,
		show: show,
		submit: submit,
		show_block: show_block,
		ajax_post: ajax_post,
		ajax_error: ajax_error,
		redirect: redirect,
		get_value_by_id: get_value_by_id,
		add_none_class: add_none_class,
		remove_none_class: remove_none_class,
		get_forms_elements_by_form_name: get_forms_elements_by_form_name,
		only_eisu: only_eisu,
		reset_index: reset_index,
		is_checked: is_checked,
		parse_json: parse_json,
		check_char: check_char,
		get_elements_by_name: get_elements_by_name,
		show_inline: show_inline,
		add_class: add_class,
		remove_class: remove_class,
		show_log: show_log,
		add_text: add_text,
	}

})();