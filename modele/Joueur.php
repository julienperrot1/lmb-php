<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');
	
/*
  Représente un joueur de la ligue
*/
class Joueur extends Objet
{
	const TYPE 		= "Joueur";
	const TABLE 	= "monobasket_joueur";
	const CHAMPS 	= "nom,prenom,pseudo,naissance,sexe,photo,nom_utilisateur,md5_mdp,droits";
	

	// Récupère le nombre d'action du joueur dans un match
	//  avec $type : 0 -> Toutes les actions
	//               1 -> Action dont le joueur est source
	//               2 -> Action dont le joueur est cible
	public function nbActionsParMatch($match_id, $type, $temps_de_jeu_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			
			$requete = "SELECT count(*) AS nbActions"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj";

			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " WHERE tdj.id = " . $temps_de_jeu_id
					 . " AND a.temps_de_jeu_id = " . $temps_de_jeu_id;
			}
			else
			{
				$requete = $requete
					 . " WHERE tdj.match_id = " . $match_id
					 . " AND a.temps_de_jeu_id = tdj.id";
			}
			
			if ($type == 0)
			{
				$requete = $requete
					 . " AND (a.joueur_acteur_id = '" . $this->get("id") . "'"
					 . "   OR a.joueur_cible_id = '" . $this->get("id") . "')";
			}
			else if ($type == 1)
			{
				$requete = $requete
					 . " AND a.joueur_acteur_id = '" . $this->get("id") . "'";
			}
			else if ($type == 2)
			{
				$requete = $requete
					 . " AND a.joueur_cible_id = '" . $this->get("id") . "'";
			}
			else
			{
				return null;
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nbActions"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le temps joué par le joueur, en secondes
	// $niveau_stats :
	//   -1 	: Tous les matchs
	//   1  	: Matchs sans stats
	//   2		: Matchs stats standards (sans les temps de jeu)
	//   3		: Matchs stats complètes
	//   5		: Matchs avec stats
	public function tempsJoueParMatch($match_id, $temps_de_jeu_id = null, $niveau_stats = -1)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER;
			
			$requete = "SELECT sum(tdj.duree) AS duree"
					 . " FROM " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE_FORMATION_JOUEUR . " fj"
					 . " WHERE tdj.match_id = m.id"
					 . " AND (m.formation1_id = fj.formation_id OR m.formation2_id = fj.formation_id)"
					 . " AND fj.joueur_id = " . $this->get("id");
			
			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " AND tdj.id = " . $temps_de_jeu_id;
			}
			else
			{
				if ($match_id != -1)
				{
					$requete = $requete
						. " AND m.id = " . $match_id;
				}
				else
				{
					$requete = $requete
						. " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";
				}
			}

			if ($niveau_stats != -1)
			{
				if ($niveau_stats == 5)
				{
					$requete = $requete
						 . " AND m.niveau_stats > 1";
				}
				else
				{
					$requete = $requete
						 . " AND m.niveau_stats = " . $niveau_stats;
				}
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["duree"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de shoot du joueur dans un match
	//  avec $type : 0 -> Tous les shoot
	//               1 -> Lancers francs
	//               2 -> Shoot à 2 points
	//               3 -> Shoot à 3 points
	//               5 -> Shoot à 2 et 3 points
	public function nbShootsParMatch($match_id, $type, $reussi = null, $temps_de_jeu_id = null, $vraie_stat = -1)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_SHOOT, $MATCH_RESULTAT_AJOUER;
			
			$requete = "SELECT count(*) AS nbShoots"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . Shoot::TABLE . " s,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Match::TABLE . " m"
					 . " WHERE a.type = '" . $ACTION_TYPE_SHOOT . "'"
					 . " AND a.specifique_id = s.id"
					 . " AND a.joueur_acteur_id = '" . $this->get("id") . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND tdj.match_id = m.id";
			
			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " AND tdj.id = " . $temps_de_jeu_id
					 . " AND a.temps_de_jeu_id = " . $temps_de_jeu_id;
			}
			else
			{
				if ($match_id != -1)
				{
					$requete = $requete
						. " AND tdj.match_id = " . $match_id;
				}
				else
				{
					$requete = $requete
						. " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";
				}
			}
			
			if ($type == 5)
			{
				$requete = $requete
					 . " AND (s.type = '2' OR s.type = '3')";
			}
			else if ($type != 0)
			{
				$requete = $requete
					 . " AND s.type = '" . $type . "'";
			}
			
			if ($reussi != null)
			{
				$requete = $requete
					 . " AND s.reussi = '" . $reussi . "'";
			}
			
			if ($reussi == null || $reussi == false || $vraie_stat == 1)
			{
				$requete = $requete
					 . " AND m.niveau_stats > 1";
			}
			
			if ($vraie_stat == 0)
			{
				$requete = $requete
					 . " AND m.niveau_stats <= 1";
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nbShoots"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de faute du joueur dans un match
	//  avec $type : 0 -> Commises et subies
	//               1 -> Commises
	//               2 -> Subies
	public function nbFautesParMatch($match_id, $type, $temps_de_jeu_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_FAUTE, $MATCH_RESULTAT_AJOUER;
			
			$requete = "SELECT count(*) AS nbFautes"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Match::TABLE . " m"
					 . " WHERE a.type = '" . $ACTION_TYPE_FAUTE . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND tdj.match_id = m.id";
			
			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " AND tdj.id = " . $temps_de_jeu_id
					 . " AND a.temps_de_jeu_id = " . $temps_de_jeu_id;
			}
			else
			{
				if ($match_id != -1)
				{
					$requete = $requete
						. " AND tdj.match_id = " . $match_id;
				}
				else
				{
					$requete = $requete
						. " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";
				}
			}
			
			if ($type == 0)
			{
				$requete = $requete
					 . " AND (a.joueur_acteur_id = '" . $this->get("id") . "'"
					 . "   OR a.joueur_cible_id = '" . $this->get("id") . "')";
			}
			if ($type == 1)
			{
				$requete = $requete
					 . " AND a.joueur_acteur_id = '" . $this->get("id") . "'";
			}
			if ($type == 2)
			{
				$requete = $requete
					 . " AND a.joueur_cible_id = '" . $this->get("id") . "'";
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nbFautes"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de rebond du joueur dans un match
	//  avec $type : 0 -> Tous
	//               1 -> Offensif
	//               2 -> Défensif
	public function nbRebondsParMatch($match_id, $type = 0, $temps_de_jeu_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_REBOND;
			
			$requete = "SELECT count(*) AS nbRebonds"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . Rebond::TABLE . " r,"
					 . "      " . TempsDeJeu::TABLE . " tdj"
					 . " WHERE a.type = '" . $ACTION_TYPE_REBOND . "'"
					 . " AND a.specifique_id = r.id"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND a.joueur_acteur_id = '" . $this->get("id") . "'";
			
			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " AND tdj.id = " . $temps_de_jeu_id;
			}
			else
			{
				if ($match_id != -1)
				{
					$requete = $requete
						 . " AND tdj.match_id = " . $match_id;
				}
			}
			
			if ($type != 0)
			{
				$requete = $requete
					 . " AND r.type = '" . $type . "'";
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nbRebonds"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de passes décisives du joueur dans un match
	public function nbPassesParMatch($match_id, $temps_de_jeu_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_PASSE;
			
			$requete = "SELECT count(*) AS nbPasses"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj"
					 . " WHERE a.type = '" . $ACTION_TYPE_PASSE . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND a.joueur_acteur_id = '" . $this->get("id") . "'";
			
			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " AND tdj.id = " . $temps_de_jeu_id;
			}
			else
			{
				if ($match_id != -1)
				{
					$requete = $requete
						 . " AND tdj.match_id = " . $match_id;
				}
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nbPasses"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de contres du joueur dans un match
	public function nbContresParMatch($match_id, $temps_de_jeu_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_CONTRE;
			
			$requete = "SELECT count(*) AS nbContres"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj"
					 . " WHERE a.type = '" . $ACTION_TYPE_CONTRE . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND a.joueur_acteur_id = '" . $this->get("id") . "'";
			
			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " AND tdj.id = " . $temps_de_jeu_id;
			}
			else
			{
				if ($match_id != -1)
				{
					$requete = $requete
						 . " AND tdj.match_id = " . $match_id;
				}
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nbContres"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre d'interceptions du joueur dans un match
	public function nbInterceptionsParMatch($match_id, $temps_de_jeu_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_INTERCEPTION;
			
			$requete = "SELECT count(*) AS nbInterceptions"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj"
					 . " WHERE a.type = '" . $ACTION_TYPE_INTERCEPTION . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND a.joueur_acteur_id = '" . $this->get("id") . "'";
			
			if ($temps_de_jeu_id != null)
			{
				$requete = $requete
					 . " AND tdj.id = " . $temps_de_jeu_id;
			}
			else
			{
				if ($match_id != -1)
				{
					$requete = $requete
						 . " AND tdj.match_id = " . $match_id;
				}
			}
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nbInterceptions"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Permet de chercher les joueurs en utilisant un champ partiel sur le nom prénom et pseudo
	public static function cherche($recherche, $tri = "")
	{
		global $BDD;
		$classe = get_called_class();
				
		$requete = "SELECT *"
		         . " FROM " . $classe::TABLE
		         . " WHERE nom like '%" . $recherche . "%'"
		         . " OR prenom like '%" . $recherche . "%'"
		         . " OR pseudo like '%" . $recherche . "%'"
		         . " OR nom_utilisateur like '%" . $recherche . "%'";
		if ($tri && $tri != "")
		{
			$requete = $requete
			         . " ORDER BY " . $tri;
		}
		
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
				$return_values[] = new $classe($ligne, true);
			}
			
			return $return_values;
		}
	}
	
	// Permet de retrouver les matchs joués par un joueur, classés par équipe
	// $match_resultat :
	//   -1 	: Tous les matchs
	//   0  	: Matchs joués
	//   1		: Matchs gagnés
	//   2		: Matchs perdus
	//   3		: Matchs nuls
	//   4		: Matchs à jouer
	public function getMatchsJoueesParEquipe($match_resultat = -1, $equipe_id = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER, $MATCH_RESULTAT_EQUIPE1, $MATCH_RESULTAT_EQUIPE2, $MATCH_RESULTAT_NUL;
			$classe = get_called_class();
					
			$requete = "SELECT f.equipe_id AS equipe_id, count(*) AS nb_matchs"
					 . " FROM " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Formation::TABLE . " f,"
					 . "      " . Match::TABLE . " m"
					 . " WHERE fj.joueur_id = '" . $this->get("id") . "'"
					 . " AND fj.formation_id = f.id";

			if ($match_resultat == -1)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)";		 
			}
			else if ($match_resultat == 0)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)"
						 . " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";	
			}
			else if ($match_resultat == 1)
			{	
				$requete = $requete
						 . " AND ((m.formation1_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE1 . "')"
						 . "   OR (m.formation2_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE2 . "'))";
			}
			else if ($match_resultat == 2)
			{	
				$requete = $requete
						 . " AND ((m.formation1_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE2 . "')"
						 . "   OR (m.formation2_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE1 . "'))";
			}
			else if ($match_resultat == 3)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)"
						 . " AND m.resultat = '" . $MATCH_RESULTAT_NUL . "'";	
			}
			else if ($match_resultat == 4)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)"
						 . " AND m.resultat = '" . $MATCH_RESULTAT_AJOUER . "'";	
			}
			
			if ($equipe_id)
			{	
				$requete = $requete
						 . " AND f.equipe_id = " . $equipe_id;		 
			}
			
			$requete = $requete
					 . " GROUP BY f.equipe_id";
					 
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
					$return_values[$index] = array();
					$return_values[$index]["equipe"] = Equipe::recup($ligne["equipe_id"]);
					$return_values[$index]["nb_matchs"] = $ligne["nb_matchs"];
				}
				
				return $return_values;
			}
		}
		else
		{
			return null;
		}
	}
	

	// Récupère le nombre de match auxquels le joueur à participé
	// $match_resultat :
	//   -1 	: Tous les matchs
	//   0  	: Matchs joués
	//   1		: Matchs gagnés
	//   2		: Matchs perdus
	//   3		: Matchs nuls
	//   4		: Matchs à jouer
	// $niveau_stats :
	//   -1 	: Tous les matchs
	//   1  	: Matchs sans stats
	//   2		: Matchs stats standards (sans les temps de jeu)
	//   3		: Matchs stats complètes
	//   5		: Matchs avec stats
	public function recupNbMatchs($match_resultat = -1, $equipe_adverse = null, $tournoi = null, $poule = null, $niveau_stats = -1)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER, $MATCH_RESULTAT_EQUIPE1, $MATCH_RESULTAT_EQUIPE2, $MATCH_RESULTAT_NUL;

			$requete = "SELECT COUNT(*) AS nb_matchs"
					 . " FROM " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE . " f,"
					 . "      " . Formation::TABLE_FORMATION_JOUEUR . " fj";
			if ($equipe_adverse)
			{
				$requete = $requete . ","
					 . "      " . Formation::TABLE . " fa";
			}
			if ($tournoi)
			{
				$requete = $requete . ","
					 . "      " . Phase::TABLE . " p";
			}
			if ($poule)
			{
				$requete = $requete . ","
					 . "      " . Poule::TABLE_POULE_MATCH . " pm";
			}
			
			$requete = $requete
					 . " WHERE fj.formation_id = f.id"
					 . " AND fj.joueur_id = " . $this->get("id");
			
			if ($equipe_adverse)
			{
				$requete = $requete
					 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			if ($tournoi)
			{
				$requete = $requete
					 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'"
					 . " AND m.phase_id = p.id";
			}
			
			if ($poule)
			{
				$requete = $requete
					 . " AND pm.poule_id = '" . $poule->get("id") . "'"
					 . " AND pm.match_id = m.id";
			}
			
			if ($match_resultat == -1)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)";		 
			}
			else if ($match_resultat == 0)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)"
						 . " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";	
			}
			else if ($match_resultat == 1)
			{	
				$requete = $requete
						 . " AND ((m.formation1_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE1 . "')"
						 . "   OR (m.formation2_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE2 . "'))";
			}
			else if ($match_resultat == 2)
			{	
				$requete = $requete
						 . " AND ((m.formation1_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE2 . "')"
						 . "   OR (m.formation2_id = f.id"
						 . "   AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE1 . "'))";
			}
			else if ($match_resultat == 3)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)"
						 . " AND m.resultat = '" . $MATCH_RESULTAT_NUL . "'";	
			}
			else if ($match_resultat == 4)
			{	
				$requete = $requete
						 . " AND (m.formation1_id = f.id"
						 . "   OR m.formation2_id = f.id)"
						 . " AND m.resultat = '" . $MATCH_RESULTAT_AJOUER . "'";	
			}
			
			if ($equipe_adverse)
			{
				$requete = $requete
						 . " AND (m.formation1_id = fa.id"
						 . "   OR m.formation2_id = fa.id)";	
			}
			
			if ($niveau_stats != -1)
			{
				if ($niveau_stats == 5)
				{
					$requete = $requete
						 . " AND m.niveau_stats > 1";
				}
				else
				{
					$requete = $requete
						 . " AND m.niveau_stats = " . $niveau_stats;
				}
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
	
	public function verifieMdp($mdp)
	{
		if ($this->_vientdelabase == true) {
			if (md5($mdp) == $this->get("md5_mdp"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}

?>