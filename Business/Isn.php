<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
abstract class Isn
{
	
	protected $num;
	
	public function __construct($num = null) {
		$this->num = $num;
	}

	public function __toString()
	{
		return $this->getNum();
	}
	
	public function getNum()
	{
		return $this->num;
	}

}
