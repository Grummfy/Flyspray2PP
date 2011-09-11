<?php

class PP_Project_User_Permissions extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'user_id'		=> self::TYPE_INT,
		'project_id'	=> self::TYPE_INT,
		'permission_id'	=> self::TYPE_INT
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
		return $this->getDB()->getDestinationPrefix() . 'project_user_permissions';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
