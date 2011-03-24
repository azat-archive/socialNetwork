<?php

/**
 * Class: Application_Form_MailsSend
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Form_MailsSend extends SocialNetwork_Form {
	public function init() {
		$this->setMethod('post');
		
		$this->addElement(
			'textarea', 'message', array(
			'label' => 'Message:',
			'rows' => 10,
			'cols' => 60,
			'required' => true,
			'filters' => array('StringTrim', 'StripTags'),
		));
		$this->addElement(
			'hidden', 'id', array(
			'filters' => array('Int'),
			'required' => true,
		));
		$this->addElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'Send',
		));
	}
}
