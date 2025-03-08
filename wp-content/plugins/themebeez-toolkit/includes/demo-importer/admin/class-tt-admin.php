<?php
/**
 * Initializes the main demo importer.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class - TT_Admin.
 * Initializes the main demo importer class.
 *
 * @since 1.0.0
 */
class TT_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {

		if ( version_compare( phpversion(), '5.3.2', '<' ) ) {

			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		} else {

			$this->includes();

			TT_Main::get_instance();
		}
	}

	/**
	 * Renders PHP required admin notice.
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {

		$message = sprintf(
			/* translators: 1: strong open tag 2: strong close tag 3: br tag */
			esc_html__( 'The %2$Themebeez Demo Importer%3$s plugin requires %2$sPHP 5.3.2+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.%4$s Your current version of PHP: %2$s%1$s%3$s', 'themebeez-toolkit' ),
			phpversion(),
			'<strong>',
			'</strong>',
			'<br>'
		);

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Loads class file to load demo content import config of the active theme.
	 *
	 * @since 1.0.0
	 */
	public function includes() {

		include_once __DIR__ . '/class-tt-admin-demo-config.php';
	}
}

return new TT_Admin();
