<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
class Revue
{
	protected $id;
	protected $titre;
	protected $editeur;
	protected $langues = array();
	protected $langues_originales = array();
	protected $issn;
	
	public function __construct() {
		$this->editeur = new Editor();
		$this->issn = new Issn();
	}
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setTitre($titre) {
		$this->titre = $titre;
		return $this;
	}
	
	public function getTitre() {
		return $this->titre;
	}
	
	public function setEditeur(Editor $editeur) {
		$this->editeur = $editeur;
		return $this;
	}
	
	public function getEditeur() {
		return $this->editeur;
	}
	
	public function addLangue($langue) {
		$this->langues[] = $langue;
		return $this;
	}
	
	public function getLangues() {
		return $this->langues;
	}
	
	public function addLangueOriginale($langue_originale) {
		$this->langues_originales[] = $langue_originale;
		return $this;
	}
	
	public function getLanguesOriginales() {
		return $this->langues_originales;
	}
	
	public function setIssn(Issn $issn) {
		$this->issn = $issn;
		return $this;
	}
	
	public function getIssn() {
		return $this->issn;
	}
	
}
