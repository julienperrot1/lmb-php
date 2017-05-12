<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Formation.php');
	
/*
  Représente une poule lors d'une phase de type "poules"
*/
class Poule extends Objet
{
	const TYPE 		= "Poule";
	const TABLE 	= "monobasket_poule";
	const CHAMPS 	= "libelle,phase_poules_id,etat,points_victoire,points_defaite,points_nul,goal_average_ecart_max";		

	const TABLE_POULE_EQUIPE 	= "monobasket_poule_equipe";
	const TABLE_POULE_MATCH 	= "monobasket_poule_match";
	const TABLE_DEPARTAGE 	    = "monobasket_departage";
	
	
	// Récupère le nombre d'équipes inscrites à la poule
	public function getNbEquipes()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT COUNT(*) AS nb_equipes"
					 . " FROM " . $classe::TABLE_POULE_EQUIPE . " pe"
					 . " WHERE pe.poule_id = '" . $this->get("id") . "'";
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nb_equipes"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de match de la poule
	// $match_resultat :
	//   -1 	: Tous les matchs
	//   1  	: Matchs en attente
	//   2  	: Matchs joués
	public function getNbMatchs($match_resultat = -1, $equipe_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER;

			$requete = "SELECT COUNT(*) AS nb_matchs"
					 . " FROM " . Match::TABLE . " m,"
					 . "      " . Poule::TABLE_POULE_MATCH . " pm";
			if ($equipe_id)
			{
				$requete = $requete . ","
					 . "      " . Formation::TABLE . " f";
			}
			
			$requete = $requete
					 . " WHERE pm.poule_id = '" . $this->get("id") . "'"
					 . " AND pm.match_id = m.id";		 
			if ($equipe_id)
			{
				$requete = $requete
					 . " AND (f.id = m.formation1_id OR f.id = m.formation2_id)"
					 . " AND f.equipe_id = '" . $equipe_id . "'";
			}
			
			if ($match_resultat == 1)
			{	
				$requete = $requete
						 . " AND m.resultat = '" . $MATCH_RESULTAT_AJOUER . "'";
			}
			else if ($match_resultat == 2)
			{	
				$requete = $requete
						 . " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nb_matchs"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère les identifiants des équipes participant à la poule
	public function getEquipes()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT *"
					 . " FROM " . $classe::TABLE_POULE_EQUIPE
					 . " WHERE poule_id = " . $this->get("id")
					 . " ORDER BY poule_id ASC, equipe_id ASC, regle ASC";
			
			$resultat = $BDD->requeteMultiResultats($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				$return_values = array();
				foreach($resultat as $ligne)
				{
					$return_values[] = $ligne;
				}
				
				return $return_values;
			}
		}
		else
		{
			return null;
		}
	}

	// Ajoute une équipe à la poule
	public function ajouteEquipe($equipe_id, $regle = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "INSERT"
					 . " INTO " . $classe::TABLE_POULE_EQUIPE
					 . " (poule_id, equipe_id, regle)"
					 . " VALUES (" . $this->get("id") . ", " . $equipe_id . ", '" . $regle . "')";
			
			$resultat = $BDD->requeteInsertion($requete);
			return true;
		}
		else
		{
			return null;
		}
	}
	
	// Enleve une équipe de la poule
	public function enleveEquipe($equipe_id, $regle = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$equipe = Equipe::recup($equipe_id);
			
			if ($this->getNbMatchs(-1, $equipe_id) == 0)
			{
				$requete = "DELETE"
						 . " FROM " . $classe::TABLE_POULE_EQUIPE
						 . " WHERE poule_id = '" . $this->get("id") . "'"
						 . " AND equipe_id = '" . $equipe_id . "'";
				
				if ($regle != null)
				{
					$requete = $requete
						 . " AND regle = '" . $regle . "'";
				}
				
				return $BDD->requeteSuppression($requete);
			}
			else
			{
				return 0;
			}
		}

		return null;
	}
	
	// Récupère les identifiants des matchs de la poule
	public function getMatchs()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT *"
					 . " FROM " . $classe::TABLE_POULE_MATCH
					 . " WHERE poule_id = " . $this->get("id");
			
			$resultat = $BDD->requeteMultiResultats($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat;
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère un match de poule à partir des 2 équipes
	public function getMatch($equipe1_id, $equipe2_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT m.id AS match_id"
					 . " FROM " . $classe::TABLE_POULE_MATCH . " pm,"
					 . "      " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE . " f1,"
					 . "      " . Formation::TABLE . " f2"
					 . " WHERE pm.poule_id = " . $this->get("id")
					 . " AND pm.match_id = m.id"
					 . " AND m.formation1_id = f1.id"
					 . " AND m.formation2_id = f2.id"
					 . " AND ((f1.equipe_id = '" . $equipe1_id . "'"
					 . "   AND f2.equipe_id = '" . $equipe2_id . "')"
					 . "  OR (f1.equipe_id = '" . $equipe2_id . "'"
					 . "   AND f2.equipe_id = '" . $equipe1_id . "'))";
			
			$resultat = $BDD->requeteMultiResultats($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				if (sizeof($resultat) > 1)
				{
					return 0;
				}
				
				return (Match::recup($resultat[0]["match_id"]));
			}
		}
		else
		{
			return null;
		}
	}
	
	// Ajoute une équipe à la poule
	public function ajouteMatch($match_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "INSERT"
					 . " INTO " . $classe::TABLE_POULE_MATCH
					 . " (poule_id, match_id)"
					 . " VALUES (" . $this->get("id") . ", " . $match_id . ")";
			
			$resultat = $BDD->requeteInsertion($requete);
			return true;
		}
		else
		{
			return null;
		}
	}
	
	// Enleve un match de la poule
	public function enleveMatch($match_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "DELETE"
					 . " FROM " . $classe::TABLE_POULE_MATCH
					 . " WHERE poule_id = '" . $this->get("id") . "'"
					 . " AND match_id = '" . $match_id . "'";
				
			return $BDD->requeteSuppression($requete);
		}

		return null;
	}
	
	// Récupère le classement actuel de la poule
	public function getClassement()
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER, $MATCH_RESULTAT_EQUIPE1, $MATCH_RESULTAT_EQUIPE2, $MATCH_RESULTAT_NUL;
			
			$return_values = array();
			$poule_equipes = $this->getEquipes();
			
			if (!$poule_equipes)
			{
				return null;
			}

			foreach($poule_equipes as $poule_equipe)
			{
				$equipe = Equipe::recup($poule_equipe["equipe_id"]);
				$return_values[] = array(	"classement" 		=> 	-1,
											"equipe_id" 		=> 	$equipe->get("id"),
											"matchs_joues" 		=> 	$equipe->recupNbMatchs(0, null, null, $this),
											"matchs_gagnes" 	=> 	$equipe->recupNbMatchs(1, null, null, $this),
											"matchs_perdus" 	=> 	$equipe->recupNbMatchs(2, null, null, $this),
											"matchs_nuls" 		=> 	$equipe->recupNbMatchs(3, null, null, $this),
											"points" 			=> 	$equipe->nbPointsPoule($this),
											"goal_average" 		=> 	$equipe->goalAveragePoule($this),
											"departage" 		=> 	$equipe->recupDepartagePoule($this)
				);
			}
			
			usort($return_values, "Poule::trieClassement");
			
			$place = 0;
			$points_precedent = -1;
			$goal_average_precedent = -9999;
			$nb_egalite = 0;
			for($i = 0; $i < sizeof($return_values); $i++)
			{		
				if ($i != 0 && Poule::trieClassement($return_values[$i], $return_values[$i - 1]) == 0)
				{
					$nb_egalite = $nb_egalite + 1;
				}
				else
				{
					$place = $place + $nb_egalite + 1;
					$nb_egalite = 0;
				}
				$points_precedent = $return_values[$i]["points"];
				$goal_average_precedent = $return_values[$i]["goal_average"];
				
				$return_values[$i]["classement"] = $place;
			}

			return $return_values;
		}
		else
		{
			return null;
		}
	}
	
	// Fonction de tri du classement
	public static function trieClassement($obj1, $obj2)
	{
		if ($obj1["points"] > $obj2["points"])
		{
			return -1;
		}
		else if ($obj1["points"] == $obj2["points"])
		{
			if ($obj1["goal_average"] > $obj2["goal_average"])
			{
				return -1;
			}
			else if ($obj1["goal_average"] == $obj2["goal_average"])
			{
				if (!$obj1["departage"])
				{
					if (!$obj2["departage"] || $obj2["departage"] == 0)
					{
						return 0;
					}
					else
					{
						return 1;
					}
				}
				
				if (!$obj2["departage"])
				{
					if (!$obj1["departage"] || $obj1["departage"] == 0)
					{
						return 0;
					}
					else
					{
						return -1;
					}
				}
				
				if ($obj1["departage"] > $obj2["departage"])
				{
					return -1;
				}
				else if ($obj1["departage"] == $obj2["departage"])
				{
					return 0;
				}
				else
				{
					return 1;
				}
			}
			else
			{
				return 1;
			}
		}
		else
		{
			return 1;
		}
	}
}

?>