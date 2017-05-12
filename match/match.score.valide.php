<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$valide = true;

	if (!isset($_POST["matchId"]) || $_POST["matchId"] == "")
	{
		print ("<DIV class=\"messageErreur\">Aucun identifiant de match n'a été envoyé vers le serveur</DIV>");
		$valide = false;
	}

	if (!isset($_POST["score1"]) || !is_numeric($_POST["score1"]) || $_POST["score1"] < 0)
	{
		print ("<DIV class=\"messageErreur\">Le score de l'équipe 1 doit être un entier positif</DIV>");
		$valide = false;
	}

	if (!isset($_POST["score2"]) || !is_numeric($_POST["score2"]) || $_POST["score2"] < 0)
	{
		print ("<DIV class=\"messageErreur\">Le score de l'équipe 2 doit être un entier positif</DIV>");
		$valide = false;
	}

	if (!isset($_POST["resultat"]) || ($_POST["resultat"] != $MATCH_RESULTAT_EQUIPE1 && $_POST["resultat"] != $MATCH_RESULTAT_EQUIPE2 && $_POST["resultat"] != $MATCH_RESULTAT_NUL))
	{
		print ("<DIV class=\"messageErreur\">Un des trois résultats possibles doit être selectionné</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		$match = Match::recup($_POST["matchId"]);
		$match->set("score1", $_POST["score1"]);
		$match->set("score2", $_POST["score2"]);
		$match->set("resultat", $_POST["resultat"]);
		$resultat = $match->enregistre();

		if ($resultat)
		{
			$temps_de_jeux = TempsDeJeu::recupParChamp("match_id", $_POST["matchId"]);
			
			if ($temps_de_jeux)
			{
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					$temps_de_jeu->set("temps_restant", 0);
					$temps_de_jeu->enregistre();
				}
			}
			
			print ("<SCRIPT>videAction(); chargeFormation1(); chargeFormation2(); chargeChronometre(); chargeScores(); chargeActionEnCours(); chargeResume();</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de l'enregistrement du match en base de données</DIV>");
		}
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour effectuer une saisie des résultats</DIV>");
}