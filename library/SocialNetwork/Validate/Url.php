<?php

/**
 * Class: SocialNetwork_Validate_Url
 * Date begin: Mar 14, 2011
 * 
 * Validate url
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Validate_Url extends Zend_Validate_Abstract {
	const INVALID_URL = 'invalidUrl';

	protected $_messageTemplates = array(
		self::INVALID_URL   => '\'%value%\' is not valid URL.',
	);

	public function isValid($value) {
		$valueString = (string)$value;
		$this->_setValue($valueString);

		if (!Zend_Uri::check($value)) {
			$this->_error(self::INVALID_URL);
			return false;
		}
		return true;
	}
}
