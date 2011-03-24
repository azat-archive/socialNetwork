<?php

/**
 * Class: Application_Form_UsersLogin
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Form_UsersLogin extends SocialNetwork_Form {
	public function init() {
		$this->setMethod('post');

		$this->addElement(
			'text', 'login', array(
			'label' => 'Login:',
			'required' => true,
			'filters' => array('StringTrim', 'StringToLower', 'StripTags'),
		));
		$this->addElement('password', 'password', array(
			'label' => 'Password:',
			'required' => true,
			'filters' => array('StringTrim'),
		));
		$this->addElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'Login',
		));
	}
}
