<?php
/**
 * Application of filter hooks for extending WordPress menu editor.
 *
 * @since 1.1.5
 *
 * @package Themebeez_Toolkit
 */

if ( ! class_exists( 'Simple_Mega_Menu_Walker_Filter' ) ) {
	/**
	 * Class - Simple_Mega_Menu_Walker_Filter.
	 * Loads custom menu fields.
	 *
	 * @since 1.1.5
	 */
	class Simple_Mega_Menu_Walker_Filter {

		/**
		 * Add menu filter.
		 *
		 * @since 1.1.5
		 */
		public function __construct() {

			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_menu_walker' ), 99 );

			$this->dependencies();
		}
		/**
		 * Load dependencies.
		 *
		 * @since 1.1.5
		 */
		public function dependencies() {

			require_once plugin_dir_path( __FILE__ ) . 'class-simple-mega-menu-walker-nav-menu-edit.php';

			require_once plugin_dir_path( __FILE__ ) . 'class-simple-mega-menu-nav-walker-edit.php';
		}


		/**
		 * Replace default menu editor walker with ours.
		 *
		 * We don't actually replace the default walker. We're still using it and
		 * only injecting some HTMLs.
		 *
		 * @since 1.1.5
		 *
		 * @param string $walker Walker class name.
		 * @return string Walker class name.
		 */
		public function edit_nav_menu_walker( $walker ) {

			$navwalker = 'Simple_Mega_Menu_Nav_Walker_Edit';

			if ( class_exists( $navwalker ) ) {

				return $navwalker;
			}

			return $walker;
		}
	}
}

$simple_mega_menu_walker_filter = new Simple_Mega_Menu_Walker_Filter();

// Uncomment the following line to test this plugin.
require_once plugin_dir_path( __FILE__ ) . 'class-simple-mega-menu-fields.php';
