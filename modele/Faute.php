<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une action de type faute lors d'un match
*/
class Faute extends Objet
{
	const TYPE 		= "Faute";
	const TABLE 	= "monobasket_faute";
	const CHAMPS 	= "action_id,type";
	
	
	// Retourne le nombre de faute d'équipe avant les lancés francs automatiques pour une période d'une durée donnée
	public static function getNbFautesEquipePourDuree($duree)
	{
		global $FAUTES_EQUIPE_PAR_DUREE;
		
		foreach ($FAUTES_EQUIPE_PAR_DUREE as $duree_periode => $nb_fautes)
		{
			if ($duree < $duree_periode)
			{
				return $nb_fautes;
			}
		}
		
		return end($FAUTES_EQUIPE_PAR_DUREE);
	}
		
	// Retourne le nombre de faute personnelle avant la disqualification pour un match d'une durée donnée
	public static function recupNbFautesPersoPourDuree($duree)
	{
		global $FAUTES_PERSO_PAR_DUREE;
		
		foreach ($FAUTES_PERSO_PAR_DUREE as $duree_match => $nb_fautes)
		{
			if ($duree <= $duree_match)
			{
				return $nb_fautes;
			}
		}
		
		return end($FAUTES_PERSO_PAR_DUREE);
	}
}

?>