<?php

/**
 * Class: Application_Model_Friends
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_Friends extends Application_Model_ItemsFactory {
	protected $_uid;
	protected $_fid;
	protected $_createTime;
	protected $_confirm;
	
	
	public function getUid() {
		return $this->_uid;
	}

	public function setUid($uid) {
		$this->_uid = $uid;
		return $this;
	}

	public function getFid() {
		return $this->_fid;
	}

	public function setFid($fid) {
		$this->_fid = $fid;
		return $this;
	}

	public function getCreateTime() {
		return $this->_createTime;
	}

	public function setCreateTime($createTime) {
		$this->_createTime = $createTime;
		return $this;
	}

	public function getConfirm() {
		return $this->_confirm;
	}

	public function setConfirm($confirm) {
		$this->_confirm = $confirm;
		return $this;
	}
}
