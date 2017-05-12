<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Match.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{	
	$match = Match::recup($_POST["matchId"]);

	if ($_POST["numFormation"] == 1)
	{
		$match->set("regle_formation1", "");
	}
	else
	{
		$match->set("regle_formation2", "");
	}

	if ($match->enregistre())
	{
		print ("<SCRIPT>chargeListePhases();</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >Erreur lors de la suppression de la règle au match</DIV>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour enlever une régle d'une formation</DIV>");
}

?>