<?php
/**
 * Controller Interface
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

/**
 * The contract every bootable controller implements.
 *
 * Hook registration happens in register_actions(), never in the
 * constructor — Bootstrap instantiates every registered controller and
 * then calls register_actions() on each, so construction must stay free
 * of side effects.
 */
interface Controller_Interface {

	/**
	 * Register the controller's hooks.
	 *
	 * @return void
	 */
	public function register_actions(): void;
}
