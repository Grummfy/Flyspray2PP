<?php

require_once _X2PP_ROOT . '/libs/PP/Tickets.php';

class Modules_Flyspray_Tasks extends AbstractModules
{
	/**
	 * @var Modules_Flyspray_Projects
	 */
	private $_projects;
	
	/**
	 * @var Modules_Flyspray_Users
	 */
	private $_users;
	
	public function __construct(DB $DB, $config)
	{
		$this->setConfig($config);
		$this->setDB($DB);
	}
	
	public function setPojectsConverter(Modules_Flyspray_Projects $projects)
	{
		$this->_projects = $projects;
	}
	
	public function setUsersConverter(Modules_Flyspray_Users $users)
	{
		$this->_users = $users;
	}

	public function convert()
	{
		if (empty($this->_users) || empty($this->_projects))
		{
			throw new RuntimeException('Users and Projetcs convert should be set!');
		}

		// get data from database
		/*
  `task_type` int(3) NOT NULL DEFAULT '0',
  `date_opened` int(11) NOT NULL DEFAULT '0',
  `opened_by` int(3) NOT NULL DEFAULT '0',
  `is_closed` int(1) NOT NULL DEFAULT '0',
  `date_closed` int(11) NOT NULL DEFAULT '0',
  `closed_by` int(3) NOT NULL DEFAULT '0',
  `closure_comment` text,
  `item_summary` varchar(100) NOT NULL,
  `detailed_desc` text,
  `item_status` int(3) NOT NULL DEFAULT '0',
  `resolution_reason` int(3) NOT NULL DEFAULT '1',
  `product_category` int(3) NOT NULL DEFAULT '0',
  `product_version` int(3) NOT NULL DEFAULT '0',
  `closedby_version` int(3) NOT NULL DEFAULT '0',
  `operating_system` int(3) NOT NULL DEFAULT '0',
  `task_severity` int(3) NOT NULL DEFAULT '0',
  `task_priority` int(3) NOT NULL DEFAULT '0',
  `last_edited_by` int(3) NOT NULL DEFAULT '0',
  `last_edited_time` int(11) NOT NULL DEFAULT '0',
  `percent_complete` int(3) NOT NULL DEFAULT '0',
  `mark_private` int(1) NOT NULL DEFAULT '0',
  `due_date` int(11) NOT NULL DEFAULT '0',
  `anon_email` varchar(100) NOT NULL DEFAULT '',
  `task_token` varchar(32) NOT NULL DEFAULT '0',
		 */
		
		$query = 'SELECT * FROM ' . $this->getDB()->getSourcePrefix() . 'tasks';
		$stmt = $this->getDB()->getSource()->query($query);

		$pp_tickets = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_ticket = new PP_Project_Tickets($this->getDB());
			$pp_ticket->setOldId($row['task_id']);
			$pp_ticket->setProject_id($this->_projects->getNewId($row['project_id']));
			$pp_ticket->setCategory_id()
			
			/*
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
			 */
			
			$pp_tickets[ $row['task_id'] ] = $pp_ticket;
		}
		$stmt->closeCursor();
				
		// insert in db
		$this->setNewIds($pp_tickets[ array_rand($pp_tickets) ]->writes2DB($pp_tickets));
		
		// clean memory
		unset($pp_tickets);
	}
}

# EOF
