<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Equipe.php');

$valide = true;

if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	if (isset($_POST["id"]))
	{
		$equipe = Equipe::recup($_POST["id"]);

		$nb_tournois = $equipe->recupNbTournois();
		$nb_matchs = $equipe->recupNbMatchs();
		if ($nb_tournois > 0)
		{
			print ("<DIV class=\"messageErreur\">Impossible de supprimer cette équipe car elle est inscrite à " . $nb_tournois . " tournoi(s) : Désinscrivez là d'abord des tournois</DIV>");
		}
		else if ($nb_matchs > 0)
		{
			print ("<DIV class=\"messageErreur\">Impossible de supprimer cette équipe car elle participe à " . $nb_matchs . " match(s) : Supprimez d'abord les matchs concernés</DIV>");
		}
		else
		{
			$resultat = Equipe::supprime($_POST["id"]);
			if ($resultat)
			{
				print ("<DIV class=\"messageInfo\">Suppression effectuée</DIV>");
				print ("<SCRIPT>chargeListeEquipes();</SCRIPT>");
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
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer cette équipe</DIV>");
}

?>