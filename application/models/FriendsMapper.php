<?php

/**
 * Class: Application_Model_FriendsMapper
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_FriendsMapper extends Application_Model_MapperFactory {
	/**
	 * Instance
	 *
	 * @var Application_Model_FriendsMapper
	 */
	protected static $_instance;
	
	
	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __construct() {
		$this->setDbTable(new Application_Model_DbTable_Friends);
		$this->setItemModel('Application_Model_Friends');
	}
	
	/**
	 * Get users friends query
	 *
	 * @param int $uid
	 * @param mixed $order
	 * @return Zend_Db_Select
	 */
	public function getQuery($uid, $order = null) {
		$union = $this->getDbTable()
					->select()
					->union(array(
						$this->getDbTable()->getAdapter()->select()->from($this->getDbTable()->getName(), array('createTime', 'confirm', 'uid AS fid', '(FALSE) AS iAdd'))->where('fid = ?', $uid)->where('confirm = "y"'),
						$this->getDbTable()->getAdapter()->select()->from($this->getDbTable()->getName(), array('createTime', 'confirm', 'fid AS fid', '(TRUE) AS iAdd'))->where('uid = ?', $uid)->where('confirm = "y"'),
					));
		
		return $this->getDbTable()
				->getAdapter()
				->select()
				->from(array('friends' => $union))
				->joinLeft('users', 'users.id = friends.fid', array('id', 'login', 'name', 'secondName'))
				->order($order);
	}
	
	/**
	 * Get number of users friends
	 *
	 * @param int $uid
	 * @return int
	 */
	public function getNum($uid) {
		return (int)$this->getDbTable()
				->getAdapter()
				->select()
				->from(array('friends' => 'friends'), array('COUNT(1)'))
				->where('confirm = "y"')
				->where(sprintf('uid = %u OR fid = %u', $uid, $uid))
				->limit(1)
				->query()
				->fetchColumn();
	}
	
	/**
	 * Get users friends-requests query
	 *
	 * @param int $uid
	 * @param mixed $order
	 * @return Zend_Db_Select
	 */
	public function getRequestsQuery($uid, $order = null, $limit = null, $offset = null) {
		return $this->getDbTable()
				->getAdapter()
				->select()
				->from(array('friends' => 'friends'))
				->joinLeft('users', 'users.id = friends.uid', array('id', 'login', 'name', 'secondName'))
				->where('confirm = "n"')
				->where('fid = ?', $uid)
				->order($order);
	}
	
	/**
	 * Get number of users friends-requests
	 *
	 * @param int $uid
	 * @return int
	 */
	public function getRequestsNum($uid) {
		return (int)$this->getDbTable()
				->getAdapter()
				->select()
				->from(array('friends' => 'friends'), array('COUNT(1)'))
				->where('confirm = "n"')
				->where('fid = ?', $uid)
				->limit(1)
				->query()
				->fetchColumn();
	}
	
	/**
	 * Check is a friend
	 *
	 * @param int $fid
	 * @param bool $confirmed
	 * @return bool
	 */
	public function isAFriend($fid, $confirmed = true) {
		$uid = Zend_Registry::get('currentUser')->getId();
		if ($uid === $fid) {
			return true;
		}
		
		$select = $this->getDbTable()->select();
		if ($confirmed) $select->where('confirm = "y"');
		
		return $select->where(sprintf('((uid = %u AND fid = %u) OR (uid = %u AND fid = %u))', $uid, $fid, $fid, $uid))
				->limit(1)
				->query()
				->rowCount() == 1;
	}
	
	/**
	 * Delete friend
	 * 
	 * @param int $fid - friend to delete
	 * @return bool
	 */
	public function deleteFriend($fid) {
		// is request from current user to $fid
		// can delete only if friendship is confirmed
		$friend = $this->findOne($this->createItemModel(array('uid' => Zend_Registry::get('currentUser')->getId(), 'fid' => $fid)));
		if ($friend) {
			if ($friend->getConfirm() == 'n') {
				throw new Application_Model_Exception('You can`t do this, your friend is not confirm yet.');
			}
			$num = $this->delete($this->createItemModel(array('uid' => Zend_Registry::get('currentUser')->getId(), 'fid' => $fid)));
		} else {
			$num = $this->delete($this->createItemModel(array('fid' => Zend_Registry::get('currentUser')->getId(), 'uid' => $fid)));
		}
		
		return ($num == 1);
	}
}
