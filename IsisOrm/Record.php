<?php

namespace Migration\IsisOrm;

/**
 * Record from ISIS data
 * @author benoit.blin@gmail.com
 */
class Record
{

	protected $values = array();

    /**
     * Get values related to an ISIS code
     * @param string $isisCodeField
     * @return array
     */
	public function get($isisCodeField) {
		
		if(!array_key_exists($isisCodeField, $this->values)) {
			return array();
		}
		return $this->values[$isisCodeField];
		
	}
	
    /**
     * Get first value related to an ISIS code (used on field known for having only one value
     * @param string $isisCodeField
     * @return string
     */
	public function getFirst($isisCodeField) {

		$value = $this->get($isisCodeField);
		if(array_key_exists(0, $value)) {
			return $value[0];
		}
		return "";

	}

    /**
     * Set a value by key
     * @param int $isisCodeField
     * @param array $value
     * @return Record()
     */
    public function set($isisCodeField, $value) {

        $this->values[$key] = $value;
        return $this;

    }

}
