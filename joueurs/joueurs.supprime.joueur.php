<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Joueur.php');

$valide = true;

if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{	
	if (isset($_POST["id"]))
	{
		$joueur = Joueur::recup($_POST["id"]);
		if ($joueur->getMatchsJoueesParEquipe())
		{
			print ("<DIV class=\"messageErreur\">Impossible de supprimer ce joueur car il est inscrit à au moins un match</DIV>");
		}
		else
		{
			$resultat = Joueur::supprime($_POST["id"]);
			if ($resultat)
			{
				print ("<DIV class=\"messageInfo\">Suppression effectuée</DIV>");
				print ("<SCRIPT>chargeListeJoueurs();</SCRIPT>");
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
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer ce joueur</DIV>");
}