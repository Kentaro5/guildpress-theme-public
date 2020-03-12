var auth_google_js = (function(){

	var auth_google_action = function(){

		let google_auth_btn = common_js.get_element_by_id('google_auth_btn');
		google_auth_btn.addEventListener('click',  send_auth_google_action, false);
	}

    var delete_google_auth_setting = function(){

        let delete_google_btn = common_js.get_element_by_id('delete_google_setting');
        delete_google_btn.addEventListener('click',  delete_auth_google, false);
    }

	var check_auth_google_action = function(){

		let google_auth_btn = common_js.get_element_by_id('google_auth_btn');
		google_auth_btn.addEventListener('click',  check_send_auth_google_action, false);
	}

	var check_send_auth_google_action = function(){

		let result = window.confirm('一度Googleカレンダーのアカウントを変更すると、同じアカウントと紐付けることができなくなります。\nそれでも、アカウントを変更しますか？');

		if( result ){

			send_auth_google_action();
		}else{

			return false;
		}
	}

    var delete_auth_google = function(){

        let auth_google_form = common_js.get_forms_elements_by_form_name('form');
        auth_google_form.admin_action.value = 'delete_google';

        common_js.submit(auth_google_form);
    }

	var send_auth_google_action = function(){

		let auth_google_form = common_js.get_forms_elements_by_form_name('form');
		auth_google_form.admin_action.value = 'auth_google';

		common_js.submit(auth_google_form);
	}

    return {

        check_auth_google_action: check_auth_google_action,
        auth_google_action: auth_google_action,
        check_send_auth_google_action: check_send_auth_google_action,
        delete_google_auth_setting: delete_google_auth_setting,
        delete_auth_google: delete_auth_google,
    }
})();



