<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhasePoules.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{	
	$valide = true;

	if (!isset($_POST["libelle"]) || $_POST["libelle"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un libelle pour la poule</DIV>");
		$valide = false;
	}

	if (!isset($_POST["points_victoire"]) || !is_numeric($_POST["points_victoire"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier une valeur numérique pour le nombre de point lors d'une victoire</DIV>");
		$valide = false;
	}

	if (!isset($_POST["points_defaite"]) || !is_numeric($_POST["points_defaite"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier une valeur numérique pour le nombre de point lors d'une défaite</DIV>");
		$valide = false;
	}

	if (!isset($_POST["points_nul"]) || !is_numeric($_POST["points_nul"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier une valeur numérique pour le nombre de point lors d'un match nul</DIV>");
		$valide = false;
	}

	if (!isset($_POST["goal_average_ecart_max"]) || !is_numeric($_POST["goal_average_ecart_max"]) || $_POST["goal_average_ecart_max"] < 0)
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier une valeur numérique positive pour l'écart max de points comptabilisés lors du calcul du goal average</DIV>");
		$valide = false;
	}

	if (!isset($_POST["etat"]) || !is_numeric($_POST["etat"]) || $_POST["etat"] > 3 )
	{
		print ("<DIV class=\"messageErreur\">Erreur de selection de l'état actuel de la poule</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		if (isset($_POST["id"]))
		{
			$poule = Poule::recup($_POST["id"]);
			$poule->set("libelle", $_POST["libelle"]);
			$poule->set("points_victoire", $_POST["points_victoire"]);
			$poule->set("points_defaite", $_POST["points_defaite"]);
			$poule->set("points_nul", $_POST["points_nul"]);
			$poule->set("goal_average_ecart_max", $_POST["goal_average_ecart_max"]);
			$poule->set("etat", $_POST["etat"]);
			$resultat = $poule->enregistre();
		}
		else
		{
			$poule = new Poule();
			$poule->set("libelle", $_POST["libelle"]);
			$poule->set("points_victoire", $_POST["points_victoire"]);
			$poule->set("points_defaite", $_POST["points_defaite"]);
			$poule->set("points_nul", $_POST["points_nul"]);
			$poule->set("goal_average_ecart_max", $_POST["goal_average_ecart_max"]);
			$poule->set("etat", $_POST["etat"]);
			$poule->set("phase_poules_id", $_POST["phasePoulesId"]);
			$resultat = $poule->cree();
			
			if ($resultat)
			{
				$phase_poules = PhasePoules::recup($_POST["phasePoulesId"]);
				$phase_poules->set("nb_poules", $phase_poules->get("nb_poules") + 1);
				$phase_poules->enregistre();
			}
		}
		
		if ($resultat)
		{
			print ("#REDIRECT#");
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de l'enregistrement de l'objet en base de données</DIV>");
		}
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour éditer une poule</DIV>");
}