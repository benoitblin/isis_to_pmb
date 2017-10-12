<?php

namespace Migration\Converter;

use Migration\IsisOrm\Record;
use Migration\IsisOrm\RecordExtractor;
use Migration\IsisOrm\Collection as IsisOrmCollection;
use Migration\Business\Notice;
use Migration\Business\RevueNotice;
use Migration\Business\Notices;
use Migration\Business\Author;
use Migration\Business\Collection;
use Migration\Business\Issn;
use Migration\Business\Editor;
use Migration\Business\Exemplaire;

/**
 * Convert ISIS record to Notice
 * @author benoit.blin@gmail.com
 */
class IsisConverter
{
	
	protected $isisLang;
	protected $idExemplaire;
	protected $idRevueNotice = 1;
	
    /**
     * @param int $startIdExemplaire : value of the last notice ID in destination database
     */
	public function __construct($startIdExemplaire = 1)
	{	
		$this->isisLang = new IsisLangue();	
		$this->idExemplaire = $startIdExemplaire;
	}
	
    /**
     * Get next notice ID
     * @return int
     */
	protected function getNextIdExemplaire()
    {
		return $this->idExemplaire++;
	}
	
    /**
     * Get next revue ID
     * @return int
     */
	protected function getNextIdRevueNotice()
    {
		return $this->idRevueNotice++;
	}
	
    /**
     * Local adapter pattern to get language code from a key name
     * @param string $string
     * @return string
     */
	protected function getCodeLangue($string)
	{
		return $this->isisLang->getCodes($string);
	} 
	
    /**
     * Convert ISIS Record collection into Notice collection
     * @param IsisOrmCollection $coll
     * @return Notices()
     */
	public function getNotices(IsisOrmCollection $coll)
    {
		$notices = new Notices();
		foreach($coll as $record) {			
			$notices->add($this->getNotice($record));		
		}
		
		return $notices;
	}
		
    /**
     * Convert ISIS Record into Notice
     * @param Record $record
     * @return Notice()
     */
	protected function getNotice(Record $record)
    {	
		$extractor = new RecordExtractor($record);

		if($extractor->isRevue()) {
			
            // Set notice as revue and populate specific fields
			$notice = new RevueNotice();
			$revue = new Revue();
			$notice
				->setId($this->getNextIdRevueNotice())
				->setRevue($revue)
				->set('serie', $extractor->getSerie())
				->set('firstPublication', $extractor->getFirstPublication())
				->set('tomeLibDate', trim($extractor->getRevueMois() . " " . $extractor->getRevueAnnee()));

			$revue->setTitre($notice->get('serie'));
			$issn = new Issn($extractor->getRevueIssn());
			$revue->setIssn($issn);

		} else {
			
			$notice = new Notice();
			
		}
		
		// Titres
		$notice
			->set('title', $extractor->getMainTitle())
			->set('subTitle', $extractor->getSubTitle())
			->set('originalTitle', $extractor->getOriginalTitle())
			->set('infos', $extractor->getInfos())
			->setDetails($extractor->getDetails())
			->set('resume', $extractor->getResume())
			->setNotes($extractor->getNotes())
			->setNotes($extractor->getNotesBis())
			->set('numPage', $extractor->getNumberOfPages())
			->set('format', $extractor->getFormat())
			->setIsbn($extractor->getIsbn())
			->set('cote', $extractor->getCote())
			->set('nature', $extractor->getSupport())
			->set('tome', $extractor->getTome())
			->set('collection', $this->getCollection($extractor))
			->set('editor', $this->getEditor($extractor));
		
		if(isset($revue)) {
			$revue->setEditeur($notice->get('editor'));
		}

		// Responsabilités
		foreach($extractor->getAuthors() as $author) {
			$notice->addAuthor($this->getAuthor($author));
		}
		foreach($extractor->getSubAuthors() as $subAuthor) {
			$notice->addSubAuthor($this->getAuthor($subAuthor));
		}
		foreach($extractor->getPrefaciers() as $prefacier) {
			$notice->addPrefacier($this->getAuthor($prefacier));
		}
		foreach($extractor->getTraducteurs() as $translator) {
			$notice->addTranslator($this->getAuthor($translator));
		}
		foreach($extractor->getDirecteurs() as $director) {
			$notice->addDirector($this->getAuthor($director));
		}
		
		// Langues
		foreach($extractor->getLangues() as $langue_edition) {
			foreach($this->getCodeLangue($langue_edition) as $langue) {
				$notice->addLangue($langue);
				if(isset($revue)) $revue->addLangue($langue);
			}
		}
		foreach($extractor->getOriginalLangues() as $langue_original) {
			foreach($this->getCodeLangue($langue_original) as $langue) {
				$notice->addOriginalLangue($langue);
				if(isset($revue)) $revue->addLangueOriginale($langue);
			}
		}
		
		// Contenus
		foreach($extractors->getPericopes() as $pericope) {
			$notice->addPericope($pericope);
		}
		foreach($extractors->getNomsPropres() as $properName) {
			$notice->addProperName($properName);
		}
		foreach($extractors->getNomsInstitutions() as $institutionName) {
			$notice->addInstitutionName($institutionName);
		}
		foreach($extractors->getNomsGeographiques() as $geographicName) {
			$notice->addGeographicName($geographicName);
		}
		foreach($extractors->getLivresBibliques() as $biblicalBook) {
			$notice->addBiblicalBook($biblicalBook);
		}
		foreach($extractors->getDescripteurs() as $descriptor) {
			$notice->addDescriptor($descriptor);
		}

		$this->setExemplaires($extractor->getNumberOfExemplaires(), $notice);
				
		return $notice;
	}

    /**
     * Set an Author from a name
     * @param string $name
     * @return Author()
     */
	protected function getAuthor($name)
    {		
		$author = new Author();
		$author->setNames($name);
		return $author;	
	}

	/**
	 * Get a collection from a record
	 * @param RecordExtractor $extractor
	 * @return Collection()
	 */
	protected function getCollection(RecordExtractor $extractor)
    {		
		$coll = new Collection();

		$coll
			->setName($extractor->getCollectionName())
			->setSubName($extractor->getCollectionSubName())
			->setNum($extractor->getCollectionNumber())
			->setIssn($extractor->getCollectionIssn());

		return $coll;
	} 
	
	/**
	 * Get a editor from a record
	 * @param RecordExtractor $extractor
	 * @return Editor()
	 */
	public function getEditor(RecordExtractor $extractor)
    {		
		$editor = new Editor();
		
		$name = $extractor->getEditorName()
		// If there are several editors name, they are concatenated
		if(is_array($name)) {
			$name = implode(" - ", $name);
		}
		$editor
			->setName($name)
			->setCity($extractor->getEditorCity())
			->setYear($extractor->getEditionYear())
			->setNum($extractor->getEditionNumber());

		return $editor;
	}
	
	/**
	 * Create a Datetime from ISIS date
	 * @param string $value (like 2016/12/25)
	 * @return \DateTime
	 */
	public function getDate($value)
    {
		return \DateTime::createFromFormat('Y/m/d', $value);	
	}
	
	/**
	 * Set copies related to Notice
	 * @param string $value : "2 exemplaires"
	 * @param Notice $notice
	 * @return void
	 */
	protected function setExemplaires($value, \Migration\Business\Notice $notice)
    {		
		// Number of copies is indicated by the first caracter (never more than 9)
		$nbExemplaire = intval(substr($value, 0, 1));
		$i = 0;
		while($i < $nbExemplaire) {
			$ex = new Exemplaire();
			$ex
				// set a new uniq ID to copy
				->setId($this->getNextIdExemplaire())
				// "Cote" and "nature" of copy are those of notice
				->setCote($notice->get('cote'))
				->setSupport($notice->get('nature'));
			$notice->addExemplaire($ex);
			$i++;
		}		
	}
	
}
