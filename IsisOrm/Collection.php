<?php

namespace Migration\IsisOrm;

/**
 * Collection of ISIS record
 * @author benoit.blin@gmail.com
 */
class Collection implements \Iterator
{

	protected $records = array();
	
	public function add(Record $record)
	{
		$this->records[] = $record;
	}
	
	public function rewind()
	{
		reset($this->records);
	}
	
	public function current()
	{
		return current($this->records);
	}

	public function key()
	{
		return key($this->records);
	}
	
	public function next()
	{
		return next($this->records);
	}
	
	public function valid()
	{
		$key = $this->key();
		return ($key !== NULL && $key !== FALSE);
	}
	
	public function count()
	{
		return count($this->records);
	}

}
