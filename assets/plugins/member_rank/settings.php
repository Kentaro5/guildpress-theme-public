<?php

require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-member-rank-model.php' );

require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-member-rank-controller.php' );
if( class_exists('MemberRankController') ){
		new MemberRankController();
	}



require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-add-gpmember-rank-items.php' );
if( class_exists('AddGpMemberRankItem') ){
		new AddGpMemberRankItem();
	}

require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-member-rank-custom-post-types.php' );
if( class_exists('AddMemberRankPostTypes') ){
		new AddMemberRankPostTypes();
	}

require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-member-rank-lists.php' );
if( class_exists('RankLists') ){
		new RankLists();
	}


require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-member-rank-meta-box.php' );
if( class_exists('GuildPressMemberRankMetaBox') ){
		new GuildPressMemberRankMetaBox();
	}

require_once( TEMP_DIR . '/assets/plugins/member_rank/class/class-guild-press-page-block-by-member-rank-box.php' );
if( class_exists('PageBlockByMemberRank') ){
		new PageBlockByMemberRank();
	}

