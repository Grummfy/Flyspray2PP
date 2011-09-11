<?php

class PP_Project_Users extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'project_id'	=> self::TYPE_INT,
		'user_id'		=> self::TYPE_INT,
		'role_id'		=> self::TYPE_INT,
		'created_on'	=> self::TYPE_STRING,
		'created_by_id'	=> self::TYPE_INT 
	);

	/**
	 * @param DB $DB database link
	 */
	public function __construct(DB $DB)
	{
		$this->_DB = $DB;
	}
	
	public function getDBTableName()
	{
		return $this->getDB()->getDestinationPrefix() . 'project_users';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
