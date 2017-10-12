<?php

namespace Migration\Converter;

/**
 * Create a new XML node like :
<f c="200">
    <s c="a">Titre</s>
</f>
 * @author benoit.blin@gmail.com
 */
class BlocNode
{
	// value of "c" attribute
	protected $code;
    // subElements
	protected $fields = array();
	
	public function __construct($code)
	{
		$this->code = $code;
	}
	
	public function addField($code, $value = "")
	{
		if($value == "") {
			return $this;
		}
		$this->fields[] = array($code, $value);
		return $this;
	}
	
    /**
     * Attach this bloc node on the parent element
     * @param \DomDocument $xml XML document
     * @param \DomElement $node parent node
     * @param string $content extra content
     */
	public function append(\DomDocument $xml, \DomElement $node, $content = null)
	{
		if(count($this->fields) == 0 && $content == null) {
			return null;
		}
		
		$f = $xml->createElement("f", $content);
		$f->setAttribute("c", $this->code);
		foreach($this->fields as $field) {
			$s = $xml->createElement("s", htmlspecialchars($field[1]));
			$s->setAttribute("c", $field[0]);
			$f->appendChild($s);
		}
		$node->appendChild($f);
			
		return null;
	}

}
