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
	public function __construct(DB $DB)
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
			
			
			/*
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  
  
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_visit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			*/
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

/*


CREATE TABLE IF NOT EXISTS `flyspray_users` (
  `user_id` int(3) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) NOT NULL,
  `user_pass` varchar(40) DEFAULT NULL,
  `real_name` varchar(100) NOT NULL,
  `jabber_id` varchar(100) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `notify_type` int(1) NOT NULL DEFAULT '0',
  `notify_own` int(6) NOT NULL DEFAULT '0',
  `account_enabled` int(1) NOT NULL DEFAULT '0',
  `dateformat` varchar(30) NOT NULL DEFAULT '',
  `dateformat_extended` varchar(30) NOT NULL DEFAULT '',
  `magic_url` varchar(40) NOT NULL DEFAULT '',
  `tasks_perpage` int(3) NOT NULL DEFAULT '0',
  `register_date` int(11) NOT NULL DEFAULT '0',
  `time_zone` int(6) NOT NULL DEFAULT '0',
  `login_attempts` int(11) NOT NULL DEFAULT '0',
  `lock_until` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `flyspray_user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `flyspray_users`
--

INSERT INTO `flyspray_users` (`user_id`, `user_name`, `user_pass`, `real_name`, `jabber_id`, `email_address`, `notify_type`, `notify_own`, `account_enabled`, `dateformat`, `dateformat_extended`, `magic_url`, `tasks_perpage`, `register_date`, `time_zone`, `login_attempts`, `lock_until`) VALUES
(1, 'Grummfy', '849e5d71d1d706398e48865371cfcaa7', 'Mr Super User', 'grummfy@im.apinc.org', 'grummfy@gmail.com', 3, 1, 1, '', 'Grummfy', '', 25, 0, 1, 0, 0),
(2, 'youri', 'fce4363132004bdf0634f873ea42de71', 'Youri', '', 'mde.root@gmail.com', 3, 0, 1, '', '', '', 25, 1281136991, 1, 0, 0),
(3, 'Robi', '7702d2a2cec339fe31095d434d7532a9', 'Robinet Sébastien', '', 'mde@itrob.be', 1, 0, 1, '', 'Grummfy', '', 25, 1299523903, 1, 0, 0);


 */

# EOF
