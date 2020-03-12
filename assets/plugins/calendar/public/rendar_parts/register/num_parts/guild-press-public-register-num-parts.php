<?php
/**
 *
 */
class Guild_Press_Public_Register_Num
{

	public function return_register_border( $register_percentage )
	{

		if( ! is_int( $register_percentage ) ) {
			return;
		}

		$html = '';
		if( $register_percentage <= 25 ){

			$html .= '<p class="calendar-border-green"></p>';

		}elseif( $register_percentage <= 50 ){

			$html .= '<p class="calendar-border-orange"></p>';

		}elseif( $register_percentage <= 99 ) {

			$html .= '<p class="calendar-border-red"></p>';

		}elseif( $register_percentage === 100 ){

			$html .= '<p class="calendar-border-gray"></p>';

		}
		return $html;

	}
}