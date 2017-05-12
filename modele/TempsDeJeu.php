<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Action.php');
include_once ($RACINE . 'modele/Faute.php');
	
/*
  Représente un temps de jeu (quart-temps, mi-temps) lors d'un match
*/
class TempsDeJeu extends Objet
{
	const TYPE 		= "TempsDeJeu";
	const TABLE 	= "monobasket_temps_de_jeu";
	const CHAMPS 	= "match_id,ordre_temporel,libelle,duree,temps_restant,nb_faute_equipe";
	
		
	// Récupère le score de la formation au temps de jeu
	public function recupScoreFormation($formation_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_SHOOT;

			$requete = "SELECT SUM(s.type) AS score"
					 . " FROM " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Action::TABLE . " a,"
					 . "      " . Shoot::TABLE . " s"
					 . " WHERE fj.formation_id = '" . $formation_id . "'"
					 . " AND a.temps_de_jeu_id = '" . $this->get("id") . "'"
					 . " AND a.type = '" . $ACTION_TYPE_SHOOT . "'"
					 . " AND s.reussi = true"
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
	
	// Récupère le nombre de fautes de la formation au temps de jeu
	public function recupNbFautesFormation($formation_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_FAUTE;

			$requete = "SELECT COUNT(*) AS nb_fautes"
					 . " FROM " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Action::TABLE . " a,"
					 . "      " . Faute::TABLE . " s"
					 . " WHERE fj.formation_id = '" . $formation_id . "'"
					 . " AND a.temps_de_jeu_id = '" . $this->get("id") . "'"
					 . " AND a.type = '" . $ACTION_TYPE_FAUTE . "'"
					 . " AND a.joueur_acteur_id = fj.joueur_id"
					 . " AND a.specifique_id = s.id";	
			
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
	
	// Retourne un nom de temps de jeu par rapport au nombre de temps de jeu du match
	public static function getLibellePourNbPeriode($nb_periodes, $index)
	{
		global $PERIODES_DESC;
		
		foreach ($PERIODES_DESC as $periodes => $desc)
		{
			if ($nb_periodes == $periodes)
			{
				return $desc . " " . $index;
			}
		}
		
		return end($PERIODES_DESC) . " " . $index;
	}
	
	// Récupère l'ordre temporel de temps de jeu suivant pour un match donné
	public function prochainOrdreTemporel($match_id)
	{
		global $BDD;

		$requete = "SELECT MAX(tdj.ordre_temporel) + 1 AS ordre_temporel"
				 . " FROM " . TempsDeJeu::TABLE . " tdj"
				 . " WHERE tdj.match_id = '" . $match_id . "'";	
		
		$resultat = $BDD->requeteMonoResultat($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			return $resultat["ordre_temporel"];
		}
	}
}

?>