<?php

abstract class AbstractModules implements IModules
{
	private $_newIds = array();

	/**
	 * @var DB database link
	 */
	private $_DB;
	
	/**
	 * @var array config
	 */
	private $_config;

	public function setDB(DB $db)
	{
		$this->_DB = $db;
	}

	public function setConfig($config)
	{
		$this->_config = $config;
	}
	
	public function setNewIds($newIds)
	{
		$this->_newIds = $newIds;
	}
	
	/**
	 * @return DB
	 */
	public function getDB()
	{
		return $this->_DB;
	}
	
	public function getConfig()
	{
		return $this->_config;
	}

	public function getNewIds()
	{
		return $this->_newIds;
	}

	public function getNewId($oldId)
	{
		return $this->_newIds[ $oldId ];
	}
}

# EOF
