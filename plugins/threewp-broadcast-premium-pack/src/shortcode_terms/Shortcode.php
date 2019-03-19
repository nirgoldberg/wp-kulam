<?php

namespace threewp_broadcast\premium_pack\shortcode_terms;

/**
	@brief		Shortcode object specifically for terms.
	@since		2016-12-20 22:16:37
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
			case 'et_pb_portfolio':
				$this->set_shortcode( $type );
				$this->add_values( 'include_categories' );
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
			'Elegant Themes Portfolio' => 'et_pb_portfolio',
		];
	}
}
