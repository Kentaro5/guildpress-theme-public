<?php

/**
 * 
 */
class Guild_Press_Calendar_Metabox
{

	public function get_metabox_by_metabox_name( $metabox_name='', $metabox )
	{
		switch ( $metabox_name ) {

			case 'register_schedule':
				settings_fields( SLUGNAME.'_register_schedule' );
				wp_nonce_field( SLUGNAME.'_register_schedule', 'register_schedule', false );
				wp_nonce_field( 'notification', 'notification-nonce', false );
				?>
				<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

					<?php do_meta_boxes( $metabox, 'normal', null ); ?>

				</div>

				<?php submit_button(); ?>
				<?php
				break;

			case 'edit_schedule':
				settings_fields( SLUGNAME.'_edit_schedule' );
				wp_nonce_field( SLUGNAME.'_edit_schedule', 'edit_schedule', false );
				wp_nonce_field( 'notification', 'notification-nonce', false );

				?>
				<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

					<?php do_meta_boxes( $metabox, 'normal', null ); ?>

				</div>

				<?php submit_button(); ?>
				<?php
				break;

			case 'register_schedule_list':
				settings_fields( SLUGNAME.'_register_schedule_list' );
				wp_nonce_field( SLUGNAME.'_register_schedule_list', 'register_schedule_list', false );
				wp_nonce_field( 'notification', 'notification-nonce', false );
				?>
				<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

					<?php do_meta_boxes( $metabox, 'normal', null ); ?>

				</div>
				<?php
				break;

			case 'schedule_email_settings':
				settings_fields( SLUGNAME.'_schedule_email_settings' );
				wp_nonce_field( SLUGNAME.'_schedule_email_settings', 'schedule_email_settings', false );
				wp_nonce_field( 'notification', 'notification-nonce', false );

				?>
				<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

					<?php do_meta_boxes( $metabox, 'normal', null ); ?>

				</div>

				<?php submit_button(); ?>
				<?php
				break;

			case 'general':
				settings_fields( SLUGNAME.'_general_settings' );
				wp_nonce_field( SLUGNAME.'_reserved_calender', 'reserved_calender', false );
				wp_nonce_field( 'notification', 'notification-nonce', false );

				?>
				<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

					<?php do_meta_boxes( $metabox, 'normal', null ); ?>

				</div>

				<?php

				break;

			default:
				settings_fields( SLUGNAME.'_general_settings' );
				wp_nonce_field( SLUGNAME.'_reserved_calender', 'reserved_calender', false );
				wp_nonce_field( 'notification', 'notification-nonce', false );

				?>
				<div id="<?php echo SLUGNAME.'_settings'; ?>" class="metabox-holder">

					<?php do_meta_boxes( $metabox, 'normal', null ); ?>

				</div>

				<?php

				break;

		}
	}

	public function get_metabox_name( $tab='' )
	{
		switch ( $tab ) {
			case 'register_schedule':
				return SLUGNAME.'_register_schedule';

			case 'register_schedule_list':
				return SLUGNAME.'_register_schedule_list';

			case 'edit_schedule':
				return SLUGNAME.'_edit_schedule';

			case 'schedule_email_settings':
				return SLUGNAME.'_schedule_email_settings';

			case 'general':
				return SLUGNAME.'_settings';

			default:
				return SLUGNAME.'_settings';
		}
	}

}