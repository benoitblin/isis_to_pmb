<?php

namespace Migration\Converter;

use Migration\Business\Notice;
use Migration\Business\Notices;
use Migration\Business\Exemplaire;
use Migration\Business\Revue;
use Migration\Business\RevueNotice;

/**
 * Produce an XML tree from Notices Collection
 * @author benoit.blin@gmail.com
 */
class XmlConverter
{

	protected $revues = array();
	protected $idRevue = 1;
	
    /**
     * Increment id of Revue
     * @return int
     */
	protected function getNextIdRevue()
    {
		return $this->idRevue++;
	}

    /**
     * Is the current revue has already been created
     * @param Revue $revue
     * @return boolean
     */
	protected function isRevueCreated(Revue $revue)
    {
        // If yes, set the ID
		if(in_array($revue->getTitre(), $this->revues)) {
			$revue->setId(array_search($revue->getTitre(), $this->revues));
			return true;
		}
		
        // If no, create ID
		$id = $this->getNextIdRevue();
		$revue->setId($id);
		$this->revues[$id] = $revue->getTitre();
		return false;		
	}

    /**
     * Build XML tree from Collection
     * @param Notices $notices
     * @return \DOMDocument
     */
	public function getXml(Notices $notices) {
		
		$xml = new \DOMDocument("1.0", "UTF-8");
		$root = $xml->createElement("unimarc");
				
		foreach($notices as $notice) {
			
			if(get_class($notice) != 'Migration\Business\RevueNotice') {

				$node = $this->setNoticeHeader($xml);
				
			} else {
				
				$revue = $notice->getRevue();
				
				if(!$this->isRevueCreated($revue)) {
					
					$node = $this->setRevueNode($xml, $revue);
					
					$root->appendChild($node);
					
				}
				
				$node = $this->setRevueHeader($xml);
				
				$bloc = new BlocNode("001");
				$bloc->append($xml, $node, $notice->getId());
					
				$bloc = new BlocNode("530");
				$bloc
					->addField("a", $revue->getTitre())
					->append($xml, $node);
					
				$bloc = new BlocNode("461");
				$bloc
					->addField("0", $revue->getId())
					->addField("t", $revue->getTitre())
					->append($xml, $node);
					
				$bloc = new BlocNode("210");
				$bloc
					->addField("h", $notice->get('firstPublication'))
					->addField("d", $notice->get('tomeLibDate'))
					->append($xml, $node);
					
			}
			
			$bloc = new BlocNode("10");
			$bloc
				->addField("a", $notice->get('isbn')->getNum())
				->append($xml, $node);
						
			$bloc = new BlocNode("101");
			foreach($notice->get('langs') as $lang) {
				$bloc->addField("a", $lang);
			}
			foreach($notice->get('originalLangs') as $lang) {
				$bloc->addField("c", $lang);
			}
			$bloc->append($xml, $node);
			
			$bloc = new BlocNode("200");
			$bloc
				->addField("e", $notice->get('subTitle'))
				->addField("d", $notice->get('originalTitle'))
				->addField("h", $notice->get('tome'));
			
			if(isset($revue)) {
				$bloc
					->addField("i", $notice->get('title'))
					->addField("a", $notice->get('tome') . ' - ' . $notice->get('firstPublication') . ' - ' . $notice->get('tomeLibDate') . ' - ' . $notice->get('title'));
			} else {
				$bloc->addField("a", $notice->get('title'));
			}
			$bloc->append($xml, $node);
				
			
			$bloc = new BlocNode("215");
			$bloc
				->addField("a", $notice->get('numPage'))
				->addField("d", $notice->get('format'))
				->append($xml, $node);

			foreach($notice->get('authors') as $key => $author) {
				if($key == 0) $code = "700";
				else $code = "701";
				$bloc = new BlocNode($code);
				$bloc
					->addField("a", $author->getLastName())
					->addField("b", $author->getFirstName())
					->addField("4", "070")
					->append($xml, $node);
			}

			$subAuthors = array(
				"070" => 'subAuthors',
				"080" => 'prefaciers',
				"651" => 'directors',
				"730" => 'translators'
			);
	
			foreach($subAuthors as $code => $name) {
				foreach($notice->get($name) as $author) {
				$bloc = new BlocNode("702");
				$bloc
					->addField("a", $author->getLastName())
					->addField("b", $author->getFirstName())
					->addField("4", $code)
					->append($xml, $node);
				}
			}
			
			$bloc = new BlocNode("210");
			$bloc
				->addField("a", $notice->get('editor')->getCity())
				->addField("c", $notice->get('editor')->getName())
				->addField("d", $notice->get('editor')->getYear())
				->append($xml, $node);
			
			$bloc = new BlocNode("205");
			$bloc
				->addField("a", $notice->get('editor')->getNum())
				->append($xml, $node);

			$bloc = new BlocNode("300");
			$bloc
				->addField("a", $notice->get('infos'))
				->append($xml, $node);
					
			$bloc = new BlocNode("327");
			$bloc
				->addField("a", $notice->get('notes'))
				->append($xml, $node);
				
			$bloc = new BlocNode("330");
			$bloc
				->addField("a", $notice->get('resume'))
				->append($xml, $node);
						
			$customed = array(
				'pericopes' => array("Péricope", "pericope", "text"),
				'properNames' => array("Nom propre", "nom_propre", "text"),
				'instiutionNames' => array("Nom d'institution", "nom_institution", "text"),
				'geographicNames' => array("Nom géographique", "nom_geographique", "list"),
				'biblicalBooks' => array("Livre biblique", "livre_biblique", "list")
			);
			
			foreach($customed as $code => $fields) {
				foreach($notice->get($code) as $value) {
					$bloc = new BlocNode("900");
					$bloc
						->addField("a", $value)
						->addField("l", $fields[0])
						->addField("n", $fields[1])
						->addField("t", $fields[2])
						->append($xml, $node);
				}
			}
			
			foreach($notice->get('descriptors') as $descriptor) {
				$bloc = new BlocNode("606");
				$bloc
					->addField("a", $descriptor)
					->append($xml, $node);
			}
			
			$bloc = new BlocNode("676");
			$bloc
				->addField("a", $notice->get('cote'))
				->append($xml, $node);
			
			$bloc = new BlocNode("225");
			$bloc
				->addField("a", $notice->get('collection')->getName())
				->addField("i", $notice->get('collection')->getSubName())
				->addField("x", $notice->get('collection')->getIssn()->getNum())
				->addField("v", $notice->get('collection')->getNum())
				->append($xml, $node);
			
			$root->appendChild($node);
			
			foreach($notice->get('exemplaires') as $exemplaire) {
				
				$this
					->setBlocExemplaire($exemplaire)
					->append($xml, $node);
				
			}
			
			unset($revue);
			
		}
		
		$xml->appendChild($root);
		
		return $xml;	
	}
	
	/**
     * Build specifics nodes for Revue
     * @param \DomDocument $xml
     * @param Revue $revue
	 * @return \DomElement
	 */
	protected function setRevueNode(\DomDocument $xml, Revue $revue)
    {		
		$node = $this->setRevueCollectionHeader($xml);
					
		$bloc = new BlocNode("001");
		$bloc->append($xml, $node, $revue->getId());
		
		$bloc = new BlocNode("200");
		$bloc
			->addField("a", $revue->getTitre())
			->append($xml, $node);
		
		$bloc = new BlocNode("210");
		$bloc
			->addField("a", $revue->getEditeur()->getCity())
			->addField("c", $revue->getEditeur()->getName())
			->append($xml, $node);
			
		$bloc = new BlocNode("225");
		$bloc
			->addField("x", $revue->getIssn()->getNum())
			->append($xml, $node);

		$bloc = new BlocNode("101");
		foreach($revue->getLangues() as $lang) {
			$bloc->addField("a", $lang);
		}
		foreach($revue->getLanguesOriginales() as $lang) {
			$bloc->addField("c", $lang);
		}
		$bloc->append($xml, $node);
		
		return $node;
	}
	
    /**
     * Build specifics nodes for Exemplaire
     * @param Exemplaire $exemplaire
	 * @return BlocNode
     */
	protected function setBlocExemplaire(Exemplaire $exemplaire)
    {		
		$code_support = "az";
		if($exemplaire->getSupport() == "Revue") $code_support = "aa";
					
		$bloc = new BlocNode("996");
		$bloc
			->addField("9", "expl_cb:".$exemplaire->getId())
			->addField("9", "expl_cote:".$exemplaire->getCote())
			->addField("9", "statut_libelle:Empruntable")
			->addField("9", "statusdoc_codage_import:e")
			->addField("9", "tdoc_libelle:".$exemplaire->getSupport())
			->addField("9", "tdoc_codage_import:".$code_support)
			->addField("9", "section_libelle:Magasin")
			->addField("9", "sdoc_codage_import:m")
			->addField("9", "lender_libelle:Fonds propre")
			->addField("9", "codestat_libelle:exemplaire")
			->addField("9", "statisdoc_codage_import:ex")
			->addField("9", "pret_flag:1")
			->addField("9", "localisation_libelle:Le Perreux sur Marne")
			->addField("9", "locdoc_codage_import:lpsm");
		
		return $bloc;
		
	}

    /**
     * Set specifics fields for Notice header
     * @param \DomDocument $xml
     * @return \DomElement
     */
	protected function setNoticeHeader(\DomDocument $xml)
	{
		$node = $xml->createElement("notice");
		
		$rs = $xml->createElement("rs", "n");
		$node->appendChild($rs);
		$dt = $xml->createElement("dt", "a");
		$node->appendChild($dt);
		$bl = $xml->createElement("bl", "m");
		$node->appendChild($bl);
		$hl = $xml->createElement("hl", "0");
		$node->appendChild($hl);
		$el = $xml->createElement("el", "1");
		$node->appendChild($el);
		$ru = $xml->createElement("rs", "i");
		$node->appendChild($ru);
		
		return $node;
	}
	
    /**
     * Set specifics fields for Revue Collection header
     * @param \DomDocument $xml
     * @return \DomElement
     */
	protected function setRevueCollectionHeader(\DomDocument $xml)
	{
		$node = $xml->createElement("notice");
		
		$rs = $xml->createElement("rs", "n");
		$node->appendChild($rs);
		$dt = $xml->createElement("dt", "a");
		$node->appendChild($dt);
		$bl = $xml->createElement("bl", "s");
		$node->appendChild($bl);
		$hl = $xml->createElement("hl", "1");
		$node->appendChild($hl);
		$el = $xml->createElement("el", "1");
		$node->appendChild($el);
		$ru = $xml->createElement("rs", "i");
		$node->appendChild($ru);
		
		return $node;
	}


    /**
     * Set specifics fields for Revue header
     * @param \DomDocument $xml
     * @return \DomElement
     */		
	protected function setRevueHeader(\DomDocument $xml)
	{
		$node = $xml->createElement("notice");
		
		$rs = $xml->createElement("rs", "n");
		$node->appendChild($rs);
		$dt = $xml->createElement("dt", "a");
		$node->appendChild($dt);
		$bl = $xml->createElement("bl", "s");
		$node->appendChild($bl);
		$hl = $xml->createElement("hl", "2");
		$node->appendChild($hl);
		$el = $xml->createElement("el", "1");
		$node->appendChild($el);
		$ru = $xml->createElement("rs", "i");
		$node->appendChild($ru);
		
		return $node;
	}
	
}
