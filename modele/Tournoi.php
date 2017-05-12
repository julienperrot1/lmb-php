<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Equipe.php');
	
/*
  Représente un tournoi d'une ligue
*/
class Tournoi extends Objet
{
	const TYPE 		= "Tournoi";
	const TABLE 	= "monobasket_tournoi";
	const CHAMPS 	= "libelle,lieu,nb_equipe_max,ligue_id";
		
	const TABLE_TOURNOI_EQUIPE 	= "monobasket_tournoi_equipe";
	
	
	// Récupère les identifiants des équipes participant au tournoi
	public function getEquipes()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "SELECT *"
					 . " FROM " . $classe::TABLE_TOURNOI_EQUIPE
					 . " WHERE tournoi_id = " . $this->get("id");
			
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
	
	// Ajoute une équipe au tournoi
	public function ajouteEquipe($equipe_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$requete = "INSERT"
					 . " INTO " . $classe::TABLE_TOURNOI_EQUIPE
					 . " (tournoi_id, equipe_id)"
					 . " VALUES (" . $this->get("id") . ", " . $equipe_id . ")";
			
			$resultat = $BDD->requeteInsertion($requete);
			return true;
		}
		else
		{
			return null;
		}
	}
	
	// Enleve une équipe du tournoi
	public function enleveEquipe($equipe_id)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
			
			$equipe = Equipe::recup($equipe_id);
			
			if ($equipe->recupNbMatchs(-1, null, $this) == 0)
			{
				$requete = "DELETE"
						 . " FROM " . $classe::TABLE_TOURNOI_EQUIPE
						 . " WHERE tournoi_id = '" . $this->get("id") . "'"
						 . " AND equipe_id = '" . $equipe->get("id") . "'";
				
				return $BDD->requeteSuppression($requete);
			}
			else
			{
				return 0;
			}
		}

		return null;
	}
	
	// Controle si une régle est déjà attribué à un classement de ce tournoi
	public function estAttribueRegle($regle)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT *"
					 . " FROM " . ClassementTournoi::TABLE . " ct"
					 . " WHERE ct.tournoi_id = '" . $this->get("id") . "'"
					 . " AND ct.regle = '" . $regle . "'";

			$resultat = $BDD->requeteMultiResultats($requete);
			if (!$resultat)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return null;
		}
	}
}

?>