<?php

/**
 * Class: SocialNetwork_Basic
 * Date begin: Feb 21, 2011
 * 
 * Some basic stuff
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Basic {
	/**
	 * Generate random string
	 *
	 * @param int $length - length of string
	 * @param string $alphabet - alphabet to use
	 * @param bool $mixInt - mix int to out string
	 * @return string
	 */
	static function randomString($length = 10, $alphabet = 'qwertyuiopasdfghjklzxcvbnm', $mixInt = true) {
		$out = '';
		$alphabetLen = mb_strlen($alphabet);
		
		for ($i = 0; $i < $length; $i++) {
			if ($mixInt && rand(1, 2) == 2) $out .= rand(0, 9);
			else $out .= $alphabet[rand(0, $alphabetLen-1)];
		}
		return $out;
	}
}
