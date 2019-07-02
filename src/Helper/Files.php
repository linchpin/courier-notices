<?php

namespace Courier\Helper;

/**
 * Class Files
 * @package Courier\Helper
 */
class Files {

	/**
	 * @param     $pattern
	 * @param int $flags
	 *
	 * @return array|false
	 */
	public static function glob_recursive( $pattern, $flags = 0 ) {
		$files = glob( $pattern, $flags );

		foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
			$files = array_merge( $files, self::glob_recursive( $dir . '/' . basename( $pattern ), $flags ) );
		}

		return $files;
	}
}
