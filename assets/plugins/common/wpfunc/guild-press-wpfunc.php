<?php

class WpFunc{

    //ユーザーがログイン状態かチェックする。
    public function is_user_logged_in()
    {
        return is_user_logged_in();
    }

    //ログインしている現在のユーザー情報を取得する
    public function wp_get_current_user()
    {
        return wp_get_current_user();
    }

    //ユーザーの権限情報取得
    public function get_userdata( $user_id )
    {
        return get_userdata( $user_id );
    }

    //指定したページへリダイレクト
    public function wp_redirect( $url )
    {
        wp_redirect( $this->esc_attr( $url ) );
        exit();
    }

    public function esc_attr( $url )
    {
        return esc_attr( $url );
    }

    //管理画面かどうかチェックする
    public function is_admin()
    {
        return is_admin();
    }


    public function get_page_by_title( $title )
    {
        return get_page_by_title( $title );
    }

    //渡されたIDからURLを返す。
    public function get_permalink( $page_ID, $leavename = false  )
    {
        return get_permalink( $page_ID, $leavename );
    }

    //ページが指定されているページかどうかチェック。
    public function is_page( $page_ID="" )
    {
        return is_page( $page_ID );
    }

    //セキュリティチェック
    public function wp_verify_nonce( $nonce, $action )
    {
        return wp_verify_nonce( $nonce, $action );
    }

    //ログインするために処理を通す。
    public function wp_signon( $credentials, $secure_cookie )
    {
        return wp_signon( $credentials, $secure_cookie );
    }

    //送られてきたユーザー情報から入らない怪しいパラメーターを取り除く。
    public function sanitize_user( $username, $strict )
    {
        return sanitize_user( $username, $strict );
    }

    //エラーがないかチェック。
    public function is_wp_error( $val )
    {
        return is_wp_error( $val );
    }

    //TOPページのURLを返す
    public function home_url( $path='', $scheme='' )
    {
        return home_url( $path, $scheme );
    }

    //TOPページのURLを返す
    public function is_page_template( $template_file='' )
    {
        return is_page_template( $template_file );
    }

    //保存したオプションを取得する。
    public function get_option( $option='', $default='' )
    {
        return get_option( $option, $default );
    }

    //オプションの更新
    public function update_option( $option='', $new_value='', $autoload='' )
    {
        return update_option( $option, $new_value, $autoload );
    }

    //オプションを新しく登録する。
    public function register_setting( $option_group='', $option_name='', $sanitize_callback='' )
    {
        return register_setting( $option_group, $option_name, $sanitize_callback );
    }

    //htmlのエスケープ処理
    public function esc_html( $text='' )
    {
        return esc_html( $text );
    }

    //htmlのエスケープ処理
    public function get_current_user_id()
    {
        return get_current_user_id();
    }


    //htmlのエスケープ処理
    public function  esc_url( $url='', $protocols='', $_context='' )
    {
        return  esc_url( $url, $protocols, $_context );
    }


    public function get_user_by( $field='', $value='' )
    {
        return get_user_by($field, $value);
    }


    //フロントで使うregister_settings
    public function add_option( $option='', $value='', $deprecated='', $autoload='yes' )
    {
        return  add_option( $option, $value, $deprecated, $autoload );
    }

    public function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ){

        return wp_mail( $to, $subject, $message, $headers, $attachments );

    }

    public function get_user_meta( $user_id, $key='', $single=false ){

        return get_user_meta( $user_id, $key, $single);

    }

    public  function add_user_meta( $user_id, $meta_key, $meta_value, $unique = false )
    {
        return  add_user_meta( $user_id, $meta_key, $meta_value, $unique );
    }

    public function get_page_link( $id, $leavename=false, $sample=false ){

        return get_page_link( $id, $leavename, $sample );

    }

    public function current_user_can( $capability="" ){

        return current_user_can( $capability );

    }

    public function admin_url( $path="", $scheme="admin" ){

        return admin_url( $path, $scheme );

    }

    public function is_ssl(){

        return is_ssl();

    }

    public function wp_nonce_field( $action="", $name="", $referer=true, $echo=true )
    {
        return wp_nonce_field( $action, $name, $referer, $echo );
    }

    //WordpressのDBからユーザー情報を引っ張ってくる処理
    public function get_users($args=array())
    {
        return get_users($args);
    }

    //ユーザーのプロフィールページのURLを取得する
    public function get_edit_user_link($user_id)
    {
        return get_edit_user_link($user_id);
    }

    //テーマのディレクトリURLを返す
    public function get_template_directory_uri()
    {
        return get_template_directory_uri();
    }

    public function get_post_type( $post = null )
    {
        return get_post_type( $post );
    }
    public function wp_reset_postdata()
    {
        wp_reset_postdata();
    }

    public function get_post_meta( $post_id, $key = '', $single = false )
    {
        return get_post_meta($post_id, $key, $single);
    }

    public function get_the_ID()
    {
        return get_the_ID();
    }

    public function get_post( $id, $output='OBJECT', $filter = 'raw' )
    {

        return get_post( $id, $output, $filter );
    }

    public function get_post_custom( $post_id = 0 )
    {
        return get_post_custom( $post_id );
    }

    public function wp_mkdir_p($target_path='')
    {
        return wp_mkdir_p( $target_path );
    }


    public function wp_is_writable($path='')
    {
        return wp_is_writable( $path );
    }

    public function nocache_headers()
    {
        return nocache_headers();
    }

    public function get_categories( $args = '' )
    {
        return get_categories( $args );
    }

    public function get_the_terms( $post, $taxonomy )
    {
        return get_the_terms( $post, $taxonomy );
    }

    public function get_query_var( $var, $default )
    {
        return get_query_var( $var, $default );
    }

    public function get_avatar_url( $id_or_email, $args = null )
    {
        return get_avatar_url( $id_or_email, $args );
    }

    public function apply_filters( $tag, $value )
    {
        return apply_filters( $tag, $value );
    }

    public function wp_get_post_terms( $post_id, $taxonomy, $args )
    {
        return  wp_get_post_terms( $post_id, $taxonomy, $args );
    }

    public function set_query_var( $var, $value )
    {
        return set_query_var( $var, $value );
    }

    public function get_template_part( $slug, $name='' )
    {
        return get_template_part( $slug, $name );
    }

    public function update_user_meta( $user_id, $meta_key, $meta_value, $prev_value='' )
    {
        return update_user_meta( $user_id, $meta_key, $meta_value, $prev_value );
    }

    public function get_taxonomy( $taxonomy )
    {
        return get_taxonomy( $taxonomy );
    }

    public function get_terms( $args = array(), $deprecated = '' )
    {
        return get_terms( $args, $deprecated );
    }

    public function locate_template( $template_names, $load = false,  $require_once = true )
    {
        return locate_template( $template_names, $load, $require_once );
    }

    public function the_permalink()
    {
        return the_permalink();
    }

    public function get_the_post_thumbnail_url( $post = null, $size = 'post-thumbnail' )
    {
        return get_the_post_thumbnail_url( $post, $size );
    }

    public function the_title( $before = '', $after = '', $echo = true )
    {
        return the_title( $before, $after, $echo );
    }

    public function wp_count_posts( $type='post', $perm='' )
    {
        return wp_count_posts( $type, $perm );
    }

    public function wp_delete_post( $postid, $force_delete = false )
    {
        return wp_delete_post( $postid, $force_delete );
    }

    public function get_pages( $args = array() )
    {
        return get_pages( $args );
    }

    public function wp_insert_user( $userdata )
    {
        return wp_insert_user( $userdata );
    }

    public function get_post_status( $post = null  )
    {
        return get_post_status( $post );
    }

    public function delete_user_meta( $user_id, $meta_key, $meta_value = '' )
    {
        return delete_user_meta( $user_id, $meta_key, $meta_value );
    }

    public function get_pagenum_link( $pagenum = 1, $escape = true )
    {
        return get_pagenum_link( $pagenum, $escape );
    }

    public function delete_option( $option )
    {
        return delete_option( $option );
    }

}


