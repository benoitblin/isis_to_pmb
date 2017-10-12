<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
class Editor
{
	protected $name;
	protected $city;
	protected $year;
	protected $num;
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setCity($city) {
		$this->city = $city;
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function setYear($year) {
		$this->year = $year;
	}
	
	public function getYear() {
		return $this->year;
	}
	
	public function setNum($num) {
		$this->num = $num;
	}
	
	public function getNum() {
		return $this->num;
	}
	
}
