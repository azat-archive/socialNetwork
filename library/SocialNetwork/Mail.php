<?php

/**
 * Class: SocialNetwork_Mail
 * Date begin: Feb 21, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Mail extends Zend_Mail {
	public function __construct($charset = 'UTF-8') {
		parent::__construct($charset);
		
		self::setDefaultFrom('support@' . str_replace('www.', '', $_SERVER['HTTP_HOST']), 'Social Network');
		$this->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
	}
	
	/**
	 * Sprintf like
	 */
	public function setBodyTextF() {
		$args = func_get_args();
		$formatedString = array_shift($args);
		
		return parent::setBodyText(vsprintf($formatedString, $args));
	}
}
