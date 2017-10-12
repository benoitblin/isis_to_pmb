<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
class Notices implements \Iterator
{

	protected $notices = array();
	
	public function add(Notice $notice)
	{
		$this->notices[] = $notice;
	}
	
	public function set($notices)
	{
		$this->notices = $notices;
	}
	
    /**
     * Split collection between subCollections
     * @param int $step : size of a subCollection
     * @return Notices()
     */
	public function split($step)
	{
		$group_of_notices = array();
		$groups = array_chunk($this->notices, $step);
		foreach($groups as $group) {
			$n = new Notices();
			$n->set($group);
			$group_of_notices[] = $n;
		}
		return $group_of_notices;
	}
	
	public function rewind()
	{
		reset($this->notices);
	}
	
	public function current()
	{
		return current($this->notices);
	}

	public function key()
	{
		return key($this->notices);
	}
	
	public function next()
	{
		return next($this->notices);
	}
	
	public function valid()
	{
		$key = $this->key();
		return ($key !== NULL && $key !== FALSE);
	}
	
	public function count()
	{
		return count($this->notices);
	}

}
