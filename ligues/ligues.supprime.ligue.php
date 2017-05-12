<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Ligue.php');
include_once ($RACINE . 'modele/Tournoi.php');

$valide = true;


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{	
	if (isset($_POST["id"]))
	{
		if (Tournoi::recupParChamp("ligue_id", $_POST["id"]))
		{
			print ("<DIV class=\"messageErreur\">Impossible de supprimer cette ligue car au moins un tournoi existe pour celle-ci : Supprimez d'abord les tournois de la ligue</DIV>");
		}
		else
		{
			$resultat = Ligue::supprime($_POST["id"]);
			if ($resultat)
			{
				print ("<DIV class=\"messageInfo\">Suppression effectuée</DIV>");
				print ("<SCRIPT>chargeListeLigues();</SCRIPT>");
			}
			else
			{
				print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de la suppression de l'objet en base de données</DIV>");
			}
		}
	}		
}	
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer cette ligue</DIV>");
}