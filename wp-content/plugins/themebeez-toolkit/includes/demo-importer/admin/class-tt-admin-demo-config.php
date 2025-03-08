<?php
/**
 * Theme configurations for demo content.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class - TT_Admin_Demo_Config.
 * Loads demo configuration classes for themes.
 *
 * @since 1.0.0
 */
class TT_Admin_Demo_Config {

	/**
	 * Current active theme.
	 *
	 * @var $theme
	 */
	private $theme = '';

	/**
	 * Theme demo import class config.
	 *
	 * @var $import_class;
	 */
	private $import_class = '';

	/**
	 * Initializes properties and fire hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->theme = wp_get_theme();
		add_filter( 'themebeez_toolkit_demo_content_import', array( $this, 'import_files' ) );
		add_action( 'themebeez_toolkit_after_demo_content_import', array( $this, 'after_import' ) );
	}

	/**
	 * Loads demo import class for the active theme.
	 *
	 * @since 1.0.0
	 */
	private function get_import_class() {

		$supported_themes = $this->supported_themes();
		$demo_class       = '';

		foreach ( $supported_themes as $theme ) {

			$theme_name = isset( $theme['theme_name'] ) ? $theme['theme_name'] : '';

			if ( trim( $theme_name ) === trim( $this->theme ) ) {
				$demo_class = isset( $theme['demo_class'] ) ? $theme['demo_class'] : '';
				break;
			}
		}

		return $demo_class;
	}

	/**
	 * Lists of supported themes.
	 *
	 * @since 1.0.0
	 */
	private function supported_themes() {

		return array(

			'royale_news'        => array(
				'theme_name' => 'Royale News',
				'demo_class' => 'TT_Theme_Demo_Royale_News',
			),
			'style_blog'         => array(
				'theme_name' => 'StyleBlog',
				'demo_class' => 'TT_Theme_Demo_Style_Blog',
			),
			'style_blog_fame'    => array(
				'theme_name' => 'Style Blog Fame',
				'demo_class' => 'TT_Theme_Demo_Style_Blog_Fame',
			),
			'cream_blog'         => array(
				'theme_name' => 'Cream Blog',
				'demo_class' => 'TT_Theme_Demo_Cream_Blog',
			),
			'styleblog_plus'     => array(
				'theme_name' => 'StyleBlog Plus',
				'demo_class' => 'TT_Theme_Demo_StyleBlog_Plus',
			),
			'royale_news_pro'    => array(
				'theme_name' => 'Royale News Pro',
				'demo_class' => 'TT_Theme_Demo_Royale_News_Pro',
			),
			'cream_magazine'     => array(
				'theme_name' => 'Cream Magazine',
				'demo_class' => 'TT_Theme_Demo_Cream_Magazine',
			),
			'cream_magazine_pro' => array(
				'theme_name' => 'Cream Magazine Pro',
				'demo_class' => 'TT_Theme_Demo_Cream_Magazine_Pro',
			),
			'royale_news_lite'   => array(
				'theme_name' => 'Royale News Lite',
				'demo_class' => 'TT_Theme_Demo_Royale_News_Lite',
			),
			'cream_blog_lite'    => array(
				'theme_name' => 'Cream Blog Lite',
				'demo_class' => 'TT_Theme_Demo_Cream_Blog_Lite',
			),
			'cream_blog_pro'     => array(
				'theme_name' => 'Cream Blog Pro',
				'demo_class' => 'TT_Theme_Demo_Cream_Blog_Pro',
			),
			'fascinate'          => array(
				'theme_name' => 'Fascinate',
				'demo_class' => 'TT_Theme_Demo_Fascinate',
			),
			'fascinate_pro'      => array(
				'theme_name' => 'Fascinate Pro',
				'demo_class' => 'TT_Theme_Demo_Fascinate_Pro',
			),
			'orchid_store'       => array(
				'theme_name' => 'Orchid Store',
				'demo_class' => 'TT_Theme_Demo_Orchid_Store',
			),
		);
	}

	/**
	 * Loads demo content import files.
	 *
	 * @since 1.0.0
	 */
	public function import_files() {

		$import_class = $this->get_import_class();

		if ( empty( $import_class ) ) {
			return array();
		}

		return $import_class::import_files();
	}

	/**
	 * Actions to perform after demo content import file.
	 *
	 * @since 1.0.0
	 *
	 * @param int $selected_import Selected import index.
	 */
	public function after_import( $selected_import ) {

		$import_class = $this->get_import_class();

		if ( empty( $import_class ) ) {
			return '';
		}

		$import_class::after_import( $selected_import );
	}
}


new TT_Admin_Demo_Config();
