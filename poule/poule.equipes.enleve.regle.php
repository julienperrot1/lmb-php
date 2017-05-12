<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Poule.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	$poule = Poule::recup($_POST["pouleId"]);

	$resultat = $poule->enleveEquipe(-1, $_POST["regle"]);
	if ($resultat)
	{
		print ("<SCRIPT>chargeListePhases()</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >Erreur lors de l'annulation de la règle à la poule</DIV>");
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour enlever une régle d'une poule</DIV>");
}

?>
	