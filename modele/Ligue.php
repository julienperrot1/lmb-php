<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/ClassementTournoi.php');
include_once ($RACINE . 'modele/Tournoi.php');
	
/*
  Représente une ligue
*/
class Ligue extends Objet
{
	const TYPE 		= "Ligue";
	const TABLE 	= "monobasket_ligue";
	const CHAMPS 	= "libelle,type,nb_tournoi_class";
	
	// Récupère le classement actuel de la ligue
	public function getClassement()
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER, $MATCH_RESULTAT_EQUIPE1, $MATCH_RESULTAT_EQUIPE2, $MATCH_RESULTAT_NUL;
			
			$return_values = array();
			
			$requete = "SELECT DISTINCT ct.equipe_id AS equipe_id"
					 . " FROM " . ClassementTournoi::TABLE . " ct,"
					 . "      " . Tournoi::TABLE . " t"
					 . " WHERE ct.tournoi_id = t.id"
					 . "   AND t.ligue_id = " . $this->get("id") . ""
					 . "   AND ct.equipe_id > 0";
			$resultat = $BDD->requeteMultiResultats($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				foreach($resultat as $equipe_r)
				{
					$equipe = Equipe::recup($equipe_r["equipe_id"]);
					
					$requete2 = "SELECT SUM(r.points) AS points"
							  . " FROM (" 
							  . "  SELECT ct.points AS points"
							  . "   FROM " . ClassementTournoi::TABLE . " ct,"
							  . "        " . Tournoi::TABLE . " t"
							  . "   WHERE ct.tournoi_id = t.id"
							  . "     AND t.ligue_id = " . $this->get("id") . ""
							  . "     AND ct.equipe_id = " . $equipe_r["equipe_id"]
							  . "     ORDER BY ct.points DESC"
							  . "     LIMIT " . $this->get("nb_tournoi_class")
							  . "  ) AS r";
					$resultat2 = $BDD->requeteMonoResultat($requete2);
					if (!$resultat2)
					{
						return null;
					}
					else
					{
						$return_values[] = array(	"classement" 		=> 	-1,
													"equipe_id" 		=> 	$equipe->get("id"),
													"points" 			=> 	$resultat2["points"]
						);
					}
				}
			
				usort($return_values, "Ligue::trieClassement");
			
				$place = 0;
				$points_precedent = -1;
				$nb_egalite = 0;
				for($i = 0; $i < sizeof($return_values); $i++)
				{		
					if ($i != 0 && Ligue::trieClassement($return_values[$i], $return_values[$i - 1]) == 0)
					{
						$nb_egalite = $nb_egalite + 1;
					}
					else
					{
						$place = $place + $nb_egalite + 1;
						$nb_egalite = 0;
					}
					$points_precedent = $return_values[$i]["points"];
					
					$return_values[$i]["classement"] = $place;
				}

				return $return_values;
			}
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
			return 0;
		}
		else
		{
			return 1;
		}
	}
}

?>