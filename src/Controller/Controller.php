<?php
/**
 * Main Controller.
 *
 * Other controllers should extend this one.
 *
 * @package CourierNotices\Controller
 */

namespace CourierNotices\Controller;

use CourierNotices\Core\View;
use CourierNotices\Model\Config;

/**
 * Class Controller
 *
 * @package Fluval\Controller
 */
class Controller extends View {

	/**
	 * Plugin configuration.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Controller constructor.
	 */
	public function __construct() {
		$this->config = new Config();
	}

}
