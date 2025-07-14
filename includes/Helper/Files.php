<?php
/**
 * File Helpers
 *
 * @package Courier\Helpers
 */

namespace CourierNotices\Helper;

/**
 * Files Class
 */
class Files {


	/**
	 * Recursive version of the PHP glob function.
	 *
	 * @since 1.0
	 *
	 * @param string $pattern Specifies the pattern to search for.
	 * @param int    $flags   Specifies special settings.
	 *
	 * @return array|false Array of filenames or directories matching a specified pattern
	 */
	public static function glob_recursive( $pattern, $flags = 0 ) {
		$files = glob( $pattern, $flags );

		foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
			$files = array_merge( $files, self::glob_recursive( $dir . '/' . basename( $pattern ), $flags ) );
		}

		return $files;

	}


}
