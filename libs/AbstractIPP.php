<?php

/*
 * setValue($value);
 * getValue();
 * fillValue();
 */

abstract class AbstractIPP implements IPP
{
	/**
	 * @return DB
	 */
	abstract public function getDB();

	abstract public function getDBTableName();
	
	private $_oldId;
	
	public function setOldId($id)
	{
		$this->_oldId = $id;
		return $this;
	}

	public function getOldId()
	{
		return $this->_oldId;
	}

	public function write2DB(IPP $value)
	{
		$ary = writes2DB(array($value));
		return $ary[0];
	}

	public function writes2DB(array $values)
	{
		$forDB = array();
		foreach($values as $value)
		{
			$forDB[ $value->getOldId() ] = $value->toArray();
		}

		return $this->getDb()->arrays2DB($this->getDBTableName(), $forDB, true);
	}

	//
	// magic stuffs
	//
	
	const TYPE_INT = 0;
	const TYPE_DOUBLE = 1;
	const TYPE_STRING = 2;

	/**
	 * value setted
	 * @var array
	 */
	protected $_vars = array();

	/**
	 * defined fields : name:type
	 * @var array
	 */
	protected $_fields = array();

	public function is_empty()
	{
		return empty($this->_vars);
	}

	public function __isset($name)
	{
		return isset($this->_vars[$name]);
	}
	
	public function __call($name, $args)
	{
		if (strpos($name, 'set') == 0)
		{
			$this->__set(lcfirst(substr($name, 3)), $args[ 0 ]);
		}
	}

	public function __set($name, $value)
	{
		$method = 'set' . ucfirst($name);
		if(!method_exists($this, $method))
		{
			if(isset($this->_fields[$name]))
			{
				switch ($this->_fields[ $name ])
				{
					case self::TYPE_INT:
						$this->_vars[$name] = (int)$value;
						break;
					case self::TYPE_DOUBLE:
						$this->_vars[$name] = (double)$value;
						break;
					case self::TYPE_STRING:
					default:
						$this->_vars[$name] = (string)$value;
						break;
				}
			}

			return $this;
		}

		return $this->$method($value);
	}

	public function __get($name)
	{
		$method = 'get' . ucfirst($name);

		if(!method_exists($this, $method))
		{
			if(isset($this->_fields[$name]))
			{
				return ($this->_vars[$name]);
			}

			return null;
		}

		return $this->$method();
	}
	
	public function setOptions(array $options)
	{
		$myInstance = $this;
		array_walk($options, function($value, $key) use($myInstance)
		{
			$myInstance->$key = $value;
		});

		return $this;
	}
	
	/**
	 * fill values
	 */
	public function fillValues()
	{
		foreach($this->_fields as $key => $type)
		{
			if (isset($this->_vars[ $key ]))
			{
				continue;
			}

			$method = 'fill' . ucfirst($key);
			if(method_exists($this, $method))
			{
				$this->$method();
				continue;
			}

			switch ($type)
			{
				case self::TYPE_INT:
					$this->_vars[ $key ] = 0;
					break;
				case self::TYPE_DOUBLE:
					$this->_vars[ $key ] = 0.0;
					break;
				case self::TYPE_STRING:
				default:
					$this->_vars[ $key ] = '';
					break;
			}
		}
	}

	public function reset()
	{
		$this->_vars = array();
	}

	public function toArray()
	{
		// $this->fillValues();
		return $this->_vars;
	}
}

# EOF
