<?php

/**
 * Class: SocialNetwork_Helper_Navigation
 * Date begin: Feb 10, 2011
 * 
 * Navigation helper
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class SocialNetwork_Helper_Navigation extends Zend_Controller_Action_Helper_Abstract {
	public function preDispatch() {
		$pages = array(
			array(
				'controller' => 'users',
				'action' => 'signup',
				'label' => 'Signup',
				'privilege' => 'signup',
				'resource' => 'users',
			),
			array(
				'controller' => 'users',
				'action' => 'login',
				'label' => 'Login',
				'privilege' => 'login',
				'resource' => 'users',
			),
			array(
				'controller' => 'users',
				'action' => 'reset-password',
				'label' => 'Reset',
				'privilege' => 'reset-password',
				'resource' => 'users',
			),
			array(
				'controller' => 'index',
				'label' => 'Main',
				'privilege' => 'index',
				'resource' => 'index',
			),
			array(
				'controller' => 'users',
				'label' => 'Users list',
				'privilege' => 'index',
				'resource' => 'users',
			),
			array(
				'controller' => 'users',
				'action' => 'profile',
				'label' => 'Profile',
				'privilege' => 'profile',
				'resource' => 'users',
			),
			array(
				'controller' => 'friends',
				'label' => 'Friends',
				'privilege' => 'friends',
				'resource' => 'friends',
				'pages' => array(
					array(
						'controller' => 'friends',
						'label' => 'List',
					),
					array(
						'controller' => 'friends',
						'action' => 'requests',
						'label' => 'Requests',
					),
				),
			),
			array(
				'controller' => 'mails',
				'label' => 'Mail',
				'privilege' => 'index',
				'resource' => 'mails',
				'pages' => array(
					array( // @TODO - write custom messages
						'controller' => 'mails',
						'action' => 'new',
						'label' => 'Write mail',
						'visible' => false,
					),
					array(
						'controller' => 'mails',
						'action' => 'inbox',
						'label' => 'Inbox',
					),
					array(
						'controller' => 'mails',
						'action' => 'send',
						'label' => 'Send',
					),
					array(
						'controller' => 'mails',
						'action' => 'message',
						'label' => 'Message',
						'visible' => false,
					),
				),
			),
			array(
				'controller' => 'users',
				'action' => 'logout',
				'label' => 'Logout',
				'privilege' => 'logout',
				'resource' => 'users',
				'class' => 'confirmDialog',
			),
		);

		$container = new Zend_Navigation($pages);
		$this->getActionController()->view->navigation($container);
	}
}
