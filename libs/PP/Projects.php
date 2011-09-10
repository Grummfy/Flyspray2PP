<?php

class PP_Projects extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'name'			=> self::TYPE_STRING,
		'priority'		=> self::TYPE_INT,
		'description'	=> self::TYPE_STRING,
		'show_description_in_overview'	=> self::TYPE_INT,
		'logo_file'			=> self::TYPE_STRING,
		'completed_on'		=> self::TYPE_STRING,
		'completed_by_id'	=> self::TYPE_INT,
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
		return $this->getDB()->getDestinationPrefix() . 'projects';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
