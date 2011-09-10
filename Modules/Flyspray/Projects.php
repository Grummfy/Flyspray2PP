<?php

// import projects
require_once _X2PP_ROOT . '/libs/PP/Projects.php';

class Modules_Flyspray_Projects implements IModules
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	private $_config;
	
	private $_newIds = array();
	
	/**
	 * @param DB $DB database link
	 */
	public function __construct(DB $DB, $config)
	{
		$this->_DB = $DB;
		$this->_config = $config;
	}

	public function convert()
	{
		// get data from database
		$query = 'SELECT * FROM ' . $this->_DB->getSourcePrefix() . 'projects';
		$stmt = $this->_DB->getSource()->query($query);

		// create array of PP_Projects
		$pp_projects = array();
		/*
INSERT INTO `flyspray_projects` (`project_id`, `project_title`, `theme_style`, `default_cat_owner`, `intro_message`, `project_is_active`, `visible_columns`, `others_view`, `anon_open`, `notify_email`, `notify_jabber`, `notify_reply`, `notify_types`, `feed_img_url`, `feed_description`, `notify_subject`, `lang_code`, `comment_closed`, `auto_assign`, `last_updated`, `default_task`, `default_entry`) VALUES
(3, 'hebergement', 'Bluey', 0, 'Gestion de l''hébergement', 1, 'id project category tasktype severity summary status progress', 0, 0, 'webmaster@grummfy.com', '', 'dev@grummfy.com', '0', '', '', '', 'fr', 0, 0, 1282394495, '', 'index'),
(2, 'mde-jdr.com - v3', 'Bluey', 0, '', 1, 'id project category tasktype severity summary status progress', 0, 0, 'grummfy@gmail.com', 'bot@mde-jdr.com', 'webmaster@grummfy.com', '1 3 4 7 10', '', '', '[%p]%s - %t : %a', 'fr', 1, 0, 1286222312, '', 'index'),
(4, 'ttlm', 'Bluey', 0, 'ttlm', 1, 'id project category tasktype severity summary status progress', 0, 0, '', '', '', '0', '', '', '', 'fr', 0, 0, 1285618194, '', 'index');
		 * 
		 */
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_project = new PP_Users($this->_DB);
			$pp_project->setOldId($row['project_id']);
			
			// set values for each fields
			$pp_project->setName($row['project_title']);
			// $pp_project->setPriority();
			$pp_project->setDescription($row['intro_message']);
			$pp_project->setCreated_on(time2SqlDateTime($row['last_updated']));
			$pp_project->setCreated_by_id($this->_config['projectpier']['default_user_id']);
			$pp_project->setUpdated_on(time2SqlDateTime($row['last_updated']));
			$pp_project->setUpdated_by_id($this->_config['projectpier']['default_user_id']);

/*
  `default_cat_owner` int(3) NOT NULL DEFAULT '0',
  `project_is_active` int(1) NOT NULL DEFAULT '0',
  `visible_columns` varchar(255) NOT NULL,
  `others_view` int(1) NOT NULL DEFAULT '0',
  `anon_open` int(1) NOT NULL DEFAULT '0',
  `notify_email` text,
  `notify_jabber` text,
  `notify_reply` text,
  `notify_types` varchar(100) NOT NULL DEFAULT '0',
  `feed_img_url` text,
  `feed_description` text,
  `notify_subject` varchar(100) NOT NULL DEFAULT '',
  `lang_code` varchar(10) NOT NULL,
  `comment_closed` int(1) NOT NULL DEFAULT '0',
  `auto_assign` int(1) NOT NULL DEFAULT '0',
  `last_updated` int(11) NOT NULL DEFAULT '0',
  `default_task` text,
  `default_entry` varchar(8) NOT NULL DEFAULT 'index',
 */

			$pp_projects[ $row['project_id'] ] = $pp_project;
		}
		$stmt->closeCursor();
				
		// insert in db
		$this->_newIds = $pp_projects[ array_rand($pp_projects) ]->writes2DB($pp_projects);
		
		// clean memory
		unset($pp_projects);
	}
	
	public function getNewIds()
	{
		return $this->_newIds;
	}
}

# EOF
