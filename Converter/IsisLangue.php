<?php

namespace Migration\Converter;

/**
 * Convert ISIS languages to PMB languages codes
 * @author benoit.blin@gmail.com
 */
class IsisLangue
{

    // List of conversion codes
	protected $langues = array(
	"chinois" => "chi",
"allemand" => "ger",
"italien" => "ita",
"francais" => "fre",
"espagnol" => "spa",
"americain" => "eng",
"latin" => "lat",
"anglais" => "eng",
"russe" => "rus",
"grec" => "grc",
"neerlandais" => "dut",
"suedois" => "swe",
"arabe" => "ara",
"thai" => "tha",
"bresilien" => "por",
"danois" => "dan",
"portugais" => "por",
"flamand" => "dut",
"francais et autres" => "fre",
"hebreu" => "heb",
"persan" => "per",
"sanscrit" => "san",
"syriaque" => "syr",
"ethiopien" => "eth",
"hindi" => "hin",
"armenien" => "arm",
"hongrois" => "hun",
"roumain" => "rum",
"tcheque" => "cze",
"polonais" => "pol",
"ukrainien" => "ukr",
"akkadien" => "akk",
"bulgare" => "bul",
"croate" => "hrv",
"vieux francais" => "fro",
"lingala" => "lin",
"catalan" => "cat",
"japonais" => "jpn",
"copte" => "cop",
"ouigour" => "uig",
"tibetain" => "tib",
"sanskrit" => "san",
"bengali" => "ben",
"turc" => "tur",
"anglais (amerique)" => "eng",
"breton" => "bre",
"moyen-allemand" => "gmh",
"norvegien" => "nor",
"anglais (etats-unis)" => "eng",
"arameen" => "arc"
	);

    /**
     * Several languages can be separated with a special char
     * @param string texte
     * @return array
     */
	public function cleanText($text)
	{
		$text = strtolower($text);
		$text = $this->removeAccents($text);
		$text = str_replace("?", "", $text);
		$text = str_replace(" - ", " ", $text);
		$text = str_replace(" / ", " ", $text);
		$text = str_replace("/", " ", $text);
		$text = str_replace(" et ", " ", $text);
		$text = str_replace(" ou ", " ", $text);
		$cleans = explode(" ", $text);
		return $cleans;
	}
	
    /**
     * Remove accents is required for comparison
     * @param string $str
     * @return string
     */
	protected function removeAccents($str)
	{
		$str = htmlentities($str, ENT_NOQUOTES);
		$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // ligatures e.g. '&oelig;'
		$str = preg_replace('#&[^;]+;#', '', $str); // remove other escaped characters
		return $str;
	}

    /**
     * Get PMB language code from ISIS language
     * @rule : if the language is not recognized, set french as default
     * @param string $text
     * @return array
     */
	public function getCodes($text)
	{
		$cleanValues = $this->cleanText($text);
		foreach($cleanValues as $cleanValue) {
			if(!array_key_exists($cleanValue, $this->langues)) {
				//throw new \Exception($text . " is not a recognized language");
				$cleanValue = "francais";
			}
			$values[] = $this->langues[$cleanValue];
		}
		return $values;
	}
}
