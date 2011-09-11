<?php

class PP_Comments extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'rel_object_id'			=> self::TYPE_INT,
		'rel_object_manager'	=> self::TYPE_STRING,
		'text'			=> self::TYPE_STRING,
		'is_private'	=> self::TYPE_INT,
		'is_anonymous'	=> self::TYPE_INT,
		'author_name'	=> self::TYPE_STRING,
		'author_email'	=> self::TYPE_STRING,
		'author_homepage'	=> self::TYPE_STRING,
		'created_on'		=> self::TYPE_STRING,
		'created_by_id'		=> self::TYPE_INT,
		'updated_on'		=> self::TYPE_STRING,
		'updated_by_id'		=> self::TYPE_INT
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
		return $this->getDB()->getDestinationPrefix() . 'comments';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
