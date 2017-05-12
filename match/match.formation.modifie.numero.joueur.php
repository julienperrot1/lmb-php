<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Match.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$formation = Formation::recup($_POST["formationId"]);
	$match = Match::recup($formation->get("match_id"));
	$formation_joueurs = $formation->getFormationJoueurs();

	$deja_inscrit = false;

	foreach ($formation_joueurs as $formation_joueur)
	{
		if ($formation_joueur["joueur_id"] == $_POST["joueurId"])
		{
			$deja_inscrit = true;
		}
	}

	if ($deja_inscrit)
	{
		if ($formation->modifieNumeroJoueur($_POST["joueurId"], $_POST["numero"]))
		{
			print ("<SCRIPT>chargeFormation1(); chargeFormation2();</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de la modification du numéro du joueur</DIV>");
		}
	}
	else
	{
		print ("<DIV class=\"messageInfo\" >Impossible de modifier ce joueur car il est introuvable dans la formation pour ce match</DIV>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour modifier le numéro d'un joueur</DIV>");
}


?>
	