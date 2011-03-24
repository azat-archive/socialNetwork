<?php

/**
 * Class: SocialNetwork_Plugin_Acl
 * Date begin: Feb 4, 2011
 * 
 * Basicly for check authorization and if it is need
 * For current page, show login message box
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
	/**
	 * ACL
	 *
	 * @var Zend_Acl
	 */
	protected $_acl;
	/**
	 * Role
	 *
	 * @var string
	 */
	protected $_role;
	
	
	public function __construct(Zend_Acl $acl) {
		$this->_acl = $acl;
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$this->role = (Zend_Registry::isRegistered('currentUser') ? 'user' : 'guest');
		Zend_Registry::set('role', $this->role);

		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->_acl);
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($this->role);
		
		$resource = $request->getControllerName();
		
		// No grants - show login message box
		if (!$this->_acl->isAllowed($this->role, $resource, $request->getActionName())) {
			if ($request->getActionName() == 'login') {
				// @TODO - maybe normal redirect of smth else
				$this->getResponse()->setRedirect('/');
			} else {
				$request->setControllerName('users')->setActionName('login')->setDispatched(false);
			}
		}
	}
}
