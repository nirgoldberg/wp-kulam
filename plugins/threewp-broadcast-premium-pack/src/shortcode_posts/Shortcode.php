<?php

namespace threewp_broadcast\premium_pack\shortcode_posts;

/**
	@brief		Shortcode container.
	@since		2018-03-22 17:11:23
**/
class Shortcode
	extends \threewp_broadcast\premium_pack\classes\shortcode_items\Shortcode
{
	/**
		@brief		Apply a wizard.
		@since		2016-07-14 13:03:27
	**/
	public function apply_wizard( $type )
	{
		switch( $type )
		{
			case 'wpsm_ac':
				$this->set_shortcode( $type );
				$this->add_value( 'id' );
			break;
			default:
				$this->set_shortcode( 'default' );
				$this->add_value( 'id' );
		}
	}

	public function get_wizard_options()
	{
		return [
			'Empty shortcode' => '',
			'Accordion FAQ' => 'WPSM_AC',
		];
	}
}
