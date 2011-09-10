<?php

class PP_Project_Messages extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
	  	'milestone_id'	=> self::TYPE_INT,
	  	'project_id'	=> self::TYPE_INT,
	  	'title'			=> self::TYPE_STRING,
	  	'text'			=> self::TYPE_STRING,
	  	'additional_text'	=> self::TYPE_STRING,
	  	'is_important'		=> self::TYPE_INT,
	  	'is_private'		=> self::TYPE_INT,
	  	'comments_enabled'	=> self::TYPE_INT,
	  	'anonymous_comments_enabled'	=> self::TYPE_INT,
	  	'created_on'	=> self::TYPE_STRING,
	  	'created_by_id'	=> self::TYPE_STRING,
	  	'updated_on'	=> self::TYPE_STRING,
	  	'updated_by_id'	=> self::TYPE_STRING
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
		return $this->getDB()->getDestinationPrefix() . 'project_messages';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
