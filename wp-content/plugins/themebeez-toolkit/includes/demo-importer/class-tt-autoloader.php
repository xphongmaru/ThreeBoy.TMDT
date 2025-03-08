<?php
/**
 * Files autoloader.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class - TT_Autoloader.
 * Autoloads required files.
 *
 * @since 1.0.0
 */
class TT_Autoloader {

	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	private $include_path = '';

	/**
	 * The Constructor.
	 */
	public function __construct() {

		if ( function_exists( '__autoload' ) ) {

			spl_autoload_register( '__autoload' );
		}

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->include_path = untrailingslashit( plugin_dir_path( THEMEBEEZTOOLKIT_PLUGIN_FILE ) ) . '/includes/demo-importer/';
	}

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param string $classname Class name.
	 * @return string
	 */
	private function get_file_name_from_class( $classname ) {

		return 'class-' . str_replace( '_', '-', $classname ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param string $path File path.
	 * @return bool successful or not
	 */
	private function load_file( $path ) {

		if ( $path && is_readable( $path ) ) {

			include_once $path;
			return true;
		}

		return false;
	}

	/**
	 * Auto-load TT classes on demand to reduce memory consumption.
	 *
	 * @param string $classname Class name.
	 */
	public function autoload( $classname ) {

		$classname = strtolower( $classname );

		if ( 0 !== strpos( $classname, 'tt_' ) ) {
			return;
		}

		$file = $this->get_file_name_from_class( $classname );

		$path = '';

		if ( 0 === strpos( $classname, 'tt_theme_demo' ) ) {
			$path = $this->include_path . 'theme-demo/';
		} elseif ( 0 === strpos( $classname, 'tt_admin' ) ) {
			$path = $this->include_path . 'admin/';
		} elseif ( 0 === strpos( $classname, 'tt_importer' ) ) {
			$path = $this->include_path . 'importer/';
		} else {
			$path = $this->include_path;
		}

		if ( empty( $path ) || ! $this->load_file( $path . $file ) ) {

			$this->load_file( $this->include_path . $file );
		}
	}
}


new TT_Autoloader();
