<?php

/**
 * Class: Application_Model_DbTable_Factory
 * Date begin: Feb 3, 2011
 * 
 * Factory for table models
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_DbTable_Factory extends Zend_Db_Table_Abstract {
	public function getName() {
		return $this->_name;
	}
}
