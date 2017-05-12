<?php

global $RACINE;
include_once ($RACINE . 'config.php');

include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/PhaseTableau.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Formation.php');

class Regle
{
	// Retourne une version lisible d'une régle
	public static function versString($regle)
	{
		$retour = "";
		
		$regle_tab = explode("-", $regle);
		if (is_numeric($regle_tab[2]))
		{
			$phase = Phase::recup($regle_tab[0]);
			$poule = Poule::recup($regle_tab[1]);
			$place = $regle_tab[2];
			
			if ($phase)
			{
				$retour = $retour . "Phase " . $phase->get("libelle") . " - ";
			}
			else
			{
				$retour = $retour . "Phase inexistante !!!";
			}
			
			if ($poule)
			{
				$retour = $retour . $poule->get("libelle");
			}
			else
			{
				$retour = $retour . "Poule inexistante !!!";
			}
			
			$retour = $retour . " - Place " . $place;
		}
		else
		{
			$phase = Phase::recup($regle_tab[0]);
			$match = Match::recup($regle_tab[1]);
			$resultat = $regle_tab[2];
			
			if ($phase)
			{
				$retour = $retour . "Phase " . $phase->get("libelle") . " - ";
			}
			else
			{
				$retour = $retour . "Phase inexistante !!!";
			}
			
			if ($match)
			{
				$retour = $retour . $match->get("libelle");
			}
			else
			{
				$retour = $retour . "Match inexistant !!!";
			}
			
			if ($regle_tab[2] == "G")
			{
				$retour = $retour . " - Gagnant";
			}
			else
			{
				$retour = $retour . " - Perdant";
			}
		}
		
		return $retour;
	}
	
	
	// Retourne le selecteur de régle complet en fonction du tournoi et de la phase concernée
	public static function recupSelecteurRegle($tournoi, $phase = null, $regle_selectionne = "")
	{
		$phases = Phase::recupParChamp("tournoi_id", $tournoi->get("id"), "ordre_temporel ASC");
		$selecteur_regle = "<OPTION></OPTION>";
		$affiche = false;
		
		$ordre_temporel = 1000;
		if ($phase)
		{
			$ordre_temporel = $phase->get("ordre_temporel");
		}
		
		foreach ($phases as $phase_autre)
		{
			if ($phase_autre->get("ordre_temporel") <= $ordre_temporel)
			{
				if ($phase_autre->get("type") == 1)
				{
					$phase_poules_autre = PhasePoules::recup($phase_autre->get("specifique_id"));
					$poules_autre = Poule::recupParChamp("phase_poules_id", $phase_poules_autre->get("id"), $tri = "libelle ASC");
					
					foreach ($poules_autre as $poule_autre)
					{
						for ($place = 1; $place <= $poule_autre->getNbEquipes(); $place++)
						{
							$regle = $phase_autre->get("id") . "-" . $poule_autre->get("id") . "-" . $place;
							
							if (($phase && !$phase->estAttribueRegle($regle)) || (!$phase && !$tournoi->estAttribueRegle($regle)))
							{
								$affiche = true;
								$selecteur_regle = $selecteur_regle . "<OPTION value=\"" . $regle . "\"";
								if ($regle == $regle_selectionne)
								{
									$selecteur_regle = $selecteur_regle . " selected=\"true\"";
								}
								$selecteur_regle = $selecteur_regle . ">" . Regle::versString($regle) . "</OPTION>";
							}
						}
					}
				}
				
				if ($phase_autre->get("type") == 2)
				{
					$phase_tableau_autre = PhasePoules::recup($phase_autre->get("specifique_id"));
					$matchs_autre = Match::recupParChamp("phase_id", $phase_autre->get("id"), $tri = "id ASC");
					
					foreach ($matchs_autre as $match_autre)
					{
						$regle = $phase_autre->get("id") . "-" . $match_autre->get("id") . "-G";
						if (($phase && !$phase->estAttribueRegle($regle)) || (!$phase && !$tournoi->estAttribueRegle($regle)))
						{
							$affiche = true;
							$selecteur_regle = $selecteur_regle . "<OPTION value=\"" . $regle . "\"";
							if ($regle == $regle_selectionne)
							{
								$selecteur_regle = $selecteur_regle . " selected=\"true\"";
							}
							$selecteur_regle = $selecteur_regle . ">" . Regle::versString($regle) . "</OPTION>";
						}
						
						$regle = $phase_autre->get("id") . "-" . $match_autre->get("id") . "-P";
						if (($phase && !$phase->estAttribueRegle($regle)) || (!$phase && !$tournoi->estAttribueRegle($regle)))
						{
							$affiche = true;
							$selecteur_regle = $selecteur_regle . "<OPTION value=\"" . $regle . "\"";
							if ($regle == $regle_selectionne)
							{
								$selecteur_regle = $selecteur_regle . " selected=\"true\"";
							}
							$selecteur_regle = $selecteur_regle . ">" . Regle::versString($regle) . "</OPTION>";
						}
					}
				}
			}
		}
		
		if ($affiche)
		{
			return $selecteur_regle;
		}
		else
		{
			return null;
		}
	}
	
	
	// Retourne l'équipe concernée par une régle pour la résolution de celle-ci
	public static function recupEquipeDepuisRegle($regle)
	{
		if ($regle && $regle != null && $regle != "")
		{
			$regle_equipe = explode("-", $regle);
			if (!is_numeric($regle_equipe[2]))
			{
				$match_regle = Match::recup($regle_equipe[1]);
				
				if (($match_regle->get("resultat") == 1 && $regle_equipe[2] == "G") || ($match_regle->get("resultat") == 2 && $regle_equipe[2] == "P"))
				{
					return (Formation::recup($match_regle->get("formation1_id"))->get("equipe_id"));
				}
				else if (($match_regle->get("resultat") == 1 && $regle_equipe[2] == "P") || ($match_regle->get("resultat") == 2 && $regle_equipe[2] == "G"))
				{
					return (Formation::recup($match_regle->get("formation2_id"))->get("equipe_id"));
				}
				else if ($match_regle->get("resultat") == 3) 
				{
					 return ("Erreur lors de l'attribution de la régle " . $classement_tournoi->get("regle_equipe") . " : Le match concerné à donné lieu à une égalité");
				}
			}
			else
			{
				$equipe_id = null;
				
				$poule_regle = Poule::recup($regle_equipe[1]);

				$classement = $poule_regle->getClassement();
				$trouve = false;
				$erreur = false;
				foreach ($classement as $classement_place)
				{
					if ($classement_place["classement"] == $regle_equipe[2])
					{
						if ($trouve)
						{
							$erreur = true;
						}
						else
						{
							$trouve = true;
							$equipe_id = $classement_place["equipe_id"];
						}
					}
				}
				
				if ($erreur)
				{
					return ("Erreur lors de l'attribution de la régle " . $classement_tournoi->get("regle_equipe") . " : Plusieurs équipes sont concernées. Vérifiez les résultats précédents et recommencez");
				}
				else if (!$trouve)
				{
					return ("Erreur lors de l'attribution de la régle " . $classement_tournoi->get("regle_equipe") . " : Aucune équipe  concernée. Vérifiez les résultats précédents et recommencez");
				}
			
				return $equipe_id;
			}
		}
		else
		{
			return null;
		}
	}
}

?>