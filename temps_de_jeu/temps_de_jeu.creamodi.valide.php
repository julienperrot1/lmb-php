<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/TempsDeJeu.php');

$valide = true;


if (!isset($_POST["matchId"]) || !is_numeric($_POST["matchId"]))
{
	print ("<DIV class=\"messageErreur\">Aucun identifiant de match n'a été trouvé</DIV>");
	$valide = false;
}

if (!isset($_POST["libelle"]) || $_POST["libelle"] == "")
{
	print ("<DIV class=\"messageErreur\">Veuillez spécifier un libelle pour le temps de jeu</DIV>");
	$valide = false;
}

if (!isset($_POST["dureeMinutes"]) || !is_numeric($_POST["dureeMinutes"]) || $_POST["dureeMinutes"] < 0 || !isset($_POST["dureeSecondes"]) || !is_numeric($_POST["dureeSecondes"]) || $_POST["dureeSecondes"] < 0 || $_POST["dureeSecondes"] > 59)
{
	print ("<DIV class=\"messageErreur\">Veuillez spécifier une durée correcte pour le temps de jeu</DIV>");
	$valide = false;
}


if ($valide)
{
	if (isset($_POST["id"]))
	{
		$temps_de_jeu = TempsDeJeu::recup($_POST["id"]);
		$temps_de_jeu->set("match_id", $_POST["matchId"]);
		$temps_de_jeu->set("libelle", $_POST["libelle"]);
		$resultat = $temps_de_jeu->enregistre();
	}
	else
	{
		$temps_de_jeu = new TempsDeJeu();
		$temps_de_jeu->set("match_id", $_POST["matchId"]);
		$temps_de_jeu->set("ordre_temporel", TempsDeJeu::prochainOrdreTemporel($_POST["matchId"]));
		$temps_de_jeu->set("libelle", $_POST["libelle"]);
		$temps_de_jeu->set("duree", ($_POST["dureeMinutes"] * 60) + $_POST["dureeSecondes"]);
		$temps_de_jeu->set("temps_restant", ($_POST["dureeMinutes"] * 60) + $_POST["dureeSecondes"]);
		$temps_de_jeu->set("nb_faute_equipe", Faute::getNbFautesEquipePourDuree(($_POST["dureeMinutes"] * 60) + $_POST["dureeSecondes"]));
		$resultat = $temps_de_jeu->cree();
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
