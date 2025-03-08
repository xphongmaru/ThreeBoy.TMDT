<?php
/**
 * Logger class used to log the data import messages.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

// Include files.
if ( ! class_exists( 'TT_Importer_Logger' ) ) {

	require TT_ABSPATH . 'includes/demo-importer/importer/class-tt-importer-logger.php';
}

if ( ! class_exists( 'TT_Importer_Logger_CLI' ) ) {

	require TT_ABSPATH . 'includes/demo-importer/importer/class-tt-importer-logger-cli.php';
}

/**
 * Class - TT_Logger.
 *
 * Logs the data import messages.
 *
 * @since 1.0.0
 */
class TT_Logger extends TT_Importer_Logger_CLI {

	/**
	 * Variable for front-end error display.
	 *
	 * @var string $error_output
	 */
	public $error_output = '';

	/**
	 * Overwritten log function from TT_Importer_Logger_CLI.
	 *
	 * Logs with an arbitrary level.
	 *
	 * @param mixed  $level Level of reporting.
	 * @param string $message Log message.
	 * @param array  $context Context to the log message.
	 */
	public function log( $level, $message, array $context = array() ) {

		// Save error messages for front-end display.
		$this->error_output( $level, $message, $context = array() );

		if ( $this->level_to_numeric( $level ) < $this->level_to_numeric( $this->min_level ) ) {
			return;
		}

		printf(
			'[%s] %s' . PHP_EOL,
			strtoupper( $level ), // phpcs:ignore
			$message // phpcs:ignore
		);
	}


	/**
	 * Save messages for error output.
	 * Only the messages greater then Error.
	 *
	 * @param mixed  $level Level of reporting.
	 * @param string $message Log message.
	 * @param array  $context Context to the log message.
	 */
	public function error_output( $level, $message, array $context = array() ) {
		if ( $this->level_to_numeric( $level ) < $this->level_to_numeric( 'error' ) ) {
			return;
		}

		$this->error_output .= sprintf(
			'[%s] %s<br>',
			strtoupper( $level ),
			$message
		);
	}
}
