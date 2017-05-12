<?php

if ($_GET["id"])
{
	include_once ('pdf_base.php');
	include_once ($RACINE . 'modele/Poule.php');
	include_once ($RACINE . "match/match.feuille.match.pdf.php");

	$poule = Poule::recup($_GET["id"]);
	foreach ($poule->getMatchs() as $poule_match)
	{
		matchFeuilleMatchPdf($pdf, $poule_match["match_id"]);
	}
	
	include_once ('pdf_end.php');
}

?>