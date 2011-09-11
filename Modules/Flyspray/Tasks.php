<?php

require_once _X2PP_ROOT . '/libs/PP/Project/Tickets.php';
require_once _X2PP_ROOT . '/libs/PP/Project/Ticket/Subscriptions.php';
require_once _X2PP_ROOT . '/libs/PP/Comments.php';

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
	
	/**
	 * @var Modules_Flyspray_Categories
	 */
	private $_categories;
	
	private $_closureComments = array();
	
	public function __construct(DB $DB, $config)
	{
		$this->setConfig($config);
		$this->setDB($DB);
	}
	
	public function setProjectsConverter(Modules_Flyspray_Projects $projects)
	{
		$this->_projects = $projects;
	}
	
	public function setUsersConverter(Modules_Flyspray_Users $users)
	{
		$this->_users = $users;
	}
	
	public function setCategoriesConverter(Modules_Flyspray_Categories $categories)
	{
		$this->_categories = $categories;
	}
	
	public function convert()
	{
		if (empty($this->_users) || empty($this->_projects))
		{
			throw new RuntimeException('Users and Projects convert should be set!');
		}

		$this->_convertTasks();	
		$this->_convertTaskComments();	
	}
	
	private function _convertTaskComments()
	{
		// get data from database
		$query = 'SELECT * FROM ' . $this->getDB()->getSourcePrefix() . 'comments';
		$stmt = $this->getDB()->getSource()->query($query);

		$pp_comments = array();
		// comment_text 	last_edited_time
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_comment = new PP_Comments($this->getDB());
			$pp_comment->setOldId($row['comment_id']);
			$pp_comment->rel_object_id = $this->getNewId($row['task_id']);
			$pp_comment->rel_object_manager = 'ProjectTickets';
			$pp_comment->text = $row['comment_text'];
			$pp_comment->is_private = $row[''];
			$pp_comment->is_anonymous = $row[''];
			$pp_comment->author_name = $row[''];
			$pp_comment->author_email = $row[''];
			$pp_comment->author_homepage = $row[''];
			$pp_comment->created_on = time2SqlDateTime($row['date_added']);
			$pp_comment->created_by_id = $this->_users->getNewId($row['user_id']);
			$pp_comment->updated_on = $row[''];
			$pp_comment->updated_by_id = $row[''];

			$pp_comments[ $row['comment_id'] ] = $pp_comments;
		}
		$stmt->closeCursor();

		// insert in db
		$pp_comments[ array_rand($pp_comments) ]->writes2DB($pp_comments);
		
		// clean memory
		unset($pp_comments);
	}

	private function _convertTasks()
	{
		// get data from database
		$query = 'SELECT t.*, GROUP_CONCAT(a.user_id SEPARATOR \',\') AS assigned_user_id FROM ' . $this->getDB()->getSourcePrefix() . 'tasks t
				LEFT JOIN ' . $this->getDB()->getSourcePrefix() . 'assigned a ON a.task_id = t.task_id GROUP BY task_id';
		$stmt = $this->getDB()->getSource()->query($query);

		$pp_tickets = array();
		$assigned = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_ticket = new PP_Project_Tickets($this->getDB());
			$pp_ticket->setOldId($row['task_id']);
			$pp_ticket->setProject_id($this->_projects->getNewId($row['project_id']));
			$pp_ticket->setCategory_id($this->_categories->getNewId($row['task_type']));
			$pp_ticket->setCreated_on(time2SqlDateTime($row['date_opened']));
			$pp_ticket->setCreated_by_id($this->_users->getNewId($row['opened_by']));
			if ($row['is_closed'] == '1')
			{
				$pp_ticket->setClosed_on(time2SqlDateTime($row['date_closed']));
				$pp_ticket->setClosed_by_id($this->_users->getNewId($row['closed_by']));
				$pp_ticket->setUpdated('closed');
				$pp_ticket->setState('closed');
				$this->_closureComments[ $row['task_id'] ] = $row['closure_comment'];
			}
			else
			{
				$pp_ticket->setClosed_on('0000-00-00 00:00:00');
				$pp_ticket->setClosed_by_id(null);
				$pp_ticket->setUpdated('open');
				$pp_ticket->setState('opened');
				$this->_closureComments[ $row['task_id'] ] = $row['closure_comment'];
			}

			if ($row['mark_private'] == '1')
			{
				$pp_ticket->setIs_private(1);
			}
			else
			{
				$pp_ticket->setIs_private(0);
			}
			
			$pp_ticket->setSummary($row['item_summary']);
			$pp_ticket->setDescription($row['detailed_desc']);
			$pp_ticket->setPriority($row['task_priority']);
			if (!empty($row['assigned_user_id']))
			{
				$users_assigned = explode(',', $row['assigned_user_id']);
				$pp_ticket->setAssigned_to_user_id($this->_users->getNewId($users_assigned[0]));
				$assigned[ $row['task_id'] ] = $users_assigned;
			}
			else
			{
				$pp_ticket->setAssigned_to_user_id(null);
			}
		
			$pp_tickets[ $row['task_id'] ] = $pp_ticket;
		}
		$stmt->closeCursor();

		// insert in db
		$this->setNewIds($pp_tickets[ array_rand($pp_tickets) ]->writes2DB($pp_tickets));
		
		// clean memory
		unset($pp_tickets);
		
		// ticket subscription (no multiple assignement on pp, so use subscriptions ...)
		$ticketSubscriptions = array();
		foreach ($assigned as $oldTicketId => $assigned_users)
		{
			foreach ($assigned_users as $user_id)
			{
				$ticketSubscriptions[ $oldTicketId ] = new PP_Project_Ticket_Subscriptions($this->getDB());
				$ticketSubscriptions[ $oldTicketId ]->setTicket_id($this->getNewId($oldTicketId));
				$ticketSubscriptions[ $oldTicketId ]->setUser_id($this->_users->getNewId($user_id));
			}
		}
		$ticketSubscriptions[ array_rand($ticketSubscriptions) ]->writes2DB($ticketSubscriptions);
		unset($ticketSubscriptions);
	}

	public function getClosureComment($task_id)
	{
		return $this->_closureComments[ $task_id ];
	}
}

# EOF
