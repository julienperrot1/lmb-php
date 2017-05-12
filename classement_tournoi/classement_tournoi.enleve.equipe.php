<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/ClassementTournoi.php');

$valide = true;


if (!isset($_POST["id"]) || !is_numeric($_POST["id"]))
{
	print ("<DIV class=\"messageErreur\">Aucun identifiant de classement n'a été trouvé</DIV>");
	$valide = false;
}


if ($valide)
{
	$classement_tournoi = ClassementTournoi::recup($_POST["id"]);
	$classement_tournoi->set("equipe_id", -1);
	$resultat = $classement_tournoi->enregistre();

	
	if ($resultat)
	{
		print ("<SCRIPT>chargeClassement();</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de l'enregistrement de l'objet en base de données</DIV>");
	}
}
