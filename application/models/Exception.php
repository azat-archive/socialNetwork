<?php

/**
 * Class: Application_Model_Exception
 * Date begin: Feb 4, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_Exception extends Zend_Application_Exception {
	public function __construct() {
		$args = func_get_args();
		$format = array_shift($args);
		parent::__construct(vsprintf($format, $args));
	}
}
