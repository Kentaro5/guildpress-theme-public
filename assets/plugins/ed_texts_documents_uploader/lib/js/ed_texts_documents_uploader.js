var ed_texts_docs_js = (function(){

    var pdfDoc;
    var page_num;
    var page_rendering_flg;
    var page_num_pendingl;
    var scale;
    var canvas;
    var ctx;

    var show_pdf = function(){

        //objectが存在するかチェック
        if(typeof ed_texts_docs !== 'undefined' ) {

            jQuery(function () {
                // URL of PDF document
                pdfDoc = null;
                page_num = 1;
                page_rendering_flg = false;
                page_num_pending = null;
                scale = 0.8;
                canvas = common_js.get_element_by_id('gp-the-canvas');
                ctx = canvas.getContext('2d');
                set_pdf_setting( ed_texts_docs );
                // 注: pdfData の型は Uint8Array
                //let pdfData = ed_texts_docs.texts_docs_url;
                let pdfData = {
                    url: ed_texts_docs.texts_docs_url,
                    cMapUrl: ed_texts_docs.cmap_url,
                    cMapPacked: ed_texts_docs.texts_docs_url,
                }
                read_pdf_file( pdfData );
            });

            register_events();
        }

    }

    var set_pdf_setting = function( ed_texts_docs ){

        pdfjsLib.workerSrc = ed_texts_docs.worker_src;
        pdfjsLib.cMapUrl = ed_texts_docs.cmap_url;
        pdfjsLib.cMapPacked = true;
    }

    var read_pdf_file = function( pdfData ){

        // pdfjsLib.getDocument でPDFドキュメントを読み込み
        pdfjsLib.getDocument(pdfData).then(function (pdf) {
                    // 1ページ目を取得する
                    pdfDoc = pdf;
                    common_js.get_element_by_id( 'gp-page-count' ).textContent = pdfDoc.numPages;
                    // Canvas に1ページ目の内容を描画
                    render_pdf_page(page_num)
                });
    }

    var register_events = function(  ){
        let canvas_box = common_js.get_element_by_id('gp-texts-docs-canvas-box');
        let gp_prev_box = common_js.get_element_by_id('gp-prev');
        let gp_next_box = common_js.get_element_by_id('gp-next');

        gp_prev_box.addEventListener('click', on_prev_page);
        gp_next_box.addEventListener('click', on_next_page);

        canvas_box.addEventListener('mouseover', mouseover_event);
        canvas_box.addEventListener('mouseout', mouseout_event);
    }

    var mouseout_event = function(){

        hide_gp_next();
        hide_gp_prev();
    }

    var mouseover_event = function( arg ){
        let all_pages =  parseInt( common_js.get_element_by_id('gp-page-count').textContent );

        if( page_num != all_pages ){
            show_gp_next();
        }

        if( page_num > 1 ){

            show_gp_prev();
        }
    }


    var on_prev_page = function(){
        if (page_num <= 1) {
            return;
        }
        page_num--;
        queue_render_page(page_num);
    }

    var on_next_page = function(){
        if (page_num >= pdfDoc.numPages) {
            return;
        }
        page_num++;
        queue_render_page(page_num);
    }

    var queue_render_page = function( page_num ){
        if (page_rendering_flg) {
            page_num_pending = page_num;
        } else {
            render_pdf_page(page_num);
        }
    }


    var get_new_scale = function( viewport ){

            let canvas_container = common_js.get_element_by_id('gp-texts-docs-canvas-box');
            let viewport_width = parseInt(viewport.width);
            let canvas_container_width = parseInt(canvas_container.clientWidth);
            return canvas_container_width / viewport_width;
    }

    var create_render_context = function( viewport, canvas ){

            let context = canvas.getContext('2d');

            canvas.height = viewport.height;
            canvas.width = viewport.width;
            let renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            return renderContext;
    }

    var set_canvas_body = function( canvas ){
            // body に挿入
            let canvas_body = common_js.get_element_by_id('gp-canvas-body')
            canvas_body.appendChild(canvas);
    }

    var show_next_prev_btn = function( page_num ){
        let all_pages =  parseInt( common_js.get_element_by_id('gp-page-count').textContent );
        if( page_num <= 1 ){

            show_gp_next();
            hide_gp_prev();
        }else if( page_num == all_pages ){

            hide_gp_next();
        }else if( page_num > 1 ){

            show_gp_prev();
            show_gp_next();
        }
    }

    var hide_gp_next = function(){
        let next_btn = common_js.get_element_by_id('gp-next');
        common_js.hide( next_btn );
    }

    var show_gp_next = function(){
        let next_btn = common_js.get_element_by_id('gp-next');
        common_js.show_block( next_btn );
    }

    var hide_gp_prev = function(){
        let prev_btn = common_js.get_element_by_id('gp-prev');
        common_js.hide( prev_btn );
    }

    var show_gp_prev = function( arg ){
        let prev_btn = common_js.get_element_by_id('gp-prev');
        common_js.show_block( prev_btn );
    }

    var render_pdf_page = function( page_num ){

        page_rendering_flg = true;
        // Using promise to fetch the page
        pdfDoc.getPage(page_num).then(function(page) {


            // Canvas に1ページ目の内容を描画
            let scale = 1;
            let viewport = page.getViewport(scale);
            let new_scale = get_new_scale( viewport );

            viewport = page.getViewport(new_scale);

            //let canvas = common_js.get_element_by_id('gp-the-canvas');

            set_canvas_body( canvas );

            show_next_prev_btn( page_num );


            let render_context = create_render_context( viewport, canvas );

            let render_task = page.render(render_context);
            //Wait for rendering to finish
            render_task.promise.then(function () {
                page_rendering_flg = false;
                if (page_num_pending !== null) {

                    render_pdf_page(page_num_pending);
                    page_num_pending = null;
                }
            });
        });
        // Update page counters
        common_js.get_element_by_id('gp-page-num').textContent = page_num;
    }

    var wp_file_uploader = function( my_saved_attachment_post_id ){
        jQuery( document ).ready( function( $ ) {
        // Uploading files
            let file_frame;
            let wp_media_post_id = wp.media.model.settings.post.id;
            let set_to_post_id = my_saved_attachment_post_id;

            jQuery('#img_uploader').on('click', function( event ){

                event.preventDefault();
                // If the media frame already exists, reopen it.
                if ( file_frame ) {
                    // Set the post ID to what we want
                    // file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                    // Open frame
                    file_frame.open();
                    return;
                } else {
                    // Set the wp.media post id so the uploader grabs the ID we want when initialised
                    wp.media.model.settings.post.id = set_to_post_id;
                }

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image to upload',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false // Set to true to allow multiple files to be selected
                });

                //frameのメニューを削除
                file_frame.on( 'menu:render:default', function( view ) {
                    // Store our views in an object.
                    var views = {};

                    // Unset default menu items
                    view.unset( 'library-separator' );
                    view.unset( 'gallery' );
                    view.unset( 'featured-image' );
                    view.unset( 'embed' );

                    // Initialize the views in our view object.
                    view.set( views );
                } );


                // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    $( '#gp_texts_docs_title' ).val( attachment.title );
                    $( '#gp_texts_docs_url' ).val( attachment.url );
                    $( '#texts_docs_id' ).val( attachment.id );


                    // Do something with attachment.id and/or attachment.url here
                    $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );


                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
                    // Finally, open the modal
                    file_frame.open();
            });
            // Restore the main ID when the add media button is pressed
            jQuery( 'a.add_media' ).on( 'click', function() {
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        });
    }

    var box_check = function(){
        let guild_press_member_list_box = common_js.get_element_by_id('guild_press_member_list_box');

        let guild_press_page_block = common_js.get_element_by_id('guild_press_rank_check');
        let guild_press_page_non_block = common_js.get_element_by_id('guild_press_page_non_block');

        //要素の状態からメンバーランクリストの非表示を設定する。
        if( guild_press_page_non_block.checked ){

            guild_press_member_list_box.style.display = 'none';
        }else if( guild_press_page_block.checked ){

            guild_press_member_list_box.style.display = 'block';
        }

        guild_press_page_non_block.addEventListener( 'change', function(){

            if(this.checked) {
                guild_press_member_list_box.style.display = 'none';
            }
        });
        guild_press_page_block.addEventListener( 'change', function(){

            if(this.checked) {
                guild_press_member_list_box.style.display = 'block';
            }
        });
    }

    return {
        set_pdf_setting: set_pdf_setting,
        show_pdf: show_pdf,
        read_pdf_file: read_pdf_file,
        register_events: register_events,
        render_pdf_page: render_pdf_page,
        get_new_scale: get_new_scale,
        show_next_prev_btn: show_next_prev_btn,
        set_canvas_body: set_canvas_body,
        hide_gp_next: hide_gp_next,
        hide_gp_prev: hide_gp_prev,
        show_gp_prev: show_gp_prev,
        show_gp_next: show_gp_next,
        wp_file_uploader: wp_file_uploader,
        box_check: box_check,
    }
})();

ed_texts_docs_js.show_pdf();