<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Equipe.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{	
	$valide = true;

	if (!isset($_POST["nom"]) || $_POST["nom"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un nom pour l'équipe</DIV>");
		$valide = false;
	}

	if (!isset($_POST["couleur_base"]) || !preg_match("/^[0-9A-F]{6}$/i", $_POST["couleur_base"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez choisir une couleur de base pour l'équipe, au format hexadécimal</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		if (isset($_POST["id"]))
		{
			$equipe = Equipe::recup($_POST["id"]);
			$equipe->set("nom", $_POST["nom"]);
			$equipe->set("couleur_base", $_POST["couleur_base"]);
			$equipe->set("photo", $_POST["photo"]);
			$resultat = $equipe->enregistre();
		}
		else
		{
			$equipe = new Equipe();
			$equipe->set("nom", $_POST["nom"]);
			$equipe->set("couleur_base", $_POST["couleur_base"]);
			$equipe->set("photo", $_POST["photo"]);
			$resultat = $equipe->cree();
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
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour éditer une équipe</DIV>");
}