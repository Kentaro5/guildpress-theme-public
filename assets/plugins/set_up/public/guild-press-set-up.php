<?php

class Public_Set_Up{

    public function __construct(){

        $this->load();
    }

    public function load()
    {

        add_action('wp_enqueue_scripts', array( $this, 'css_js_set_up' ) );
    }

    //cssとjsをセットアップする。
    public function css_js_set_up()
    {

        $template_css = get_stylesheet_uri();
        $normalize_css_url = get_template_directory_uri() . '/assets/css/normalize/normalize.css';
        $bootstrap_css = get_template_directory_uri() . '/assets/css/bootstrap/bootstrap.min.css';
        $main_css = get_template_directory_uri() . '/assets/css/main.css';
        $time_picker_css_url = get_template_directory_uri().'/assets/lib/time-picker/jquery.timepicker.min.css';

        $guild_press_common_js_url = get_template_directory_uri() . '/assets/js/common/guild_press_common.js';
        $guild_press_js_url = get_template_directory_uri() . '/assets/js/public/guild_press.js';
        $time_picker_js_url = get_template_directory_uri() . '/assets/lib/time-picker/jquery.timepicker.min.js';

        $lightbox_url = get_template_directory_uri() . '/assets/lib/dombox/dom.js';

        $bootstrap_js = get_template_directory_uri().'/assets/js/bootstrap/bootstrap.min.js';
        $jquery = '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js';
        $jquery_ui = '//ajax.googleapis.com/ajax/libs/jqueryui/1.5.2/jquery-ui.min.js';
        $guild_press_calendar_js_url = get_template_directory_uri() . '/assets/js/calendar/calendar.js';


        // WordPress提供のjquery.jsを読み込まない
        wp_deregister_script('jquery');

        // サイト共通css
        wp_enqueue_style( 'template-css', $template_css );
        wp_enqueue_style( 'normalize-css', $normalize_css_url );
        wp_enqueue_style( 'timepickercss', $time_picker_css_url );
        wp_enqueue_style( '201801bootstrap', $bootstrap_css );
        wp_enqueue_style( 'main-css', $main_css );

        //サイト共通js
        // jQueryの読み込み
        wp_enqueue_script( 'jquery', $jquery, "", "20160608" );
        wp_enqueue_script( 'jquery-ui', $jquery_ui, "", "20160608" );
        wp_enqueue_script( 'guild-press-common', $guild_press_common_js_url, "", "20160608" );
        wp_enqueue_script( 'guild-press-calendar-js', $guild_press_calendar_js_url, "", "20160608"  );
        wp_enqueue_script( 'timepickerjs', $time_picker_js_url, "", "20160608"  );
        wp_enqueue_script( 'lightbox', $lightbox_url, "", "20160608" );
        wp_enqueue_script( 'bootstrapjs', $bootstrap_js, "", "20160608" );
        wp_enqueue_script( 'app-js', $guild_press_js_url, "", "20160608" );
    }

}