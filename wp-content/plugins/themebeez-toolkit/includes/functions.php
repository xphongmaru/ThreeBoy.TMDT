<?php
/**
 * Theme related functions.
 * Fires theme notice and setup theme configuration for theme page.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! function_exists( 'themebeez_toolkit_theme' ) ) {
	/**
	 * Returns currently activated theme.
	 *
	 * @since 1.0.0
	 */
	function themebeez_toolkit_theme() {

		$theme = wp_get_theme();

		return $theme;
	}
}


/**
 * Function that gets text-domain of currently activated theme.
 */
if ( ! function_exists( 'themebeez_toolkit_theme_text_domain' ) ) {
	/**
	 * Gets the text domain of currently activated theme.
	 *
	 * @since 1.0.0
	 */
	function themebeez_toolkit_theme_text_domain() {

		$theme = themebeez_toolkit_theme();

		$theme_text_domain = $theme->get( 'TextDomain' );

		return $theme_text_domain;
	}
}


if ( ! function_exists( 'themebeez_toolkit_template_name' ) ) {
	/**
	 * Gets the name of currently activated theme.
	 *
	 * @since 1.0.0
	 */
	function themebeez_toolkit_template_name() {

		$theme = themebeez_toolkit_theme();

		$template = $theme->get( 'Template' );

		return $template;
	}
}

/**
 * Function that gets all the themes by themebeez.
 */
if ( ! function_exists( 'themebeez_toolkit_theme_array' ) ) {
	/**
	 * Definition of supported themes by toolkit.
	 *
	 * @since 1.0.0
	 */
	function themebeez_toolkit_theme_array() {

		return array( 'royale-news', 'cream-blog', 'royale-news-pro', 'cream-blog-pro', 'styleblog-plus', 'cream-magazine', 'cream-magazine-pro', 'fascinate', 'fascinate-pro', 'orchid-store' );
	}
}


/**
 * Function to load theme info
 */
if ( ! function_exists( 'themebeez_toolkit_theme_info_demo_loader' ) ) {
	/**
	 * Displays admin notice and loads configuration for theme page.
	 *
	 * @since 1.0.0
	 */
	function themebeez_toolkit_theme_info_demo_loader() {

		$theme_text_domain = themebeez_toolkit_theme_text_domain();

		if ( ! in_array( $theme_text_domain, themebeez_toolkit_theme_array(), true ) ) {
			return;
		}

		require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/' . $theme_text_domain . '-config.php';

		if (
			'orchid-store' === $theme_text_domain ||
			themebeez_toolkit_template_name() === 'orchid-store'
		) {

			require_once plugin_dir_path( __FILE__ ) . 'simple-mega-menu/class-simple-mega-menu-walker-filter.php';

			require_once plugin_dir_path( __FILE__ ) . 'simple-mega-menu/class-simple-mega-menu-nav-walker.php';

			add_filter(
				'wp_nav_menu_args',
				function ( $args ) {

					return array_merge(
						$args,
						array(
							'walker' => new Simple_Mega_Menu_Nav_Walker(),
						)
					);
				}
			);
		}
	}
}

add_action( 'themebeez_toolkit_load_theme_info_demo', 'themebeez_toolkit_theme_info_demo_loader' );
