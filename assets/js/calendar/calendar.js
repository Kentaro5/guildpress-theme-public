var calendar_js = (function(){

    var set_time_picker = function( target_element, args ){

        if( args === undefined ){
            args = {
                'timeFormat': 'H:i',
                'step': 10,
                'scrollDefault': 'now'
            }
        }

        jQuery('#'+target_element).timepicker( args );
    }

    var set_fixed_time_picker = function( id_name, date_time1, date_time2 ){
        jQuery(document).ready(function($){

            jQuery('#'+id_name).timepicker({
                'timeFormat': 'H:i',
                'minTime': date_time1,
                'maxTime': date_time2,
                'step': 10
            });

        });
    }

    var change_zen_num_to_han_num = function( target_element ){

        jQuery('#'+target_element).on('change', function () {

            let form = common_js.get_form_elements_by_tag_name( "input" );
            let element = form[target_element];

            let half_element_val = common_js.change_zen_num_to_han_num(this.value)
            element.value = half_element_val;

        });
    }

    var check_empty_val = function( result, name ){
        if( result.result === false ){

            alert(name[result.num]+'の部分が何も入力されていません。');
        }
    }

    var submit_date_val = function( target_element, id_name_arr, error_arr ){

        jQuery('#'+target_element).click(function(event) {

            let form_tag = common_js.get_form_elements_by_tag_name( "input" );

            let form_val_arr = [];

            for (let i = 0; i < id_name_arr.length; i++) {

                form_val_arr[i] = common_js.get_form_val( form_tag, id_name_arr[i] );
            }

            let result = common_js.check_void_value( form_val_arr );
            check_empty_val(result, error_arr);

            return check_val_is_num ( result, form_val_arr, error_arr, id_name_arr );

        });
    }

    var check_val_is_num = function( result, form_val_arr, error_arr, id_list ){
        if( result.result === false ){

            return false;
        }else{

            for (let i = 0; i < form_val_arr.length; i++) {

                if( id_list[i] !== 'title' ){

                    if( common_js.check_val_is_num( form_val_arr[i] )　!== true ){

                        alert( error_arr[i]+"の部分に指定された以外の文字が使用されています。" );
                        return false;
                    }
                }
            }

            return true;
        }
    }

    var show_pop_up = function( pop_box_id ){
        let target_element = common_js.get_element_by_id('pop_box_'+pop_box_id);
        common_js.show( target_element );
    }

    var hide_pop_up = function( pop_box_id ){
        let target_element = common_js.get_element_by_id('pop_box_'+pop_box_id);
        common_js.hide( target_element );
    }
    var delete_admin_shcedule_success = function( response, back_url ){

        let load_animation = common_js.get_element_by_id('loadingAnim');
        common_js.hide( load_animation );
        if(response === 'success'){

            alert("登録したスケジュールを削除しました。");

            common_js.redirect(back_url);
        }else{
            console.log("response:"+response)
            alert("不具合がおきました。");
        }

    }

    var delete_shcedule_success = function( response, back_url, post_data ){

        let delete_option_id = post_data.delete_option_id;
        hide_load_anim( delete_option_id );
        if(response === 'success'){

            alert("登録したスケジュールを削除しました。");

            common_js.redirect(back_url);
        }else{
            console.log("response:"+response)
            alert("不具合がおきました。");
        }

    }

    var hide_load_anim = function( delete_option_id ){

        let load_animation = common_js.get_element_by_id('loadingAnim'+delete_option_id);
        common_js.hide( load_animation );
    }

    var delete_shcedule_error = function( response, post_data ){

        let delete_option_id = post_data.delete_option_id;
        console.log("response:"+response)
        alert("不具合がおきました。");
        hide_load_anim( delete_option_id );
    }

    var delete_admin_shcedule_error = function( response ){

        console.log("response:"+response)
        alert("不具合がおきました。");
        let load_animation = common_js.get_element_by_id('loadingAnim');
        common_js.hide( load_animation );
    }

    return {
        set_time_picker: set_time_picker,
        change_zen_num_to_han_num: change_zen_num_to_han_num,
        check_empty_val: check_empty_val,
        check_val_is_num : check_val_is_num,
        show_pop_up : show_pop_up,
        hide_pop_up : hide_pop_up,
        delete_shcedule_success : delete_shcedule_success,
        delete_shcedule_error : delete_shcedule_error,
        set_fixed_time_picker : set_fixed_time_picker,
        submit_date_val : submit_date_val,
        delete_admin_shcedule_success : delete_admin_shcedule_success,
        delete_admin_shcedule_error : delete_admin_shcedule_error,
    }
})();