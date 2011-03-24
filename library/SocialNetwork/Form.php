<?php

/**
 * Class: SocialNetwork_Form
 * Date begin: Feb 4, 2011
 * 
 * Default form
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Form extends Zend_Form {
	/**
	 * Set default action - current url
	 */
	public function __construct($options = null) {
		parent::__construct($options);
		$this->setAction(Zend_Controller_Front::getInstance()->getRequest()->getRequestUri());
		$this->addElementPrefixPath('SocialNetwork_Validate_', 'SocialNetwork/Validate/', 'validate');
	}
	
	/**
	 * Rewrite CSS class of dl
	 * 
	 * Load the default decorators
	 *
	 * @return void
	 */
	public function loadDefaultDecorators() {
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return $this;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this->addDecorator('FormElements')
				->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'socialNetworkForm'))
				->addDecorator('Form');
		}
		return $this;
	}
}
