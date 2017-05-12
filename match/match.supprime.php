<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');

$valide = true;


if (isset($_POST["matchId"]))
{
	$message_erreur = "";
	
	$match = Match::recup($_POST["matchId"]);
	
	$temps_de_jeux = TempsDeJeu::recupParChamp("match_id", $_POST["matchId"]);
	
	if ($temps_de_jeux)
	{
		foreach ($temps_de_jeux as $temps_de_jeu)
		{
			$actions = Action::recupParChamp("temps_de_jeu_id", $temps_de_jeu->get("id"));
			foreach ($actions as $action)
			{
				if (!Action::supprimeAvecSpecifique($action->get("id")))
				{
					$message_erreur = $message_erreur . "Erreur de suppression de l'action " . $action->get("id") . "<BR/>";
				}
			}
			
			if (!TempsDeJeu::supprime($temps_de_jeu->get("id")))
			{
				$message_erreur = $message_erreur . "Erreur de suppression de l'action " . $temps_de_jeu->get("id") . "<BR/>";
			}
		}
	}
	
	$formations = Formation::recupParChamp("match_id", $_POST["matchId"]);
	if ($formations)
	{
		foreach ($formations as $formation)
		{
			if (!$formation->enleveJoueurs())
			{
				$message_erreur = $message_erreur . "Erreur de la suppression des liens entre la formation " . $formation->get("id") . " et les joueurs<BR/>";
			}
			
			if (!Formation::supprime($formation->get("id")))
			{
				$message_erreur = $message_erreur . "Erreur de suppression de la formation " . $formation->get("id") . "<BR/>";
			}
		}
	}
	
	$poule = $match->getPoule();
	if ($poule)
	{
		if (!$poule->enleveMatch($match->get("id")))
		{
			$message_erreur = $message_erreur . "Erreur lors de la suppression du match de la poule " . $poule->get("id") . "<BR/>";
		}
	}
	
	if (!Match::supprime($match->get("id")))
	{
		$message_erreur = $message_erreur . "Erreur lors de la suppression du match " . $match->get("id") . "<BR/>";
	}

	
	if ($message_erreur == "")
	{
		print ("#REDIRECT#");
	}
	else
	{
		print ("<DIV class=\"messageErreur\">" . $message_erreur . "</DIV>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Aucun identifiant de match n'a été passé en paramètre</DIV>");
}
