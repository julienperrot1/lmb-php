<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhaseTableau.php');
include_once ($RACINE . 'modele/Match.php');

$valide = true;

if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{	
	if (!isset($_POST["tournoiId"]) || !is_numeric($_POST["tournoiId"]))
	{
		print ("<DIV class=\"messageErreur\">Aucun identifiant de tournoi n'a été trouvé</DIV>");
		$valide = false;
	}

	if (!isset($_POST["libelle"]) || $_POST["libelle"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un libelle pour la phase</DIV>");
		$valide = false;
	}

	if (!isset($_POST["date"]) || !preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/i", $_POST["date"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier une date correcte (jouer et mois sur 1 ou 2 chiffre(s), année sur 4 chiffres</DIV>");
		$valide = false;
	}
	else
	{	
		list($annee, $mois, $jour) = explode('-', $_POST["date"]);
		if (! checkdate($mois, $jour, $annee))
		{
			print ("<DIV class=\"messageErreur\">La date choisie n'existe pas</DIV>");
			$valide = false;
		}
	}

	if (!isset($_POST["type"]) || !is_numeric($_POST["type"]) || $_POST["type"] < 1 || $_POST["type"] > 3)
	{
		print ("<DIV class=\"messageErreur\">Aucun type n'a été selectionné</DIV>");
		$valide = false;
	}

	if ($_POST["type"] == 1)
	{
		if (!isset($_POST["nb_poules"]) || !is_numeric($_POST["nb_poules"]) || $_POST["nb_poules"] < 0)
		{
			print ("<DIV class=\"messageErreur\">Veuillez spécifier un nombre de poule correct (nombre positif)</DIV>");
			$valide = false;
		}
		
		if (!isset($_POST["nb_periode_match"]) || !is_numeric($_POST["nb_periode_match"]) || $_POST["nb_periode_match"] < 0)
		{
			print ("<DIV class=\"messageErreur\">Veuillez spécifier un nombre de périodes correct (nombre positif)</DIV>");
			$valide = false;
		}
		
		if (!isset($_POST["duree_periode_match_minutes"]) || !is_numeric($_POST["duree_periode_match_minutes"]) || $_POST["duree_periode_match_minutes"] < 0 || !isset($_POST["duree_periode_match_secondes"]) || !is_numeric($_POST["duree_periode_match_secondes"]) || $_POST["duree_periode_match_secondes"] < 0 || $_POST["duree_periode_match_secondes"] > 59)
		{
			print ("<DIV class=\"messageErreur\">Veuillez spécifier une durée correcte pour les périodes</DIV>");
			$valide = false;
		}
	}

	if ($_POST["type"] == 2)
	{

		if (!isset($_POST["etat"]) || !is_numeric($_POST["etat"]) || $_POST["etat"] < 0)
		{
			print ("<DIV class=\"messageErreur\">Veuillez spécifier un état correct</DIV>");
			$valide = false;
		}
		
		if (!isset($_POST["id"]) && (!isset($_POST["matchs"]) || !is_numeric($_POST["matchs"]) || $_POST["matchs"] < 1 || $_POST["matchs"] > 3))
		{
			print ("<DIV class=\"messageErreur\">Le type de construction du tableau n'a pas été correctement spécifié</DIV>");
			$valide = false;
		}
		
		if (!isset($_POST["id"]) && (!isset($_POST["nb_equipes"]) || !is_numeric($_POST["nb_equipes"]) || $_POST["nb_equipes"] < 2))
		{
			print ("<DIV class=\"messageErreur\">Le nombre d'équipes est incorrect (doit être au moins de 2 équipes)</DIV>");
			$valide = false;
		}
		
		if (!isset($_POST["id"]) && ($valide && $_POST["matchs"] == 3 && floor(-log($_POST["nb_equipes"], 0.5)) != -log($_POST["nb_equipes"], 0.5)))
		{
			print ("<DIV class=\"messageErreur\">Impossible de construire un tableau complet si celui-ci n'est pas équilibré (le nmbre d'équipes doit être une puissance de 2</DIV>");
			$valide = false;
		}
	}

	if ($valide)
	{
		if (isset($_POST["id"]))
		{
			$phase = Phase::recup($_POST["id"]);
			$phase->set("libelle", $_POST["libelle"]);
			$phase->set("date", $_POST["date"]);
			$resultat = $phase->enregistre();
		}
		else
		{
			$phase = new Phase();
			$phase->set("tournoi_id", $_POST["tournoiId"]);
			$phase->set("libelle", $_POST["libelle"]);
			$phase->set("date", $_POST["date"]);
			$phase->set("ordre_temporel", Phase::prochainOrdreTemporel($_POST["tournoiId"]));
			$phase->set("type", $_POST["type"]);
			$phase->set("specifique_id", -1);
			$resultat = $phase->cree();
		}
		
		if ($resultat)
		{
			if ($_POST["type"] == 1)
			{
				if (isset($_POST["id"]))
				{
					$phase_poules = PhasePoules::recup($phase->get("specifique_id"));
					$phase_poules->set("nb_periode_match", $_POST["nb_periode_match"]);
					$phase_poules->set("duree_periode_match", ($_POST["duree_periode_match_minutes"] * 60) + $_POST["duree_periode_match_secondes"]);
					$resultat = $phase_poules->enregistre();
				}
				else
				{
					$phase_poules = new PhasePoules();
					$phase_poules->set("phase_id", $phase->get("id"));
					$phase_poules->set("nb_poules", $_POST["nb_poules"]);
					$phase_poules->set("nb_periode_match", $_POST["nb_periode_match"]);
					$phase_poules->set("duree_periode_match", ($_POST["duree_periode_match_minutes"] * 60) + $_POST["duree_periode_match_secondes"]);
					$resultat = $phase_poules->cree();
					
					if ($resultat)
					{
						$phase->set("specifique_id", $phase_poules->get("id"));
						$resultat = $phase->enregistre();
					}
					
					if ($resultat)
					{
						for ($index_poule = 1; $index_poule <= $phase_poules->get("nb_poules"); $index_poule++)
						{
							$poule = new Poule();
							$poule->set("libelle", "Poule " . $index_poule);
							$poule->set("phase_poules_id", $phase_poules->get("id"));
							$poule->set("etat", 1);
							$poule->set("points_victoire", $POINTS_VICTOIRE_DEFAUT);
							$poule->set("points_defaite", $POINTS_DEFAITE_DEFAUT);
							$poule->set("points_nul", $POINTS_NUL_DEFAUT);
							$poule->set("goal_average_ecart_max", $GOAL_AVERAGE_ECART_MAX_DEFAUT);
							$resultat = $poule->cree();
							
							if (!$resultat)
							{
								print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de la création de la poule " . $index_poule . "</DIV>");
							}
						}
					}
				}
			}
			else if ($_POST["type"] == 2)
			{
				if (isset($_POST["id"]))
				{
					$phase_tableau = PhaseTableau::recup($phase->get("specifique_id"));
					$phase_tableau->set("etat", $_POST["etat"]);
					$resultat = $phase_tableau->enregistre();
				}
				else
				{
					$phase_tableau = new PhaseTableau();
					$phase_tableau->set("phase_id", $phase->get("id"));
					$phase_tableau->set("etat", $_POST["etat"]);
					$resultat = $phase_tableau->cree();
					
					if ($resultat)
					{
						$phase->set("specifique_id", $phase_tableau->get("id"));
						$resultat = $phase->enregistre();
					}
					
					if ($resultat)
					{
						print ("<DIV class=\"messageErreur\">");
						$nb_niveau_complet = floor(-log($_POST["nb_equipes"], 0.5));
						print $nb_niveau_complet;
						print ("<BR/>");
						
						$nb_matchs_niveau = $_POST["nb_equipes"] - pow(2, $nb_niveau_complet);
						$nb_matchs_niveau_precedent = 0;
						$tab_matchs_niveau_precedent = array();
						
						print $nb_matchs_niveau;
						print ("<BR/>");
						
						for ( $niveau = $nb_niveau_complet + 1; $niveau >= 1; $niveau-- )
						{
							$tab_matchs_niveau = array();
							for ( $match_index = 1; $match_index <= $nb_matchs_niveau; $match_index++ )
							{
								$match_libelle = $MATCHS_TABLEAU_DESC[$niveau];
								if ($niveau > 1)
								{
									$match_libelle = $match_libelle . " " 
												   . ($match_index - (floor(($match_index - 1) / pow(2, $niveau - 1)) * pow(2, $niveau - 1)));
												   
									if ($match_index > pow(2, $niveau - 1))
									{
										$match_libelle = $match_libelle . " "
													   . " des places "
													   . ((floor(($match_index - 1) / pow(2, $niveau - 1)) * pow(2, $niveau)) + 1)
													   . " à "
													   . (((floor(($match_index - 1) / pow(2, $niveau - 1)) + 1) * pow(2, $niveau)));
									}			   
								}
								else
								{
									if ($match_index > 1)
									{
										$match_libelle = $match_libelle . " "
													   . " pour la place "
													   . ((floor(($match_index - 1) / pow(2, $niveau - 1)) * pow(2, $niveau)) + 1);
									}
								}
								
								$regle_equipe1 = "";
								$regle_equipe2 = "";
								if (ceil($nb_matchs_niveau_precedent / 2) >= $match_index
								 || ($nb_matchs_niveau_precedent >= $match_index && $_POST["matchs"] == 3))
								{
									$base = ((floor(($match_index - 1) / pow(2, $niveau))) * pow(2, $niveau - 1));
									$limite = ((floor(($match_index - 1) / pow(2, $niveau)) + 1) * pow(2, $niveau - 1))
											 + (floor(($match_index - 1) / pow(2, $niveau)) * pow(2, $niveau - 1));

									if ($match_index <= $limite)
									{
										$regle_equipe1 = $phase->get("id") . "-" . $tab_matchs_niveau_precedent[(($match_index - $base) * 2) - 2] . "-G";
										$regle_equipe2 = $phase->get("id") . "-" . $tab_matchs_niveau_precedent[(($match_index - $base) * 2) - 1] . "-G";
									}
									else
									{
										$regle_equipe1 = $phase->get("id") . "-" . $tab_matchs_niveau_precedent[(($match_index - ($limite - $base)) * 2) - 2] . "-P";
										$regle_equipe2 = $phase->get("id") . "-" . $tab_matchs_niveau_precedent[(($match_index - ($limite - $base)) * 2) - 1] . "-P";
									}
								}
								
								$resultat = ajouteMatch(	$match_libelle,
															$_POST["nb_periode_match_" . $niveau],
															($_POST["duree_periode_match_minutes_" . $niveau] * 60) + $_POST["duree_periode_match_secondes_" . $niveau],
															$regle_equipe1,
															$regle_equipe2);
															
								if (!$resultat)
								{
									break;
								}
							}
							
							if ($_POST["matchs"] == 2 && $niveau == 1 && $nb_niveau_complet >= 2 && $resultat)
							{
								$resultat = ajouteMatch(	"Petite finale",
															$_POST["nb_periode_match_6"],
															($_POST["duree_periode_match_minutes_6"] * 60) + $_POST["duree_periode_match_secondes_6"],
															$phase->get("id") . "-" . $tab_matchs_niveau_precedent[0] . "-P",
															$phase->get("id") . "-" . $tab_matchs_niveau_precedent[1] . "-P");
							}
							
							$nb_matchs_niveau_precedent = $nb_matchs_niveau;
							$tab_matchs_niveau_precedent = $tab_matchs_niveau;
							
							if ($_POST["matchs"] == 3)
							{
								$nb_matchs_niveau = pow(2, $nb_niveau_complet - 1);
							}
							else
							{
								$nb_matchs_niveau = pow(2, $niveau - 2);
							}
														
							if (!$resultat)
							{
								break;
							}
						}
						
						print ("</DIV>");
					}
				}
			}
			
			if ($resultat)
			{
				print ("#REDIRECT#");
			}
			else
			{
				print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de l'enregistrement de l'objet spécifique en base de données</DIV>");
			}
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de l'enregistrement de l'objet en base de données</DIV>");
		}
	}
}	
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour éditer une phase</DIV>");
}




function ajouteMatch($libelle, $nb_periode, $duree_periode, $regle_equipe1, $regle_equipe2)
{
	global $tab_matchs_niveau, $phase, $MATCH_RESULTAT_AJOUER;
	
	$match = new Match();
	$match->set("libelle", $libelle);
	$match->set("date", $_POST["date"]);
	$match->set("formation1_id", -1);
	$match->set("formation2_id", -1);
	$match->set("resultat", $MATCH_RESULTAT_AJOUER);
	$match->set("arbitre1_id", -1);
	$match->set("arbitre2_id", -1);
	$match->set("regle_formation1", $regle_equipe1);
	$match->set("regle_formation2", $regle_equipe2);
	$match->set("phase_id", $phase->get("id"));
	$match->set("niveau_stats", 0);
	if (!$match->cree())
	{
		print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de la création du match " . $libelle . "</DIV>");
		return false;
	} 		
	else
	{
		for ($index_temporel = 1; $index_temporel <= $nb_periode; $index_temporel++)
		{
			$temps_de_jeu = new TempsDeJeu();
			$temps_de_jeu->set("match_id", $match->get("id"));
			$temps_de_jeu->set("ordre_temporel", $index_temporel);
			$temps_de_jeu->set("duree", $duree_periode);
			$temps_de_jeu->set("libelle", TempsDeJeu::getLibellePourNbPeriode($nb_periode, $index_temporel));
			$temps_de_jeu->set("temps_restant", $duree_periode);
			$temps_de_jeu->set("nb_faute_equipe", Faute::getNbFautesEquipePourDuree($duree_periode));
			if (!$temps_de_jeu->cree())
			{
				print ("<DIV class=\"messageErreur\">Erreur de création du temps de jeu " . $index_temporel . " pour le match \"" . $libelle . "</DIV>");
				return false;
			} 
		}
	}
	
	$tab_matchs_niveau[] = $match->get("id");
	return true;
}