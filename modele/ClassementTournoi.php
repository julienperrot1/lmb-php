<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente le classement d'une équipe lors d'un tournoi d'une ligue
*/
class ClassementTournoi extends Objet
{
	const TYPE 		= "CLassementTournoi";
	const TABLE 	= "monobasket_classement_tournoi";
	const CHAMPS 	= "place,equipe_id,regle_equipe,tournoi_id,points";
	
	
	// Récupère la place suivante pour un tournoi donné
	public static function prochainePlace($tournoi_id)
	{
		global $BDD;

		$requete = "SELECT MAX(place) + 1 AS place"
				 . " FROM " . ClassementTournoi::TABLE . " ct"
				 . " WHERE ct.tournoi_id = '" . $tournoi_id . "'";	
		
		$resultat = $BDD->requeteMonoResultat($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			if ($resultat["place"])
			{
				return $resultat["place"];
			}
			else
			{
				return "1";
			}
		}
	}
	
	// Récupère la nombre de points par défaut pour une place donnée
	public static function recupPointsParPlace($place)
	{
		global $TOURNOI_POINTS_PAR_PLACE;
		
		if ($TOURNOI_POINTS_PAR_PLACE[$place])
		{
			return $TOURNOI_POINTS_PAR_PLACE[$place];
		}
		
		return null;
	}
	
	// Récupère un classement pour un tournoi et une équipe donnée
	public static function recupParTournoiEtEquipe($tournoi_id, $equipe_id)
	{
		global $BDD;

		$requete = "SELECT id"
				 . " FROM " . ClassementTournoi::TABLE . " ct"
				 . " WHERE ct.tournoi_id = " . $tournoi_id
				 . " AND ct.equipe_id = " . $equipe_id;	
		
		$resultat = $BDD->requeteMonoResultat($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			return ClassementTournoi::recup($resultat["id"]);
		}
	}
}

?>