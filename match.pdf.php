<?php

if ($_GET["id"])
{
	include_once ('pdf_base.php');
	include_once ($RACINE . "match/match.feuille.match.pdf.php");
	
	matchFeuilleMatchPdf($pdf, $_GET["id"]);
	
	include_once ('pdf_end.php');
}

?>