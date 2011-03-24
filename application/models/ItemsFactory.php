<?php

/**
 * Class: Application_Model_ItemsFactory
 * Date begin: Feb 3, 2011
 * 
 * Items factory
 * 
 * @package socialNetwork
 * @author Azat Khuzhin
 */
abstract class Application_Model_ItemsFactory {
	public function __construct(array $options = null) {
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function __set($name, $value) {
		$method = 'set' . ucfirst($name);
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Application_Model_Exception('Invalid %s property', get_class($this));
		}
		$this->$method($value);
	}

	public function __get($name) {
		$method = 'get' . ucfirst($name);
		if (('mapper' == $name) || ('all' == $name || 'All' == $name) || !method_exists($this, $method)) {
			throw new Application_Model_Exception('Invalid %s property', get_class($this));
		}
		return $this->$method();
	}

	/**
	 * Set options
	 *
	 * @param array $options
	 * @return Application_Model_ItemsFactory 
	 */
	public function setOptions(array $options) {
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			} else {
				throw new Application_Model_Exception('No method "%s" in "%s"', $method, get_class($this));
			}
		}
		return $this;
	}
	
	/**
	 * Get all properties
	 *
	 * @param bool $nullTo - null to into output set
	 * @return array
	 */
	public function getAll($nullTo = false) {
		$methods = get_class_methods(get_class($this));
		$set = array();
		foreach ($methods as $method) {
			if ($method == 'getAll' || substr($method, 0, 3) != 'get') continue;
			
			$val = call_user_func(array(&$this, $method));
			if ($nullTo || !is_null($val)) {
				$set[lcfirst(substr($method, 3))] = $val;
			}
		}
		return $set;
	}
}
