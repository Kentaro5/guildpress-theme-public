var widget_lesson_overview_js = (function(){

    var set_up = function(){

        var add_widget_overview_elem = common_js.get_element_by_class_name( "add_guild_press_widget_overview_box" );

        var delete_widget_overview_elem = common_js.get_element_by_class_name( "delete_guild_press_widget_overview_box" );

        for (let i = 0; i < add_widget_overview_elem.length; i++) {
            add_widget_overview_elem[i].addEventListener('click', guildpress_add_action, false);
        }

        for (let i = 0; i < delete_widget_overview_elem.length; i++) {
            delete_widget_overview_elem[i].addEventListener('click', guildpress_delete_action, false);
        }
    }

    var guildpress_add_action = function(e) {
        //クリック遷移止める
        e.preventDefault();

        let overview_block_nodes = e.target.parentNode.parentNode.querySelector('#guild_press_widget_overview_block');

        //overview_block_nodesにセットされている他の要素も取得
        let overview_box_nodes = overview_block_nodes.children;

        //一番最後に挿入するために、最後の要素を取得して、コピー。
        let copy_node = overview_box_nodes[overview_box_nodes.length - 1].cloneNode(true);

        copy_node.childNodes.forEach( function( value, index ) {

            if( value.children !== undefined ){

                Array.prototype.forEach.call(value.children, function(node_element) {

                    delete_node_values( node_element );
                })

                set_btn_delete_action( value );
            }
        })

        overview_block_nodes.appendChild(copy_node);

        let save_btn_element = e.target.parentNode.parentNode.parentNode.querySelector('input[name="savewidget"]');
        set_save_btn_activate( save_btn_element );
    };

    var set_save_btn_activate = function( save_btn_element ){
        save_btn_element.value = "保存";
        save_btn_element.disabled = false;
    }
    var delete_node_values = function( node_element ){

        if( node_element.matches('select') ){

            node_element.selectedIndex = 0;
        }else if( node_element.matches('input') ){

            node_element.value = '';
        }
    }

    var set_btn_delete_action = function( node_element ){

        if( node_element.matches('.delete_guild_press_widget_overview_box') ){
            //新しく生成されたものには、クリックイベントがつかないので、ここで登録。
            node_element.addEventListener('click', guildpress_delete_action, false);
        }
    }

    var guildpress_delete_action = function(e) {
        e.preventDefault();
        //削除する要素を取得
        let target_remove_node = e.target.parentNode;

        let overview_box = e.target.parentNode.parentNode.querySelectorAll('#guild_press_widget_overview_box');

        //最後の要素の場合は削除させない。
        if( overview_box.length > 1 ){
            let save_btn_element = e.target.parentNode.parentNode.parentNode.parentNode.querySelector('input[name="savewidget"]');
            set_save_btn_activate( save_btn_element );
            target_remove_node.remove();
        }else{
            alert("項目が１つしかない場合は、削除することができません。")
        }
    };

    var after_add_widget_action = function() {

        jQuery(document).on('widget-updated widget-added', function(){
            set_up();
        });
    }

    return {
        set_up: set_up,
        guildpress_add_action: guildpress_add_action,
        delete_node_values: delete_node_values,
        set_btn_delete_action: set_btn_delete_action,
        guildpress_delete_action: guildpress_delete_action,
        after_add_widget_action: after_add_widget_action,
        set_save_btn_activate: set_save_btn_activate,

    }

})();