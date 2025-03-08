<?php
/**
 * Class to log reports during the data import.
 *
 * @since 1.0.0
 *
 * @package Themebeez_Toolkit
 */

/**
 * Class - TT_Importer_Logger_CLI.
 *
 * Logs reports during data import.
 *
 * @since 1.0.0
 */
class TT_Importer_Logger_CLI extends TT_Importer_Logger {

	/**
	 * Minimum level of reporting.
	 *
	 * @var string $min_level
	 */
	public $min_level = 'notice';

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed  $level Level of reporting.
	 * @param string $message Log message.
	 * @param array  $context Context to the log message.
	 * @return null
	 */
	public function log( $level, $message, array $context = array() ) {

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
	 * Returns the number associated with the level of reporting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $level Level of reporting.
	 * @return int
	 */
	public static function level_to_numeric( $level ) {

		$levels = array(
			'emergency' => 8,
			'alert'     => 7,
			'critical'  => 6,
			'error'     => 5,
			'warning'   => 4,
			'notice'    => 3,
			'info'      => 2,
			'debug'     => 1,
		);

		if ( ! isset( $levels[ $level ] ) ) {
			return 0;
		}

		return $levels[ $level ];
	}
}
