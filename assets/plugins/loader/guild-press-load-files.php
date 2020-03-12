<?php


require_once( TEMP_DIR . '/assets/plugins/common/error/guild-press-error-message.php' );

require_once( TEMP_DIR . '/assets/plugins/common/basic/guild-press-basic.php' );

require_once( TEMP_DIR . '/assets/plugins/email/guild-press-email.php' );

//WordPress特有の関数まとめ。
require_once( TEMP_DIR . '/assets/plugins/common/wpfunc/guild-press-wpfunc.php' );

require_once( TEMP_DIR . '/assets/plugins/widget/front/lesson_lists/guild-press-front-lesson-category-widget.php' );

require_once( TEMP_DIR . '/assets/plugins/widget/front/lesson_overview_lists/guildpress-front-lesson-overview-widget.php' );

require_once( TEMP_DIR . '/assets/plugins/widget/front/user_status/guild-press-front-user-status-widget.php' );

require_once( TEMP_DIR . '/assets/plugins/widget/front/recent_posts/guild-press-front-recent-posts-widget.php' );

// サイドバー部分
require_once( TEMP_DIR . '/assets/plugins/widget/sidebar/recent_post/guild-press-recent-post-widget.php' );
require_once( TEMP_DIR . '/assets/plugins/widget/sidebar/user_status/guild-press-side-bar-widget.php' );
require_once( TEMP_DIR . '/assets/plugins/widget/sidebar/lesson_list/guild-press-lesson-list-widget.php' );
require_once( TEMP_DIR . '/assets/plugins/widget/sidebar/new_lesson_list/guild-press-new-lesson-list.php' );

require_once( TEMP_DIR . '/assets/plugins/widget/guild-press-add-register-widget.php' );

require_once( TEMP_DIR . '/assets/plugins/abstract/user_register/abstract-user-register.php' );

require_once( TEMP_DIR . '/assets/plugins/calendar/public/abstract/abstract-calendar.php' );

require_once( TEMP_DIR . '/assets/plugins/calendar/public/rendar_parts/guild-press-public-rendar-parts.php' );

require_once( TEMP_DIR . '/assets/plugins/db/user_progress/normal/guild-press-model.php' );
require_once( TEMP_DIR . '/assets/plugins/db/user_progress/back_up/guild-press-bk-model.php' );


require_once( TEMP_DIR . '/assets/plugins/email/guild-press-email-filter.php' );
if( class_exists('Guild_Press_Email_Filter') ){
        new Guild_Press_Email_Filter();
    }

require_once( TEMP_DIR . '/assets/plugins/custom_post/guild-press-custom-post.php' );
    if( class_exists('Guild_Press_Custom_Post') ){
        new Guild_Press_Custom_Post();
    }

//カスタムタクソノミークラス
require_once( TEMP_DIR . '/assets/plugins/taxonomy/admin/guild-press-lesson-taxonomy.php' );
    if( class_exists('Guild_Press_Lesson_Taxnomy') ){
        new Guild_Press_Lesson_Taxnomy();
    }

require_once( TEMP_DIR . '/assets/plugins/lesson/public/guild-press-public-lesson.php' );
    if( class_exists('Guild_Press_Public_Lesson') ){
        new Guild_Press_Public_Lesson();
    }

//マイページクラス
require_once( TEMP_DIR . '/assets/plugins/mypage/public/guild-press-public-my-page.php' );
    if( class_exists('Guild_Press_Public_My_Page') ){
        new Guild_Press_Public_My_Page();
    }

require_once( TEMP_DIR . '/assets/plugins/login/public/guild-press-public-login.php' );
    if( class_exists('Guild_Press_Public_Login') ){
        new Guild_Press_Public_Login();
    }

require_once( TEMP_DIR . '/assets/plugins/user_register/public/guild-press-user-register.php' );
    if( class_exists('Public_User_Register') ){
        new Public_User_Register();
    }

require_once( TEMP_DIR . '/assets/plugins/paypal/public/guild-press-public-paypal.php' );
    if( class_exists('Guild_Press_User_PayPal_Register') ){
        new Guild_Press_User_PayPal_Register();
    }


if( is_admin() ){
    require_once( TEMP_DIR . '/assets/plugins/set_up/admin/guild-press-set-up.php' );
    if( class_exists('Admin_Set_Up') ){
        new Admin_Set_Up();
    }

    require_once( TEMP_DIR . '/assets/plugins/theme_support/admin/guild-press-theme-support.php' );
    if( class_exists('Guild_Press_Theme_Support') ){
        new Guild_Press_Theme_Support();
    }

    require_once( TEMP_DIR . '/assets/plugins/general_setting/guild-press-admin-general-setting.php' );
    if( class_exists('Guild_Press_Admin_General_Setting') ){
        new Guild_Press_Admin_General_Setting();
    }

    require_once( TEMP_DIR . '/assets/plugins/metabox/register_metabox/guild-press-admin-register-metabox.php' );
    if( class_exists('Admin_Register_Metabox') ){
        new Admin_Register_Metabox();
    }

    require_once( TEMP_DIR . '/assets/plugins/calendar/admin/guild-press-admin-calendar.php' );
    if( class_exists('Guild_Press_Admin_Calendar') ){
        new Guild_Press_Admin_Calendar();
    }

    require_once( TEMP_DIR . '/assets/plugins/user_progress/admin/guild-press-user-progress.php' );
    if( class_exists('Guild_Press_User_Progress') ){
        new Guild_Press_User_Progress();
    }

    require_once( TEMP_DIR . '/assets/plugins/paypal/admin/paypal_list/guild-press-paypal-list.php' );
    if( class_exists('Guild_Press_PayPal_List') ){
        new Guild_Press_PayPal_List();
    }

    require_once( TEMP_DIR . '/assets/plugins/paypal/admin/guild-press-admin-paypal.php' );
    if( class_exists('Guild_Press_Admin_Paypal') ){
        new Guild_Press_Admin_Paypal();
    }

    require_once( TEMP_DIR . '/assets/plugins/original_form/admin/guild-press-original-form.php' );
    if( class_exists('Guild_Press_Original_Form') ){
        new Guild_Press_Original_Form();
    }

    require_once( TEMP_DIR . '/assets/plugins/db/user_progress/normal/guild-press-installer.php' );
    if( class_exists('Guild_Press_User_Progress_Installer') ){
        new Guild_Press_User_Progress_Installer();
    }


    require_once( TEMP_DIR . '/assets/plugins/db/user_progress/back_up/guild-press-bk-installer.php' );
    if( class_exists('Guild_Press_User_Progress_Bk_Installer') ){
        new Guild_Press_User_Progress_Bk_Installer();
    }


    require_once( TEMP_DIR . '/assets/plugins/admin_user_original_form/user_payment_status/guild-press-admin-user-payment-status.php' );
    if( class_exists('User_Payment_Status') ){
        new User_Payment_Status();
    }

    require_once( TEMP_DIR . '/assets/plugins/admin_user_original_form/custom_user_form/guild-press-admin-custom-user-form.php' );
    if( class_exists('Admin_Custom_User_Form') ){
        new Admin_Custom_User_Form();
    }

    require_once( TEMP_DIR . '/assets/plugins/user_delete/admin/guild-press-user-delete.php' );
    if( class_exists('Admin_User_Delete') ){
        new Admin_User_Delete();
    }

}else{

    require_once( TEMP_DIR . '/assets/plugins/set_up/public/guild-press-set-up.php' );
    if( class_exists('Public_Set_Up') ){
        new Public_Set_Up();
    }

    require_once( TEMP_DIR . '/assets/plugins/qna/public/guild-press-public-qna.php' );
    if( class_exists('Guild_Press_Public_Qna') ){
        new Guild_Press_Public_Qna();
    }

    require_once( TEMP_DIR . '/assets/plugins/news/guild-press-public-news.php' );
    if( class_exists('Guild_Press_Public_News') ){
        new Guild_Press_Public_News();
    }


    require_once( TEMP_DIR . '/assets/plugins/member/public/guild-press-public-member.php' );
    if( class_exists('Guild_Press_Public_Member') ){
        $member = new Guild_Press_Public_Member();
    }

    require_once( TEMP_DIR . '/assets/plugins/calendar/public/guild-press-public-calendar.php' );
    if( class_exists('Guild_Press_Public_Calendar') ){
        $calender = new Guild_Press_Public_Calendar();
    }


}


require_once( TEMP_DIR . '/assets/plugins/ajax/public/guild-press-public-ajax-func.php' );

require_once( TEMP_DIR . '/assets/plugins/google_calendar/settings.php' );
require_once( TEMP_DIR . '/assets/plugins/member_rank/settings.php' );
require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/settings.php' );
require_once( TEMP_DIR . '/assets/plugins/lock_page_content/settings.php' );
