<?php
/**
 * Definition of demo content for Orchid Store theme.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class - TT_Theme_Demo_Orchid_Store.
 *
 * @since 1.0.0
 */
class TT_Theme_Demo_Orchid_Store extends TT_Theme_Demo {

	/**
	 * Defines demo content files sources.
	 *
	 * @since 1.0.0
	 */
	public static function import_files() {

		if ( class_exists( 'Orchid_Store_Pro_Demo_Import' ) ) {

			$demo_class = new Orchid_Store_Pro_Demo_Import();

			return $demo_class->demo_import();
		} else {

			$server_url = 'https://themebeez.com/demo-contents/orchid-store/';

			$demo_urls = array(
				array(
					'import_file_name'           => esc_html__( 'Default Demo', 'themebeez-toolkit' ),
					'import_file_url'            => $server_url . 'demo-one/contents.xml',
					'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
					'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
					'import_preview_image_url'   => $server_url . 'demo-one/screenshot.jpg',
					'demo_url'                   => 'https://demo.themebeez.com/demos-2/orchid-store/',
				),
				array(
					'import_file_name'           => esc_html__( 'Elementor Demo', 'themebeez-toolkit' ),
					'import_file_url'            => $server_url . 'demo-two/contents.xml',
					'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
					'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
					'import_preview_image_url'   => $server_url . 'demo-two/screenshot.jpg',
					'demo_url'                   => 'https://demo.themebeez.com/demos-2/orchid-store-ii/',
				),
				array(
					'import_file_name'           => esc_html__( 'RTL Demo', 'themebeez-toolkit' ),
					'import_file_url'            => $server_url . 'demo-three/contents.xml',
					'import_widget_file_url'     => $server_url . 'demo-three/widgets.wie',
					'import_customizer_file_url' => $server_url . 'demo-three/customizer.dat',
					'import_preview_image_url'   => $server_url . 'demo-three/screenshot.jpg',
					'demo_url'                   => 'https://demo.themebeez.com/demos-2/orchid-store-iii/',
				),
			);

			return $demo_urls;
		}
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

		$primary_menu   = get_term_by( 'name', 'Primary menu', 'nav_menu' );
		$secondary_menu = get_term_by( 'name', 'Secondary menu', 'nav_menu' );

		set_theme_mod(
			'nav_menu_locations',
			array(
				'menu-1' => $primary_menu->term_id,
				'menu-2' => $secondary_menu->term_id,
			)
		);

		$import_file_name = isset( $selected_import['import_file_name'] ) ? $selected_import['import_file_name'] : '';

		$front_page_query_arg = array();

		if ( ! empty( $import_file_name ) ) {
			if ( 'Elementor Demo' === $import_file_name || 'RTL Demo' === $import_file_name ) {
				if ( 'Elementor Demo' === $import_file_name && class_exists( 'Orchid_Store_Pro' ) ) {
					$front_page_query_arg['pagename'] = 'homepage';
				} else {
					$front_page_query_arg['pagename'] = 'elementor-front-page';
				}
			} else {
				$front_page_query_arg['pagename'] = 'homepage';
			}
		}

		$front_page = new WP_Query( $front_page_query_arg );

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

		update_option( 'show_on_front', 'page' );
	}
}
