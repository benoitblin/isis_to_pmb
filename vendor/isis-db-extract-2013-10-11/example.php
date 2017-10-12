<?php
include './ISIS/Exception/ExtractDataNVFBASEException.php';
include './ISIS/Exception/LoaderFileReadException.php';
include './ISIS/Exception/LoaderFileRequirementException.php';
include './ISIS/Exception/LoaderPathException.php';
include './ISIS/Database/Extract.php';
include './ISIS/Database/Loader.php';

/**
 * 
 * $loader = new ISIS\Database\Loader('./Data/isis');
 * $database = $loader->extract();
*/

/**
 * $loader = new ISIS\Database\Loader('./Data/isis');
 * $database = new \ISIS\Database\Extract($loader);
 */

$keys = array(
	24 => "Titre",
	104 => "Collection",
	10 => "Infos",
	20 => "Auteur",
	21 => "Auteur secondaire",
	23 => "Sous-titre",
	27 => "Edition",
	28 => "Pages",
	29 => "Revue",
	31 => "Langue de l'original",
	40 => "Tome",
	50 => "Résumé",
	55 => "Notes",
	60 => "Nom propre",
	61 => "Nom d'institution",
	62 => "Descripteurs",
	63 => "Nom géographique",
	64 => "Péricope",
	65 => "Livre biblique",
	70 => "Bibliothéque",
	71 => "Cote",
	81 => "ISBN",
	90 => "Date de validation",
	91 => "Date de saisie",
	92 => "Op. de saisie",
	93 => "Indexeur",
	100 => "Titre parallèle",
	101 => "Traducteur(s)",
	102 => "Préfacier",
	105 => "Sous-collection",
	106 => "Format",
	108 => "1ère publication",
	110 => "Sous la direction de",
	111 => "Recension(s)",
	12 => "Detail"
};

echo "<table style='border:1pt solid black'><tr></tr>";

foreach($keys as $title) {
	echo "<th>".$title."</th>";
}

echo "</tr>";

$database = new \ISIS\Database\Extract('./Data/isis');

foreach ($database as $record){
	echo "<tr>";
	foreach($keys as $key => $title) {
		echo "<td>";
		if(array_key_exists($key, $record)) {
			echo $record[$key];
		}
		echo "</td>";
	}
	echo "</tr>";
}

echo "</table>";

$mfn = 2;

/**
 * Yo can also access to record using;
 */
// var_dump( $database->fetch($mfn));

?>
