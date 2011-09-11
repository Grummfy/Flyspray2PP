<?php

class PP_Project_Ticket_Subscriptions extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'ticket_id'	=> self::TYPE_INT,
		'user_id'	=> self::TYPE_INT
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
		return $this->getDB()->getDestinationPrefix() . 'project_ticket_subscriptions';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
