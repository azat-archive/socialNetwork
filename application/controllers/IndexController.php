<?php

/**
 * Class: IndexController
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class IndexController extends Zend_Controller_Action {
	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		$this->view->users = Application_Model_UsersMapper::getInstance()->num();
	}
}
