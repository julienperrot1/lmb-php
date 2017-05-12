<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une phase de type "poules"
*/
class PhasePoules extends Objet
{
	const TYPE 		= "PhasePoules";
	const TABLE 	= "monobasket_phase_poules";
	const CHAMPS 	= "phase_id,nb_poules,nb_periode_match,duree_periode_match";
	
	
	// Récupère les équipes du tournoi n'étant pas attribué à cette phase
	public function getEquipesNonAttribuees()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT te.equipe_id AS equipe_id"
					 . " FROM " . Tournoi::TABLE_TOURNOI_EQUIPE . " te,"
					 . "      " . Phase::TABLE . " p"
					 . " WHERE te.tournoi_id = p.tournoi_id"
					 . " AND p.id = '" . $this->get("phase_id") . "'"
					 . " AND te.equipe_id NOT IN (SELECT poe.equipe_id"
					 . "                          FROM " . Poule::TABLE_POULE_EQUIPE . " poe,"
					 . "                               " . Poule::TABLE . " po"
					 . "                          WHERE poe.poule_id = po.id"
					 . "                          AND po.phase_poules_id = '" . $this->get("phase_id") . "')";

			$resultat = $BDD->requeteMultiResultats($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				$return_values = array();
				foreach($resultat as $index => $ligne)
				{
					$return_values[] = Equipe::recup($ligne["equipe_id"]);
				}
				
				return $return_values;
			}
		}
		else
		{
			return null;
		}
	}
	
	// Controle si une régle est déjà attribué à une poule de cette phase
	public function estAttribueRegle($regle)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT *"
					 . " FROM " . Poule::TABLE . " p,"
					 . "      " . Poule::TABLE_POULE_EQUIPE . " pe"
					 . " WHERE p.phase_poules_id = '" . $this->get("phase_id") . "'"
					 . " AND p.id = pe.poule_id"
					 . " AND pe.regle = '" . $regle . "'";

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