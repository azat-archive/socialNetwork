<?php

/**
 * Class: SocialNetwork_View_Helper_Users
 * Date begin: Feb 17, 2011
 * 
 * Users helper
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_View_Helper_Users extends Zend_View_Helper_Abstract {
	public function users() {
		return $this;
	}
	
	/**
	 * Get current user info
	 *
	 * @return ItemsFactory
	 */
	public function getCurrentUser() {
		return Zend_Registry::get('currentUser');
	}
}
