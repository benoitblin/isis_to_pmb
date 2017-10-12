<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
class RevueNotice extends Notice
{
	
	protected $id;	
	protected $revue;
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setRevue(Revue $revue) {
		$this->revue = $revue;
		return $this;
	}
	
	public function getRevue() {
		return $this->revue;
	}
	
}
