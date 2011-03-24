<?php

/**
 * Class: Application_Model_Mails
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_Mails extends Application_Model_ItemsFactory {
	protected $_id;
	protected $_uid;
	protected $_aid;
	protected $_createTime;
	protected $_message;
	protected $_readed;
	
	
	public function getId() {
		return $this->_id;
	}

	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	public function getUid() {
		return $this->_uid;
	}

	public function setUid($uid) {
		$this->_uid = $uid;
		return $this;
	}

	public function getAid() {
		return $this->_aid;
	}

	public function setAid($aid) {
		$this->_aid = $aid;
		return $this;
	}

	public function getCreateTime() {
		return $this->_createTime;
	}

	public function setCreateTime($createTime) {
		$this->_createTime = $createTime;
		return $this;
	}

	public function getMessage() {
		return $this->_message;
	}

	public function setMessage($message) {
		$this->_message = $message;
		return $this;
	}

	public function getReaded() {
		return $this->_readed;
	}

	public function setReaded($readed) {
		$this->_readed = $readed;
		return $this;
	}
}
