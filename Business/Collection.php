<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
class Collection
{
	protected $name;
	protected $subname;
	protected $num;
	protected $issn;
	
	public function __construct() {
		$this->issn = new Issn();
		
	}
	
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getName() {
		return $this->name;
	}
	
	public function setSubName($subname) {
		$this->subname = $subname;
		return $this;
	}

	public function getSubName() {
		return $this->subname;
	}
	
	public function setNum($num) {
		$this->num = $num;
		return $this;
	}
	
	public function getNum() {
		return $this->num;
	}
	
	public function setIssn($issn) {
		$this->issn = new Issn($issn);
		return $this;
	}
	
	public function getIssn() {
		return $this->issn;
	}
	
}
