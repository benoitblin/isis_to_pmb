<?php

namespace Migration\Business;

/**
 * @author benoit.blin@gmail.com
 */
class Notice
{

	/* Authors */

	protected $authors = array();
	protected $subAuthors = array();
	protected $translators = array();
	protected $prefaciers = array();
	protected $directors = array();
	
	/* Titles */
	
	protected $title;
	protected $subTitle;
	protected $originalTitle;
		
	/* Editor */
	
	protected $editor;
	
	/* Collection */
	
	protected $collection;
	
	/* Notes */
	
	protected $infos;
	protected $resume;
	protected $notes;
	
	/* Collation */
	
	protected $numPage;
	protected $format;
	
	/* Langues */
	
	protected $langs = array();
	protected $originalLangs = array();
	
	/* Identifiers */
	
	protected $cote;
	protected $nature;
	protected $isbn;
	
	/* Exemplaires */
	
	protected $exemplaires = array();
	
	/* Content */
	
	protected $descriptors = array();
	protected $properNames = array();
	protected $instiutionNames = array();
	protected $geographicNames = array();
	protected $pericopes = array();
	protected $biblicalBooks = array();
	
	/* Serie */
	
	protected $serie;
	protected $tome;
	protected $tomeLibDate;
	protected $serieIssn;
	protected $firstPublication;

	/* Input data */
	
	protected $inputDate;
	protected $inputAuthor;
	
	public function __construct()
	{
		$this->isbn = new Isbn();
	}
	
	public function set($name, $value) {
		
		$this->$name = $value;
		return $this;
		
	}
	
	public function get($name) {
		
		return $this->$name;
		
	}
		
	public function addAuthor(Author $author)
	{
		$this->authors[] = $author;
		return $this;
	}
		
	public function addSubAuthor(Author $author)
	{
		$this->subAuthors[] = $author;
		return $this;
	}
	
	public function addTranslator(Author $translator)
	{
		$this->translators[] = $translator;
		return $this;
	}
	
	public function addPrefacier(Author $prefacier)
	{
		$this->prefaciers[] = $prefacier;
		return $this;
	}
	
	public function addDirector(Author $director)
	{
		$this->directors[] = $director;
		return $this;
	}
	
	public function addLangue($code)
	{
		$this->langs[] = $code;
		return $this;
	}
	
	public function hasLangue($code)
	{
		return in_array($code, $this->langs);
	}
	
	public function addOriginalLangue($code)
	{
		$this->originalLangs[] = $code;
		return $this;
	}
	
	public function addPericope($pericope)
	{
		$this->pericopes[] = $pericope;
		return $this;
	}

	public function addProperName($properName)
	{
		$this->properNames[] = $properName;
		return $this;
	}
	
	public function addInstitutionName($instiutionName)
	{
		$this->instiutionNames[] = $instiutionName;
		return $this;
	}
	
	public function addGeographicName($geographicName)
	{
		$this->geographicNames[] = $geographicName;
		return $this;
	}
	
	public function addBiblicalBook($biblicalBook)
	{
		$this->biblicalBooks[] = $biblicalBook;
		return $this;
	}
	
	public function addDescriptor($descriptor)
	{
		$this->descriptors[] = $descriptor;
		return $this;
	}
	
	public function addExemplaire(Exemplaire $exemplaire)
	{
		$this->exemplaires[] = $exemplaire;
		return $this;
	}
	
	public function setDetails($details)
	{
		$notes = trim(str_replace("Detail", "", $details));
		$this->notes .= $notes;
		return $this;
	}
	
	public function setNotes($notes)
	{
		$this->notes .= $notes;
		return $this;
	}
	
	public function setIsbn($isbn)
	{
		$this->isbn = new Isbn($isbn);
		return $this;
	}
	
}
