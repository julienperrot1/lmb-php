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

	if (!$deja_inscrit)
	{
		if (sizeof($tournoi_equipes) >= $tournoi->get("nb_equipe_max"))
		{
			print ("<DIV class=\"messageErreur\" >Le nombre maximum d'équipes à été atteint pour ce tournoi</DIV>");
		}
		else if ($tournoi->ajouteEquipe($_POST["equipeId"]))
		{
			print ("<SCRIPT>chargeListePhases();chargeListeEquipes();</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de l'ajout de l'équipe au tournoi</DIV>");
		}
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >Impossible d'ajouter cette équipe car elle est déjà inscrite pour ce tournoi</DIV>");
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour enlever une équipe d'un tournoi</DIV>");
}

?>
	