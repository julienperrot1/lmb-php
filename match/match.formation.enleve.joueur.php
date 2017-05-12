<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$formation = Formation::recup($_POST["formationId"]);
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
		$resultat = $formation->enleveJoueur($_POST["joueurId"]);
		if ($resultat === 0)
		{
			print ("<DIV class=\"messageErreur\" >Impossible de retirer ce joueur car il a déjà participé à une action du match</DIV>");
		}
		else if ($resultat)
		{
			print ("<SCRIPT>chargeFormation1(); chargeFormation2();</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de l'annulation de la participation du joueur au match</DIV>");
		}
	}
	else
	{
		print ("<SCRIPT>chargeFormation1(); chargeFormation2();</SCRIPT>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour enlever un joueur d'une formation</DIV>");
}


?>
	