<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une ligne de statistiques cumulées
*/
class Stat extends Objet
{
	const TYPE 		= "Stat";
	const TABLE 	= "monobasket_stat";
	const CHAMPS 	= "joueur_id,equipe_id,temps_de_jeu_id,match_id,tournoi_id,equipe_adverse_id,stat,somme,diviseur";
	
		
	public static function ajouteStats($stat_name, $joueur_id, $equipe_id, $temps_de_jeu_id, $match_id, $tournoi_id, $equipe_adverse_id, $ajout_somme, $ajout_diviseur)
	{
		Stat::ajoute($stat_name, $joueur_id, -1, $temps_de_jeu_id, -1, -1, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, $joueur_id, -1, -1, $match_id, -1, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, $joueur_id, -1, -1, -1, $tournoi_id, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, $joueur_id, -1, -1, -1, -1, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, -1, $equipe_id, $temps_de_jeu_id, -1, -1, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, -1, $equipe_id, -1, $match_id, -1, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, -1, $equipe_id, -1, -1, $tournoi_id, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, -1, $equipe_id, -1, -1, -1, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, $joueur_id, $equipe_id, -1, -1, $tournoi_id, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, $joueur_id, $equipe_id, -1, -1, -1, -1, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, $joueur_id, -1, -1, -1, -1, $equipe_adverse_id, $ajout_somme, $ajout_diviseur);
		Stat::ajoute($stat_name, -1, $equipe_id, -1, -1, -1, $equipe_adverse_id, $ajout_somme, $ajout_diviseur);
		
		return 0;
	}
	
	private static function ajoute($stat_name, $joueur_id, $equipe_id, $temps_de_jeu_id, $match_id, $tournoi_id, $equipe_adverse_id, $ajout_somme, $ajout_diviseur)
	{
		$stat = Stat::recupParSpecificite($stat_name, $joueur_id, $equipe_id, $temps_de_jeu_id, $match_id, $tournoi_id, $equipe_adverse_id);
		if (isset($stat))
		{
			$stat->set("somme", $stat->get("somme") + $ajout_somme);
			$stat->set("diviseur", $stat->get("diviseur") + $ajout_diviseur);
			$stat->enregistre();
		}
	    else
	    {
			$stat = new Stat();
	     	$stat->set("joueur_id", $joueur_id);
	     	$stat->set("equipe_id", $equipe_id);
	     	$stat->set("temps_de_jeu_id", $temps_de_jeu_id);
	     	$stat->set("match_id", $match_id);
	     	$stat->set("tournoi_id", $tournoi_id);
	     	$stat->set("equipe_adverse_id", $equipe_adverse_id);
	     	$stat->set("stat", $stat_name);
	     	$stat->set("somme", $ajout_somme);
	    	
	    	if ($ajout_diviseur > 1)
	    	{
	    		$stat->set("diviseur", $ajout_diviseur);
	    	}
	    	else
	    	{
	    		$stat->set("diviseur", 1);
	    	}
	    	
			print ("#####DEBUG####");
			print ("#####DEBUG####" . $stat->get("joueur_id"));
	     	print ("#####DEBUG####" . $stat->get("equipe_id"));
	     	print ("#####DEBUG####" . $stat->get("temps_de_jeu_id"));
	     	print ("#####DEBUG####" . $stat->get("match_id"));
	     	print ("#####DEBUG####" . $stat->get("tournoi_id"));
	     	print ("#####DEBUG####" . $stat->get("equipe_adverse_id"));
	     	print ("#####DEBUG####" . $stat->get("stat"));
	     	print ("#####DEBUG####" . $stat->get("somme"));
			$stat->cree();
		}

		return 0;
	}
	
	
	// Permet de récupérer un élément par son identifiant primaire
	public static function recupParSpecificite($stat, $joueur_id, $equipe_id, $temps_de_jeu_id, $match_id, $tournoi_id, $equipe_adverse_id)
	{
		global $BDD;
		$classe = get_called_class();
		
		$requete = "SELECT *"
		         . " FROM " . $classe::TABLE
				 . " WHERE stat = '" . $stat . "'"
				 . " AND joueur_id = " . $joueur_id
				 . " AND equipe_id = " . $equipe_id
				 . " AND temps_de_jeu_id = " . $temps_de_jeu_id
				 . " AND match_id = " . $match_id
				 . " AND tournoi_id = " . $tournoi_id
				 . " AND equipe_adverse_id = " . $equipe_adverse_id . ";";
		
		$resultat = $BDD->requeteMonoResultat($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			return (new $classe($resultat, true));
		}
	}
}

?>