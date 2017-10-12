<?php

namespace Migration\Business;

/**
 * @param benoit.blin@gmail.com
 */
class Author
{
	
	protected $id;

	protected $lastName = "";
	protected $firstName = "";

	/**
	 * Set last name and first name from a full name
	 * The rule is : a last name is fully uppercased, a first name not
 	 * string $fullNameRaw
 	 */
	public function setNames($fullNameRaw) {

		$firstNames = array();		
		$lastNames = array();

		$words = explode(" ", $fullNameRaw);
		foreach($words as $word) {
			if($word == strtoupper($word)) {
				$lastNames[] = $word;
			} else {
				$firstNames[] = $word;
			}
		}
		
		$this->lastName = implode(" ", $lastNames);
		$this->firstName = implode(" ", $firstNames);

	}

	public function setLastName($lastName) {

		$this->lastName = $lastName;

	}

	public function setFirstName($firstName) {

		$this->firstName = $firstName;

	}

	public function getLastName() {

		return $this->lastName;

	}

	public function getFirstName() {

		return $this->firstName;

	}

	public function setId($id) {
		
		$this->id = $id;

	}

	public function getId() {

		return $this->id;

	}

}
