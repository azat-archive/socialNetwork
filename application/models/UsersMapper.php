<?php

/**
 * Class: Application_Model_UsersMapper
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_UsersMapper extends Application_Model_MapperFactory {
	/**
	 * Instance
	 *
	 * @var Application_Model_UsersMapper
	 */
	protected static $_instance;
	
	
	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __construct() {
		$this->setDbTable(new Application_Model_DbTable_Users);
		$this->setItemModel('Application_Model_Users');
	}
	
	/**
	 * Signup user
	 *
	 * @param Application_Model_Users $user
	 * @return mixed
	 */
	public function signup(Application_Model_Users $user) {
		$user->setPassword(md5($user->getPassword()));
		$user->setRegTime(time());
		// check if exists some thing with this login
		if ($this->findOne($this->createItemModel(array('login' => $user->getLogin())))) {
			throw new Application_Model_Exception('User with login "%s" already exists', $user->getLogin());
		}
		// check if exists some thing with this email
		if ($this->findOne($this->createItemModel(array('email' => $user->getEmail())))) {
			throw new Application_Model_Exception('User with email "%s" already exists', $user->getEmail());
		}
		return $this->save($user);
	}

	/**
	 * Login user
	 *
	 * @param Application_Model_Users $user 
	 * @return mixed
	 */
	public function login(Application_Model_Users $user) {
		$user->setPassword(md5($user->getPassword()));
		// check if exists some thing with this login
		$user = $this->findOne($this->createItemModel(array('login' => $user->getLogin(), 'password' => $user->getPassword())));
		if ($user) {
			// session
			$session = Zend_Registry::get('session');
			if (!($session instanceof Zend_Session_Namespace)) {
				throw new Application_Model_Exception('Property in Zend_Registry "session" must be instance of "Zend_Session_Namespace"');
			}
			$session->user = $user->getAll();
			Zend_Registry::set('currentUser', $user);
			// cookie
			$responce = Zend_Registry::get('responce');
			if (!($responce instanceof Zend_Controller_Response_Abstract)) {
				throw new Application_Model_Exception('Property in Zend_Registry "responce" must be instance of "Zend_Controller_Response_Abstract"');
			}
			$responce->setHeader('Set-Cookie', new Zend_Http_Cookie('id', $user->getId(), Zend_Registry::get('cookieDomain'), time()+60*60*24*30, '/'));
			$responce->setHeader('Set-Cookie', new Zend_Http_Cookie('hash', md5($user->getPassword() . substr($user->getLogin(), -2)), Zend_Registry::get('cookieDomain'), time()+60*60*24*30, '/'));
			
			return true;
		}
		return false;
	}
	
	/**
	 * Test authorization
	 * 
	 * @return bool
	 */
	public function testAuthorization() {
		$session = Zend_Registry::get('session');
		if (!($session instanceof Zend_Session_Namespace)) {
			throw new Application_Model_Exception('Property in Zend_Registry "session" must be instance of "Zend_Session_Namespace"');
		}
		// info in session
		if ($session->user) {
			// check if exists some thing with this login
			$user = $this->getDbTable()
						->select()
						->where('id = ?', $session->user['id'])
						->limit(1)
						->query()
						->fetch();
			
			if (!$user) {
				$this->logout();
				return false;
			}
			$session->user = $user;
			Zend_Registry::set('currentUser', $this->createItemModel($user));
			$this->updateLastVisitTime();
			return true;
		}
		$request = Zend_Registry::get('request');
		if (!($request instanceof Zend_Controller_Request_Abstract)) {
			throw new Application_Model_Exception('Property in Zend_Registry "request" must be instance of "Zend_Controller_Request_Abstract"');
		}
		// info in cookie
		if ($request->getCookie('id') && $request->getCookie('hash')) {
			// check if exists some thing with this login
			$result = $this->getDbTable()
						->select()
						->where('id = ?', $request->getCookie('id'))
						->limit(1)
						->query()
						->fetch();
			if (!$result) {
				$this->logout();
				return false;
			}
			$user = $this->createItemModel($result);
			// session
			$session->user = $user->getAll();
			Zend_Registry::set('currentUser', $user);
			// cookie
			$responce = Zend_Registry::get('responce');
			if (!($responce instanceof Zend_Controller_Response_Abstract)) {
				throw new Application_Model_Exception('Property in Zend_Registry "responce" must be instance of "Zend_Controller_Response_Abstract"');
			}
			$responce->setHeader('Set-Cookie', new Zend_Http_Cookie('id', $user->getId(), Zend_Registry::get('cookieDomain'), time()+60*60*24*30, '/'), true);
			$responce->setHeader('Set-Cookie', new Zend_Http_Cookie('hash', md5($user->getPassword() . substr($user->getLogin(), -2)), Zend_Registry::get('cookieDomain'), time()+60*60*24*30, '/'), true);
			$this->updateLastVisitTime();
			return true;			
		}
		return false;
	}
	
	/**
	 * User logout
	 * 
	 * @return bool
	 */
	public function logout() {
		// session
		$session = Zend_Registry::get('session');
		if (!($session instanceof Zend_Session_Namespace)) {
			throw new Application_Model_Exception('Property in Zend_Registry "session" must be instance of "Zend_Session_Namespace"');
		}
		unset($session->user);
		// cookie
		$responce = Zend_Registry::get('responce');
		if (!($responce instanceof Zend_Controller_Response_Abstract)) {
			throw new Application_Model_Exception('Property in Zend_Registry "responce" must be instance of "Zend_Controller_Response_Abstract"');
		}
		$responce->setHeader('Set-Cookie', new Zend_Http_Cookie('id', null, Zend_Registry::get('cookieDomain'), time()-3600, '/'), true);
		$responce->setHeader('Set-Cookie', new Zend_Http_Cookie('hash', null, Zend_Registry::get('cookieDomain'), time()-3600, '/'), true);
		return true;
	}
	
	/**
	 * Reset password for current user
	 * Send email with new one
	 * 
	 * @param string $email - email
	 * @return bool
	 */
	public function resetPassword($email) {
		$newPassword = SocialNetwork_Basic::randomString(15);
		
		// retrive info
		$user = $this->findOne($this->createItemModel(array('email' => $email)));
		if (!$user) {
			throw new Application_Model_Exception('No user with email "%s"', $email);
		}
		$item = $this->createItemModel(array('id' => $user->getId(), 'password' => md5($newPassword)));
		// update password
		$this->save($item);
		
		$mail = new SocialNetwork_Mail();
		return $mail->addTo($user->getEmail(), $user->getLogin())->
				setBodyTextF('Hi, %s, your new password is "%s"', $user->getName(), $newPassword)->
				setSubject('Reset password')->
				send();
	}
	
	/**
	 * Update last visit time of user
	 * 
	 * @return mixed
	 */
	protected function updateLastVisitTime() {
		if (!Zend_Registry::isRegistered('currentUser')) {
			throw new Application_Model_Exception('Property in Zend_Registry "currentUser" must be set');
		}
		
		$user = Zend_Registry::get('currentUser');
		return $this->save($this->createItemModel(array('id' => $user->getId(), 'lastVisitTime' => time())));
	}
}
