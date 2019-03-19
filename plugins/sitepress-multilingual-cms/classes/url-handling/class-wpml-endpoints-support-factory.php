<?php

class WPML_Endpoints_Support_Factory implements IWPML_Frontend_Action_Loader, IWPML_Backend_Action_Loader, IWPML_Deferred_Action_Loader {

	public function get_load_action() {
		return 'plugins_loaded';
	}

	/**
	 * @return WPML_Endpoints_Support
	 */
	public function create() {
		global $sitepress;

		if ( defined( 'WPML_ST_VERSION' ) ) {
			$translatable_element_factory = new WPML_Translation_Element_Factory( $sitepress );

			return new WPML_Endpoints_Support( $translatable_element_factory, $sitepress->get_current_language(), $sitepress->get_default_language() );
		}

		return null;
	}
}