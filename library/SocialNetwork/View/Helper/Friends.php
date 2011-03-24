<?php

/**
 * Class: SocialNetwork_View_Helper_Friends
 * Date begin: Feb 17, 2011
 * 
 * Friends helper
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_View_Helper_Friends extends Zend_View_Helper_Abstract {
	public function friends() {
		return $this;
	}
	
	/**
	 * Get current user info
	 *
	 * @return bool
	 */
	public function isAFriend($fid) {
		return Application_Model_FriendsMapper::getInstance()->isAFriend($fid);
	}
}
