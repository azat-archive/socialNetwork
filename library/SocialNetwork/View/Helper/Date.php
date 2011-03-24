<?php

/**
 * Class: SocialNetwork_View_Helper_Date
 * Date begin: Feb 17, 2011
 * 
 * Date helper
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_View_Helper_Date extends Zend_View_Helper_Abstract {
	/**
	 * Date format
	 *
	 * @param int $unixtimeStamp - unixtime stamp
	 * @return string
	 */
	public function Date($unixtimeStamp) {
		if (!$unixtimeStamp) {
			// return null; // ?
			return 'no';
		}
		
		if ($unixtimeStamp <= time()) {
			if ($unixtimeStamp >= strtotime('-1 hour')) return 'now';
			if ($unixtimeStamp >= strtotime('-1 day')) return 'today';
			if ($unixtimeStamp >= strtotime('-2 day')) return 'yesterday';
		}
		if ($unixtimeStamp >= time() && $unixtimeStamp <= strtotime('+1 day')) {
			return 'tomorrow';
		}
		// current year
		if (date('Y', $unixtimeStamp) == date('Y')) {
			return date('j F', $unixtimeStamp);
		}
		return date('j F Y', $unixtimeStamp);
	}
}
