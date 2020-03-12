
//objectが存在するかチェック
if(typeof ed_texts_docs !== 'undefined' ) {
  // URL of PDF document
  var pdfDoc = null,
  pageNum = 1,
  pageRendering = false,
  pageNumPending = null,
  scale = 0.8,
  canvas = common_js.get_element_by_id('gp-the-canvas'),
  ctx = canvas.getContext('2d');

  $(function () {
    PDFJS.workerSrc = ed_texts_docs.worker_src;
    PDFJS.cMapUrl = ed_texts_docs.cmap_url;
    PDFJS.cMapPacked = true;

    let url = ed_texts_docs.texts_docs_url;

    // 注: pdfData の型は Uint8Array
    let pdfData = url;

    // PDFJS.getDocument でPDFドキュメントを読み込み
    PDFJS.getDocument(pdfData).then(function (pdf) {
        // 1ページ目を取得する

        pdfDoc = pdf;
        common_js.get_element_by_id('gp-page-count').textContent = pdfDoc.numPages;
        // Canvas に1ページ目の内容を描画
        renderPage(pageNum)
      });

    common_js.get_element_by_id('gp-prev').addEventListener('click', onPrevPage);
    common_js.get_element_by_id('gp-next').addEventListener('click', onNextPage);

    let canvas_box = common_js.get_element_by_id('gp-texts-docs-canvas-box');
    canvas_box.addEventListener('mouseover', mouseover_event);
    canvas_box.addEventListener('mouseout', mouseout_event);
  });

}

var test = 300 / 1024;


  function renderPage(num) {

    pageRendering = true;
    // Using promise to fetch the page
    pdfDoc.getPage(num).then(function(page) {

      // Canvas に1ページ目の内容を描画
      var scale = 1;

      let canvas_container = common_js.get_element_by_id('gp-texts-docs-canvas-box');
      var viewport = page.getViewport(scale);

      let viewport_width = parseInt(viewport.width);
      let canvas_container_width = parseInt(canvas_container.clientWidth);
      let new_scale = canvas_container_width / viewport_width;

      viewport = page.getViewport(new_scale);


        // var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');



        canvas.height = viewport.height;
        canvas.width = viewport.width;
        var renderContext = {
            canvasContext: context,
            viewport: viewport
        };

        var renderTask = page.render(renderContext);
       // body に挿入
       let canvas_body = common_js.get_element_by_id('gp-canvas-body')

       canvas_body.appendChild(canvas);

       let all_pages =  parseInt( common_js.get_element_by_id('gp-page-count').textContent );
       if( pageNum <= 1 ){

          show_gp_next();
          hide_gp_prev();
       }else if( pageNum == all_pages ){

          hide_gp_next();
       }else if( pageNum > 1 ){

          show_gp_prev();
          show_gp_next();
      }

        //Wait for rendering to finish
        renderTask.promise.then(function () {
            pageRendering = false;
            if (pageNumPending !== null) {
            //New page rendering is pending
            renderPage(pageNumPending);
            pageNumPending = null;
          }
        });
      });
    // Update page counters
    common_js.get_element_by_id('gp-page-num').textContent = num;
  }

  /**
   * If another page rendering in progress, waits until the rendering is
   * finised. Otherwise, executes rendering immediately.
   */
   function queueRenderPage(num) {

    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
   }

  /**
   * Displays previous page.
   */
   function onPrevPage() {
    if (pageNum <= 1) {
        return;
    }
    pageNum--;
    queueRenderPage(pageNum);
   }


  /**
   * Displays next page.
   */
   function onNextPage() {
    if (pageNum >= pdfDoc.numPages) {
        return;
    }
    pageNum++;
    queueRenderPage(pageNum);
   }


   function mouseover_event(){

    let all_pages =  parseInt( common_js.get_element_by_id('gp-page-count').textContent );
    if( pageNum != all_pages ){
        show_gp_next();
    }

    if( pageNum > 1 ){

        show_gp_prev();
    }

   }

   function show_gp_next(){

      common_js.get_element_by_id('gp-next').style.display = 'block';
   }

   function show_gp_prev(){

      common_js.get_element_by_id('gp-prev').style.display = 'block';
   }

    function hide_gp_next(){

      common_js.get_element_by_id('gp-next').style.display = 'none';
   }

   function hide_gp_prev(){

      common_js.get_element_by_id('gp-prev').style.display = 'none';
   }

   function mouseout_event(){

        hide_gp_next();
        hide_gp_prev();

   }