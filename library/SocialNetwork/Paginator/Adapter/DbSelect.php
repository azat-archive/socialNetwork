<?php

/**
 * Class: SocialNetwork_Paginator_Adapter_DbSelect
 * Date begin: Feb 26, 2011
 * 
 * DB Adapter for paginator
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Paginator_Adapter_DbSelect extends Zend_Paginator_Adapter_DbSelect {
	/**
	 * Mapper object
	 *
	 * @var MapperFactory
	 */
	protected $_mapper;
	
	/**
	 *
	 * @param Application_Model_MapperFactory $mapper
	 * @param Zend_Db_Select $select
	 * @param mixed $numSelect 
	 */
	public function __construct(Application_Model_MapperFactory $mapper, Zend_Db_Select $select = null, $numSelect = null) {
		$this->_mapper = $mapper;
		// default
		if (is_null($select)) {
			$select = $this->_mapper->getDbTable()->select();
		}
		parent::__construct($select);
		if (!is_null($numSelect)) {
			$this->setRowCount($numSelect);
		}
	}
	
	/**
	 * Get items and default wrapper
	 *
	 * @param int $offset
	 * @param int $itemCountPerPage
	 * @return Application_Model_ItemsFactory
	 */
	public function getItems($offset, $itemCountPerPage) {
		$items = parent::getItems($offset, $itemCountPerPage);
		return $this->_mapper->toObject($items, true);
	}
}
