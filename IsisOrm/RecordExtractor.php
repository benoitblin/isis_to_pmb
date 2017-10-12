<?php

namespace Migration\IsisOrm;

/**
 * Verbode manipulation of a Record
 * @author benoit.blin@gmail.com
 */
class RecordExtractor
{

	protected $record;

	public function __construct(Record $record) {

		$this->record = $record;

	}

	protected function getCoteRaw() {

		return $this->record->getFirst(71);

	}

	protected function getEditorRaw() {

		return $this->record->getFirst(27);

	}

	protected function getIsbnRaw() {

		return $this->record->getFirst(103);

	}

	protected function getIssnRaw() {

		return $this->record->getFirst(25);

	}

	protected function getCollectionRaw() {

		return $this->record->getFirst(104);

	}

	protected function getTomeRaw() {

		return $this->record->getFirst(40);

	}

	public function getMainTitle() {

		return $this->record->getFirst(24);

	}

	public function getSubTitle() {

		return $this->record->getFirst(23);

	}

	public function getOriginalTitle() {

		return $this->record->getFirst(100);

	}

	public function getAuthors() {

		return $this->record->get(20);

	}

	public function getSecondAuthors() {

		return $this->record->get(21);

	}

	public function getPrefaciers() {

		return $this->record->get(102);

	}

	public function getTraducteurs() {

		return $this->record->get(101);

	}

	public function getDirecteurs() {

		return $this->record->get(110);

	}

	public function getEditorName() {

		$editor = $this->getEditorRaw();
		if(is_array($editor) && array_key_exists('m', $editor)) {
			return $editor['m'];
		}
		if(is_array($editor) && array_key_exists('z', $editor)) {
			$year = $editor['z'];
			if(is_array($year)) {
				return $year[0];
			}
		}			
		return null;

	}

	public function getEditorCity() {

		$editor = $this->getEditorRaw();
		if(is_array($editor) && array_key_exists('l', $editor)) {
			return $editor['l'];
		}
		return null;

	}

	public function getEditionYear() {

		$editor = $this->getEditorRaw();
		if(is_array($editor) && array_key_exists('z', $editor)) {
			$year = $editor['z'];
			if(is_array($year)) {
				return $year[1];
			}
			return $year;
		}

		$year = $this->record->getFirst(33);
		if($year != null) {
			return $year;
		}
		
		$year = $this->record->getFirst(35);
		if($year != null) {
			return $year;
		}

		return null;

	}

	public function getEditionNumber() {

		$editor = $this->getEditorRaw();
		if(is_array($editor) && array_key_exists('n', $editor)) {
			return $editor['n'];
		}

		$num = $this->record->getFirst(103);
		if($num != "") {
			return $num;
		}

		$isbn = $this->getIsbnRaw();
		if(is_array($isbn) && array_key_exists('e', $isbn)) {
			return $isbn['e'];
		}

		return null;

	}

	public function getIsbn() {

		$isbn = $this->getIsbnRaw();

		if(is_array($isbn)) {
			if(array_key_exists('m', $isbn)) {
				$subIsbn = $isbn['m'];
				if(is_array($subIsbn)) {
					$subIsbn = implode('-', $subIsbn);
				}
				return $subIsbn;
			}
		} elseif($isbn != "") {
			return $isbn;
		}

		$issn = $this->getIssnRaw();
		if(is_array($issn) && array_key_exists('m', $issn)) {
			$cleanedValue = trim(str_replace("ISSN :", "", $issn['m']));
			if(strlen($cleanedValue) >= 9) {
				return $cleanedValue;
			}
		}

		return null;

	}

	public function getCollectionIssn() {

		$coll = $this->getCollectionRaw();
		if(is_array($coll) && array_key_exists('m', $coll)) {
			return trim(str_replace("ISSN :", "", $coll['m']));
		}

		$issn = $this->getIssnRaw();
		if(is_array($issn) && array_key_exists('m', $issn)) {
			$cleanedValue = trim(str_replace("ISSN :", "", $issn['m']));
			if(strlen($cleanedValue) < 9) {
				return $cleanedValue;
			}
		}

		return null;

	}

	public function getCollectionName() {

		$coll = $this->getCollectionRaw();
		if(is_array($coll) && array_key_exists('l', $coll)) {
			$name = $coll['l'];
			if(is_array($name)) {
				$name = implode("", $name);
			}
			return $name;
		}

		return null;

	}

	public function getCollectionSubName() {

		$coll = $this->getCollectionRaw();
		if(is_array($coll) && array_key_exists('s', $coll)) {
			return $coll['s'];
		}

		return null;

	}

	public function getCollectionNumber() {

		$coll = $this->getCollectionRaw();
		if(is_array($coll) && array_key_exists('n', $coll)) {
			return trim(str_replace("n ", "", $coll['n']));
		}

		return null;
		
	}

	public function getNumberOfPages() {

		$pages = $record->getFirst(28);
		if(is_array($pages) && array_key_exists('p', $pages)) {
			$pages = $pages['p'];
		}
		return $pages;

	}

	public function getInfos() {

		return $this->record->getFirst(10);		

	}

	public function getResume() {

		return $this->record->getFirst(50);

	}

	public function getNotes() {

		return $this->record->getFirst(55);

	}

	public function getNotesBis() {

		return $this->record->getFirst(107);

	}

	public function getDetails() {

		return $this->record->getFirst(12);

	}

    public function isRevue() {

        return ($this->record->getFirst(29) != null);

    }

	public function getRevue() {

		return $this->record->getFirst(29);

	}

	public function getRevueIssn() {

		$tome = $this->getTomeRaw();
		if(is_array($tome) && array_key_exists('n', $tome)) {
			return trim(str_replace("ISSN :", "", $tome['n']));
		}

		return null;		
	}

	public function getLangues() {

		$langues = $this->record->get(30);
		$cote = $this->getCoteRaw();
		if(is_array($cote) && array_key_exists('l', $cote) && !in_array($cote['l'], $langue)) {
			$langues[] = $cote['l'];
		}
		return $langues;

	}

	public function getOriginalLangues() {

		return $this->record->get(31);

	}

	public function getTome() {

		$tome = $this->getTomeRaw();
		if(is_array($tome)) {
			$value = "";
			if(array_key_exists('t', $tome)) {
				$value = $tome['t'];
			}
			if($value == "") {
				if(array_key_exists('n', $tome)) {
					$value = $tome['n'];
				} else {
					return null;
				}
			}
			 
			$first = substr($value, 0, 1);
			if($first == "n" || $first == "t") {
				$value = trim(substr($value, 1));
			}
			return trim($value);
		}	

		return null;

	}

	public function getRevueMois() {

		$tome = $this->getTomeRaw();
		if(is_array($tome) && array_key_exists('m', $tome)) {
			return $tome['m'];
		}

		return null;

	}

	public function getRevueAnnee() {

		$tome = $this->getTomeRaw();
		if(is_array($tome) && array_key_exists('z', $tome)) {
			return $tome['z'];
		}

		return null;
		
	}

	public function getNomsPropres() {

		return $this->record->get(60);

	}

	public function getNomsInstitutions() {

		return $this->record->get(61);

	}

	public function getDescripteurs() {

		return $this->record->get(62);

	}

	public function getNomsGeographiques() {

		return $this->record->get(63);

	}

	public function getPericopes() {

		return $this->record->get(64);

	}

	public function getLivresBibliques() {

		return $this->record->get(65);

	}

	public function getCote() {

		$cote = $this->getCoteRaw();
		if(is_array($cote) && array_key_exists('v', $cote)) {
			return $cote['v'];
		}
		return null;

	}

	public function getSupport() {

		$cote = $this->getCoteRaw();
		if(is_array($cote) && array_key_exists('t', $cote)) {
			return $cote['t'];
		}
		return null;

	}

	public function getNumberOfExemplaires() {

		$cote = $this->getCoteRaw();
		if(is_array($cote) && array_key_exists('n', $cote)) {
			return $cote['n'];
		}
		return null;

	}

	public function getFormat() {

		return $this->record->getFirst(106);

	}

	public function getFirstPublication() {

		return $this->record->getFirst(108);
	}

}
