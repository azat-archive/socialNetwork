<?php

/**
 * Class: Application_Form_UsersSignup
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Form_UsersSignup extends SocialNetwork_Form {
	public function init() {
		$this->setMethod('post');
		
		$this->addElement(
			'text', 'login', array(
			'label' => 'Login:',
			'required' => true,
			'filters' => array('StringTrim', 'StringToLower', 'StripTags'),
		));
		$this->addElement(
			'text', 'name', array(
			'label' => 'Name:',
			'required' => true,
			'filters' => array('StringTrim', 'StripTags'),
		));
		$this->addElement(
			'text', 'secondName', array(
			'label' => 'Second name:',
			'required' => true,
			'filters' => array('StringTrim', 'StripTags'),
		));
		$this->addElement(
			'text', 'email', array(
			'label' => 'Email:',
			'required' => true,
			'validators' => array('EmailAddress'),
			'filters' => array('StringTrim', 'StringToLower', 'StripTags'),
		));
		$this->addElement('password', 'password', array(
			'label' => 'Password:',
			'required' => true,
			'filters' => array('StringTrim'),
		));
		$this->addElement('password', 'passwordConfirm', array(
			'label' => 'Confirm password:',
			'required' => true,
			'filters' => array('StringTrim'),
		));
		$this->addElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'Login',
		));
	}
}
