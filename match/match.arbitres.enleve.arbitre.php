<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{	
	$match = Match::recup($_POST["matchId"]);
	$numero = $_POST["numero"];

	if ($numero == 1)
	{
		$match->set("arbitre1_id", $match->get("arbitre2_id"));
		$match->set("arbitre2_id", -1);
		print ("<SCRIPT>chargeArbitres();</SCRIPT>");
	}
	else if ($numero == 2)
	{
		$match->set("arbitre2_id", -1);
		print ("<SCRIPT>chargeArbitres();</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageInfo\" >L'arbitre choisi doit exister !</DIV>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour enlever un arbitre du match</DIV>");
}

?>
	