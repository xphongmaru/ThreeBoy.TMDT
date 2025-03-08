<?php
/**
 * Definition of demo content for Fascinate theme.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class - TT_Theme_Demo_Fascinate.
 *
 * @since 1.0.0
 */
class TT_Theme_Demo_Fascinate extends TT_Theme_Demo {

	/**
	 * Defines demo content files sources.
	 *
	 * @since 1.0.0
	 */
	public static function import_files() {

		$server_url = 'https://themebeez.com/demo-contents/fascinate/';

		$demo_urls = array(
			array(
				'import_file_name'           => esc_html__( 'Demo One', 'themebeez-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/contents.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.jpg',
				'demo_url'                   => 'https://demo.themebeez.com/demos-2/fascinate/',
			),
		);

		return $demo_urls;
	}

	/**
	 * Action to perfom after demo content import.
	 *
	 * @since 1.0.0
	 *
	 * @param string $selected_import Selected import.
	 */
	public static function after_import( $selected_import ) {

		update_option( 'widget_block', array() );

		$primary_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		$header_menu  = get_term_by( 'name', 'Top Menu', 'nav_menu' );

		set_theme_mod(
			'nav_menu_locations',
			array(
				'menu-1' => $primary_menu->term_id,
				'menu-2' => $header_menu->term_id,
			)
		);
	}
}
