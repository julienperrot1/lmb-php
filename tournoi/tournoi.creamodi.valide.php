<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Ligue.php');

$valide = true;


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{	
	if (!isset($_POST["libelle"]) || $_POST["libelle"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un libelle pour le tournoi</DIV>");
		$valide = false;
	}

	if (!isset($_POST["nb_equipe_max"]) || !is_numeric($_POST["nb_equipe_max"]) || $_POST["nb_equipe_max"] < 2 )
	{
		print ("<DIV class=\"messageErreur\">Le nombre d'équipes doit être numérique et supérieur ou égal à 2</DIV>");
		$valide = false;
	}

	if (!isset($_POST["ligue_id"]) || !Ligue::recup($_POST["ligue_id"]))
	{
		print ("<DIV class=\"messageErreur\">Impossible de trouver la ligue choisie</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		if (isset($_POST["id"]))
		{
			$tournoi = Tournoi::recup($_POST["id"]);
			$tournoi->set("libelle", $_POST["libelle"]);
			$tournoi->set("lieu", $_POST["lieu"]);
			$tournoi->set("nb_equipe_max", $_POST["nb_equipe_max"]);
			$tournoi->set("ligue_id", $_POST["ligue_id"]);
			$resultat = $tournoi->enregistre();
		}
		else
		{
			$tournoi = new Tournoi();
			$tournoi->set("libelle", $_POST["libelle"]);
			$tournoi->set("lieu", $_POST["lieu"]);
			$tournoi->set("nb_equipe_max", $_POST["nb_equipe_max"]);
			$tournoi->set("ligue_id", $_POST["ligue_id"]);
			$resultat = $tournoi->cree();
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
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour éditer un tournoi</DIV>");
}