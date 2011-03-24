<?php

/**
 * Class: MailsController
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class MailsController extends Zend_Controller_Action {
	/**
	 * Mapper object
	 *
	 * @var Application_Model_MapperFactory
	 */
	protected $_mapper;
	
	
	public function init() {
		$this->_mapper = Application_Model_MailsMapper::getInstance();
	}

	public function indexAction() {
		$this->getHelper('Redirector')->gotoSimpleAndExit('inbox', 'mails');
	}

	public function newAction() {
		$id = (int)$this->getRequest()->getParam('id');
		if (!$id) {
			$this->getRequest()->setParam('error_handler', Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION);
			return $this->getRequest()->setControllerName('error')->setActionName('error')->setDispatched(false);
		}
		
		$form = new Application_Form_MailsSend();
		$request = $this->getRequest();
		if ($request->isPost()) {
			$data = $request->getPost();
			if ($form->isValid($data)) {
				$data = $form->getValidValues($data);
				
				$item = $this->_mapper->createItemModel(array(
					'uid' => $id,
					'aid' => Zend_Registry::get('currentUser')->getId(),
					'message' => $data['message'],
				));
				
				$this->_mapper->send($item);
				$this->getHelper('Redirector')->gotoSimpleAndExit(null, 'mails');
			}
		}
		
		$form->setDefault('id', $id);
		$this->view->headTitle('Write message');
		$this->view->form = $form;
	}
	
	public function inboxAction() {
		$this->view->headTitle('Inbox');
		
		$paginator = new SocialNetwork_Paginator(new SocialNetwork_Paginator_Adapter_DbSelect(
			$this->_mapper,
			$this->_mapper->getQuery(true, 'createTime desc'),
			$this->_mapper->getNum(true)
		));
		$this->view->paginator = $paginator;
	}
	
	public function sendAction() {
		$this->view->headTitle('Send');

		$paginator = new SocialNetwork_Paginator(new SocialNetwork_Paginator_Adapter_DbSelect(
			$this->_mapper,
			$this->_mapper->getQuery(false, 'createTime desc'),
			$this->_mapper->getNum(false)
		));
		$this->view->paginator = $paginator;
	}
	
	public function messageAction() {
		$message = $this->_mapper->message($this->getRequest()->getParam('id'));
		$this->view->headTitle('Message from ' . $message->getLogin());
		$this->view->message = $message;
	}
}
