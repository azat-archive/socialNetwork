<?php

/**
 * Class: Bootstrap
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	public function run() {
		$frontController = Zend_Controller_Front::getInstance();
		
		Zend_Loader_Autoloader::getInstance()->registerNamespace('SocialNetwork_');
		$this->getResource('view')->addHelperPath('SocialNetwork/View/Helper/', 'SocialNetwork_View_Helper_');
		
		$frontController->registerPlugin(new SocialNetwork_Plugin_Router);
		$frontController->registerPlugin(new SocialNetwork_Plugin_Auth);
		$frontController->registerPlugin(new SocialNetwork_Plugin_Acl(new SocialNetwork_Acl));
		
		Zend_Controller_Action_HelperBroker::addHelper(new SocialNetwork_Helper_Navigation);
		
		$this->getResource('view')->headTitle('Social Network')->setSeparator(' | ');
		
		return parent::run();
	}
}
