<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/PhaseTableau.php');
include_once ($RACINE . 'modele/Poule.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{	
	if (isset($_POST["id"]))
	{
		$phase = Phase::recup($_POST["id"]);

		if ($phase->get("type") == 1)
		{
			$phase_poules = PhasePoules::recup($phase->get("specifique_id"));
			$poules = Poule::recupParChamp("phase_poules_id", $phase_poules->get("id"));
			
			if ($poules)
			{
				print ("<DIV class=\"messageErreur\">Impossible de supprimer cette phase car elle contient " . sizeof($poules) . " poule(s) : Supprimez d'abord les poules concernées</DIV>");
			}
			else
			{
				$resultat = PhasePoules::supprime($phase->get("specifique_id"));
				
				if ($resultat)
				{
					$resultat = Phase::supprime($phase->get("id"));
				}
			}
		}

		if ($phase->get("type") == 2)
		{
			$phase_tableau = PhaseTableau::recup($phase->get("specifique_id"));
			$matchs = Match::recupParChamp("phase_id", $phase->get("id"));
			
			if ($matchs)
			{
				print ("<DIV class=\"messageErreur\">Impossible de supprimer cette phase car elle contient " . sizeof($matchs) . " match(s) : Supprimez d'abord les matchs concernés</DIV>");
			}
			else
			{
				$resultat = PhaseTableau::supprime($phase->get("specifique_id"));
				
				if ($resultat)
				{
					$resultat = Phase::supprime($phase->get("id"));
				}
			}
		}
		
		if ($resultat)
		{
			print ("<DIV class=\"messageInfo\">Suppression effectuée</DIV>");
			print ("<SCRIPT>chargeListePhases();</SCRIPT>");
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de la suppression de l'objet en base de données</DIV>");
		}
	}
}	
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer cette phase</DIV>");
}
