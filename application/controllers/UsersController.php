<?php

/**
 * Class: UsersController
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class UsersController extends Zend_Controller_Action {
	/**
	 * Mapper object
	 *
	 * @var Application_Model_MapperFactory
	 */
	protected $_mapper;
	
	
	public function init() {
		$this->_mapper = Application_Model_UsersMapper::getInstance();
	}
	
	public function indexAction() {
		$this->view->users = $this->_mapper->findAll(array('id desc'));
		$this->view->headTitle('Users list');
		
		$paginator = new SocialNetwork_Paginator(new SocialNetwork_Paginator_Adapter_DbSelect($this->_mapper));
		$this->view->paginator = $paginator;
	}

	public function signupAction() {
		$request = $this->getRequest();
		$form = new Application_Form_UsersSignup;

		if ($request->isPost()) {
			$data = $request->getPost();
			
			if ($form->isValid($data) && ($data['password'] == $data['passwordConfirm'])) {
				$data = $form->getValidValues($data);
				
				$item = $this->_mapper->createItemModel(array(
					'login' => $data['login'],
					'password' => $data['password'],
					'email' => $data['email'],
					'name' => $data['name'],
					'secondName' => $data['secondName'],
				));

				try {
					$id = $this->_mapper->signup($item);
					$this->getHelper('Redirector')->gotoSimpleAndExit('login', 'users');
				} catch (Application_Model_Exception $e) {
					$this->view->error = $e->getMessage();
				}
			}
		}

		$this->view->headTitle('Signup');
		$this->view->form = $form;
	}

	public function loginAction() {
		$request = $this->getRequest();
		$form = new Application_Form_UsersLogin();

		if ($request->isPost()) {
			$data = $request->getPost();
			if ($form->isValid($data)) {
				$data = $form->getValidValues($data);
				
				$item = $this->_mapper->createItemModel(array(
					'login' => $data['login'],
					'password' => $data['password'],
				));

				$status = $this->_mapper->login($item);
				if ($status) {
					$this->getHelper('Redirector')->gotoSimpleAndExit('profile', 'users');
				}
				$this->view->error = 'Such combination of login & password is not meet';
			}
		}

		$this->view->headTitle('Login');
		$this->view->form = $form;
	}

	public function logoutAction() {
		$this->_mapper->logout();
		$this->getHelper('Redirector')->gotoSimpleAndExit('login', 'users');
	}

	public function profileAction() {
		$id = (int)$this->getRequest()->getParam('id');
		
		if (!$id) {
			$this->view->user = Zend_Registry::get('currentUser');
		} else {
			$user = $this->_mapper->findOne($this->_mapper->createItemModel(array('id' => $id)));
			if (!$user) {
				return $this->getRequest()->setControllerName('error')->setActionName('user')->setDispatched(false);
			}
			$this->view->user = $user;
		}
		
		$this->view->headTitle(($id && Zend_Registry::get('currentUser')->getId() != $id ? $user->getLogin() . '`s' : 'Your') . ' profile');
	}
	
	public function resetPasswordAction() {
		$request = $this->getRequest();
		$form = new Application_Form_UsersResetPassword();
		
		$this->view->headTitle('Reset password');
		if ($request->isPost()) {
			$data = $request->getPost();
			if ($form->isValid($data)) {
				try {
					$ok = $this->_mapper->resetPassword($data['email']);
					$this->view->error = 'Password successfully reset, new password send to you by email.';
					return true;
				} catch (Application_Model_Exception $e) {
					$this->view->error = $e->getMessage();
				}
			}
		}
		$this->view->form = $form;
	}
}
