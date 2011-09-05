<?php

interface IPP
{
	/**
	 * write one objects to database
	 * @param object $value object to write
	 * @return int id of new record
	 */
	public function write2DB(IPP $value);

	/**
	 * write multiple object to database
	 * @param array $values values of objects to write
	 * @return array id of new record, [id_values => new id]
	 */
	public function writes2DB(array $values);

	/**
	 * Convert the current object to an array with key of db field
	 */
	public function toArray();
	
	public function getOldId();
	
	public function setOldId($id);
}

# EOF
