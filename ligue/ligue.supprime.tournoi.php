<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Phase.php');

$valide = true;


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{	
	if (isset($_POST["id"]))
	{
		if (Phase::recupParChamp("tournoi_id", $_POST["id"]))
		{
			print ("<DIV class=\"messageErreur\">Impossible de supprimer ce tournoi car au moins une phase existe pour celle-ci : Supprimez d'abord les phases du tournoi</DIV>");
		}
		else
		{
			$resultat = Tournoi::supprime($_POST["id"]);
			if ($resultat)
			{
				print ("<DIV class=\"messageInfo\">Suppression effectuée</DIV>");
				print ("<SCRIPT>chargeListeTournois();</SCRIPT>");
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
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer ce tournoi</DIV>");
}