<?php

if ($_GET["id"])
{
	include_once ('pdf_base.php');
	include_once ($RACINE . 'modele/Match.php');
	include_once ($RACINE . "match/match.feuille.match.pdf.php");

	$matchs = Match::recupParChamp("phase_id", $_GET["id"]);
	foreach ($matchs as $match)
	{
		matchFeuilleMatchPdf($pdf, $match->get("id"));
	}
	
	include_once ('pdf_end.php');
}

?>