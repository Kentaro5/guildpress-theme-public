<?php

require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/class/class-guild-press-ed-texts-docs-uploader-model.php' );

require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/class/class-guild-press-ed-texts-docs-uploader-set-up-url.php' );
if( class_exists('EdTextsDocsSetUpUrl') ){
		new EdTextsDocsSetUpUrl();
	}

require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/class/class-guild-press-ed-texts-docs-uploader-controller.php' );
if( class_exists('EdTextsDocsController') ){
		new EdTextsDocsController();
	}



require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/class/class-guild-press-ed-texts-docs-uploader-custom-post-types.php' );
if( class_exists('AddEdTextsDocsPostTypes') ){
		new AddEdTextsDocsPostTypes();
	}

require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/class/class-guild-press-ed-texts-docs-uploader-lists.php' );
if( class_exists('EdTextsDocsLists') ){
		new EdTextsDocsLists();
	}


require_once( TEMP_DIR . '/assets/plugins/ed_texts_documents_uploader/class/class-guild-press-ed-texts-docs-uploader-meta-box.php' );
if( class_exists('GuildPressEdTextsDocsMetaBox') ){
		new GuildPressEdTextsDocsMetaBox();
	}

