<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Match.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$formation = Formation::recup($_POST["formationId"]);
	$match = Match::recup($formation->get("match_id"));
	$formation1 = Formation::recup($match->get("formation1_id"));
	$formation2 = Formation::recup($match->get("formation2_id"));
	$formation1_joueurs = $formation1->getFormationJoueurs();
	$formation2_joueurs = $formation2->getFormationJoueurs();

	$deja_inscrit = false;

	foreach ($formation1_joueurs as $formation1_joueur)
	{
		if ($formation1_joueur["joueur_id"] == $_POST["joueurId"])
		{
			$deja_inscrit = true;
		}
	}

	foreach ($formation2_joueurs as $formation2_joueur)
	{
		if ($formation2_joueur["joueur_id"] == $_POST["joueurId"])
		{
			$deja_inscrit = true;
		}
	}

	if (!$deja_inscrit)
	{
		if ($formation->ajouteJoueur($_POST["joueurId"], $_POST["numero"]))
		{
			print ("<SCRIPT>chargeFormation1(); chargeFormation2();</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de l'ajout du joueur à la formation</DIV>");
		}
	}
	else
	{
		print ("<DIV class=\"messageInfo\" >Impossible d'ajouter ce joueur car il est déjà inscrit à une formation pour ce match</DIV>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour ajouter un joueur à une formation</DIV>");
}


?>
	