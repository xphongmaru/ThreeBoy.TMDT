<?php
/**
 * Abstract class for defining demo content import sources, actions before and after the import.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'TT_Theme_Demo' ) ) {
	/**
	 * Abstract class - TT_Theme_Demo
	 *
	 * @since 1.0.0
	 */
	abstract class TT_Theme_Demo {

		/**
		 * Defines demo content files sources.
		 *
		 * @since 1.0.0
		 */
		public static function import_files() {

			$demo_urls = array(
				'import_file_name'           => '',
				'import_file_url'            => '',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => '',
				'import_preview_image_url'   => '',
				'demo_url'                   => '',
				'import_notice'              => '',
			);

			return $demo_urls;
		}

		/**
		 * Action to perfom after demo content import.
		 *
		 * @since 1.0.0
		 */
		public static function before_import() {}

		/**
		 * Action to perfom after demo content import.
		 *
		 * @since 1.0.0
		 *
		 * @param string $selected_import Selected import.
		 */
		public static function after_import( $selected_import ) {}
	}
}
