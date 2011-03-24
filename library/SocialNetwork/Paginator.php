<?php

/**
 * Class: SocialNetwork_Paginator
 * Date begin: Feb 26, 2011
 * 
 * Paginator
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Paginator extends Zend_Paginator {
	public function __construct($adapter) {
		parent::__construct($adapter);
		
		$this->setItemCountPerPage(20);
		$this->setCacheEnabled(false);
		$this->setDefaultScrollingStyle('Sliding');
		
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator.phtml');
		
		$this->setCurrentPageNumber(Zend_Controller_Front::getInstance()->getRequest()->getParam('page'));
	}
	
	/**
	 * Set current items
	 *
	 * @param mixed $items
	 * @return SocialNetwork_Paginator 
	 */
	public function setCurrentItems($items) {
		$this->_currentItems = $items;
		return $this;
	}
}
