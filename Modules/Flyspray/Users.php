<?php

// import users
require_once _X2PP_ROOT . '/libs/PP/Users.php';

class Modules_Flyspray_Users extends AbstractModules
{
	public function __construct(DB $DB, $config)
	{
		$this->setConfig($config);
		$this->setDB($DB);
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
		$query = 'SELECT * FROM ' . $this->getDB()->getSourcePrefix() . 'users';
		$stmt = $this->getDB()->getSource()->query($query);

		// create array of PP_Users
		$pp_users = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pp_user = new PP_Users($this->getDB());
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
		$this->setNewIds($pp_users[ array_rand($pp_users) ]->writes2DB($pp_users));
		
		// clean memory
		unset($pp_users);
	}
}

# EOF
