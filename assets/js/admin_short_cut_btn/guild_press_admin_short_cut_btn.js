var admin_shortcut_btn_js = (function(){

    var lesson_desc = function(){

        let open_lesson_desc_tag = '<div class="lesson_desc">';

        let lesson_desc_text = 'ココにテキストを入れて下さい。';
        let close_lesson_desc_tag = '</div>';

        let lesson_desc_tag = open_lesson_desc_tag+lesson_desc_text+close_lesson_desc_tag;
        QTags.addButton( 'lesson_desc_btn', 'レッスン説明用ボタン', lesson_desc_tag, '', '', 'lesson_desc_btn', 201 );
    }

    var add_guild_press_contents_short_code = function(){

        let open_guild_press_contents_tag = '[guild_press_contents]';

        QTags.addButton( 'guild_press_contents_btn', 'guild_press_contentsショートコード', open_guild_press_contents_tag, '', '', 'guild_press_contents_btn', 201 );
    }

    var add_guild_press_lesson_list_short_code = function(){

        let open_guild_press_lesson_list_tag = '[guild_press_lesson slug="こちらにスラッグ名を入れてください。"]';

        QTags.addButton( 'guild_press_lesson_list_btn', 'guild_press_lesson_listショートコード', open_guild_press_lesson_list_tag, '', '', 'guild_press_contents_btn', 201 );
    }

    return {
        lesson_desc: lesson_desc,
        add_guild_press_contents_short_code: add_guild_press_contents_short_code,
        add_guild_press_lesson_list_short_code: add_guild_press_lesson_list_short_code,
    }

})();