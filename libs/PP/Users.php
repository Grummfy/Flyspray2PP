<?php

class PP_Users extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'company_id'	=> self::TYPE_INT,
		'username'		=> self::TYPE_STRING,
		'email'			=> self::TYPE_STRING,
		'homepage'		=> self::TYPE_STRING,
		'token'			=> self::TYPE_STRING,
		'salt'			=> self::TYPE_STRING,
		'twister'		=> self::TYPE_STRING,
		'display_name'	=> self::TYPE_STRING,
		'title'			=> self::TYPE_STRING,
		'avatar_file'	=> self::TYPE_STRING,
		'use_gravatar'	=> self::TYPE_INT,
		'office_number'	=> self::TYPE_STRING,
		'fax_number'	=> self::TYPE_STRING,
		'mobile_number'	=> self::TYPE_STRING,
		'home_number'	=> self::TYPE_STRING,
		'timezone'		=> self::TYPE_DOUBLE,
		'created_on'	=> self::TYPE_STRING,
		'created_by_id'	=> self::TYPE_INT,
		'updated_on'	=> self::TYPE_STRING,
		'last_login'	=> self::TYPE_STRING,
		'last_visit'	=> self::TYPE_STRING,
		'last_activity'	=> self::TYPE_STRING,
		'is_admin'		=> self::TYPE_INT,
		'auto_assign'	=> self::TYPE_INT,
		'use_LDAP'		=> self::TYPE_INT
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
		return $this->getDB()->getDestinationPrefix() . 'users';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
