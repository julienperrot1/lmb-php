<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'modele/Match.php');
	
/*
  Représente une équipe formée pour un tournoi
*/
class Formation extends Objet
{
	const TYPE 		= "Formation";
	const TABLE 	= "monobasket_formation";
	const CHAMPS 	= "match_id,equipe_id";
	
	const TABLE_FORMATION_JOUEUR 	= "monobasket_formation_joueur";
	
	// Récupère les identifiants des joueurs participant à la formation
	public function getFormationJoueurs()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT *"
					 . " FROM " . $classe::TABLE_FORMATION_JOUEUR
					 . " WHERE formation_id = " . $this->get("id")
					 . " ORDER BY numero";
			
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
	
	// Récupère le numéro d'un joueur dans la formation
	public function getNumeroJoueur($joueur_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT numero"
					 . " FROM " . $classe::TABLE_FORMATION_JOUEUR
					 . " WHERE formation_id = " . $this->get("id")
					 . " AND joueur_id = " . $joueur_id;
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["numero"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère l'identifiant d'un joueur d'une formation à partir de son numéro
	public function getIdentifiantJoueur($numero)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT joueur_id"
					 . " FROM " . $classe::TABLE_FORMATION_JOUEUR
					 . " WHERE formation_id = " . $this->get("id")
					 . " AND numero = '" . $numero . "'";
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["joueur_id"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Ajoute un joueur à la formation
	public function ajouteJoueur($joueur_id, $numero)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "INSERT"
					 . " INTO " . $classe::TABLE_FORMATION_JOUEUR
					 . " (formation_id, joueur_id, numero)"
					 . " VALUES (" . $this->get("id") . ", " . $joueur_id . ", '" . $numero . "')";
			
			$resultat = $BDD->requeteInsertion($requete);
			return true;
		}
		else
		{
			return null;
		}
	}
	
	// Modifie le numéro d'un joueur dans la formation
	public function modifieNumeroJoueur($joueur_id, $numero)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "UPDATE " . $classe::TABLE_FORMATION_JOUEUR
					 . " SET numero = '" . $numero . "'"
					 . " WHERE formation_id = " . $this->get("id")
					 . " AND joueur_id = " . $joueur_id;
			
			$resultat = $BDD->requeteInsertion($requete);
			return true;
		}
		else
		{
			return null;
		}
	}
	
	// Enleve un joueur à la formation
	public function enleveJoueur($joueur_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$joueur = Joueur::recup($joueur_id);
			$match = Match::recup($this->get("match_id"));
			
			if ($joueur->nbActionsParMatch($match->get("id"), 0) == 0)
			{
				$requete = "DELETE"
						 . " FROM " . $classe::TABLE_FORMATION_JOUEUR
						 . " WHERE formation_id = '" . $this->get("id") . "'"
						 . " AND joueur_id = '" . $joueur->get("id") . "'";
				
				return $BDD->requeteSuppression($requete);
			}
			else
			{
				return 0;
			}
		}

		return null;
	}
	
	// Enleve tous les joueurs à la formation
	public function enleveJoueurs()
	{
		if ($this->_vientdelabase == true)
		{
			global $BDD;
			$classe = get_called_class();
			
			$requete = "DELETE"
					 . " FROM " . $classe::TABLE_FORMATION_JOUEUR
					 . " WHERE formation_id = '" . $this->get("id") . "'";
				
			return $BDD->requeteSuppression($requete);
		}

		return null;
	}
	
	public function dupliquePlusRecente($equipe_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT MAX(fj.formation_id) AS formation_id"
					 . " FROM " . $classe::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . $classe::TABLE . " f"
					 . " WHERE f.equipe_id = '" . $equipe_id . "'"
					 . " AND fj.formation_id = f.id"
					 . " AND fj.formation_id <> '" . $this->get("id") . "'";
			
			$resultat = $BDD->requeteMonoResultat($requete);
			
			if ($resultat && is_numeric($resultat["formation_id"]))
			{
				$formation_plus_recente = Formation::recup($resultat["formation_id"]);
				$formation_plus_recente_joueurs = $formation_plus_recente->getFormationJoueurs();
				
				if ($formation_plus_recente_joueurs)
				{
					foreach ($formation_plus_recente_joueurs as $formation_plus_recente_joueur)
					{
						$this->ajouteJoueur($formation_plus_recente_joueur["joueur_id"], $formation_plus_recente_joueur["numero"]);
					}
					
					return sizeof($formation_plus_recente_joueurs);
				}
				
				return 0;
			}
		}

		return null;
	}
}

?>