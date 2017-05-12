<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une phase de type "tableau"
*/
class PhaseTableau extends Objet
{
	const TYPE 		= "PhaseTableau";
	const TABLE 	= "monobasket_phase_tableau";
	const CHAMPS 	= "phase_id,etat";
	
	
	// Controle si une régle est déjà attribué à un match de cette phase
	public function estAttribueRegle($regle)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT *"
					 . " FROM " . Match::TABLE . " m"
					 . " WHERE m.phase_id = '" . $this->get("phase_id") . "'"
					 . " AND (m.regle_formation1 = '" . $regle . "'"
					 . "   OR m.regle_formation2 = '" . $regle . "')";

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