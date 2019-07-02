<?php

namespace Courier\Helper;

/**
 * Class Utils
 * @package LinchpinHelpdesk\Helper
 */
class Utils {

	/**
	 * @param     $second
	 * @param int $precision
	 *
	 * @throws \Exception
	 */
	public static function round_time( $second, $precision = 30 ) {
		// 1) Set number of seconds to 0 (by rounding up to the nearest minute if necessary)
		$datetime = new \DateTime();

		$second = (int) $datetime->format( 's' );

		if ( $second > 30 ) {
			// Jumps to the next minute
			$datetime->add( new \DateInterval( 'PT' . ( 60 - $second ) . 'S' ) );
		} elseif ( $second > 0 ) {
			// Back to 0 seconds on current minute
			$datetime->sub( new \DateInterval( 'PT' . $second . 'S' ) );
		}
		// 2) Get minute
		$minute = (int) $datetime->format( 'i' );

		// 3) Convert modulo $precision
		$minute = $minute % $precision;

		if ( $minute > 0 ) {
			// 4) Count minutes to next $precision-multiple minutes
			$diff = $precision - $minute;
			// 5) Add the difference to the original date time
			$datetime->add( new \DateInterval( 'PT' . $diff . 'M' ) );
		}
	}

	/**
	 * Round our time
	 *
	 * @param $seconds
	 *
	 * @return float|int
	 */
	public static function rounded_fractional_time( $seconds = '' ) {

		if ( $seconds ) {
			return 0;
		}

		$hours   = floor( $seconds / 3600 );
		$minutes = floor( ( $seconds / 60 ) % 60 );

		// Round down to nearest 15 minutes
		$rounded_minutes = ceil( $minutes / 15 ) * 15;
		$quarter_minutes = ( $rounded_minutes / 15 ) / 4;

		return $hours + $quarter_minutes;
	}
}
