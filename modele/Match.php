<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Action.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Shoot.php');
include_once ($RACINE . 'modele/Faute.php');
include_once ($RACINE . 'modele/Poule.php');
	
/*
  Représente un match lors d'un tournoi de la ligue
*/
class Match extends Objet
{
	const TYPE 		= "Match";
	const TABLE 	= "monobasket_match";
	const CHAMPS 	= "libelle,date,formation1_id,formation2_id,regle_formation1,regle_formation2,resultat,score1,score2,arbitre1_id,arbitre2_id,phase_id,niveau_stats";
	
	
	// Récupère le score de la formation au match
	public function recupScoreFormation($formation_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_SHOOT;

			$requete = "SELECT SUM(s.type) AS score"
					 . " FROM " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Shoot::TABLE . " s"
					 . " WHERE fj.formation_id = '" . $formation_id . "'"
					 . " AND tdj.match_id = '" . $this->get("id") . "'"
					 . " AND a.type = '" . $ACTION_TYPE_SHOOT . "'"
					 . " AND s.reussi = true"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND a.joueur_acteur_id = fj.joueur_id"
					 . " AND a.specifique_id = s.id";	
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat || $resultat["score"] == "")
			{
				return 0;
			}
			else
			{
				return $resultat["score"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de fautes du joueur pour le match
	public function recupNbFautesJoueur($joueur_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_FAUTE;

			$requete = "SELECT COUNT(*) AS nb_fautes"
					 . " FROM " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Action::TABLE . " a"
					 . " WHERE tdj.match_id = '" . $this->get("id") . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND a.type = '" . $ACTION_TYPE_FAUTE . "'"
					 . " AND a.joueur_acteur_id = '" . $joueur_id . "'";	
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nb_fautes"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère la poule à laquelle est liée le match, s'il y en a une
	public function getPoule()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT pm.poule_id AS poule_id"
					 . " FROM " . Poule::TABLE_POULE_MATCH . " pm"
					 . " WHERE pm.match_id = '" . $this->get("id") . "'";
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return Poule::recup($resultat["poule_id"]);
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le détail des scores, pour une formation, avec le numéro du joueur et le temps de jeu concerné
	public function recupDetailScore($formation_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_SHOOT;
		
			$requete = "SELECT tdj.ordre_temporel AS temps_de_jeu,"
					 . "       fj.numero AS numero,"
					 . "       s.type AS points"
					 . " FROM " . Shoot::TABLE . " s,"
					 . "      " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Formation::TABLE_FORMATION_JOUEUR . " fj"
					 . " WHERE s.id = a.specifique_id"
					 . " AND a.type = '" . $ACTION_TYPE_SHOOT . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND tdj.match_id = '" . $this->get("id") . "'"
					 . " AND s.reussi = '1'"
					 . " AND fj.joueur_id = a.joueur_acteur_id"
					 . " AND fj.formation_id = '" . $formation_id . "'"
					 . " ORDER BY tdj.ordre_temporel ASC,"
					 . "          a.temps ASC";
				
			$resultat = $BDD->requeteMultiResultats($requete);
		
			$return_values = array();
			$score = 0;
			if ($resultat)
			{
				foreach($resultat as $ligne)
				{
					$score = $score + $ligne["points"];
					$return_values[$score]["temps_de_jeu"] = $ligne["temps_de_jeu"];
					$return_values[$score]["numero_joueur"] = $ligne["numero"];
				}
			}
			
			return $return_values;
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le détail des fautes, pour un joueur, avec le type de faute et le temps de jeu concerné
	public function recupDetailFautes($joueur_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_FAUTE;
		
			$requete = "SELECT tdj.ordre_temporel AS temps_de_jeu,"
					 . "       f.type AS type_faute"
					 . " FROM " . Faute::TABLE . " f,"
					 . "      " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj"
					 . " WHERE f.id = a.specifique_id"
					 . " AND a.type = '" . $ACTION_TYPE_FAUTE . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND tdj.match_id = '" . $this->get("id") . "'"
					 . " AND a.joueur_acteur_id = '" . $joueur_id . "'"
					 . " ORDER BY tdj.ordre_temporel ASC,"
					 . "          a.temps ASC";
				
			$resultat = $BDD->requeteMultiResultats($requete);
		
			$return_values = array();
			if ($resultat)
			{
				foreach($resultat as $ligne)
				{
					$return_values[] = array(	"temps_de_jeu"	=>	$ligne["temps_de_jeu"],
												"type" 			=> 	$ligne["type_faute"]);
				}
			}
			
			return $return_values;
		}
		else
		{
			return null;
		}
	}
	
	// Récupère la durée totale du match
	public function recupDuree()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
		
			$requete = "SELECT SUM(tdj.duree) AS duree"
					 . " FROM " . TempsDeJeu::TABLE . " tdj"
					 . " WHERE tdj.match_id = '" . $this->get("id") . "'";
				
			$resultat = $BDD->requeteMonoResultat($requete);
		
			if ($resultat)
			{
				return $resultat["duree"];
			}
			
			return null;
		}
		else
		{
			return null;
		}
	}
}

?>