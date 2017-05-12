<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/PhaseTableau.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{		
	$valide = true;

	if (!isset($_POST["libelle"]) || $_POST["libelle"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un libelle pour le match</DIV>");
		$valide = false;
	}

	if (!isset($_POST["date"]) || !preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/i", $_POST["date"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifiez une date correcte (jour et mois sur 1 ou 2 chiffre(s), année sur 4 chiffres</DIV>");
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

	if (!isset($_POST["id"]))
	{
		if (!isset($_POST["equipe1Id"]) || !isset($_POST["equipe2Id"]))
		{
			print ("<DIV class=\"messageErreur\">Veuillez choisir deux équipes dans les listes</DIV>");
			$valide = false;
		}
		else if ($_POST["equipe1Id"] == $_POST["equipe2Id"])
		{
			print ("<DIV class=\"messageErreur\">Veuillez choisir deux équipes différentes</DIV>");
			$valide = false;
		}
		
		if (!isset($_POST["formation1"]) || !isset($_POST["formation2"]))
		{
			print ("<DIV class=\"messageErreur\">Veuillez choisir les méthodes de création des formations pour les 2 équipes</DIV>");
			$valide = false;
		}
		
		if (isset($_POST["pouleId"]))
		{
			$poule = Poule::recup($_POST["pouleId"]);
			$phase_poules = PhasePoules::recup($poule->get("phase_poules_id"));
			$nb_periode_match = $phase_poules->get("nb_periode_match");
			$duree_periode_match = $phase_poules->get("duree_periode_match");
			$phase_id = $phase_poules->get("phase_id");
		}
		else if (isset($_POST["phaseTableauId"]))
		{
			$phase_tableau = PhaseTableau::recup($_POST["phaseTableauId"]);
			$phase_id = $phase_tableau->get("phase_id");
			
			if (!isset($_POST["nb_periode_match"]) || !isset($_POST["nb_periode_match"]))
			{
				print ("<DIV class=\"messageErreur\">Veuillez choisir le nombre de périodes du match</DIV>");
				$valide = false;
			}
			else
			{
				$nb_periode_match = $_POST["nb_periode_match"];
			}
			
			if (!isset($_POST["duree_periode_match_minutes"]) || !isset($_POST["duree_periode_match_minutes"]) || !isset($_POST["duree_periode_match_secondes"]) || !isset($_POST["duree_periode_match_secondes"]))
			{
				print ("<DIV class=\"messageErreur\">Veuillez choisir la durée du match</DIV>");
				$valide = false;
			}
			else
			{
				$duree_periode_match = ($_POST["duree_periode_match_minutes"] * 60) + $_POST["duree_periode_match_secondes"];
			}
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Erreur : Le match n'est lié à aucune phase d'un tournoi : Contactez votre administrateur</DIV>");
			$valide = false;
		}
	}

	if ($valide)
	{
		if (isset($_POST["id"]))
		{
			$match = Match::recup($_POST["id"]);
			$match->set("libelle", $_POST["libelle"]);
			$match->set("date", $_POST["date"]);
			$resultat = $match->enregistre();
		}
		else
		{
			$message_erreur = "";
			
			$match = new Match();
			$match->set("libelle", $_POST["libelle"]);
			$match->set("date", $_POST["date"]);
			$match->set("phase_id", $phase_id);
			$match->set("formation1_id", -1);
			$match->set("formation2_id", -1);
			$match->set("regle_formation1", "");
			$match->set("regle_formation2", "");
			$match->set("arbitre1_id", -1);
			$match->set("arbitre2_id", -1);
			$match->set("resultat", $MATCH_RESULTAT_AJOUER);
			$match->set("niveau_stats", 0);
			if (!$match->cree())
			{
				$message_erreur = $message_erreur . "Erreur de création du match<BR/>";
			} 
			
			if ($poule)
			{
				if (!$poule->ajouteMatch($match->get("id")))
				{
					$message_erreur = $message_erreur . "Erreur de liaison du match avec la poule<BR/>";
				}
			}
			
			if ($message_erreur == "")
			{
				$formation1 = new Formation();
				$formation1->set("equipe_id", $_POST["equipe1Id"]);
				$formation1->set("match_id", $match->get("id"));
				if (!$formation1->cree())
				{
					$message_erreur = $message_erreur . "Erreur de création de la formation 1<BR/>";
				}
				else if ($_POST["formation1"] == 1)
				{
					$formation1->dupliquePlusRecente($_POST["equipe1Id"]);
				}
			}
			
			if ($message_erreur == "")
			{
				$formation2 = new Formation();
				$formation2->set("equipe_id", $_POST["equipe2Id"]);
				$formation2->set("match_id", $match->get("id"));
				if (!$formation2->cree())
				{
					$message_erreur = $message_erreur . "Erreur de création de la formation 2<BR/>";
				} 
				else if ($_POST["formation2"] == 1)
				{
					$formation2->dupliquePlusRecente($_POST["equipe2Id"]);
				}
			}
			
			if ($message_erreur == "")
			{
				$match->set("formation1_id", $formation1->get("id"));
				$match->set("formation2_id", $formation2->get("id"));
				$match->enregistre();
				
				for ($index_temporel = 1; $index_temporel <= $nb_periode_match; $index_temporel++)
				{
					$temps_de_jeu = new TempsDeJeu();
					$temps_de_jeu->set("match_id", $match->get("id"));
					$temps_de_jeu->set("ordre_temporel", $index_temporel);
					$temps_de_jeu->set("duree", $duree_periode_match);
					$temps_de_jeu->set("libelle", TempsDeJeu::getLibellePourNbPeriode($nb_periode_match, $index_temporel));
					$temps_de_jeu->set("temps_restant", $duree_periode_match);
					$temps_de_jeu->set("nb_faute_equipe", Faute::getNbFautesEquipePourDuree($duree_periode_match));
					if (!$temps_de_jeu->cree())
					{
						$message_erreur = $message_erreur . "Erreur de création du temps de jeu " . $index_temporel . "<BR/>";
					} 
				}
			}
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
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour créer un match</DIV>");
}