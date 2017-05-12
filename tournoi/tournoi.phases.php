<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhaseTableau.php');
include_once ($RACINE . 'utils/Tableur.php');
include_once ($RACINE . 'utils/Regle.php');


if (isset($_POST["tournoiId"]) && $_POST["tournoiId"] != "")
{
	$phases = Phase::recupParChamp("tournoi_id", $_POST["tournoiId"], $tri = "ordre_temporel ASC");

	if ($phases)
	{
		foreach ($phases as $phase)
		{
			$libelle = $phase->get("libelle");
			$ordre_temporel = $phase->get("ordre_temporel");
			$date = $phase->get("date");
			$type = $phase->get("type");
			$specifique_id = $phase->get("specifique_id");
			
			print ("<DIV id=\"tournoi.phase." . $phase->get("id") . "\" class=\"texte\">");
			print ("<DIV class=\"soustitre\">Phase " . $ordre_temporel . " : " . $libelle . " (" . $date . ")</DIV>");
			print ("<BR/>");
			
			if ($type == 1)
			{
				$phase_poules = PhasePoules::recup($specifique_id);
				$duree_minutes = floor($phase_poules->get("duree_periode_match") / 60);
				$duree_secondes = $phase_poules->get("duree_periode_match") - ($duree_minutes * 60);
				
				print ($phase_poules->get("nb_poules") . " poules, matchs en " . $phase_poules->get("nb_periode_match") . " période(s) de " . $duree_minutes . " minutes" );
				if ($duree_secondes > 0)
				{
					print (" " . $duree_secondes . " secondes");
				}
				print ("<BR/>");
				
				$equipes_restantes = $phase_poules->getEquipesNonAttribuees();
				if ($equipes_restantes)
				{
					$selecteur_equipe = "<OPTION></OPTION>";
					foreach ($equipes_restantes as $equipe_restante)
					{
						$selecteur_equipe = $selecteur_equipe .
							"<OPTION value=\"" . $equipe_restante->get("id") . "\">" .
							$equipe_restante->get("nom") . "</OPTION>";
					}	
				}
				else
				{
					$selecteur_equipe = "";
				}
				
				$poules = Poule::recupParChamp("phase_poules_id", $phase_poules->get("id"), $tri = "libelle ASC");
				if ($poules)
				{
					Tableur::dessineTableau($poules, true
										  , array("Poule", "Etat", "Matchs (prévus / joués)", "Equipes", "Actions")
										  , array(function ($objet) { return $objet->get("libelle"); }
												, function ($objet) { global $ETAT_DESC;
																	  return $ETAT_DESC[$objet->get("etat")]; }
												, function ($objet) { return $objet->getNbMatchs() . " (" . $objet->getNbMatchs(1) . " / " . $objet->getNbMatchs(2) . ")"; }
												, function ($objet) {
													global $utilisateur_en_cours;
													$poule_equipes = $objet->getEquipes();
													if ($poule_equipes)
													{
														$retour = "";
														foreach ($poule_equipes as $poule_equipe)
														{
															if ($poule_equipe["equipe_id"] > 0)
															{
																$equipe = Equipe::recup($poule_equipe["equipe_id"]);
																
																$retour = $retour . "<A href=\"equipe.php?id=" . $equipe->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B></A>";
																if ($objet->get("etat") <= 1 && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
																{
																	$retour = $retour . " <IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveEquipePoule(" . $objet->get("id") . ", " . $equipe->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
																}
															}
															else
															{
																$retour = $retour . Regle::versString($poule_equipe["regle"]);
																
																if ($objet->get("etat") <= 1 && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
																{
																	$retour = $retour . " <IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveReglePoule(" . $objet->get("id") . ", '" . $poule_equipe["regle"] . "');\" src=\"images/suppression.jpg\"></IMG>";
																}
															}
															$retour = $retour . "<BR/>";
														}
													}
													else
													{
														$retour = "Aucune équipe dans la poule pour le moment";
													}
													return $retour;
												  }
												, function ($objet) {
													global $selecteur_equipe, $phase_poules, $phase, $phases, $utilisateur_en_cours;
													$retour = "";
													if ($objet->get("etat") <= 1 && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
													{
														$selecteur_regle = Regle::recupSelecteurRegle(Tournoi::recup($phase->get("tournoi_id")), $phase);				
														
														if ($selecteur_regle)
														{
															$retour = $retour
																. "Ajouter une règle : "
																. "<SELECT id=\"tournoi.phases.poule." . $objet->get("id") . ".regle.ajoute"
																. "\" onchange=\"ajouteReglePoule(" . $objet->get("id") . ");\">"
																. $selecteur_regle
																. "</SELECT><BR/>";
														}
													}
													
													if ($objet->get("etat") <= 1 && $selecteur_equipe && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
													{
														$retour = $retour
															. "Ajouter une équipe : "
															. "<SELECT id=\"tournoi.phases.poule." . $objet->get("id") . ".equipe.ajoute"
															. "\" onchange=\"ajouteEquipePoule(" . $objet->get("id") . ");\">"
															. $selecteur_equipe
															. "</SELECT><BR/>";
													}
													
													$retour = $retour
														. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"affichePoule(" . $objet->get("id") . ");\" src=\"images/validation.jpg\"></IMG>";
														
													if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
													{
														$retour = $retour
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifiePoule(" . $objet->get("id") . ", " . $phase_poules->get("id") . ");\" src=\"images/modification.jpg\"></IMG>"
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimePoule(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
													}
													
													return $retour;
												  }
												 )
										  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");
				}
				else
				{
					if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
					{
						print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.poule.suppression.phase\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimePhase(" . $phase->get("id") . ");\">Supprimer cette phase</DIV>");	
					}
				}
				
				if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
				{
					print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.poule.creation.poule\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creePoule(" . $phase_poules->get("id") . ");\">Création d'une nouvelle poule pour cette phase</DIV>");	
					
					print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.phase.resolution.regles\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"resoutReglesPoule(" . $phase_poules->get("id") . ");\">Résolution des régles</DIV>");	
				}
			}
			
			
			
			
			
			if ($type == 2)
			{
				$phase_tableau = PhaseTableau::recup($specifique_id);
				
				$matchs = Match::recupParChamp("phase_id", $phase->get("id"), $tri = "id ASC");
				if ($matchs)
				{
					Tableur::dessineTableau($matchs, true
										  , array("Libellé", "Equipe1", "Resultat", "Equipe2", "Actions")
										  , array(function ($objet) { 	return $objet->get("libelle"); }
												, function ($objet) { 	global $phase, $phase_tableau, $phases, $utilisateur_en_cours;
																		$retour = " ";
																		if ($objet->get("formation1_id") > 0)
																		{
																			$formation = Formation::recup($objet->get("formation1_id"));
																			$equipe = Equipe::recup($formation->get("equipe_id"));
																			$retour = "<A href=\"equipe.php?id=" . $equipe->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B></A>";
																		}
																		else if ($objet->get("regle_formation1") != "")
																		{
																			$retour = $retour . Regle::versString($objet->get("regle_formation1"));
																			
																			if ($objet->get("resultat") == 0 && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
																			{
																				$retour = $retour . " <IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveRegleMatch(" . $objet->get("id") . ", '1');\" src=\"images/suppression.jpg\"></IMG>";
																			}
																		}
																		else
																		{
																			if ($phase_tableau->get("etat") <= 1 && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
																			{
																				$selecteur_regle = Regle::recupSelecteurRegle(Tournoi::recup($phase->get("tournoi_id")), $phase);
																				
																				if ($selecteur_regle)
																				{
																					$retour = $retour .
																						  "Ajouter une règle : "
																						. "<SELECT id=\"tournoi.phases.match." . $objet->get("id") . ".regle.ajoute"
																						. "\" onchange=\"ajouteRegleMatch(" . $objet->get("id") . ", '1');\">"
																						. $selecteur_regle
																						. "</SELECT><BR/>";
																				}
																			}
																		}
																		
																		return $retour;
																	}
												, function ($objet) {   $retour = " ";
																		if ($objet->get("resultat") > 0)
																		{
																			if ($objet->get("formation1_id") > 0)
																			{
																				$formation1 = Formation::recup($objet->get("formation1_id"));
																				$equipe1 = Equipe::recup($formation1->get("equipe_id"));
																				$retour = $retour ."<B><FONT color=\"#" . $equipe1->get("couleur_base") . "\">" . $objet->get("score1") . "</FONT></B>";
																			}
																			$retour = $retour . " - ";
																			if ($objet->get("formation2_id") > 0)
																			{
																				$formation2 = Formation::recup($objet->get("formation2_id"));
																				$equipe2 = Equipe::recup($formation2->get("equipe_id"));
																				$retour = $retour ."<B><FONT color=\"#" . $equipe2->get("couleur_base") . "\">" . $objet->get("score2") . "</FONT></B>";
																			}
																		}
																		else
																		{
																			$retour = "A jouer";
																		}
																		return $retour;
																		
																	}
												, function ($objet) { 	global $phase, $phase_tableau, $phases, $utilisateur_en_cours;
																		$retour = "";
																		if ($objet->get("formation2_id") > 0)
																		{
																			$formation = Formation::recup($objet->get("formation2_id"));
																			$equipe = Equipe::recup($formation->get("equipe_id"));
																			$retour = "<A href=\"equipe.php?id=" . $equipe->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B></A>";
																		}
																		else if ($objet->get("regle_formation2") != "")
																		{
																			$retour = $retour . Regle::versString($objet->get("regle_formation2"));
																			
																			if ($objet->get("resultat") == 0 && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
																			{
																				$retour = $retour . " <IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveRegleMatch(" . $objet->get("id") . ", '2');\" src=\"images/suppression.jpg\"></IMG>";
																			}
																		}
																		else
																		{
																			if ($phase_tableau->get("etat") <= 1 && isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
																			{
																				$selecteur_regle = Regle::recupSelecteurRegle(Tournoi::recup($phase->get("tournoi_id")), $phase);
																				
																				if ($selecteur_regle)
																				{
																					$retour =
																						  "Ajouter une règle : "
																						. "<SELECT id=\"tournoi.phases.match." . $objet->get("id") . ".regle.ajoute"
																						. "\" onchange=\"ajouteRegleMatch(" . $objet->get("id") . ", '2');\">"
																						. $selecteur_regle
																						. "</SELECT><BR/>";
																				}
																			}
																		}
																		
																		return $retour;
																	}
												, function ($objet) {
																		global $utilisateur_en_cours;
																		$retour = "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheMatch(" . $objet->get("id") . ");\" src=\"images/validation.jpg\"></IMG>";
																				
																		if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
																		{
																			$retour = $retour
																				. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieMatch(" . $objet->get("id") . ");\" src=\"images/modification.jpg\"></IMG>"
																				. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeMatch(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
																		}
																		
																		return $retour;
																	}
											)
										  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");
				}
				else
				{
					if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
					{
						print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.poule.suppression.phase\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimePhase(" . $phase->get("id") . ");\">Supprimer cette phase</DIV>");	
					}
				}
				
				if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
				{															
					print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.tableau.creation.match\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeMatch(" . $phase_tableau->get("id") . ");\">Création d'un nouveau match pour cette phase</DIV>");	
					
					print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.phase.resolution.regles\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"resoutReglesTableau(" . $phase_tableau->get("id") . ");\">Résolution des régles</DIV>");	
				}
			}
				
					
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{	
				print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.modification.phase\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifiePhase(" . $phase->get("id") . ");\">Modification de la phase</DIV>");	
			}
			
			print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.phase.feuilles_matchs.pdf\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"fichesDeMatchsPdf(" . $phase->get("id") . ");\">Imprimer les feuilles de match</DIV>");	
			
			print ("</DIV>");
			print ("<BR/>");
		}
	}
	else
	{
		print ("Aucune phase n'a été créée pour ce tournoi");
	}
	
	if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
	{	
		print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.creation.phase\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creePhase();\">Création d'une nouvelle phase pour ce tournoi</DIV>");	
	}
}

?>
	