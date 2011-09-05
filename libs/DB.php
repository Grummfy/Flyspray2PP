<?php

class DB
{
	/**
	 * source db
	 */
	private $_in;

	private $_in_prefix = '';

	/**
	 * destination db
	 */
	private $_out;
	
	private $_out_prefix = '';

	public function __construct($in_dsn, $in_user, $in_password, $in_prefix, $out_dsn, $out_user, $out_password, $out_prefix)
	{
		try
		{
			$this->_in = new PDO($in_dsn, $in_user, $in_password);
		}
		catch (PDOException $e)
		{
			throw new ErrorException('Error while triing to connect to source database : ' . $e->getMessage());
		}

		try
		{
			$this->_out = new PDO($out_dsn, $out_user, $out_password);
		}
		catch (PDOException $e)
		{
			throw new ErrorException('Error while triing to connect to destiantion database : ' . $e->getMessage());
		}

		$this->_in_prefix = $in_prefix;
		$this->_out_prefix = $out_prefix;
	}

	/**
	 * @return the source PDO link
	 */
	public function getSource()
	{
		return $this->_in;
	}
	
	public function getSourcePrefix()
	{
		return $this->_in_prefix;
	}

	/**
	 * @return the destiantion PDO link
	 */
	public function getDestination()
	{
		return $this->_out;
	}
	
	public function getDestinationPrefix()
	{
		return $this->_out_prefix;
	}

	/**
	 * Write an array of array or simple array to database
	 * @param string table the name of the table
	 * @param array key = field name, value = value
	 * @param bool multiple true ssi array contains array of stuff to set in db
	 * @return array new id
	 */
	public function arrays2DB($table, array $values, $multiple = false)
	{
		if (!$multiple)
		{
			$values = array($values);
		}

		$keys = array_keys($values[ 0 ]);
		$query = 'INSERT INTO ' . $table . ' (' . implode(',', $keys) . ') VALUES (' . implode(',', array_fill(0, count($keys), '?')) . ')';

		$this->_out->beginTransaction();
		$stmt = $this->_out->prepare($query);

		$newIds = array();

		try
		{
			foreach($values as $old_id => $value)
			{
				$stmt->execute($value);
				$newIds[ $old_id ] = $this->_out->lastInsertId();
			}
		}
		catch (PDOException $e)
		{
			$this->_out->rollBack();
			throw new ErrorException('Impossible to insert data (change have been rollbacked) : ' . $e->getMessage());
		}
		$this->_out->commit();
		
		return $newIds;
	}
}

# EOF
