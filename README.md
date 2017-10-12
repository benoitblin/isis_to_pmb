# ISIS 2 PMB
Extract data from ISIS database and create XML files to import in PMB

Requirements : carlossosa/ISISDatabaseExtract

How to use :

```php
// Required to import huge files
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');

// Activate autoloading
include 'Autoload.php';
$autoloader = new \Migration\Autoload();
$autoloader->activate();

// Extract fields from ISIS databases
$orm = new \Migration\IsisOrm\Handler();
$data = $orm->extract(dirname(__FILE__) . '/ISIS DATA FILES');
unset($orm);

// Convert to Notices
$conv = new \Migration\Converter\IsisConverter();
$notices = $conv->getNotices($data);
unset($data);
unset($conv);

// Converter to XML
$xmlConv = new \Migration\Converter\XmlConverter();

foreach($notices->split(1000) as $key => $subNotices) {
	$xmlConv->getXml($subNotices)->save(dirname(__FILE__) . '/export/import' . $key . '.fic');
}

echo "Succès : " . ($key + 1) . " fichiers crées";
```
