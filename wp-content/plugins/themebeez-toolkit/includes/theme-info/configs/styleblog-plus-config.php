<?php
/**
 * Theme Info Configurations - Style Blog Plus.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! function_exists( 'themebeez_toolkit_styleblog_plus_config' ) ) {
	/**
	 * Configuration of theme page - Royale News Pro.
	 *
	 * @since 1.0.0
	 */
	function themebeez_toolkit_styleblog_plus_config() {

		$config = array(
			'sale_plan'       => 'Pro',
			'menu_name'       => esc_html__( 'StyleBlog Plus Info', 'themebeez-toolkit' ),
			'page_name'       => esc_html__( 'StyleBlog Plus Info', 'themebeez-toolkit' ),
			// Quick links.
			'quick_links'     => array(
				'documentation_url'   => array(
					'title'      => esc_html__( 'Documentation', 'themebeez-toolkit' ),
					'desc'       => esc_html__( 'Stuck due to an issue? Our detailed documentation will surely clear up any confusions you have!', 'themebeez-toolkit' ),
					'icon'       => 'dashicons-media-document',
					'link_title' => esc_html__( 'Read Now', 'themebeez-toolkit' ),
					'link_url'   => 'https://themebeez.com/docs/style-blog-documentation/',
					'link_class' => 'button tt-button button-secondary',
				),
				'theme_support_url'   => array(
					'title'      => esc_html__( 'Support', 'themebeez-toolkit' ),
					'desc'       => esc_html__( 'Get in touch with our support team. You can always submit a support ticket for help.', 'themebeez-toolkit' ),
					'icon'       => 'dashicons-megaphone',
					'link_title' => esc_html__( 'Create Post', 'themebeez-toolkit' ),
					'link_url'   => 'https://themebeez.com/support-forum/style-blog-pro-theme-support/',
					'link_class' => 'button tt-button button-secondary',
				),
				'feature_request_url' => array(
					'title'      => esc_html__( 'Feature Request', 'themebeez-toolkit' ),
					'desc'       => esc_html__( 'Please take a moment to suggest any features that could enhance our product.', 'themebeez-toolkit' ),
					'icon'       => 'dashicons-marker',
					'link_title' => esc_html__( 'Make a request', 'themebeez-toolkit' ),
					'link_url'   => 'https://themebeez.com/support-forum/style-blog-pro-theme-support/',
					'link_class' => 'button tt-button button-secondary',
				),
			),
			// Tabs.
			'tabs'            => array(
				'getting_started'   => esc_html__( 'Getting Started', 'themebeez-toolkit' ),
				'starter_templates' => esc_html__( 'Starter Templates', 'themebeez-toolkit' ),
				'plugins'           => esc_html__( 'Plugins', 'themebeez-toolkit' ),
				'changelog'         => esc_html__( 'Changelog', 'themebeez-toolkit' ),
			),
			// Getting started.
			'getting_started' => array(
				'free' => array(
					array(
						'title'               => esc_html__( 'Site Identity', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Font/Color Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_font_color_section' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Header Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_header_option' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Breadcrumb Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_breadcrumb_option' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Post Page Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_single_post_options' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Footer Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_footer_options' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Post Meta Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_meta_option' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Other Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_other_options' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Slider Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_feat_slider_options' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Blog List Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_blog_option' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Affiliate Product Section', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_affshop_option' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Video Post Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=styleblog_plus_vlog_option' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Color Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=colors' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Header Image', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=header_image' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Background Image', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=background_image' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Menu Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[panel]=nav_menus' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Widgets', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[panel]=widgets' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Homepage Options', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=static_front_page' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
					array(
						'title'               => esc_html__( 'Additional CSS', 'themebeez-toolkit' ),
						'text'                => '',
						'button_label'        => esc_html__( 'Customize', 'themebeez-toolkit' ),
						'button_link'         => esc_url( admin_url( 'customize.php?autofocus[section]=custom_css' ) ),
						'is_button'           => false,
						'recommended_actions' => false,
						'is_new_tab'          => false,
					),
				),
			),
		);

		Themebeez_Toolkit_Theme_Info::init( $config );
	}
}

add_action( 'after_setup_theme', 'themebeez_toolkit_styleblog_plus_config' );
