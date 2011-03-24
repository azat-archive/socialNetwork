<?php

/**
 * Class: Application_Model_Users
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_Users extends Application_Model_ItemsFactory {
	protected $_id;
	protected $_login;
	protected $_password;
	protected $_name;
	protected $_secondName;
	protected $_regTime;
	protected $_lastVisitTime;
	protected $_email;
	

	public function getId() {
		return $this->_id;
	}

	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	public function getLogin() {
		return $this->_login;
	}

	public function setLogin($login) {
		$this->_login = $login;
		return $this;
	}

	public function getPassword() {
		return $this->_password;
	}

	public function setPassword($password) {
		$this->_password = $password;
		return $this;
	}

	public function getName() {
		return $this->_name;
	}

	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	public function getSecondName() {
		return $this->_secondName;
	}

	public function setSecondName($secondName) {
		$this->_secondName = $secondName;
		return $this;
	}

	public function getRegTime() {
		return $this->_regTime;
	}

	public function setRegTime($regTime) {
		$this->_regTime = $regTime;
		return $this;
	}

	public function getLastVisitTime() {
		return $this->_lastVisitTime;
	}

	public function setLastVisitTime($lastVisitTime) {
		$this->_lastVisitTime = $lastVisitTime;
		return $this;
	}

	public function getEmail() {
		return $this->_email;
	}

	public function setEmail($email) {
		$this->_email = $email;
		return $this;
	}
}
