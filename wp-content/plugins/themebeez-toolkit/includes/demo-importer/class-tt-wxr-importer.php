<?php
/**
 * WXR importer class used in the Themebeez Demo Importer plugin.
 * Needed to extend the TT_Importer_WXR_Importer class to get/set the importer protected variables,
 * for use in the multiple AJAX calls.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

/**
 * Class - TT_WXR_Importer.
 * Needed to extend the TT_Importer_WXR_Importer class to get/set the importer protected variables,
 * for use in the multiple AJAX calls.
 *
 * @since 1.0.0
 */
class TT_WXR_Importer extends TT_Importer_WXR_Importer {

	/**
	 * Class initialization.
	 *
	 * @param array $options Options.
	 */
	public function __construct( $options = array() ) {

		parent::__construct( $options );

		// Set current user to $mapping variable.
		// Fixes the [WARNING] Could not find the author for ... log warning messages.
		$current_user_obj = wp_get_current_user();
		$this->mapping['user_slug'][ $current_user_obj->user_login ] = $current_user_obj->ID;
	}

	/**
	 * Get all protected variables from the TT_Importer_WXR_Importer needed for continuing the import.
	 */
	public function get_importer_data() {

		return array(
			'mapping'            => $this->mapping,
			'requires_remapping' => $this->requires_remapping,
			'exists'             => $this->exists,
			'user_slug_override' => $this->user_slug_override,
			'url_remap'          => $this->url_remap,
			'featured_images'    => $this->featured_images,
		);
	}

	/**
	 * Sets all protected variables from the TT_Importer_WXR_Importer needed for continuing the import.
	 *
	 * @param array $data with set variables.
	 */
	public function set_importer_data( $data ) {

		$this->mapping            = empty( $data['mapping'] ) ? array() : $data['mapping'];
		$this->requires_remapping = empty( $data['requires_remapping'] ) ? array() : $data['requires_remapping'];
		$this->exists             = empty( $data['exists'] ) ? array() : $data['exists'];
		$this->user_slug_override = empty( $data['user_slug_override'] ) ? array() : $data['user_slug_override'];
		$this->url_remap          = empty( $data['url_remap'] ) ? array() : $data['url_remap'];
		$this->featured_images    = empty( $data['featured_images'] ) ? array() : $data['featured_images'];
	}
}
