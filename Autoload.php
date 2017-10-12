<?php

namespace Migration;

/**
 * Configure PHP autoloading
 * @author benoit.blin@gmail.com
 */
class Autoload
{

    /**
     * Register autoload implementation
     * @see http://php.net/manual/en/function.spl-autoload-register.php
     * @return boolean
     */
	public function activate()
	{
		return spl_autoload_register(array($this, 'autoload'));
	}
	
    /**
     * Include file related to class name
     * @param string full class name
     */
	protected function autoload($class)
	{
		$path = false;
		$tabPath = explode("\\", $class);
        // Local class (namespace start with \Migration)
		if($tabPath[0] == 'Migration') {
            $path = $this->getLocalNamespace($tabPath);
		}
        // Vendor class
		if($path == false) {
			$path = $this->getVendorNamespace($tabPath);
		}
		if($path == false) {
			throw new \Exception ("Unable to load " . $class);
		}
		require_once $path;
	}
	
    /**
     * Build path from a local namespace
     * @param array of namespace elements
     * @return string path
     * @throw \Exception file doesn't exist
     */
	protected function getLocalNamespace($tabPath)
	{
        // remove "\Migration" element of namespace
        $relativePath = implode("/", array_slice($tabPath, 1));
		$absolutePath = dirname(__FILE__) . '/' . $path . '.php';	
		if(file_exists($absolutePath)) {
			return $absolutePath;
		}
		throw new \Exception ("File : " . $absolutePath . " doesn't exist");
	}
	
    /**
     * Build path from a vendor namespace
     * @param array of namespace elements
     * @return string path
     * @throw \Exception file doesn't exist
     */
	protected function getVendorNamespace($tabPath)
	{
        $relativePath = implode("/", $tabPath);
		$base = dirname(__FILE__) . '/vendor';
        // Search file in each folder of /vendor 
		foreach(scandir($base) as $folder) {
			if(is_dir($base . '/' . $folder)) {
				$absolutePath = $base . '/' . $folder . '/' . $path . '.php';
				if(file_exists($absolutePath)) {
					return $absolutePath;
				}
			}
		}
		throw new \Exception ("File : " . $absolutePath . " doesn't exist in /vendor");
	}

}
