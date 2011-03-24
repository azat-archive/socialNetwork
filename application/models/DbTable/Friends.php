<?php

/**
 * Class: Application_Model_DbTable_Users
 * Date begin: Feb 3, 2011
 * 
 * Table with users friends
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_DbTable_Friends extends Application_Model_DbTable_Factory {
	protected $_name = 'friends';
	protected $_primary = array('uid', 'fid');
}
