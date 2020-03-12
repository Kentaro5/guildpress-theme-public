<?php


/**
*
*/
class Pagination
{
    public function __construct()
    {
        $this->wpfunc = new WpFunc;
        $this->basic = new Basic;
        $this->load();
    }

    public function load()
    {

        $this->pagination_template = 'templates/public/pagination/normal/pagination.php';
    }

    public function show_pagination( $paged='', $pages = '', $range = 5 )
    {

        ob_start();
        $this->pagination_page( $paged, $pages, $range );
        $pagination_page = ob_get_contents();
        ob_end_clean();
        return $pagination_page;
    }

    public function pagination_page( $paged='', $pages = '', $range = 5 )
    {

        //表示するページ数（５ページを表示）
        $showitems = ($range * 2)+1;
        $html = '';
        //全ページが１でない場合はページネーションを表示する
        if(1 != $pages)
        {
            $prev_link = $this->wpfunc->get_pagenum_link($paged - 1);
            $next_link = $this->wpfunc->get_pagenum_link($paged + 1);
            $gp_data = array(
                'prev_link' => $prev_link,
                'next_link' => $next_link,
                'pages' => $pages,
                'paged' => $paged,
                'range' => $range,
                'showitems' => $showitems,
            );
            if( ! $file_path = $this->basic->load_template( $this->pagination_template, false ) ){

                return;
            }

            include( $file_path );
        }
    }

}