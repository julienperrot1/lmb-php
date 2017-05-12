<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Action.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	if (isset($_POST["actionId"]))
	{
		if (Action::supprimeAvecSpecifique($_POST["actionId"]))
		{
			print ("<SCRIPT>chargeFormation1(); chargeFormation2(); chargeScores(); chargeResume();</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de la suppression de l'action</DIV>");
		}
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer une action</DIV>");
}
