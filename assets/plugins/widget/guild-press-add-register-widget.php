<?php


add_action('widgets_init', function () {
    $before_widget = '<div class="col-md-3">';
    $before_widget .= '<div id="%1$s" class="c-section %2$s">';
    $before_widget .= '<div class="footer-widgets-container">';

    $after_widget = '</div></div></div>';

    $before_title = '<h2 class="footer-widgets-title mt0 mb0">';
    $after_title = '</h2>';
    $after_title .= '<p class="footer-widgets-border mb0"></p>';

    register_sidebar([
        'name' => __('フッターエリア'),
        'id' => 'gp-footer-widget-area',
        'description' => __('これはページのフッター部分に表示されます。'),
        'before_widget' => $before_widget,
        'after_widget' => $after_widget,
        'before_title' => $before_title,
        'after_title' => $after_title,
    ]);

});


add_action('widgets_init', function () {

    $before_widget = '<div class="container">';
    $before_widget .= '<div class="content-box post-lists front-widget-content">';
    $after_widget = '</div></div>';

    $before_title = '<h2 class="front-widget-box-title main-color">';
    $after_title = '</h2>';

    register_sidebar(array(
        'name' => 'フロントページ',
        'id' => 'gp-front-widget',
        'description' => sprintf(__('設定をすることで、トップページに表示したい項目を自由に編集することができます。', 'gp-front-widget')),
        'before_widget' => $before_widget,
        'after_widget' => $after_widget,
        'before_title' => $before_title,
        'after_title' => $after_title,
    ));

});

add_action('widgets_init', function () {

    $before_widget = '<div class="sidebar-content">';
    $after_widget = '</div>';

    $before_title = '<h2 class="widget-title">';
    $after_title = '</h2>';

    register_sidebar(array(
        'name' => 'サイドバー',
        'id' => 'gp-side-bar-widget',
        'description' => sprintf(__('設定をすることで、サイドバーに表示したい項目を設定できます。', 'gp-side-bar-widget')),
        'before_widget' => $before_widget,
        'after_widget' => $after_widget,
        'before_title' => $before_title,
        'after_title' => $after_title,
    ));
});
