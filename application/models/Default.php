<?php

/**
 * Class: Application_Model_Default
 * Date begin: Feb 3, 2011
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
class Application_Model_Default extends Application_Model_ItemsFactory {
	/**
	 * All properties
	 *
	 * @var array
	 */
	protected $_properties = array();
	
	
	public function __call($name, $arguments) {
		$act = substr($name, 0, 3);
		$property =	lcfirst(substr($name, 3));
		
		switch ($act) {
			case 'set':
				if (!isset($arguments)) {
					throw new Application_Model_Exception('For %s need first argument', $name);
				}
				
				$this->_properties[$property] = $arguments[0];
				return $this;
				break;
			case 'get':
				return $this->_properties[$property];
				break;
		}
		throw new Application_Model_Exception('No method %s in %s', $name, get_class($this));
	}

	/**
	 * Set options
	 *
	 * @param array $options
	 * @return Application_Model_Default 
	 */
	public function setOptions(array $options) {
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			$this->$method($value);
		}
		return $this;
	}
	
	public function __set($name, $value) {
		$this->_properties[lcfirst($name)] = $value;
	}
	
	public function __get($name) {
		return $this->_properties[lsfirst($name)];
	}
}
