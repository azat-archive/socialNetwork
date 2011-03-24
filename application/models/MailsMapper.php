<?php

/**
 * Class: Application_Model_MailsMapper
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_MailsMapper extends Application_Model_MapperFactory {
	/**
	 * Instance
	 *
	 * @var Application_Model_MailsMapper
	 */
	protected static $_instance;

	
	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __construct() {
		$this->setDbTable(new Application_Model_DbTable_Mails);
		$this->setItemModel('Application_Model_Mails');
	}
	
	/**
	 * Send mail
	 *
	 * @param Application_Model_Mails $item
	 * @return mixed
	 */
	public function send(Application_Model_Mails $item) {
		$item->setCreateTime(time());
		
		return parent::save($item);
	}
	
	/**
	 * Get query for messages
	 *
	 * @param bool $inbox - is inbox // otherwise - send messages
	 * @param mixed $order
	 * @return Zend_Db_Select
	 */
	public function getQuery($inbox, $order = null) {
		return $this->getDbTable()
				->getAdapter()
				->select()
				->from(array('mails' => 'mails'))
				->where($inbox ? 'mails.uid = ?' : 'mails.aid = ?', Zend_Registry::get('currentUser')->getId())
				->joinLeft('users', 'users.id = mails.' . ($inbox ? 'aid' : 'uid'), array('login', 'name', 'secondName'))
				->order($order);
	}
	
	/**
	 * Get number of messages
	 *
	 * @param bool $inbox - is inbox // otherwise - send messages
	 * @return int
	 */
	public function getNum($inbox) {
		return (int)$this->getDbTable()
				->getAdapter()
				->select()
				->from(array('mails' => 'mails'), 'COUNT(id)')
				->where($inbox ? 'mails.uid = ?' : 'mails.aid = ?', Zend_Registry::get('currentUser')->getId())
				->limit(1)
				->query()
				->fetchColumn();
	}
	
	/**
	 * Get message
	 *
	 * @param int $id - message id
	 * @return Application_Model_Default
	 */
	public function message($id) {
		$message = $this->findOne($this->createItemModel(array('id' => (int)$id)));
		if (!$message) {
			throw new Application_Model_Exception('No message with id %u', $id);
		}
		// set readed status for messages from inbox of current user only
		if ($message->getUid() == Zend_Registry::get('currentUser')->getId()) {
			$this->save($message->setReaded('y'));
		}
		
		$notCurrentUserId = ($message->getUid() == Zend_Registry::get('currentUser')->getId() ? $message->getAid() : $message->getUid());
		$usersMapper = Application_Model_UsersMapper::getInstance();
		$user = $usersMapper->findOne($usersMapper->createItemModel(array('id' => $notCurrentUserId)));
		
		$model = new Application_Model_Default(array_merge($message->getAll(), array(
			'login' => $user->getLogin(),
			'name' => $user->getName(),
			'secondName' => $user->getSecondName(),
			'notCurrentUserId' => $notCurrentUserId,
		)));
		return $model;
	}
}
