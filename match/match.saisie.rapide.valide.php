<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$valide = true;

	if (!isset($_POST["matchId"]) || $_POST["matchId"] == "")
	{
		print ("<DIV class=\"messageErreur\">Aucun identifiant de match n'a été envoyé vers le serveur</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		$match = Match::recup($_POST["matchId"]);
		$phase = Phase::recup($match->get("phase_id"));
		$tournoi = Tournoi::recup($phase->get("tournoi_id"));
		$formation1 = Formation::recup($match->get("formation1_id"));
		$formation2 = Formation::recup($match->get("formation2_id"));
		
		if ($match)
		{
			$temps_de_jeux = TempsDeJeu::recupParChamp("match_id", $_POST["matchId"]);
			
			if ($temps_de_jeux)
			{
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					$ordre_temporel = $temps_de_jeu->get("ordre_temporel");
					
					if (isset($_POST["points1_" . $ordre_temporel]) && $_POST["points1_" . $ordre_temporel] != "")
					{
						$points = explode(",", $_POST["points1_" . $ordre_temporel]);
						$temps = 0;
						foreach ($points as $point)
						{	
							$panier = explode("-", $point);
							$joueur_id = $formation1->getIdentifiantJoueur($panier[0]);
							
							if ($joueur_id)
							{
								if (isset($panier[1]))
								{
									$type = $panier[1];
								}
								else
								{
									$type = 2;
								}
								
								$action = new Action();
								$action->set("temps_de_jeu_id", $temps_de_jeu->get("id"));
								$action->set("temps", $temps);
								$action->set("joueur_acteur_id", $joueur_id);
								$action->set("type", $ACTION_TYPE_SHOOT);
								$action->cree();
								
								$shoot = new Shoot();
								$shoot->set("action_id", $action->get("id"));
								$shoot->set("type", $type);
								$shoot->set("reussi", true);
								$shoot->cree();
								
								$action->set("specifique_id", $shoot->get("id"));
								$action->enregistre();
								
                // Statistiques
                if ($type != 1)
                {
                  Stat::ajouteStats("SHOOT", $joueur_id, $formation1->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation2->get("equipe_id"), 1, 0);						
                  Stat::ajouteStats("SHOOT-REUSSI", $joueur_id, $formation1->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation2->get("equipe_id"), 1, 0);
                }
                
                Stat::ajouteStats("SHOOT-" . $type, $joueur_id, $formation1->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation2->get("equipe_id"), 1, 0);
                Stat::ajouteStats("SHOOT-" . $type . "-REUSSI", $joueur_id, $formation1->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation2->get("equipe_id"), 1, 0);
                Stat::ajouteStats("POINT", $joueur_id, $formation1->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation2->get("equipe_id"), $type, 0);
          
								$temps = $temps + 1;
							}
							else
							{
								print ("<DIV class=\"messageErreur\">Impossible de trouver le joueur ayant le numéro " . $panier[0] . " dans la formation 1</DIV>");
								$valide = false;
							}
						}
					}
					
					if (isset($_POST["points2_" . $ordre_temporel]) && $_POST["points2_" . $ordre_temporel] != "")
					{
						$points = explode(",", $_POST["points2_" . $ordre_temporel]);
						$temps = 0;
						foreach ($points as $point)
						{	
							$panier = explode("-", $point);
							$joueur_id = $formation2->getIdentifiantJoueur($panier[0]);
							if ($joueur_id)
							{
								if (isset($panier[1]))
								{
									$type = $panier[1];
								}
								else
								{
									$type = 2;
								}
								
								$action = new Action();
								$action->set("temps_de_jeu_id", $temps_de_jeu->get("id"));
								$action->set("temps", $temps);
								$action->set("joueur_acteur_id", $joueur_id);
								$action->set("type", $ACTION_TYPE_SHOOT);
								$action->cree();
								
								$shoot = new Shoot();
								$shoot->set("action_id", $action->get("id"));
								$shoot->set("type", $type);
								$shoot->set("reussi", true);
								$shoot->cree();
								
								$action->set("specifique_id", $shoot->get("id"));
								$action->enregistre();
																
                // Statistiques
                if ($type != 1)
                {
                  Stat::ajouteStats("SHOOT", $joueur_id, $formation2->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation1->get("equipe_id"), 1, 0);						
                  Stat::ajouteStats("SHOOT-REUSSI", $joueur_id, $formation2->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation1->get("equipe_id"), 1, 0);
                }
                
                Stat::ajouteStats("SHOOT-" . $type, $joueur_id, $formation2->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation1->get("equipe_id"), 1, 0);
                Stat::ajouteStats("SHOOT-" . $type . "-REUSSI", $joueur_id, $formation2->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation1->get("equipe_id"), 1, 0);
                Stat::ajouteStats("POINT", $joueur_id, $formation2->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation1->get("equipe_id"), $type, 0);
          
								$temps = $temps + 1;
							}
							else
							{
								print ("<DIV class=\"messageErreur\">Impossible de trouver le joueur ayant le numéro " . $panier[0] . " dans la formation 2</DIV>");
								$valide = false;
							}
						}
					}
					
					if (isset($_POST["fautes1_" . $ordre_temporel]) && $_POST["fautes1_" . $ordre_temporel] != "")
					{
						$fautes = explode(",", $_POST["fautes1_" . $ordre_temporel]);
						$temps = 0;
						foreach ($fautes as $faute)
						{	
							$detail_faute = explode("-", $faute);
							$joueur_id = $formation1->getIdentifiantJoueur($detail_faute[0]);
							if ($joueur_id)
							{
								if (isset($detail_faute[1]))
								{
									$type = $detail_faute[1];
								}
								else
								{
									$type = "P";
								}
								
								$action = new Action();
								$action->set("temps_de_jeu_id", $temps_de_jeu->get("id"));
								$action->set("temps", $temps);
								$action->set("joueur_acteur_id", $joueur_id);
								$action->set("type", $ACTION_TYPE_FAUTE);
								$action->cree();
							
								$faute = new Faute();
								$faute->set("action_id", $action->get("id"));
								$faute->set("type", $type);
								$faute->cree();
								
								$action->set("specifique_id", $faute->get("id"));
								$action->enregistre();
                
								// Statistiques
                Stat::ajouteStats("FAUTE", $joueur_id, $formation1->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation2->get("equipe_id"), 1, 0);
         
								$temps = $temps + 1;
							}
							else
							{
								print ("<DIV class=\"messageErreur\">Impossible de trouver le joueur ayant le numéro " . $detail_faute[0] . " dans la formation 1</DIV>");
								$valide = false;
							}
						}
					}
					
					if (isset($_POST["fautes2_" . $ordre_temporel]) && $_POST["fautes2_" . $ordre_temporel] != "")
					{
						$fautes = explode(",", $_POST["fautes2_" . $ordre_temporel]);
						$temps = 0;
						foreach ($fautes as $faute)
						{	
							$detail_faute = explode("-", $faute);
							$joueur_id = $formation2->getIdentifiantJoueur($detail_faute[0]);
							if ($joueur_id)
							{
								if (isset($detail_faute[1]))
								{
									$type = $detail_faute[1];
								}
								else
								{
									$type = "P";
								}
								
								$action = new Action();
								$action->set("temps_de_jeu_id", $temps_de_jeu->get("id"));
								$action->set("temps", $temps);
								$action->set("joueur_acteur_id", $joueur_id);
								$action->set("type", $ACTION_TYPE_FAUTE);
								$action->cree();
							
								$faute = new Faute();
								$faute->set("action_id", $action->get("id"));
								$faute->set("type", $type);
								$faute->cree();
								
								$action->set("specifique_id", $faute->get("id"));
								$action->enregistre();
								
								// Statistiques
                Stat::ajouteStats("FAUTE", $joueur_id, $formation2->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation1->get("equipe_id"), 1, 0);
         
								$temps = $temps + 1;
							}
							else
							{
								print ("<DIV class=\"messageErreur\">Impossible de trouver le joueur ayant le numéro " . $detail_faute[0] . " dans la formation 2</DIV>");
								$valide = false;
							}
						}
					}
				}
			}
			
			if ($valide)
			{
				print ("#REDIRECT#");
			}
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de la récupération du match en base de données</DIV>");
		}
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour effectuer une saisie rapide des résultats</DIV>");
}