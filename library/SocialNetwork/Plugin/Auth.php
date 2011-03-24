<?php

/**
 * Class: SocialNetwork_Plugin_Auth
 * Date begin: Feb 10, 2011
 * 
 * Basicly for check auth of user
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Plugin_Auth extends Zend_Controller_Plugin_Abstract {
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		// Http
		Zend_Registry::set('request', $this->getRequest());
		Zend_Registry::set('responce', $this->getResponse());
		
		// Cookie domain
		Zend_Registry::set('cookieDomain', '.' . str_replace('www.', '', $_SERVER['HTTP_HOST']));
		
		// Session
		Zend_Registry::set('session', new Zend_Session_Namespace('socialNetwork'));
		
		// Auth
		Application_Model_UsersMapper::getInstance()->testAuthorization();
	}
}
