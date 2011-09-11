<?php

class PP_Project_Ticket_Changes extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'ticket_id'		=> self::TYPE_INT,
		'type'			=> self::TYPE_STRING,
		'from_data'		=> self::TYPE_STRING,
		'to_data'		=> self::TYPE_STRING,
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
		return $this->getDB()->getDestinationPrefix() . 'project_ticket_changes';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
