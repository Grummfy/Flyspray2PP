<?php

class PP_Project_Tickets extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'project_id'	=> self::TYPE_INT,
		'category_id'	=> self::TYPE_INT,
		'assigned_to_company_id'	=> self::TYPE_INT,
		'assigned_to_user_id'		=> self::TYPE_INT,
		'summary'		=> self::TYPE_STRING,
		'type'			=> self::TYPE_STRING,
		'description'	=> self::TYPE_STRING,
		'priority'		=> self::TYPE_STRING,
		'state'			=> self::TYPE_STRING,
		'is_private'	=> self::TYPE_INT,
		'closed_on'		=> self::TYPE_STRING,
		'closed_by_id'	=> self::TYPE_INT,
		'created_on'	=> self::TYPE_STRING,
		'created_by_id'	=> self::TYPE_INT,
		'updated_on'	=> self::TYPE_STRING,
		'updated_by_id'	=> self::TYPE_INT,
		'updated'		=> self::TYPE_STRING
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
		return $this->getDB()->getDestinationPrefix() . 'project_tickets';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
