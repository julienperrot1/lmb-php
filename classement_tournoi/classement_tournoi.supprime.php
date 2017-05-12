<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/ClassementTournoi.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{	
	if (isset($_POST["id"]))
	{
		$resultat = ClassementTournoi::supprime($_POST["id"]);
	}
		
	if ($resultat)
	{
		print ("<DIV class=\"messageInfo\">Suppression effectuée</DIV>");
		print ("<SCRIPT>chargeClassement();</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de la suppression de l'objet en base de données</DIV>");
	}
}	
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer ce classement</DIV>");
}
