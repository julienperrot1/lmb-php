<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Poule.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	if (isset($_POST["pouleId"]))
	{
		$poule = Poule::recup($_POST["pouleId"]);
		$poule->set("etat", 3);
		
		if ($poule->enregistre())
		{
			print ("#REDIRECT#");
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Erreur lors de l'enregistrement de la poule</DIV>");
		}
	}
	else
	{
		print ("<DIV class=\"messageErreur\">Aucun identifiant de poule n'a été passé en paramètre</DIV>");
	}
}			
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour terminer cette poule</DIV>");
}