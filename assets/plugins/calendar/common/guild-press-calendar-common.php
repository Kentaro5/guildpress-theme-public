<?php
/**
*
*/

class Guild_Press_Calendar_Common
{

    public function __construct(){
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
    }

    public function get_google_calendar_args( $clendar_data, $register_schedule_data, $loop_num )
    {
        $register_schedule_data['google_event_id'] = ( isset($register_schedule_data['google_event_id']) && $register_schedule_data['google_event_id'] !== "" ) ? $register_schedule_data['google_event_id'] : '';

        $google_calendar_args = array(
            'date_id' => $register_schedule_data['date_id'],
            'user_key' => 'user_id',
            'schedule_id' => $clendar_data[$register_schedule_data['date_id']]['register_task'][$loop_num],
            'google_event_id' => $register_schedule_data['google_event_id'],
            'the_month' => $clendar_data['date_id']
        );

        return $google_calendar_args;
    }

    public function check_user_id_exit( $user_ids, $google_calendar_args, $calendar_data, $calender_slug_name, $date_id )
    {

        if( empty( $user_ids ) ){

            return;
        }

        for ($i=0; $i < count( $user_ids ); $i++) {
            //データ取得
            $book_user_data = $this->wpfunc->get_userdata($user_ids[$i]);

            if( $book_user_data === false ){

                $delete_id = $google_calendar_args['schedule_id'].'_'.$user_ids[$i];
                delete_option( $delete_id );

                unset( $user_ids[$i] );
                unset( $calendar_data[$date_id]['user_id'][$google_calendar_args['schedule_id']][$i] );

                //googleカレンダーのやつも削除する必要がある。
                //この時にデータベースからユーザーのIDも削除した方が良いかも。
                do_action( 'public_'.SLUGNAME.'_after_delete_schedule', $google_calendar_args );
            }
        }

        $calendar_data[$date_id]['register_task'] = $this->basic->reset_arr_order( $calendar_data[$date_id]['register_task'] );

        $calendar_data[$date_id]['user_id'][$google_calendar_args['schedule_id']] = $this->basic->reset_arr_order( $calendar_data[$date_id]['user_id'][$google_calendar_args['schedule_id']] );

        $this->wpfunc->update_option( $calender_slug_name, $calendar_data );

        return array_values( $user_ids );
    }

}