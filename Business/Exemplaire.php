<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
class Exemplaire
{
	
	protected $id;
	protected $cote;
	protected $support;
	
	public function getId() {
		return $this->id;
	}
	
	public function getCote() {
		return $this->cote;
	}
	
	public function getSupport() {
		return $this->support;
	}
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function setCote($cote) {
		$this->cote = $cote;
		return $this;
	}
	
	public function setSupport($support) {
		$this->support = $support;
		return $this;
	}
	
}
