<?php

class Admin_Set_Up{

    public function __construct(){
        $this->load();
    }

    public function load()
    {

        add_action( 'admin_enqueue_scripts', array( $this, 'css_js_set_up' ) );
    }

    //cssとjsをセットアップする。
    public function css_js_set_up()
    {

        $normalize_css_url = get_template_directory_uri() . '/assets/css/normalize/normalize.css';
        $admin_css_url = get_template_directory_uri() . '/assets/css/admin/style.css';
        $time_picker_css_url = get_template_directory_uri().'/assets/lib/time-picker/jquery.timepicker.min.css';

        $admin_js_url = get_template_directory_uri() . '/assets/js/admin/guild_press_admin.js';
        $admin_short_cut_btn_js_url = get_template_directory_uri() . '/assets/js/admin_short_cut_btn/guild_press_admin_short_cut_btn.js';
        $time_picker_js_url = get_template_directory_uri() . '/assets/lib/time-picker/jquery.timepicker.min.js';

        $guild_press_common_js_url = get_template_directory_uri() . '/assets/js/common/guild_press_common.js';
        $guild_press_calendar_js_url = get_template_directory_uri() . '/assets/js/calendar/calendar.js';

        $lesson_overview_lists_js_url = get_template_directory_uri() . '/assets/js/widget/front/lesson-overview-lists.js';

        wp_enqueue_script( 'guild-press-common', $guild_press_common_js_url, "", "20160608" );

        wp_enqueue_script( 'timepickerjs', $time_picker_js_url, "", "20160608"  );

        wp_enqueue_script( 'guild-press-time-picker-js', $guild_press_calendar_js_url, "", "20160608"  );

        wp_enqueue_script( 'app-js', $admin_js_url, "", "20160608" );
        wp_enqueue_script( 'app-shortcut-js', $admin_short_cut_btn_js_url, "", "20160608" );
        wp_enqueue_script( 'app-widget-js', $lesson_overview_lists_js_url, "", "20190817" );

        // サイト共通css
        wp_enqueue_style( 'normalize', $normalize_css_url );

        wp_enqueue_style( 'timepickercss', $time_picker_css_url );

        // サイト共通css
        wp_enqueue_style( 'guild-press-admin', $admin_css_url );

    }

}

