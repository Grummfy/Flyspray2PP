<?php

// import projects
require_once _X2PP_ROOT . '/libs/PP/Projects.php';

class Modules_Flyspray_Projects extends AbstractModules
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
		$query = 'SELECT * FROM ' . $this->getDB()->getSourcePrefix() . 'projects';
		$stmt = $this->getDB()->getSource()->query($query);

		// create array of PP_Projects
		$pp_projects = array();
		
		$config = $this->getConfig();
		$config = $config['projectpier'];

		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_project = new PP_Users($this->getDB());
			$pp_project->setOldId($row['project_id']);
			
			// set values for each fields
			$pp_project->setName($row['project_title']);
			// $pp_project->setPriority();
			$pp_project->setDescription($row['intro_message']);
			$pp_project->setCreated_on(time2SqlDateTime($row['last_updated']));
			$pp_project->setCreated_by_id($config['default_user_id']);
			$pp_project->setUpdated_on(time2SqlDateTime($row['last_updated']));
			$pp_project->setUpdated_by_id($config['default_user_id']);

			$pp_projects[ $row['project_id'] ] = $pp_project;
		}
		$stmt->closeCursor();
				
		// insert in db
		$this->setNewIds($pp_projects[ array_rand($pp_projects) ]->writes2DB($pp_projects));
		
		// clean memory
		unset($pp_projects);
	}
}

# EOF
