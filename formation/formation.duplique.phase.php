<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Phase.php');

$formation = Formation::recup($_POST["formationId"]);
$match_repere = Match::recup($formation->get("match_id"));
$matchs = Match::recupParChamp("phase_id", $match_repere->get("phase_id"));


$nombre_match_modifie = 0;

foreach ($matchs as $match)
{
	if (!$match_repere->egal($match))
	{
		$est_deja_demarre = false;
		
		if ($match->get("resultat") == $MATCH_RESULTAT_AJOUER)
		{
			$temps_de_jeux = TempsDeJeu::recupParChamp("match_id", $match->get("id"));
			if ($temps_de_jeux)
			{
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					$actions = Action::recupParChamp("temps_de_jeu_id", $temps_de_jeu->get("id"));
					if ($actions)
					{
						$est_deja_demarre = true;
					}
				}
			}
		}
		else
		{
			$est_deja_demarre = true;
		}
		
		if (!$est_deja_demarre)
		{
			$formation1 = Formation::recup($match->get("formation1_id"));
			$formation2 = Formation::recup($match->get("formation2_id"));
			
			$formation_a_completer = null;
			if ($formation1 && $formation->get("equipe_id") == $formation1->get("equipe_id"))
			{
				$formation_a_completer = $formation1;
			}
			if ($formation2 && $formation->get("equipe_id") == $formation2->get("equipe_id"))
			{
				$formation_a_completer = $formation2;
			}
			
			if ($formation_a_completer)
			{
				$formation_a_completer->enleveJoueurs();
				
				foreach ($formation->getFormationJoueurs() as $formation_joueur)
				{
					$formation_a_completer->ajouteJoueur($formation_joueur["joueur_id"], $formation_joueur["numero"]);
				}
				
				$nombre_match_modifie = $nombre_match_modifie + 1;
			}
		}
	}
}

print ("<DIV class=\"messageInfo\" >" . $nombre_match_modifie . " match(s) ont été mis à jour</DIV>");

?>
	