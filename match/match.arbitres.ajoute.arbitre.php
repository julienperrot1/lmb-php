<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Match.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{			
	$match = Match::recup($_POST["matchId"]);
	$arbitre1_id = $match->get("arbitre1_id");
	$arbitre2_id = $match->get("arbitre2_id");

	if ($_POST["joueurId"] == $arbitre1_id || $_POST["joueurId"] == $arbitre2_id)
	{
		print ("<DIV class=\"messageInfo\" >Ce joueur est déjà arbitre de ce match</DIV>");
	}
	else if ($arbitre2_id && $arbitre2_id != null && $arbitre2_id != -1)
	{
		print ("<DIV class=\"messageInfo\" >Deux arbitres ont déjà été selectionnés pour cette rencontre</DIV>");
	}
	else
	{	
		if ($arbitre1_id && $arbitre1_id != null && $arbitre1_id != -1)
		{
			$match->set("arbitre2_id", $_POST["joueurId"]);
		}
		else
		{
			$match->set("arbitre1_id", $_POST["joueurId"]);
		}
		$match->enregistre();
		
		print ("<SCRIPT>chargeArbitres();</SCRIPT>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour ajouter un arbitre au match</DIV>");
}

?>
	