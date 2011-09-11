<?php

require_once _X2PP_ROOT . '/libs/PP/Project/Categories.php';

class Modules_Flyspray_Categories extends AbstractModules
{
	/**
	 * @var Modules_Flyspray_Projects
	 */
	private $_projects;

	public function __construct(DB $DB, $config)
	{
		$this->setConfig($config);
		$this->setDB($DB);
	}
	
	public function setProjectsConverter(Modules_Flyspray_Projects $projects)
	{
		$this->_projects = $projects;
	}

	public function convert()
	{
		if (empty($this->_projects))
		{
			throw new RuntimeException('Projects convert should be set!');
		}

		// get data from database
		$query = 'SELECT * FROM ' . $this->getDB()->getSourcePrefix() . 'list_tasktype';
		$stmt = $this->getDB()->getSource()->query($query);

		$pp_types = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_type = new PP_Project_Tickets($this->getDB());
			$pp_type->setOldId($row['tasktype_id']);
			if ($row['project_id'] == '0')
			{
				$pp_type->setProject_id(0);
			}
			else
			{
				$pp_type->setProject_id($this->_projects->getNewId($row['project_id']));
			}
			$pp_type->setName($row['tasktype_name']);
			$pp_type->setDescription($row['tasktype_name']);			
			$pp_types[ $row['tasktype_id'] ] = $pp_type;
		}
		$stmt->closeCursor();
				
		// insert in db
		$this->setNewIds($pp_tickets[ array_rand($pp_tickets) ]->writes2DB($pp_tickets));
		
		// clean memory
		unset($pp_tickets);
	}
}

# EOF
