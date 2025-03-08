<?php
/**
 * Define general settings fields of plugin.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes/setting-functions/fields
 */

if ( ! function_exists( 'addonify_compare_products_general_setting_fields' ) ) {
	/**
	 * General setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_general_setting_fields() {

		return array(
			'enable_product_comparison'            => array(
				'label'       => __( 'Enable Products Comparison', 'addonify-compare-products' ),
				'description' => __( 'If disabled, products comparison will not be functional.', 'addonify-compare-products' ),
				'type'        => 'switch',
				'badge'       => 'Required',
				'value'       => addonify_compare_products_get_option( 'enable_product_comparison' ),
			),
			'enable_login_required'                => array(
				'label'       => __( 'Enable Login Required', 'addonify-compare-products' ),
				'description' => __( 'If enabled, products comparison will be available only to logged in users.', 'addonify-compare-products' ),
				'type'        => 'switch',
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'enable_login_required' ),
			),
			'enable_product_comparison_on_archive' => array(
				'label'       => __( 'Enable Products Comparison on Products Loop', 'addonify-compare-products' ),
				'description' => __( 'If disabled, products comparison will not be functional on products loop.', 'addonify-compare-products' ),
				'type'        => 'switch',
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'enable_product_comparison_on_archive' ),
			),
			'enable_product_comparison_on_single'  => array(
				'label'       => __( 'Enable Products Comparison on Product Single Page', 'addonify-compare-products' ),
				'description' => __( 'If disabled, products comparison will not be functional on product single page.', 'addonify-compare-products' ),
				'type'        => 'switch',
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'enable_product_comparison_on_single' ),
			),
			'compare_products_display_type'        => array(
				'type'        => 'select',
				'placeholder' => __( 'Select a page', 'addonify-compare-products' ),
				'label'       => __( 'Products Comparison Display', 'addonify-compare-products' ),
				'description' => __( 'Select a method to display product comparison.', 'addonify-compare-products' ),
				'dependent'   => array( 'enable_product_comparison' ),
				'choices'     => array(
					'popup' => __( 'Popup Modal', 'addonify-compare-products' ),
					'page'  => __( 'Page', 'addonify-compare-products' ),
				),
				'value'       => addonify_compare_products_get_option( 'compare_products_display_type' ),
			),
			'compare_page'                         => array(
				'type'        => 'select',
				'placeholder' => __( 'Select a page', 'addonify-compare-products' ),
				'label'       => __( 'Products Comparison Page', 'addonify-compare-products' ),
				'description' => __( 'Select a page to display products comparison table.', 'addonify-compare-products' ),
				'dependent'   => array( 'enable_product_comparison' ),
				'choices'     => addonify_compare_products_get_pages(),
				'value'       => addonify_compare_products_get_option( 'compare_page' ),
			),
			'compare_products_cookie_expires'      => array(
				'type'        => 'number',
				'style'   	  => 'buttons-plus-minus',
				'min'         => 1,
				'max'         => 365,
				'step'        => 1,
				'label'       => __( 'Time to Save Compare Data', 'addonify-compare-products' ),
				'dependent'   => array( 'enable_product_comparison' ),
				'description' => __( 'Set the number of days to save the compare products data in browser cookie.', 'addonify-compare-products' ),
				'value'       => addonify_compare_products_get_option( 'compare_products_cookie_expires' ),
			),
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_styles_settings_fields' ) ) {
	/**
	 * Style setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_styles_settings_fields() {

		return array(
			'load_styles_from_plugin' => array(
				'type'        => 'switch',
				'className'   => '',
				'label'       => __( 'Enable dynamic styles', 'addonify-compare-products' ),
				'description' => __( 'Enable to apply styles and colors from the plugin.', 'addonify-compare-products' ),
				'value'       => addonify_compare_products_get_option( 'load_styles_from_plugin' ),
			),
		);
	}
}

/**
 * Add setting fields into the global setting fields array.
 *
 * @since 1.0.0
 * @param mixed $settings_fields Setting fields.
 * @return array
 */
function addonify_compare_products_add_general_fields_to_settings_fields( $settings_fields ) {

	$settings_fields = array_merge( $settings_fields, addonify_compare_products_general_setting_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_compare_products_styles_settings_fields() );

	return $settings_fields;
}
add_filter( 'addonify_compare_products_settings_fields', 'addonify_compare_products_add_general_fields_to_settings_fields' );
