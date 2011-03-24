<?php

/**
 * Class: SocialNetwork_Plugin_Router
 * Date begin: Feb 10, 2011
 * 
 * This class for not default routes
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Plugin_Router extends Zend_Controller_Plugin_Abstract {
	public function __construct() {
		$router = new Zend_Controller_Router_Rewrite();

		Zend_Controller_Front::getInstance()->setRouter($router);
	}
}
