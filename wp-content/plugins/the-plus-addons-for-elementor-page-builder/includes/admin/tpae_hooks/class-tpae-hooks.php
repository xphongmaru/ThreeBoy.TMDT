<?php
/**
 * The file store Database Default Entry
 *
 * @link        https://posimyth.com/
 * @since       6.0.0
 *
 * @package     the-plus-addons-for-elementor-page-builder
 */

/**Exit if accessed directly.*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tpae_Hooks' ) ) {

	/**
	 * Tpae_Hooks
	 *
	 * @since 6.0.0
	 */
	class Tpae_Hooks {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Member Variable
		 *
		 * @var global_setting
		 */
		public $global_setting = array();

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since 6.0.0
		 */
		public function __construct() {
			add_action( 'tpae_db_default', array( $this, 'tpae_db_default' ), 10 );
			add_filter( 'tpae_get_post_type', array( $this, 'tpae_get_post_type' ), 10, 2 );
			add_filter( 'tpae_enable_widgets', array( $this, 'tpae_enable_widgets' ), 10, 1 );
			add_filter( 'tpae_remove_backend_files', array( $this, 'tpae_remove_backend_files' ), 10, 1 );
		}

		/**
		 * Create Default widget entry
		 *
		 * @since 6.0.0
		 */
		public function tpae_db_default() {

			// Get Widget List.
			$default_load = get_option( 'theplus_options' );
			if ( empty( $default_load ) ) {
				$theplus_options['check_elements']  = array( 'tp_accordion', 'tp_adv_text_block', 'tp_blockquote', 'tp_blog_listout', 'tp_button', 'tp_contact_form_7', 'tp_countdown', 'tp_clients_listout', 'tp_gallery_listout', 'tp_flip_box', 'tp_heading_animation', 'tp_header_extras', 'tp_heading_title', 'tp_info_box', 'tp_navigation_menu_lite', 'tp_page_scroll', 'tp_progress_bar', 'tp_number_counter', 'tp_pricing_table', 'tp_scroll_navigation', 'tp_social_icon', 'tp_tabs_tours', 'tp_team_member_listout', 'tp_testimonial_listout', 'tp_video_player', 'tp_plus_form' );
				$theplus_options['extras_elements'] = array();

				add_option( 'theplus_options', $theplus_options, '', 'on' );
			} elseif ( ! is_array( $default_load['check_elements'] ) || ! is_array( $default_load['extras_elements'] ) ) {
					$theplus_options['check_elements']  = is_array( $default_load['check_elements'] ) ? $default_load['check_elements'] : array();
					$theplus_options['extras_elements'] = is_array( $default_load['extras_elements'] ) ? $default_load['extras_elements'] : array();

					update_option( 'theplus_options', $theplus_options, '', 'on' );
			}

			// Get Listing Data.
			$get_listing_data = get_option( 'post_type_options' );
			if ( empty( $get_listing_data ) ) {
				$def_listing_data = array(
					'client_post_type'      => 'disable',
					'testimonial_post_type' => 'disable',
					'team_member_post_type' => 'disable',
				);

				add_option( 'post_type_options', $def_listing_data, '', 'on' );
			}

			// Get custom css & js.
			$get_styling_data = get_option( 'theplus_styling_data' );
			if ( empty( $get_styling_data ) ) {
				$def_styling_data = array(
					'tp_styling_hidden'         => 'hidden',
					'theplus_custom_css_editor' => '',
					'theplus_custom_js_editor'  => '',
				);

				add_option( 'theplus_styling_data', $def_styling_data, '', 'on' );
			}

			$get_theplus_performance = get_option( 'theplus_performance' );
			if ( empty( $get_theplus_performance ) ) {
				$set_theplus_performance = array(
					'plus_cache_option' => 'separate',
				);

				add_option( 'theplus_performance', $set_theplus_performance, '', 'on' );
			}

			// Set Extra Option.
			$get_extra_option = get_option( 'theplus_api_connection_data' );
			if ( empty( $get_extra_option ) ) {
				$set_extra_option = array(
					'plus_lazyload_opt'                  => 'disable',
					'plus_lazyload_opt_anim'             => 'fade',
					'theplus_facebook_app_id'            => '',
					'load_icons_mind'                    => 'disable',
					'gmap_api_switch'                    => 'enable',
					'load_pre_loader_func'               => 'disable',
					'scroll_animation_offset'            => 85,
					'theplus_site_key_recaptcha'         => '',
					'theplus_secret_key_recaptcha'       => '',
					'theplus_facebook_app_secret'        => '',
					'theplus_google_client_id'           => '',
					'theplus_google_analytics_id'        => '',
					'theplus_facebook_pixel_id'          => '',
					'load_icons_mind_ids'                => '',
					'theplus_google_map_api'             => '',
					'theplus_mailchimp_api'              => '',
					'theplus_mailchimp_id'               => '',
					'load_pre_loader_lottie_js'          => 'on',
					'load_pre_loader_func_ids'           => '',
					'dynamic_category_thumb_check'       => 'on',
					'theplus_woo_swatches_switch'        => 'on',
					'theplus_custom_field_video_switch'  => 'on',
					'theplus_woo_recently_viewed_switch' => 'on',
					'theplus_woo_countdown_switch'       => 'on',
					'theplus_woo_thank_you_page_select'  => '2',
					'bodymovin_load_js_check'            => 'on',
				);

				add_option( 'theplus_api_connection_data', $set_extra_option, '', 'on' );
			}
		}

		/**
		 * Create for the get Post types
		 *
		 * @since 6.0.0
		 */
		public function tpae_get_post_type( $post_type, $name ) {

			$get_post_type = get_option( 'post_type_options' );

			$values = '';
			if ( 'post_type' === $post_type ) {
				if ( isset( $get_post_type[ $name ] ) && ! empty( $get_post_type[ $name ] ) ) {
					$values = $get_post_type[ $name ];
				}
			}

			return $values;
		}

		/**
		 * Enable all widgets.
		 *
		 * @since 6.1.3
		 */
		public function tpae_enable_widgets( $type = '' ) {

			$widget_data = get_option( 'theplus_options' );

			if ( in_array( 'widgets', $type, true ) ) {
				$check_elements = array(
					'tp_accordion',
					'tp_age_gate',
					'tp_audio_player',
					'tp_blockquote',
					'tp_button',
					'tp_breadcrumbs_bar',
					'tp_chart',
					'tp_countdown',
					'tp_coupon_code',
					'tp_carousel_anything',
					'tp_dynamic_categories',
					'tp_dark_mode',
					'tp_heading_title',
					'tp_info_box',
					'tp_messagebox',
					'tp_navigation_menu',
					'tp_number_counter',
					'tp_progress_bar',
					'tp_pricing_list',
					'tp_post_title',
					'tp_pricing_table',
					'tp_protected_content',
					'tp_post_content',
					'tp_post_featured_image',
					'tp_pre_loader',
					'tp_post_navigation',
					'tp_post_author',
					'tp_post_comment',
					'tp_post_meta',
					'tp_row_background',
					'tp_style_list',
					'tp_syntax_highlighter',
					'tp_site_logo',
					'tp_table',
					'tp_table_content',
					'tp_tabs_tours',
					'tp_adv_text_block',
					'tp_google_map',
					'tp_video_player',
					'tp_wp_login_register',
					'tp_post_search',
					'tp_header_extras',
					'tp_horizontal_scroll_advance',
					'tp_image_factory',
					'tp_mobile_menu',
					'tp_navigation_menu_lite',
					'tp_page_scroll',
					'tp_scroll_sequence',
					'tp_design_tool',
					'tp_advanced_buttons',
					'tp_advanced_typography',
					'tp_advertisement_banner',
					'tp_shape_divider',
					'tp_animated_service_boxes',
					'tp_heading_animation',
					'tp_before_after',
					'tp_carousel_remote',
					'tp_circle_menu',
					'tp_cascading_image',
					'tp_draw_svg',
					'tp_dynamic_device',
					'tp_flip_box',
					'tp_hotspot',
					'tp_hovercard',
					'tp_wp_bodymovin',
					'tp_smooth_scroll',
					'tp_morphing_layouts',
					'tp_mouse_cursor',
					'tp_off_canvas',
					'tp_process_steps',
					'tp_scroll_navigation',
					'tp_switcher',
					'tp_timeline',
					'tp_unfold',
					'tp_blog_listout',
					'tp_clients_listout',
					'tp_dynamic_listing',
					'tp_dynamic_smart_showcase',
					'tp_gallery_listout',
					'tp_product_listout',
					'tp_team_member_listout',
					'tp_testimonial_listout',
					'tp_search_bar',
					'tp_search_filter',
					'tp_social_embed',
					'tp_social_feed',
					'tp_social_icon',
					'tp_social_reviews',
					'tp_social_sharing',
					'tp_contact_form_7',
					'tp_everest_form',
					'tp_plus_form',
					'tp_gravity_form',
					'tp_mailchimp',
					'tp_meeting_scheduler',
					'tp_ninja_form',
					'tp_wp_forms',
					'tp_caldera_forms',
					'tp_woo_cart',
					'tp_woo_checkout',
					'tp_woo_compare',
					'tp_woo_wishlist',
					'tp_wp_quickview',
					'tp_woo_multi_step',
					'tp_woo_myaccount',
					'tp_woo_order_track',
					'tp_woo_single_basic',
					'tp_woo_single_image',
					'tp_woo_single_pricing',
					'tp_woo_single_tabs',
					'tp_woo_thank_you',
				);

				if ( isset( $widget_data['check_elements'] ) ) {
					$widget_data['check_elements'] = $check_elements;
				}
			}

			if ( in_array( 'extensions', $type, true ) ) {
				$extensions = array(
					'plus_cross_cp',
					'plus_equal_height',
					'plus_section_column_link',
					'column_custom_css',
					'section_custom_css',
					'custom_width_column',
					'column_mouse_cursor',
					'order_sort_column',
					'column_sticky',
					'plus_adv_shadow',
					'plus_glass_morphism',
					'section_scroll_animation',
					'plus_display_rules',
					'plus_event_tracker',
				);

				if ( isset( $widget_data['extras_elements'] ) ) {
					$widget_data['extras_elements'] = $extensions;
				}
			}

			update_option( 'theplus_options', $widget_data );
		}

		/**
		 * Remove backend in directory files
		 *
		 * @since 6.1.3
		 */
		public function tpae_remove_backend_files( $type = '' ) {

			if ( in_array( 'backend', $type, true ) ) {

				$files_to_delete = array(
					L_THEPLUS_ASSET_PATH . '/theplus.min.css',
					L_THEPLUS_ASSET_PATH . '/theplus.min.js',
				);

				foreach ( $files_to_delete as $file ) {
					if ( file_exists( $file ) ) {
						wp_delete_file( str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, str_replace( array( '//', '\\\\' ), array( '/', '\\' ), $file ) ) );
					}
				}

				$action_page = 'tpae_backend_cache';
				if ( false === get_option( $action_page ) ) {
					add_option( $action_page, time() );
				} else {
					update_option( $action_page, time() );
				}
			}
		}
	}

	Tpae_Hooks::get_instance();
}
