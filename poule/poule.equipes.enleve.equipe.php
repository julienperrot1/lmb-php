<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Poule.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	$poule = Poule::recup($_POST["pouleId"]);

	$resultat = $poule->enleveEquipe($_POST["equipeId"]);
	if ($resultat === 0)
	{
		print ("<DIV class=\"messageInfo\" >Impossible de retirer cette équipe car elle est inscrite à un match de cette poule</DIV>");
	}
	else if ($resultat)
	{
		print ("<SCRIPT>chargeListePhases()</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >Erreur lors de l'annulation de la participation de l'équipe à la poule</DIV>");
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour enlever une équipe d'une poule</DIV>");
}

?>
	