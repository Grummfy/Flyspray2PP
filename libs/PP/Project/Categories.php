<?php

class PP_Project_Categories extends AbstractIPP
{
	/**
	 * @var DB database link
	 */
	private $_DB;
	
	protected $_fields = array(
		'project_id'	=> self::TYPE_INT,
		'name'			=> self::TYPE_STRING,
		'description'	=> self::TYPE_STRING
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
		return $this->getDB()->getDestinationPrefix() . 'project_categories';
	}

	public function getDB()
	{
		return $this->_DB; 
	}
}

# EOF
