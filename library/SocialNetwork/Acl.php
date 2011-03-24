<?php

/**
 * Class: SocialNetwork_Acl
 * Date begin: Feb 4, 2011
 * 
 * Set access control list
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Acl extends Zend_Acl {
	public function __construct() {
		$this->addRole(new Zend_Acl_Role('guest'));
		$this->addRole(new Zend_Acl_Role('user'), 'guest');
		
		$this->addResource(new Zend_Acl_Resource('index'));
		$this->addResource(new Zend_Acl_Resource('users'));
		$this->addResource(new Zend_Acl_Resource('error'));
		$this->addResource(new Zend_Acl_Resource('friends'));
		$this->addResource(new Zend_Acl_Resource('mails'));

		// index
		$this->allow('guest', 'index');
		
		// errors
		$this->allow('guest', 'error');
		
		// users
		$this->allow('guest', 'users', array('index', 'signup', 'login', 'reset-password'));
		$this->deny('user', 'users', array('signup', 'login', 'reset-password'));
		$this->allow('user', 'users', array('logout', 'profile'));
		
		// friends
		$this->allow('user', 'friends');
		
		// mails
		$this->allow('user', 'mails');
	}
}
