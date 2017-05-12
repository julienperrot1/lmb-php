<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Poule.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	$valide = true;

	if (!isset($_POST["pouleId"]))
	{
		print ("<DIV class=\"messageErreur\">L'identifiant de la poule est introuvable</DIV>");
		$valide = false;
	}

	if (!isset($_POST["equipeId"]))
	{
		print ("<DIV class=\"messageErreur\">L'identifiant de l'équipe est introuvable</DIV>");
		$valide = false;
	}

	if (!isset($_POST["points"]) || !is_numeric($_POST["points"]) || $_POST["points"] < 0)
	{
		print ("<DIV class=\"messageErreur\">Les points de départage doivent être un nombre positif, ou 0</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		$equipe = Equipe::recup($_POST["equipeId"]);
		$poule = Poule::recup($_POST["pouleId"]);
		
		if ($equipe->indiqueDepartagePoule($poule, $_POST["points"]))
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
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour ajouter un départage</DIV>");
}

?>
	