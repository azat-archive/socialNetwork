<?php

/**
 * Class: FriendsController
 * Date begin: Feb 3, 2011
 * 
 * @TODO add paginator
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class FriendsController extends Zend_Controller_Action {
	/**
	 * Mapper object
	 *
	 * @var Application_Model_MapperFactory
	 */
	protected $_mapper;
	
	
	public function init() {
		$this->_mapper = Application_Model_FriendsMapper::getInstance();
	}

	public function indexAction() {
		return $this->getRequest()->setActionName('user')->setParam('id', Zend_Registry::get('currentUser')->getId())->setDispatched(false);
	}

	public function userAction() {
		$id = (int)$this->getRequest()->getParam('id');
		$cuid = Zend_Registry::get('currentUser')->getId(); // current user id
		
		if ($id != $cuid) {
			$userMapper = Application_Model_UsersMapper::getInstance();
			$this->view->user = $user = $userMapper->findOne($userMapper->createItemModel(array('id' => $id)));
		}
		$paginator = new SocialNetwork_Paginator(new SocialNetwork_Paginator_Adapter_DbSelect(
			$this->_mapper,
			$this->_mapper->getQuery($id, 'createTime desc'),
			$this->_mapper->getNum($id)
		));
		$friends = $paginator->getCurrentItems();
		
		// diff friends of current user & user with id = $id
		if ($id != $cuid) {
			$currentFriends = $this->_mapper->get($cuid);
			foreach ($friends as $friend) {
				$friend->setConfirm('n');
				
				foreach ($currentFriends as $currentFriend) {
					if ($currentFriend->getFid() == $friend->getFid()) {
						$friend->setConfirm('y');
					}
				}
			}
		}
		// refresh data
		$paginator->setCurrentItems($friends);
		
		$this->view->headTitle((Zend_Registry::get('currentUser')->getId() != $id ? $user->getLogin() . '`s' : 'Your') . ' friends');
		$this->view->paginator = $paginator;
		$this->view->id = $id;
	}

	public function requestsAction() {
		$id = Zend_Registry::get('currentUser')->getId();
		$paginator = new SocialNetwork_Paginator(new SocialNetwork_Paginator_Adapter_DbSelect(
			$this->_mapper,
			$this->_mapper->getRequestsQuery($id, 'createTime desc'),
			$this->_mapper->getRequestsNum($id)
		));
		$this->view->paginator = $paginator;
		$this->view->headTitle('Your requests to friends');
	}

	public function addAction() {
		$id = (int)$this->getRequest()->getParam('id');
		
		if (!$id) {
			return $this->getRequest()->setControllerName('error')->setActionName('friends')->setDispatched(false);
		}
		
		// @TODO - confirmed state
		// check is alredy friend
		if ($this->_mapper->isAFriend($id, false)) {
			$this->getHelper('Redirector')->gotoSimpleAndExit(null, 'friends');
		}
		
		$this->_mapper->save($this->_mapper->createItemModel(array(
			'uid' => Zend_Registry::get('currentUser')->getId(),
			'fid' => $id,
			'createTime' => time(),
		)));
		$this->getHelper('Redirector')->gotoSimpleAndExit(null, 'friends');
	}
	
	public function confirmAction() {
		$id = (int)$this->getRequest()->getParam('id');
		
		if (!$id) {
			return $this->getRequest()->setControllerName('error')->setActionName('friends')->setDispatched(false);
		}
		
		$this->_mapper->update(
			$this->_mapper->createItemModel(array(
				'confirm' => 'y',
			)),
			$this->_mapper->createItemModel(array(
				'uid' => Zend_Registry::get('currentUser')->getId(),
				'fid' => $id,
			))
		);
		$this->getHelper('Redirector')->gotoSimpleAndExit(null, 'friends');
	}

	public function deleteAction() {
		$id = (int)$this->getRequest()->getParam('id');
		
		if (!$id) {
			return $this->getRequest()->setControllerName('error')->setActionName('friends')->setDispatched(false);
		}
		
		$this->_mapper->deleteFriend($id);
		$this->getHelper('Redirector')->gotoSimpleAndExit(null, 'friends');
	}
}
