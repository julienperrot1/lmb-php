<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/ClassementTournoi.php');

$valide = true;


if (!isset($_POST["tournoi_id"]) || !is_numeric($_POST["tournoi_id"]))
{
	print ("<DIV class=\"messageErreur\">Aucun identifiant de tournoi n'a été trouvé</DIV>");
	$valide = false;
}

if (!isset($_POST["place"]) || !is_numeric($_POST["place"]) || $_POST["place"] < 1)
{
	print ("<DIV class=\"messageErreur\">La place d'un classement ddoit être un entier supérieur ou égal à 1</DIV>");
	$valide = false;
}

if (!isset($_POST["regle_equipe"]) || $_POST["regle_equipe"] == "")
{
	print ("<DIV class=\"messageErreur\">Une régle doit être selectionnée</DIV>");
	$valide = false;
}

if (isset($_POST["points"]) && (!is_numeric($_POST["points"]) || $_POST["points"] < 0))
{
	print ("<DIV class=\"messageErreur\">Les points liés au classement doivent être un nombre positif</DIV>");
	$valide = false;
}


if ($valide)
{
	if (isset($_POST["id"]))
	{
		$classement_tournoi = ClassementTournoi::recup($_POST["id"]);
		$classement_tournoi->set("place", $_POST["place"]);
		$classement_tournoi->set("regle_equipe", $_POST["regle_equipe"]);
		$classement_tournoi->set("points", $_POST["points"]);
		$resultat = $classement_tournoi->enregistre();
	}
	else
	{
		$classement_tournoi = new ClassementTournoi();
		$classement_tournoi->set("place", $_POST["place"]);
		$classement_tournoi->set("equipe_id", -1);
		$classement_tournoi->set("regle_equipe", $_POST["regle_equipe"]);
		$classement_tournoi->set("tournoi_id", $_POST["tournoi_id"]);
		$classement_tournoi->set("points", ClassementTournoi::recupPointsParPlace($_POST["place"]));
		$resultat = $classement_tournoi->cree();
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
