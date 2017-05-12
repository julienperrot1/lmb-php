<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Tournoi.php');


$tournoi = Tournoi::recup($_POST["tournoiId"]);
$tournoi_equipes = $tournoi->getEquipes();

if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$deja_inscrit = false;
	foreach ($tournoi_equipes as $tournoi_equipe)
	{
		if ($tournoi_equipe["equipe_id"] == $_POST["equipeId"])
		{
			$deja_inscrit = true;
		}
	}

	if ($deja_inscrit)
	{
		$resultat = $tournoi->enleveEquipe($_POST["equipeId"]);
		if ($resultat === 0)
		{
			print ("<DIV class=\"messageErreur\" >Impossible de retirer cette équipe car elle est inscrite à un match de ce tournoi</DIV>");
		}
		else if ($resultat)
		{
			print ("<SCRIPT>chargeListePhases();chargeListeEquipes()</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de l'annulation de la participation de l'équipe au tournoi</DIV>");
		}
	}
	else
	{
		print ("<SCRIPT>chargeListeEquipes();</SCRIPT>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour enlever une équipe d'un tournoi</DIV>");
}

?>
	