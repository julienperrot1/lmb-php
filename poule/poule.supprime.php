<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhasePoules.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	if (isset($_POST["id"]))
	{
		$poule = Poule::recup($_POST["id"]);
		$phase_poules = PhasePoules::recup($poule->get("phase_poules_id"));

		$nb_matchs = $poule->getNbMatchs();
		if ($nb_matchs > 0)
		{
			print ("<DIV class=\"messageErreur\">Impossible de supprimer cette poule car elle contient " . $nb_matchs . " match(s) : Supprimez d'abord les matchs concernés</DIV>");
		}
		else
		{
			$poule_equipes = $poule->getEquipes();
			if ($poule_equipes)
			{
				foreach ($poule_equipes as $poule_equipe)
				{
					$poule->enleveEquipe($poule_equipe["equipe_id"]);
				}
			}
			
			$resultat = Poule::supprime($_POST["id"]);
			if ($resultat)
			{
				$phase_poules->set("nb_poules", $phase_poules->get("nb_poules") - 1);
				$phase_poules->enregistre();
				
				print ("<DIV class=\"messageInfo\">Suppression effectuée</DIV>");
				print ("<SCRIPT>chargeListePhases();</SCRIPT>");
			}
			else
			{
				print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de la suppression de l'objet en base de données</DIV>");
			}
		}
	}
}			
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer cette poule</DIV>");
}