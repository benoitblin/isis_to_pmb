<?php

namespace Migration\IsisOrm;

use ISIS\Database\Extract as Extract;

/**
 * Adapter pattern to ISIS-extract vendor
 * @author benoit.blin@gmail.com
 */
class Handler implements HandlerInterface
{
	
    protected $recordFactory;

    public function __construct() {

        $this->recordFactory = new RecordFactory();

    }

    /**
     * {@inheritdoc}
     */
	public function extract($root, $start = 0, $end = null)	{

        // Recover all data from ISIS		
		$data = new Extract($root);

		$collection = new Collection();
		
		if($end == null) $end = count($data);
		$i = -1;
		
		foreach($data as $raw) {
										
			$i++;
			if($i < $start) {
				continue;
			} elseif($i >= $end) {
				break;
			}
			
            // Eliminate empty set
			if(!is_array($raw)) continue;
            if(!array_key_exists("024", $raw) || $raw["024"] == "") continue;

            // Build a record from raw
			$record = $this->recordFactory->populate($raw);
			$collection->add($record);
			
		}

		return $collection;
	}
	
}
