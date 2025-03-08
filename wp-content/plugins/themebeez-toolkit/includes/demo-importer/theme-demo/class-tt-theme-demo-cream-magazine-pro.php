<?php
/**
 * Definition of demo content for Cream Magazine Pro theme.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class - TT_Theme_Demo_Cream_Magazine_Pro.
 *
 * @since 1.0.0
 */
class TT_Theme_Demo_Cream_Magazine_Pro extends TT_Theme_Demo {

	/**
	 * Defines demo content files sources.
	 *
	 * @since 1.0.0
	 */
	public static function import_files() {

		$server_url = 'https://themebeez.com/demo-contents/cream-magazine-pro/';

		$demo_urls = array(
			array(
				'import_file_name'           => esc_html__( 'Demo One', 'themebeez-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/contents.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://themebeez.com/demos/?theme=cream-magazine-pro',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Two', 'themebeez-toolkit' ),
				'import_file_url'            => $server_url . 'demo-two/contents.xml',
				'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-two/screenshot.png',
				'demo_url'                   => 'https://themebeez.com/demos/?theme=cream-magazine-pro-ii',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Three', 'themebeez-toolkit' ),
				'import_file_url'            => $server_url . 'demo-three/contents.xml',
				'import_widget_file_url'     => $server_url . 'demo-three/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-three/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-three/screenshot.png',
				'demo_url'                   => 'https://themebeez.com/demos/?theme=cream-magazine-pro-iii',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Four', 'themebeez-toolkit' ),
				'import_file_url'            => $server_url . 'demo-four/contents.xml',
				'import_widget_file_url'     => $server_url . 'demo-four/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-four/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-four/screenshot.png',
				'demo_url'                   => 'https://themebeez.com/demos/?theme=cream-magazine-pro-iv',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Five', 'themebeez-toolkit' ),
				'import_file_url'            => $server_url . 'demo-five/contents.xml',
				'import_widget_file_url'     => $server_url . 'demo-five/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-five/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-five/screenshot.png',
				'demo_url'                   => 'https://themebeez.com/demos/?theme=cream-magazine-pro-v',
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
		$footer_menu  = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

		set_theme_mod(
			'nav_menu_locations',
			array(
				'menu-1' => $primary_menu->term_id,
				'menu-2' => $header_menu->term_id,
				'menu-3' => $footer_menu->term_id,
			)
		);

		update_option( 'show_on_front', 'page' );

		$front_page = new WP_Query( array( 'pagename' => 'home' ) );

		if ( $front_page->have_posts() ) {
			while ( $front_page->have_posts() ) {
				$front_page->the_post();
				update_option( 'page_on_front', get_the_ID() );
			}
			wp_reset_postdata();
		}

		$blog_page = new WP_Query( array( 'pagename' => 'blog' ) );

		if ( $blog_page->have_posts() ) {
			while ( $blog_page->have_posts() ) {
				$blog_page->the_post();
				update_option( 'page_for_posts', get_the_ID() );
			}
			wp_reset_postdata();
		}
	}
}
