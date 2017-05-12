<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');


if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
{
	$valide = true;

	if (isset($_POST["pouleId"]))
	{
		$poule = Poule::recup($_POST["pouleId"]);
		$phase_poules = PhasePoules::recup($poule->get("phase_poules_id"));
		$phase = Phase::recup($phase_poules->get("phase_id"));
		$numero_match = 1;
		
		$message_erreur = "";
					
		$poule_equipes = $poule->getEquipes();
		if ($poule_equipes) 
		{
			foreach ($poule_equipes as $poule_equipe1)
			{
				$equipe1 = Equipe::recup($poule_equipe1["equipe_id"]);
				
				foreach ($poule_equipes as $poule_equipe2)
				{
					$equipe2 = Equipe::recup($poule_equipe2["equipe_id"]);
					
					if (!$equipe1->egal($equipe2) && !$poule->getMatch($equipe1->get("id"), $equipe2->get("id")))
					{
						$match = new Match();
						$match->set("libelle", "Match " . $numero_match);
						$match->set("phase_id", $phase_poules->get("phase_id"));
						$match->set("formation1_id", -1);
						$match->set("formation2_id", -1);
						$match->set("formation1_id", -1);
						$match->set("formation1_id", -1);
						$match->set("regle_formation1", "");
						$match->set("regle_formation2", "");
						$match->set("arbitre1_id", -1);
						$match->set("arbitre2_id", -1);
						$match->set("date", $phase->get("date"));
						$match->set("resultat", $MATCH_RESULTAT_AJOUER);
						$match->set("niveau_stats", 0);
						if (!$match->cree())
						{
							$message_erreur = $message_erreur . "Erreur de création du match " . $numero_match . "<BR/>";
						} 
						
						if (!$poule->ajouteMatch($match->get("id")))
						{
							$message_erreur = $message_erreur . "Erreur de liaison du match " . $numero_match . " avec la poule<BR/>";
						}
						
						if ($message_erreur == "")
						{
							$formation1 = new Formation();
							$formation1->set("equipe_id", $equipe1->get("id"));
							$formation1->set("match_id", $match->get("id"));
							if (!$formation1->cree())
							{
								$message_erreur = $message_erreur . "Erreur de création de la formation 1 du match " . $numero_match . "<BR/>";
							}
							else
							{
								$formation1->dupliquePlusRecente($equipe1->get("id"));
							}
						}
						
						if ($message_erreur == "")
						{
							$formation2 = new Formation();
							$formation2->set("equipe_id", $equipe2->get("id"));
							$formation2->set("match_id", $match->get("id"));
							if (!$formation2->cree())
							{
								$message_erreur = $message_erreur . "Erreur de création de la formation 2 du match " . $numero_match . "<BR/>";
							}
							else
							{
								$formation2->dupliquePlusRecente($equipe2->get("id"));
							}
						}
						
						if ($message_erreur == "")
						{
							$match->set("formation1_id", $formation1->get("id"));
							$match->set("formation2_id", $formation2->get("id"));
							$match->enregistre();
							
							for ($index_temporel = 1; $index_temporel <= $phase_poules->get("nb_periode_match"); $index_temporel++)
							{
								$temps_de_jeu = new TempsDeJeu();
								$temps_de_jeu->set("match_id", $match->get("id"));
								$temps_de_jeu->set("ordre_temporel", $index_temporel);
								$temps_de_jeu->set("duree", $phase_poules->get("duree_periode_match"));
								$temps_de_jeu->set("libelle", TempsDeJeu::getLibellePourNbPeriode($phase_poules->get("nb_periode_match"), $index_temporel));
								$temps_de_jeu->set("temps_restant", $phase_poules->get("duree_periode_match"));
								$temps_de_jeu->set("nb_faute_equipe", Faute::getNbFautesEquipePourDuree($phase_poules->get("duree_periode_match")));
								if (!$temps_de_jeu->cree())
								{
									$message_erreur = $message_erreur . "Erreur de création du temps de jeu " . $index_temporel . " pour le match " . $numero_match . "<BR/>";
								} 
							}
						}
					
						$numero_match = $numero_match + 1;
					}
					
					if ($message_erreur != "")
					{
						break;
					}
				}
					
				if ($message_erreur != "")
				{
					break;
				}
			}
			
			if ($message_erreur == "")
			{
				$poule->set("etat", 2);
				if (!$poule->enregistre())
				{
					$message_erreur = $message_erreur . "Erreur lors de l'enregistrement de la poule<BR/>";
				}
			}
		}
		else
		{
			$message_erreur = $message_erreur . "<DIV class=\"messageErreur\">Aucune équipe inscrite : Aucun match à créer</DIV>";
		}
		
		if ($message_erreur == "")
		{
			print ("#REDIRECT#");
		}
		else
		{
			print ("<DIV class=\"messageErreur\">" . $message_erreur . "</DIV>");
		}
	}
	else
	{
		print ("<DIV class=\"messageErreur\">Aucun identifiant de poule n'a été passé en paramètre</DIV>");
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour créer les matchs de cette poule</DIV>");
}

?>
