<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Poule.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	$poule = Poule::recup($_POST["pouleId"]);

	if ($poule->ajouteEquipe(-1, $_POST["regle"]))
	{
		print ("<SCRIPT>chargeListePhases();</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >Erreur lors de l'ajout de la règle à la poule</DIV>");
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour ajouter une régle à une poule</DIV>");
}

?>
	