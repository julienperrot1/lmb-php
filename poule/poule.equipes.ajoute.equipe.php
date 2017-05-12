<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Poule.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	$poule = Poule::recup($_POST["pouleId"]);

	if ($poule->ajouteEquipe($_POST["equipeId"]))
	{
		print ("<SCRIPT>chargeListePhases();</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >Erreur lors de l'ajout de l'équipe à la poule</DIV>");
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour ajouter une équipe à une poule</DIV>");
}

?>
	