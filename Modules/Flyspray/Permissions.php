<?php

// import projects
require_once _X2PP_ROOT . '/libs/PP/Projects.php';

class Modules_Flyspray_Permissions extends AbstractModules
{
	/**
	 * @param DB $DB database link
	 */
	public function __construct(DB $DB, $config)
	{
		$this->setConfig($config);
		$this->setDB($DB);
	}

	public function convert()
	{
		// get data from database
		$query = 'SELECT GROUP_CONCAT( ug.user_id SEPARATOR \',\' ) AS group_members, g.* FROM ' .
			$this->getDB()->getSourcePrefix() . 'groups g LEFT JOIN ' .
			$this->getDB()->getSourcePrefix() . 'users_in_groups ug ON ug.group_id = g.group_id GROUP BY group_id';
		$stmt = $this->getDB()->getSource()->query($query);

		$pp_permissions = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_project = new PP_Projects($this->getDB());
			$pp_project->setOldId($row['project_id']);
			
			// set values for each fields
			$pp_project->setName($row['project_title']);
			// $pp_project->setPriority();
			$pp_project->setDescription($row['intro_message']);
			$pp_project->setCreated_on(time2SqlDateTime($row['last_updated']));
			$pp_project->setCreated_by_id($config['default_user_id']);
			$pp_project->setUpdated_on(time2SqlDateTime($row['last_updated']));
			$pp_project->setUpdated_by_id($config['default_user_id']);

			$pp_permissions[ $row['project_id'] ] = $pp_project;
		}
		$stmt->closeCursor();
				
		// insert in db
		$this->setNewIds($pp_permissions[ array_rand($pp_permissions) ]->writes2DB($pp_permissions));
		
		// clean memory
		unset($pp_permissions);
	}
}

# EOF
