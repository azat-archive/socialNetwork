<?php

/**
 * Class: Application_Model_MapperFactory
 * Date begin: Feb 3, 2011
 * 
 * Mapper factory
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
abstract class Application_Model_MapperFactory {
	/**
	 * DB Instance
	 *
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;
	/**
	 * Items model
	 *
	 * @var Application_Model_ItemsFactory
	 */
	protected $_itemModel;
	
	
	/**
	 * Create an instance of class
	 * 
	 * @return Application_Model_MapperFactory
	 */
	abstract public static function getInstance();
	
	/**
	 * Set db table
	 *
	 * @param Zend_Db_Table_Abstract $dbTable
	 * @return Application_Model_MapperFactory 
	 */
	public function setDbTable(Zend_Db_Table_Abstract $dbTable) {
		$this->_dbTable = $dbTable;
		return $this;
	}

	/**
	 * Get db table
	 *
	 * @return Zend_Db_Table_Abstractct
	 */
	public function getDbTable() {
		return $this->_dbTable;
	}

	/**
	 * Set main item model
	 *
	 * @param string $itemModel - item model
	 * @return Application_Model_MapperFactory
	 */
	public function setItemModel($itemModel) {
		$this->_itemModel = $itemModel;
		return $this;
	}
	
	/**
	 * Get main item model
	 *
	 * @return string
	 */
	public function getItemModel() {
		return $this->_itemModel;
	}
	
	/**
	 * Create main item model
	 *
	 * @return string
	 */
	public function createItemModel(array $options = null) {
		$instance = new $this->_itemModel;
		if (!is_null($options)) {
			$instance->setOptions($options);
		}
		return $instance;
	}
	
	/**
	 * Save record
	 *
	 * @param Application_Model_ItemsFactory $item
	 * @return mixed
	 */
	public function save(Application_Model_ItemsFactory $item) {
		$data = $item->getAll();
		
		if (!$data) {
			throw new Application_Model_Exception('No data');
		}
		
		// update a record
		if (!empty($data['id'])) {
			$id = $data['id'];
			unset($data['id']);
			
			// @TODO - not only int maybe?
			return $this->getDbTable()->update($data, sprintf('id = %u', $id));
		}
		// add a record
		return $this->getDbTable()->insert($data);
	}
	
	/**
	 * Update record
	 *
	 * @param Application_Model_ItemsFactory $item
	 * @param Application_Model_ItemsFactory $where
	 * @return bool
	 */
	public function update(Application_Model_ItemsFactory $item, Application_Model_ItemsFactory $where) {
		$data = $item->getAll();
		$whereCondition = $item->getAll();
		if (!$data || !$whereCondition) {
			throw new Application_Model_Exception('No data or where condition');
		}
		// rewrite array for using placeholders
		$where = array();
		foreach ($whereCondition as $cond => &$term) {
			$where[$cond . ' = ?'] = $term;
		}
		
		return $this->getDbTable()->update($data, $where);
	}
	
	/**
	 * Insert record
	 *
	 * @param Application_Model_ItemsFactory $item
	 * @return bool
	 */
	public function insert(Application_Model_ItemsFactory $item) {
		$data = $item->getAll();
		if (!$data) {
			throw new Application_Model_Exception('No data');
		}
		
		return $this->getDbTable()->insert($data);
	}
	
	/**
	 * Delete record
	 *
	 * @param Application_Model_ItemsFactory $item
	 * @return bool
	 */
	public function delete(Application_Model_ItemsFactory $item) {
		$data = $item->getAll();
		if (!$data) {
			throw new Application_Model_Exception('No data');
		}
		// rewrite array for using placeholders
		$where = array();
		foreach ($data as $cond => &$term) {
			$where[$cond . ' = ?'] = $term;
		}
		
		return $this->getDbTable()->delete($where);
	}
	
	/**
	 * Find records
	 *
	 * @param Application_Model_ItemsFactory $item
	 * @param array $order
	 * @param int $limit
	 * @param int $offset
	 * @return mixed (array if something is found, otherwise null)
	 */
	public function find(Application_Model_ItemsFactory $item, $order = null, $limit = null, $offset = null) {
		$select = $this->getDbTable()->select();
		foreach ($item->getAll() as $key => $value) {
			$select->where($key . ' = ?', $value);
		}
		$result = $select->order($order)->limit($limit, $offset)->query()->fetchAll();
		if ($result) {
			return $this->toObject($result);
		}
		return null;
	}
	
	/**
	 * Find one records
	 *
	 * @param Application_Model_ItemsFactory $item
	 * @param array $order
	 * @return mixed (array if something is found, otherwise null)
	 */
	public function findOne(Application_Model_ItemsFactory $item, $order = null) {
		$select = $this->getDbTable()->select();
		foreach ($item->getAll() as $key => $value) {
			$select->where($key . ' = ?', $value);
		}
		$result = $select->order($order)->limit(1, 0)->query()->fetch();
		if ($result) {
			return $this->createItemModel($result);
		}
		return null;
	}
	
	/**
	 * Number of records
	 *
	 * @param Application_Model_ItemsFactory $item
	 * @return int
	 */
	public function num(Application_Model_ItemsFactory $item = null) {
		$select = $this->getDbTable()->getAdapter()->select()->from($this->getDbTable()->getName(), array('COUNT(*)'));
		if ($item) {
			foreach ($item->getAll() as $key => $value) {
				$select->where($key . ' = ?', $value);
			}
		}
		return (int)$select->limit(1, 0)->query()->fetchColumn();
	}
	
	/**
	 * Find all records
	 *
	 * @param array $order
	 * @param int $limit
	 * @param int $offset
	 * @return mixed (array if something is found, otherwise null)
	 */
	public function findAll($order = null, $limit = null, $offset = null) {
		$result = $this->getDbTable()->select()->order($order)->limit($limit, $offset)->query()->fetchAll();
		if ($result) {
			return $this->toObject($result);
		}
		return null;
	}
	
	/**
	 * Transform array to object
	 *
	 * @param array $items - items
	 * @param bool $default - use default model. not that set by this::setItemModel()
	 * @return Application_Model_ItemsFactory
	 */
	public function toObject(array $items, $default = false) {
		$objectName = $this->getItemModel();
		if (!$objectName) {
			throw new Application_Model_Exception('Not set items model for class "%s"', get_class($this));
		}
		
		$set = array();
		foreach ($items as $item) {
			$set[] = $default ? new Application_Model_Default($item) : $this->createItemModel($item);
		}
		return $set;
	}
}
