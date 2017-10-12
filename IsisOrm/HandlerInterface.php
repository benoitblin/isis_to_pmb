<?php

namespace Migration\IsisOrm;

/**
 * Interface for an adapter pattern on ISIS extractor
 * @author benoit.blin@gmail.com
 */
interface HandlerInterface
{
	
    /**
     * Extract data from ISIS and populate a Collection object with data from $start to $end
     * @param $root path to folder containing ISIS data files
     * @param $start cursor of first element to return
     * @param $end cursor of last element to return
     * @return Collection()
     */
	public function extract($root, $start = 0, $end = null);
	
}
