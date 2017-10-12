<?php

namespace Migration\IsisOrm;

/**
 * Factory pattern to build record from ISIS data
 * @author benoit.blin@gmail.com
 */
class RecordFactory
{
	
    /**
     * Create a Record and populate it
     * @param array $raw : raw data from ISIS set
     * @return Record()
     */
	public function populate($raw) {
		
        $record = new Record();

		foreach($raw as $key => $field) {
			
			$value = array();
			foreach($field as $subField) {
				$value[] = $this->getTextValue($subField);
			}
			$record->set(values[intval($key)], $value);
			
		}
        
        return $record;
		
	}

    /**
     * Explore a field until to find values
     * @param mixed $field
     * @return string
     */
	protected function getTextValue($field) {
		
		if(is_array($field)) {
			
			$values = array();
			foreach($field as $key => $value) {
				$values[$key] = $this->getTextValue($value);
			}
			return $values;
			
		}

		//return trim(utf8_decode($field));
        return trim($field);

	}

}
