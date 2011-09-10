<?php

// import users
require_once _X2PP_ROOT . '/libs/PP/Users.php';

class Modules_Flyspray_Users implements IModules
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	private $_newIds = array();
	
	/**
	 * @param DB $DB database link
	 */
	public function __construct(DB $DB, $config)
	{
		$this->_DB = $DB;
	}

	/**
	 * generate a password for user
	 */
	public function generatePassword()
	{
		// see pp086/application/UserController.class.php:86
		return substr(sha1(uniqid(rand(), true)), rand(0, 25), 13);
	}

	public function convert()
	{
		// get data from database
		$query = 'SELECT * FROM ' . $this->_DB->getSourcePrefix() . 'users';
		$stmt = $this->_DB->getSource()->query($query);

		// create array of PP_Users
		$pp_users = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_user = new PP_Users($this->_DB);
			$pp_user->setOldId($row['user_id']);
			// set values for each fields
			$pp_user->setUsername($row['user_name']);
			$pp_user->setEmail($row['email_address']);
			// $pp_user->setToken();
			// $pp_user->setSalt();
			// $pp_user->setTwister();
			$pp_user->setDisplay_name($row['real_name']);
			//$pp_user->setHomepage();
			//$pp_user->setTitle();
			//$pp_user->setAvatar_file();
			//$pp_user->setUse_gravatar();
			$pp_user->setTimezone($row['time_zone']);
			$pp_user->setCreated_on(time2SqlDateTime($row['register_date']));
			$pp_user->setUpdated_on(time2SqlDateTime(time()));
			//$pp_user->setLast_login();
			//$pp_user->setLast_visit();
			//$pp_user->setLast_activity();
			//$pp_user->setIs_admin();
			//$pp_user->setAuto_assign();

			$pp_users[ $row['user_id'] ] = $pp_user;
		}
		$stmt->closeCursor();
				
		// insert in db
		$this->_newIds = $pp_users[ array_rand($pp_users) ]->writes2DB($pp_users);
		
		// clean memory
		unset($pp_users);
	}
	
	public function getNewIds()
	{
		return $this->_newIds;
	}
}

# EOF
