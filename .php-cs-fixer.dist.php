<?php
/**
 * PHP-CS-Fixer configuration
 *
 * (c) Linchpin <sayhi@alley.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Linchpin
 */

$finder = PhpCsFixer\Finder::create()->in(
	[
		__DIR__ . '/includes/',
		__DIR__ . '/blocks/src/',
		__DIR__ . '/blocks/src-iapi/',
	]
);

$config = new PhpCsFixer\Config();
$config->setRules(
	[
		'@PHP81Migration'                => true,
		'method_argument_space'          => false,
		'final_class'                    => FALSE,
		'native_constant_invocation'     => true,
		'native_function_casing'         => true,
		'native_function_invocation'     => true,
		'native_type_declaration_casing' => true,
	]
);
$config->setFinder( $finder );

return $config;
