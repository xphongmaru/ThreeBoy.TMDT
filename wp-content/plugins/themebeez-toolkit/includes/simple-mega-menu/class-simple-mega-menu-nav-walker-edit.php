<?php
/**
 * Extends Simple_Mega_Menu_Walker_Nav_Menu_Edit to display custom menu fields.
 *
 * @since 1.1.5
 *
 * @package Themebeez_Toolkit.
 */

if ( ! class_exists( 'Simple_Mega_Menu_Nav_Walker_Edit' ) ) {
	/**
	 * Class - Simple_Mega_Menu_Nav_Walker_Edit.
	 * Extends - Simple_Mega_Menu_Walker_Nav_Menu_Edit.
	 *
	 * @since 1.1.5
	 */
	class Simple_Mega_Menu_Nav_Walker_Edit extends Simple_Mega_Menu_Walker_Nav_Menu_Edit {

		/**
		 * Starts the list before the elements are added.
		 *
		 * @see Walker_Nav_Menu::start_lvl()
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Not used.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @see Walker_Nav_Menu::end_lvl()
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Not used.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {}

		/**
		 * Start the element output.
		 *
		 * We're injecting our custom fields after the div.submitbox
		 *
		 * @see Walker_Nav_Menu::start_el()
		 * @since 0.1.0
		 * @since 0.2.0 Update regex pattern to support WordPress 4.7's markup.
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item   Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Menu item args.
		 * @param int    $id     Nav menu ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$item_output = '';

			parent::start_el( $item_output, $item, $depth, $args, $id );

			$output .= preg_replace(
				// NOTE: Check this regex from time to time!
				'/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/',
				$this->get_fields( $item, $depth, $args ),
				$item_output
			);
		}


		/**
		 * Get custom fields
		 *
		 * @access protected
		 * @since 0.1.0
		 * @uses add_action() Calls 'menu_item_custom_fields' hook
		 *
		 * @param object $item   Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Menu item args.
		 * @param int    $id     Nav menu ID.
		 *
		 * @return string Form fields
		 */
		protected function get_fields( $item, $depth, $args = array(), $id = 0 ) {

			ob_start();

			/**
			 * Get menu item custom fields from plugins/themes
			 *
			 * @since 0.1.0
			 * @since 1.0.0 Pass correct parameters.
			 *
			 * @param int    $item_id  Menu item ID.
			 * @param object $item     Menu item data object.
			 * @param int    $depth    Depth of menu item. Used for padding.
			 * @param array  $args     Menu item args.
			 * @param int    $id       Nav menu ID.
			 *
			 * @return string Custom fields HTML.
			 */
			do_action( 'wp_nav_menu_item_custom_fields', $item->ID, $item, $depth, $args, $id );

			return ob_get_clean();
		}
	}
}
