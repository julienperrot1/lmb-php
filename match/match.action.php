<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Action.php');
include_once ($RACINE . 'modele/Shoot.php');
include_once ($RACINE . 'modele/Faute.php');
include_once ($RACINE . 'modele/PasseDecisive.php');
include_once ($RACINE . 'modele/Rebond.php');
include_once ($RACINE . 'modele/Contre.php');
include_once ($RACINE . 'modele/Interception.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Stat.php');

include_once ($RACINE . 'utils/Logger.php');

$vide = true;
$joueur_cible_possible = true;
$termine = false;
$description_action = "";

$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
$match = Match::recup($temps_de_jeu->get("match_id"));
$phase = Phase::recup($match->get("phase_id"));
$tournoi = Tournoi::recup($phase->get("tournoi_id"));

$joueur_source = null;
$formation_source = null;
$joueur_cible = null;
$formation_cible = null;

if ($_POST["joueurSourceId"] != "null")
{
	$joueur_source = Joueur::recup($_POST["joueurSourceId"]);
}

if ($_POST["formationSourceId"] != "null")
{
	$formation_source = Formation::recup($_POST["formationSourceId"]);
	if ($match->get("formation1_id") == $formation_source->get("id"))
	{
		$formation_cible = Formation::recup($match->get("formation2_id"));
	}
	else if ($match->get("formation2_id") == $formation_source->get("id"))
	{
		$formation_cible = Formation::recup($match->get("formation1_id"));
	}
}

if ($_POST["joueurCibleId"] != "null")
{
	$joueur_cible = Joueur::recup($_POST["joueurCibleId"]);
}

if ($_POST["formationCibleId"] != "null")
{
	$formation_cible = Formation::recup($_POST["formationCibleId"]);
}




if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	// Si aucune action n'a encore été selectionnée
	if ($_POST["actionType"] == "null" )
	{
		print ("Choisissez une action : ");
		print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_SHOOT . ", null, null);\">Shoot</DIV>");
		print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_FAUTE . ", null, null);\">Faute</DIV>");
		print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_PASSE . ", null, null);\">Passe décisive</DIV>");
		print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_REBOND . ", null, null);\">Rebond</DIV>");
		print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_CONTRE . ", null, null);\">Contre</DIV>");
		print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_INTERCEPTION . ", null, null);\">Interception</DIV>");
		print ("<DIV class=\"champ_a_cliquer\" =\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_GENERIQUE . ", null, null);\">Autre</DIV>");
	}
	else
	{	
		$vide = false;

		// Detail d'une action de type shoot
		if ($_POST["actionType"] == $ACTION_TYPE_SHOOT)
		{
			if ($_POST["actionDetail"] != "null" && $_POST["actionReussite"] != "null")
			{
				if ($_POST["joueurSourceId"] != "null")
				{
					$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
					
					$action = new Action();
					$action->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
					$action->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
					$action->set("joueur_acteur_id", $_POST["joueurSourceId"]);
					$action->set("type", $_POST["actionType"]);
					$action->cree();
					
					$shoot = new Shoot();
					$shoot->set("action_id", $action->get("id"));
					$shoot->set("type", $_POST["actionDetail"]);
					$shoot->set("reussi", $_POST["actionReussite"]);
					$shoot->cree();
					
					$action->set("specifique_id", $shoot->get("id"));
					$action->enregistre();
							
					if ($_POST["actionDetail"] != 1)
					{
						Stat::ajouteStats("SHOOT", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);						
					}
					
					Stat::ajouteStats("SHOOT-" . $_POST["actionDetail"], $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
					if ($_POST["actionReussite"] == 1)
					{
						Stat::ajouteStats("POINT", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), $_POST["actionDetail"], 0);
						Stat::ajouteStats("SHOOT-REUSSI", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
						Stat::ajouteStats("SHOOT-" . $_POST["actionDetail"] . "-REUSSI", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
					}
					
					$termine = true;
				}
				else
				{
					print ("Choisissez dans les compositions des équipes le joueur qui a shooté");
				}
							
				// Descriptif
				$description_action = $SHOOT_TYPE_DESC[$_POST["actionDetail"]]
							  . " " . $ACTION_REUSSITE_DESC[$_POST["actionReussite"]];
			}
			else
			{
				$joueur_cible_possible = false;
				
				print ("Type de shoot : ");
				foreach ($SHOOT_TYPE_DESC as $shoot_type => $shoot_type_desc)
				{
					print ("<DIV class=\"champ_a_cliquer_ok\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_SHOOT . ", " . $shoot_type . ", 1);\">" . $shoot_type_desc . "</DIV>");
					print ("<DIV class=\"champ_a_cliquer_nok\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_SHOOT . ", " . $shoot_type . ", 0);\">" . $shoot_type_desc . "</DIV>");
				}

				// Descriptif
				$description_action = "Shoot";
			}
		}
		
		// Detail d'une action de type faute
		else if ($_POST["actionType"] == $ACTION_TYPE_FAUTE)
		{
			if ($_POST["actionDetail"] != "null")
			{
				if ($_POST["joueurSourceId"] != "null" && $_POST["joueurCibleId"] != "null")
				{
					$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
					
					$action = new Action();
					$action->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
					$action->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
					$action->set("joueur_acteur_id", $_POST["joueurSourceId"]);
					$action->set("joueur_cible_id", $_POST["joueurCibleId"]);
					$action->set("type", $_POST["actionType"]);
					$action->cree();
					
					$faute = new Faute();
					$faute->set("action_id", $action->get("id"));
					$faute->set("type", $_POST["actionDetail"]);
					$faute->cree();
					
					$action->set("specifique_id", $faute->get("id"));
					$action->enregistre();
												
					Stat::ajouteStats("FAUTE", $_POST["joueurSourceId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);

					$termine = true;
				}
				else if ($_POST["joueurSourceId"] == "null")
				{
					print ("Choisissez dans les compositions des équipes le joueur fautif");
				}
				else if ($_POST["joueurCibleId"] == "null")
				{
					print ("Choisissez dans les compositions des équipes le joueur cible de la faute, ou <DIV class=\"champ_a_cliquer\"  onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(0, 0);\">Valider la faute sans joueur cible</DIV>");
				}
				
				// Descriptif
				$description_action = $FAUTE_TYPE_DESC[$_POST["actionDetail"]];
			}
			else
			{
				print ("Type de faute : ");
				foreach ($FAUTE_TYPE_DESC as $faute_type => $faute_type_desc)
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_FAUTE . ", '" . $faute_type . "', null);\">" . $faute_type . "</DIV>");
				}
				
				// Descriptif
				$description_action = "Faute";
			}
		}
		
		// Detail d'une action de type passe décisive
		else if ($_POST["actionType"] == $ACTION_TYPE_PASSE)
		{
			if ($_POST["joueurSourceId"] != "null" && $_POST["joueurCibleId"] != "null")
			{
				$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
				
				$action1 = new Action();
				$action1->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
				$action1->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
				$action1->set("joueur_acteur_id", $_POST["joueurSourceId"]);
				$action1->set("joueur_cible_id", $_POST["joueurCibleId"]);
				$action1->set("type", $_POST["actionType"]);
				$action1->cree();
				
				$passe_decisive = new PasseDecisive();
				$passe_decisive->set("action_id", $action1->get("id"));
				$passe_decisive->cree();
				
				$action1->set("specifique_id", $passe_decisive->get("id"));
				$action1->enregistre();
				
				Stat::ajouteStats("PASSE", $_POST["joueurSourceId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
					
				$action2 = new Action();
				$action2->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
				$action2->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
				$action2->set("joueur_acteur_id", $_POST["joueurCibleId"]);
				$action2->set("type", $ACTION_TYPE_SHOOT);
				$action2->cree();
				
				$shoot = new Shoot();
				$shoot->set("action_id", $action2->get("id"));
				$shoot->set("type", 2);
				$shoot->set("reussi", 1);
				$shoot->cree();
				
				$action2->set("specifique_id", $shoot->get("id"));
				$action2->enregistre();
									
				Stat::ajouteStats("POINT", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 2, 0);
				Stat::ajouteStats("SHOOT", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
				Stat::ajouteStats("SHOOT-REUSSI", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
				Stat::ajouteStats("SHOOT-2", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
				Stat::ajouteStats("SHOOT-2-REUSSI", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);

				$termine = true;
			}
			else if ($_POST["joueurSourceId"] == "null")
			{
				print ("Choisissez dans les compositions des équipes le joueur ayant réalisé la passe décisive");
			}
			else if ($_POST["joueurCibleId"] == "null")
			{
				print ("Choisissez dans les compositions des équipes le joueur cible de la passe décisive (il sera automatiquement crédité d'un shoot à 2 points réussi)");
			}
			
			// Descriptif
			$description_action = "Passe décisive";
		}
		
		// Detail d'une action de type rebond
		else if ($_POST["actionType"] == $ACTION_TYPE_REBOND)
		{
			if ($_POST["actionDetail"] != "null")
			{
				if ($_POST["joueurSourceId"] != "null")
				{
					$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
					
					$action = new Action();
					$action->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
					$action->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
					$action->set("joueur_acteur_id", $_POST["joueurSourceId"]);
					$action->set("type", $_POST["actionType"]);
					$action->cree();
					
					$rebond = new Rebond();
					$rebond->set("action_id", $action->get("id"));
					$rebond->set("type", $_POST["actionDetail"]);
					$rebond->cree();
					
					$action->set("specifique_id", $rebond->get("id"));
					$action->enregistre();
					
					Stat::ajouteStats("REBOND-" . $_POST["actionDetail"], $_POST["joueurSourceId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
					Stat::ajouteStats("REBOND", $_POST["joueurSourceId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
					
					$termine = true;
				}
				else if ($_POST["joueurSourceId"] == "null")
				{
					print ("Choisissez dans les compositions des équipes le joueur ayant réalisé le rebond");
				}
				
				// Descriptif
				$description_action = $REBOND_TYPE_DESC[$_POST["actionDetail"]];
			}
			else
			{
				$joueur_cible_possible = false;
				
				print ("Type de rebond : ");
				foreach ($REBOND_TYPE_DESC as $rebond_type => $rebond_type_desc)
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_REBOND . ", " . $rebond_type . ", null);\">" . $rebond_type_desc . "</DIV>");
				}
				
				// Descriptif
				$description_action = "Rebond";
			}
		}
		
		// Detail d'une action de type contre
		else if ($_POST["actionType"] == $ACTION_TYPE_CONTRE)
		{
			if ($_POST["joueurSourceId"] != "null" && $_POST["joueurCibleId"] != "null")
			{
				$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
				
				$action = new Action();
				$action->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
				$action->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
				$action->set("joueur_acteur_id", $_POST["joueurSourceId"]);
				$action->set("joueur_cible_id", $_POST["joueurCibleId"]);
				$action->set("type", $_POST["actionType"]);
				$action->cree();
				
				$contre = new Contre();
				$contre->set("action_id", $action->get("id"));
				$contre->cree();
				
				$action->set("specifique_id", $contre->get("id"));
				$action->enregistre();
					
				Stat::ajouteStats("CONTRE", $_POST["joueurSourceId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
					
					
				$action2 = new Action();
				$action2->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
				$action2->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
				$action2->set("joueur_acteur_id", $_POST["joueurCibleId"]);
				$action2->set("type", $ACTION_TYPE_SHOOT);
				$action2->cree();
				
				$shoot = new Shoot();
				$shoot->set("action_id", $action2->get("id"));
				$shoot->set("type", 2);
				$shoot->set("reussi", 0);
				$shoot->cree();
				
				$action2->set("specifique_id", $shoot->get("id"));
				$action2->enregistre();
									
				Stat::ajouteStats("SHOOT", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
				Stat::ajouteStats("SHOOT-2", $_POST["joueurCibleId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);

				$termine = true;
			}
			else if ($_POST["joueurSourceId"] == "null")
			{
				print ("Choisissez dans les compositions des équipes le joueur ayant réalisé le contre");
			}
			else if ($_POST["joueurCibleId"] == "null")
			{
				print ("Choisissez dans les compositions des équipes le joueur ayant subit le contre (il sera automatiquement crédité d'un shoot à 2 points raté)");
			}
			
			// Descriptif
			$description_action = "Contre";
		}
		
		// Detail d'une action de type interception
		else if ($_POST["actionType"] == $ACTION_TYPE_INTERCEPTION)
		{
			if ($_POST["joueurSourceId"] != "null" && $_POST["joueurCibleId"] != "null")
			{
				$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
				
				$action = new Action();
				$action->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
				$action->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
				$action->set("joueur_acteur_id", $_POST["joueurSourceId"]);
				$action->set("joueur_cible_id", $_POST["joueurCibleId"]);
				$action->set("type", $_POST["actionType"]);
				$action->cree();
				
				$interception = new Interception();
				$interception->set("action_id", $action->get("id"));
				$interception->cree();
				
				$action->set("specifique_id", $interception->get("id"));
				$action->enregistre();
					
				Stat::ajouteStats("INTERCEPTION", $_POST["joueurSourceId"], $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
					
				$termine = true;
			}
			else if ($_POST["joueurSourceId"] == "null")
			{
				print ("Choisissez dans les compositions des équipes le joueur ayant réalisé l'interception");
			}
			else if ($_POST["joueurCibleId"] == "null")
			{
				print ("Choisissez dans les compositions des équipes le joueur ayant déclenché la perte de balle");
			}
			
			// Descriptif
			$description_action = "Interception";
		}
		
		// Detail d'une action de type générique
		else if ($_POST["actionType"] == $ACTION_TYPE_GENERIQUE)
		{
		if ($_POST["actionDetail"] != "null")
			{
				if ($_POST["joueurSourceId"] != "null" && $_POST["joueurCibleId"] != "null")
				{
					$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
					
					$action = new Action();
					$action->set("temps_de_jeu_id", $_POST["tempsDeJeuId"]);
					$action->set("temps", $temps_de_jeu->get("duree") - ($_POST["tempsMinutes"] * 60) - $_POST["tempsSecondes"]);
					$action->set("joueur_acteur_id", $_POST["joueurSourceId"]);
					$action->set("joueur_cible_id", $_POST["joueurCibleId"]);
					$action->set("type", $_POST["actionType"]);
					$action->set("commentaire", $_POST["actionDetail"]);
					$action->cree();
					
					$termine = true;
				}
				else if ($_POST["joueurSourceId"] == "null")
				{
					print ("Choisissez dans les compositions des équipes le joueur source de l'action");
				}
				else if ($_POST["joueurCibleId"] == "null")
				{
					print ("Choisissez dans les compositions des équipes le joueur cible de l'action, ou <DIV class=\"champ_a_cliquer\"  onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(0, 0);\">Valider l'action sans joueur cible</DIV>");
				}
				
				// Descriptif
				$description_action = "Action diverse (" . $_POST["actionDetail"] . ")";
			}
			else
			{
				print ("Commentaire sur l'action : ");
				print ("<INPUT class=\"champTexte\" id=\"match.action.commentaire\" type=\"text\" maxlength=\"255\" size=\"20\"> : ");
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixAction(" . $ACTION_TYPE_GENERIQUE . ", document.getElementById('match.action.commentaire').value, null);\">OK</DIV>");
				
				// Descriptif
				$description_action = "Action diverse";
			}
		}
	}

	print ("<BR/>");
	print ($description_action);

	// Sélection d'un joueur source
	if (isset($joueur_source) && isset($formation_source) && !$termine)
	{
		$vide = false;
		
		if ($joueur_source && $formation_source)
		{
			print (" de " . $joueur_source->get("pseudo") . " (" . $formation_source->getNumeroJoueur($joueur_source->get("id")) . ")");
		}
		else
		{
			print ("<DIV class=\"messageErreur\" >Impossible de récupérer les données du joueur " . $_POST["joueurSourceId"] . " pour la formation " . $_POST["formationSourceId"] . "</DIV>");
		}
		
		// Sélection d'un joueur cible
		if (isset($joueur_cible) && isset($formation_cible) && $joueur_cible_possible)
		{
			print (" sur " . $joueur_cible->get("pseudo") . " (" . $formation_cible->getNumeroJoueur($joueur_cible->get("id")) . ")");
		}
	}

	// Affichage du temps
	if ($_POST["tempsMinutes"] != "null" && $_POST["tempsSecondes"] != "null" && !$termine)
	{	
		$vide = false;
		
		print (" à " . $_POST["tempsMinutes"] . ":" . $_POST["tempsSecondes"]);
	}

	// Si aucune action n'a	été commencée
	if ($vide && !$termine)
	{
		print ("Aucune action en cours.");
	}

	if ($termine)
	{
		print ("<SCRIPT>videAction(); chargeFormation1(); chargeFormation2(); chargeScores(); chargeActionEnCours(); chargeResume();</SCRIPT>");
	}
}
				
?>
	