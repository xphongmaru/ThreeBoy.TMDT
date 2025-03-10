<?php
/**
 * The file store Database Default Entry
 *
 * @link        https://posimyth.com/
 * @since       6.1.4
 *
 * @package     the-plus-addons-for-elementor-page-builder
 */

/**Exit if accessed directly.*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tpae_Widgets_Scan' ) ) {

	/**
	 * Tpae_Widgets_Scan
	 *
	 * @since 6.1.4
	 */
	class Tpae_Widgets_Scan {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

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
		 * @since 6.1.4
		 */
		public function __construct() {
			add_filter( 'tpae_widget_scan', array( $this, 'tpae_widget_scan' ), 10, 2 );
		}

		/**
		 * Scan Unused Widget
		 *
		 * @since 6.1.4
		 *
		 * @param array $type    Optional. An array of strings specifying the type of scan to perform.
		 */
		public function tpae_widget_scan( $type = array() ) {
			if ( in_array( 'get_unused_widgets', $type, true ) ) {
				return $this->tpae_get_elements_status_scan();
			}

			if ( in_array( 'get_wdkit_unused_widgets', $type, true ) ) {
				$widgets = $this->tpae_get_elements_status_scan();
				if ( ! empty( $widgets['widgets'] ) ) {
					$check_elements = array_keys( $widgets['widgets'] );
					$widget_data    = get_option( 'theplus_options' );
					if ( isset( $widget_data['check_elements'] ) ) {
						$widget_data['check_elements'] = $check_elements;
					}
					update_option( 'theplus_options', $widget_data );
				}
				return $this->tpae_set_response( true, 'success.', 'success.' );
			}
		}

		/**
		 * Scan Widget : Get all Scan Widget list
		 *
		 * @since 6.1.4
		 */
		public function tpae_get_elements_status_scan() {

			if ( ! current_user_can( 'install_plugins' ) ) {
				$response = $this->tpae_set_response( false, 'Invalid nonce.', 'The security check failed. Please refresh the page and try again.' );
				return $response;
			}

			global $wpdb;

			$post_ids = $wpdb->get_col( 'SELECT `post_id` FROM `' . $wpdb->postmeta . '`WHERE `meta_key` = \'_elementor_version\';' );

			// New & Optimize Query.
			// $query = " SELECT MIN(id) AS post_id FROM {$wpdb->posts} WHERE post_type = 'revision' GROUP BY post_title HAVING COUNT(*) > 1 ";
			// $post_ids = $wpdb->get_col($query);

			$tp_widgets_list = '';

			$page = ! empty( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

			$theplus_options = get_option( 'theplus_options' );
			if ( ! empty( $theplus_options ) && isset( $theplus_options['check_elements'] ) ) {
				$tp_widgets_list = $theplus_options['check_elements'];
			}

			if ( empty( $post_ids ) ) {
				$output['message'] = 'All Unused Widgets Found!';
				$output['widgets'] = $tp_widgets_list;

				return $output;
			}

			$scan_post_ids = array();
			$countwidgets  = array();

			foreach ( $post_ids as $post_id ) {
				if ( 'revision' === get_post_type( $post_id ) ) {
					continue;
				}

				$get_widgets = $this->tpae_check_elements_status_scan( $post_id, $tp_widgets_list );

				$scan_post_ids[ $post_id ] = $get_widgets;

				if ( ! empty( $get_widgets ) ) {
					foreach ( $get_widgets as $value ) {
						if ( ! empty( $value ) && in_array( $value, $tp_widgets_list, true ) ) {
							$countwidgets[ $value ] = ( isset( $countwidgets[ $value ] ) ? absint( $countwidgets[ $value ] ) : 0 ) + 1;
						}
					}
				}
			}

			$output = array();
			$val1   = count( $tp_widgets_list );
			$val2   = count( $countwidgets );
			$val3   = $val1 - $val2;

			$output['message'] = '* ' . $val3 . ' Unused Widgets Found!';
			$output['widgets'] = $countwidgets;

			$this->countwidgets = $countwidgets;

			return $output;
		}

		/**
		 * Scan Widget : Check Elements Status for Scanning
		 *
		 * @since 6.1.4
		 *
		 * @param int   $post_id         Optional. The post ID to check elements status for.
		 * @param array $tp_widgets_list Optional. The list of The Plus Addons widgets.
		 */
		public function tpae_check_elements_status_scan( $post_id = '', $tp_widgets_list = '' ) {

			if ( ! current_user_can( 'install_plugins' ) ) {
				$response = $this->tpae_set_response( false, 'Invalid nonce.', 'The security check failed. Please refresh the page and try again.' );
				return $response;
			}

			if ( ! empty( $post_id ) ) {
				$meta_data = \Elementor\Plugin::$instance->documents->get( $post_id );

				if ( is_object( $meta_data ) ) {
					$meta_data = $meta_data->get_elements_data();
				}

				if ( empty( $meta_data ) ) {
					return '';
				}

				$to_return = array();

				\Elementor\Plugin::$instance->db->iterate_data(
					$meta_data,
					function ( $element ) use ( $tp_widgets_list, &$to_return ) {
						if ( ! empty( $element['widgetType'] ) && array_key_exists( str_replace( '-', '_', $element['widgetType'] ), array_flip( $tp_widgets_list ) ) ) {
							$to_return[] = str_replace( '-', '_', $element['widgetType'] );
						}
					}
				);
			}

			return array_values( $to_return );
		}

		/**
		 * Set the response data.
		 *
		 * @since 6.1.4
		 *
		 * @param bool   $success     Indicates whether the operation was successful. Default is false.
		 * @param string $message     The main message to include in the response. Default is an empty string.
		 * @param string $description A more detailed description of the message or error. Default is an empty string.
		 */
		public function tpae_set_response( $success = false, $message = '', $description = '' ) {

			$response = array(
				'success'     => $success,
				'message'     => esc_html( $message ),
				'description' => esc_html( $description ),
			);

			return $response;
		}
	}

	Tpae_Widgets_Scan::get_instance();
}
