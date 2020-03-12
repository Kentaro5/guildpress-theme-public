<?php

class Guild_Press_Public_User_Taken_List
{
    public function __construct()
    {

        $this->basic = new Basic();
        $this->wpfunc = new WpFunc();
    }
    //ユーザーが現在取得しているレッスンリストを返す
    public function get_user_taken_lesson_list()
    {
        //現在のユーザーデータ取得
        $user = $this->wpfunc->wp_get_current_user();

        //ユーザーの進捗状況取得
        $taken_lesson_list = $this->basic->getUserLessonProgress( $user->ID );

        return $taken_lesson_list;
    }

}