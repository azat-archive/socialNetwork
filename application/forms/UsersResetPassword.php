<?php

/**
 * Class: Application_Form_UsersResetPassword
 * Date begin: Feb 21, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Form_UsersResetPassword extends SocialNetwork_Form {
	public function init() {
		$this->setMethod('post');
		
		$this->addElement(
			'text', 'email', array(
			'label' => 'Email:',
			'required' => true,
			'validators' => array('EmailAddress'),
			'filters' => array('StringTrim', 'StringToLower', 'StripTags'),
		));
		$this->addElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'Reset',
		));
	}
}
