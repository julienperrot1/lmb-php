<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Ligue.php');

$valide = true;


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	if (!isset($_POST["libelle"]) || $_POST["libelle"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un libelle pour la ligue</DIV>");
		$valide = false;
	}

	if (!isset($_POST["type"]) || !is_numeric($_POST["type"]) || !$LIGUE_TYPE_DESC[$_POST["type"]])
	{
		print ("<DIV class=\"messageErreur\">Veuillez choisir un type de tournoi</DIV>");
		$valide = false;
	}

	if (!isset($_POST["nb_tournoi_class"]) || !is_numeric($_POST["nb_tournoi_class"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez choisir un nombre de tournois comptants pour le classement</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		if (isset($_POST["id"]))
		{
			$ligue = Ligue::recup($_POST["id"]);
			$ligue->set("libelle", $_POST["libelle"]);
			$ligue->set("type", $_POST["type"]);
			$ligue->set("nb_tournoi_class", $_POST["nb_tournoi_class"]);
			$resultat = $ligue->enregistre();
		}
		else
		{
			$ligue = new Ligue();
			$ligue->set("libelle", $_POST["libelle"]);
			$ligue->set("type", $_POST["type"]);
			$ligue->set("nb_tournoi_class", $_POST["nb_tournoi_class"]);
			$resultat = $ligue->cree();
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
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour éditer une ligue</DIV>");
}