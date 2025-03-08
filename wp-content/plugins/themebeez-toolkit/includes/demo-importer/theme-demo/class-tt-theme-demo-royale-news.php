<?php
/**
 * Definition of demo content for Royale News theme.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class - TT_Theme_Demo_Royale_News.
 *
 * @since 1.0.0
 */
class TT_Theme_Demo_Royale_News extends TT_Theme_Demo {

	/**
	 * Defines demo content files sources.
	 *
	 * @since 1.0.0
	 */
	public static function import_files() {

		$server_url = 'https://themebeez.com/demo-contents/royale-news/';

		$demo_urls = array(
			array(
				'import_file_name'           => __( 'Demo One', 'themebeez-toolkit' ),
				'import_file_url'            => $server_url . 'contents.xml',
				'import_widget_file_url'     => $server_url . 'widgets.wie',
				'import_customizer_file_url' => $server_url . 'customizer.dat',
				'import_preview_image_url'   => $server_url . 'screenshot.png',
				'demo_url'                   => 'https://themebeez.com/demos/?theme=royale-news',
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
		$social_menu  = get_term_by( 'name', 'Social Menu', 'nav_menu' );
		$footer_menu  = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

		set_theme_mod(
			'nav_menu_locations',
			array(
				'primary' => $primary_menu->term_id,
				'social'  => $social_menu->term_id,
				'footer'  => $footer_menu->term_id,
			)
		);

		$business_category    = get_term_by( 'slug', 'business', 'category' );
		$business_category_id = $business_category->term_id;

		$politics_category    = get_term_by( 'slug', 'politics', 'category' );
		$politics_category_id = $politics_category->term_id;

		$travel_category    = get_term_by( 'slug', 'travel', 'category' );
		$travel_category_id = $travel_category->term_id;

		$sports_category    = get_term_by( 'slug', 'sports', 'category' );
		$sports_category_id = $sports_category->term_id;

		$fashion_category    = get_term_by( 'slug', 'fashion', 'category' );
		$fashion_category_id = $fashion_category->term_id;

		$events_category    = get_term_by( 'slug', 'fashion', 'category' );
		$events_category_id = $events_category->term_id;

		$technology_category    = get_term_by( 'slug', 'technology', 'category' );
		$technology_category_id = $technology_category->term_id;

		$banner_widget = get_option( 'widget_royale-news-main-highlight-two' );

		$banner_widget[1]['cat_1'] = absint( $business_category_id );
		$banner_widget[1]['cat_2'] = absint( $politics_category_id );
		$banner_widget[1]['cat_3'] = absint( $travel_category_id );

		update_option( 'widget_royale-news-main-highlight-two', $banner_widget );

		$first_widget = get_option( 'widget_royale-news-news-layout-widget-one' );

		$first_widget[1]['cat'] = absint( $politics_category_id );

		$first_widget[2]['cat'] = absint( $fashion_category_id );

		update_option( 'widget_royale-news-news-layout-widget-one', $first_widget );

		$second_widget = get_option( 'widget_royale-news-news-layout-widget-two' );

		$second_widget[2]['cat'] = absint( $sports_category_id );

		$second_widget[3]['cat'] = absint( $technology_category_id );

		update_option( 'widget_royale-news-news-layout-widget-two', $second_widget );

		$third_widget = get_option( 'widget_royale-news-bottom-news-widget-one' );

		$third_widget[1]['cat'] = array( absint( $business_category_id ), absint( $travel_category_id ) );

		update_option( 'widget_royale-news-bottom-news-widget-one', $third_widget );

		set_theme_mod( 'royale_news_ticker_news_category', $events_category_id );

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
