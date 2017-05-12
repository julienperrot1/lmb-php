<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une phase d'un tournoi d'une ligue
*/
class Phase extends Objet
{
	const TYPE 		= "Phase";
	const TABLE 	= "monobasket_phase";
	const CHAMPS 	= "tournoi_id,libelle,date,ordre_temporel,type,specifique_id";
	
	
	// Récupère l'ordre temporel de phase suivant pour un tournoi donné
	public static function prochainOrdreTemporel($tournoi_id)
	{
		global $BDD;

		$requete = "SELECT MAX(ordre_temporel) + 1 AS ordre_temporel"
				 . " FROM " . Phase::TABLE . " p"
				 . " WHERE p.tournoi_id = '" . $tournoi_id . "'";	
		
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
	
	// Controle si une régle est déjà attribué à cette phase
	public function estAttribueRegle($regle)
	{
		if ($this->_vientdelabase == true) {
			if ($this->get("type") == 1)
			{
				$phase_poules = PhasePoules::recup($this->get("specifique_id"));
				return $phase_poules->estAttribueRegle($regle);
			}
			
			if ($this->get("type") == 2)
			{
				$phase_tableau = PhaseTableau::recup($this->get("specifique_id"));
				return $phase_tableau->estAttribueRegle($regle);
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