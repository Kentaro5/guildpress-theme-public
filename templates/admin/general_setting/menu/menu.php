<div id="guild_press_tabs" class="nav-tab-wrapper">
	<a class="nav-tab <?php if( $gp_data['active'] === 'general' || $gp_data['active'] === '' ) { ?> nav-tab-active <?php } ?>" href="<?php echo $this->wpfunc->esc_attr( $gp_data['general_link'] ); ?>">
		一般設定
	</a>
	<a class="nav-tab <?php if( $gp_data['active'] === 'google_calendar_settings' ) { ?> nav-tab-active <?php } ?>" href="<?php echo $this->wpfunc->esc_attr( $gp_data['google_calendar_link'] ); ?>">
		Googleカレンダー設定
	</a>
</div>