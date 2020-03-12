<?php

require_once( TEMP_DIR . '/assets/plugins/lock_page_content/class/class-guild-press-lock-page-content-model.php' );

require_once( TEMP_DIR . '/assets/plugins/lock_page_content/class/class-guild-press-lock-page-content-controller.php' );
if( class_exists('LockContentController') ){
		new LockContentController();
	}


require_once( TEMP_DIR . '/assets/plugins/lock_page_content/class/class-guild-press-lock-page-box.php' );
if( class_exists('LockPageBox') ){
		new LockPageBox();
	}

require_once( TEMP_DIR . '/assets/plugins/lock_page_content/class/class-guild-press-lock-page-quiz-box.php' );
if( class_exists('QuizPageBox') ){
		new QuizPageBox();
	}