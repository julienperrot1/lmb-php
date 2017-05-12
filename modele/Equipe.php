<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Joueur.php');
	
/*
  Représente une équipe de la ligue
*/
class Equipe extends Objet
{
	const TYPE 		= "Equipe";
	const TABLE 	= "monobasket_equipe";
	const CHAMPS 	= "nom,couleur_base,photo";
	
	// Récupère le nombre de tournoi auxquels l'équipe à participé
	public function recupNbTournois()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT COUNT(*) AS nb_tournois"
					 . " FROM " . Tournoi::TABLE_TOURNOI_EQUIPE . " tte"
					 . " WHERE tte.equipe_id = '" . $this->get("id") . "'";	
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["nb_tournois"];
			}
		}
		else
		{
			return null;
		}
	}

	// Récupère les tournois auxquels l'équipe à participé
	public function recupTournois()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT tte.tournoi_id AS tournoi_id"
					 . " FROM " . Tournoi::TABLE_TOURNOI_EQUIPE . " tte"
					 . " WHERE tte.equipe_id = '" . $this->get("id") . "'"
					 . " ORDER BY tte.tournoi_id DESC";	
			
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
					$tournoi = Tournoi::recup($ligne["tournoi_id"]);
					
					if ($tournoi)
					{
						$return_values[] = $tournoi;
					}
					else
					{
						return null;
					}
				}
				
				return $return_values;
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de match auxquels l'équipe à participé
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
					 . " FROM " . Phase::TABLE . " p"
					 . " LEFT JOIN " . Match::TABLE . " m ON m.phase_id = p.id"
					 . " LEFT JOIN " . Formation::TABLE . " f ON ";
					 
			if ($match_resultat == -1)
			{	
				$requete = $requete
						 . "(m.formation1_id = f.id OR m.formation2_id = f.id)";		 
			}
			else if ($match_resultat == 0)
			{	
				$requete = $requete
						 . "(m.formation1_id = f.id OR m.formation2_id = f.id) AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";	
			}
			else if ($match_resultat == 1)
			{	
				$requete = $requete
						 . "(m.formation1_id = f.id AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE1 . "') OR (m.formation2_id = f.id AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE2 . "')";
			}
			else if ($match_resultat == 2)
			{	
				$requete = $requete
						 . "(m.formation1_id = f.id AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE2 . "') OR (m.formation2_id = f.id AND m.resultat = '" . $MATCH_RESULTAT_EQUIPE1 . "')";
			}
			else if ($match_resultat == 3)
			{	
				$requete = $requete
						 . "(m.formation1_id = f.id OR m.formation2_id = f.id) AND m.resultat = '" . $MATCH_RESULTAT_NUL . "'";	
			}
			else if ($match_resultat == 4)
			{	
				$requete = $requete
						 . "(m.formation1_id = f.id OR m.formation2_id = f.id) AND m.resultat = '" . $MATCH_RESULTAT_AJOUER . "'";	
			}
			
			$requete = $requete
					 . " LEFT JOIN " . Formation::TABLE . " fa ON (m.formation1_id = fa.id OR m.formation2_id = fa.id) AND fa.id <> f.id"
					 . " LEFT JOIN " . Poule::TABLE_POULE_MATCH . " pm ON pm.match_id = m.id";

					 
			$requete = $requete
					 . " WHERE f.equipe_id = '" . $this->get("id") . "'";
			
			if ($equipe_adverse)
			{
				$requete = $requete
					 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			if ($tournoi)
			{
				$requete = $requete
					 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'";
			}
			
			if ($poule)
			{
				$requete = $requete
					 . " AND pm.poule_id = '" . $poule->get("id") . "'";
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
	
	// Récupère le temps de jeu de l'équipe
	// $match_resultat :
	//   -1 	: Tous les matchs
	//   0  	: Matchs joués
	//   1		: Matchs gagnés
	//   2		: Matchs perdus
	//   3		: Matchs nuls
	//   4		: Matchs à jouer
	public function recupTempsDeJeu($match_resultat = -1, $equipe_adverse = null, $tournoi = null, $poule = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER, $MATCH_RESULTAT_EQUIPE1, $MATCH_RESULTAT_EQUIPE2, $MATCH_RESULTAT_NUL;

			$requete = "SELECT SUM(tdj.duree) AS duree"
					 . " FROM " . Match::TABLE . " m,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Formation::TABLE . " f";
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
					 . " WHERE tdj.match_id = m.id"
					 . "   AND f.equipe_id = '" . $this->get("id") . "'";
			
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
			
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return ($resultat["duree"] / 60);
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère la liste des joueurs ayant joué pour cette équipe, ainsi que le nombre de matchs joués par chacun
	public function recupJoueursNbMatch($match_resultat = -1, $equipe_adverse = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;

			$requete = "SELECT j.id AS joueur_id
			                 , count(*) AS nb_matchs"
					 . " FROM " . Joueur::TABLE . " j,"
					 . "      " . Formation::TABLE . " f,"
					 . "      " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Match::TABLE . " m"
					 . " WHERE f.equipe_id = '" . $this->get("id") . "'"
					 . " AND f.id = fj.formation_id"
					 . " AND j.id = fj.joueur_id";
					 
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
					 
			$requete = $requete
					 . " GROUP BY fj.joueur_id"
					 . " ORDER BY nb_matchs DESC";
				
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
					if ($ligne["joueur_id"] == 0)
					{
						return null;
					}
					
					$return_values[] = array ( "joueur" 	=> 	Joueur::recup($ligne["joueur_id"])
									         , "nb_matchs" 	=> 	$ligne["nb_matchs"] );
				}
				
				return $return_values;
			}
		}
		else
		{
			return null;
		}
	}
	
	// Récupère le nombre de shoot de l'équipe dans un match
	//  avec $type : 0 -> Tous les shoot
	//               1 -> Lancers francs
	//               2 -> Shoot à 2 points
	//               3 -> Shoot à 3 points
	//               5 -> Shoot à 2 et 3 points
	public function nbShootsParMatch($match_id, $type, $reussi = null, $temps_de_jeu_id = null, $vraie_stat = -1, $poule = null, $equipe_adverse = null, $tournoi = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_SHOOT, $MATCH_RESULTAT_AJOUER;
			
			$requete = "SELECT count(*) AS nbShoots"
					 . " FROM " . Phase::TABLE . " p"
					 . " LEFT JOIN " . Match::TABLE . " m ON m.phase_id = p.id"
					 . " LEFT JOIN " . TempsDeJeu::TABLE . " tdj ON tdj.match_id = m.id"
					 . " LEFT JOIN " . Poule::TABLE_POULE_MATCH . " pm ON pm.match_id = m.id"
					 . " LEFT JOIN " . Action::TABLE . " a ON a.temps_de_jeu_id = tdj.id"
					 . " LEFT JOIN " . Shoot::TABLE . " s ON a.specifique_id = s.id"
					 . " LEFT JOIN " . Formation::TABLE_FORMATION_JOUEUR . " fj ON a.joueur_acteur_id = fj.joueur_id"
					 . " LEFT JOIN " . Formation::TABLE . " f ON ((fj.formation_id = m.formation1_id AND f.id = fj.formation_id) OR (fj.formation_id = m.formation2_id AND f.id = fj.formation_id))"
					 . " LEFT JOIN " . Formation::TABLE . " fa ON ((m.formation1_id = fa.id AND formation1_id <> f.id) OR (m.formation2_id = fa.id AND formation2_id <> f.id))";
					 
			
			$requete = $requete
					 . " WHERE f.equipe_id = '" . $this->get("id") . "'"
					 . "   AND a.type = '" . $ACTION_TYPE_SHOOT . "'";
					 
					 
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
				else
				{
					$requete = $requete
						. " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";
				}
			}
			
			if ($tournoi)
			{
				$requete = $requete
					 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'";
			}
					 
			if ($equipe_adverse)
			{
				$requete = $requete
					 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			if ($poule)
			{
				$requete = $requete
					 . " AND pm.poule_id = '" . $poule->get("id") . "'";
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
	
	// Récupère le nombre de faute du l'équipe dans un match
	//  avec $type : 0 -> Commises et subies
	//               1 -> Commises
	//               2 -> Subies
	public function nbFautesParMatch($match_id, $type, $temps_de_jeu_id = null, $poule = null, $equipe_adverse = null, $tournoi = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_FAUTE;
			
			$requete = "SELECT count(*) AS nbFautes"
					 . " FROM " . Phase::TABLE . " p"
					 . " LEFT JOIN " . Match::TABLE . " m ON m.phase_id = p.id"
					 . " LEFT JOIN " . TempsDeJeu::TABLE . " tdj ON tdj.match_id = m.id"
					 . " LEFT JOIN " . Poule::TABLE_POULE_MATCH . " pm ON pm.match_id = m.id"
					 . " LEFT JOIN " . Action::TABLE . " a ON a.temps_de_jeu_id = tdj.id"
					 . " LEFT JOIN " . Formation::TABLE_FORMATION_JOUEUR . " fj ON ";
					 
			if ($type == 0)
			{
				$requete = $requete
					 . "(a.joueur_acteur_id = fj.joueur_id OR a.joueur_cible_id = fj.joueur_id)";
			}
			if ($type == 1)
			{
				$requete = $requete
					 . "a.joueur_acteur_id = fj.joueur_id";
			}
			if ($type == 2)
			{
				$requete = $requete
					 . "a.joueur_cible_id = fj.joueur_id";
			}		 
			
			$requete = $requete
					 . " LEFT JOIN " . Formation::TABLE . " f ON ((fj.formation_id = m.formation1_id AND f.id = fj.formation_id) OR (fj.formation_id = m.formation2_id AND f.id = fj.formation_id))"
					 . " LEFT JOIN " . Formation::TABLE . " fa ON ((m.formation1_id = fa.id AND formation1_id <> f.id) OR (m.formation2_id = fa.id AND formation2_id <> f.id))";
			
			
			$requete = $requete
					 . " WHERE f.equipe_id = '" . $this->get("id") . "'"
					 . "   AND a.type = '" . $ACTION_TYPE_FAUTE . "'";
			
			
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
				else
				{
					$requete = $requete
						. " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";
				}
			}
			
			if ($tournoi)
			{
				$requete = $requete
					 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'";
			}
					 
			if ($equipe_adverse)
			{
				$requete = $requete
					 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			if ($poule)
			{
				$requete = $requete
					 . " AND pm.poule_id = '" . $poule->get("id") . "'";
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
	
	// Récupère le nombre de rebond de l'équipe dans un match
	//  avec $type : 0 -> Tous
	//               1 -> Offensif
	//               2 -> Défensif
	public function nbRebondsParMatch($match_id, $type, $temps_de_jeu_id = null, $poule = null, $equipe_adverse = null, $tournoi = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_REBOND;
			
			$requete = "SELECT count(*) AS nbRebonds"
					 . " FROM " . Phase::TABLE . " p"
					 . " LEFT JOIN " . Match::TABLE . " m ON m.phase_id = p.id"
					 . " LEFT JOIN " . TempsDeJeu::TABLE . " tdj ON tdj.match_id = m.id"
					 . " LEFT JOIN " . Poule::TABLE_POULE_MATCH . " pm ON pm.match_id = m.id"
					 . " LEFT JOIN " . Action::TABLE . " a ON a.temps_de_jeu_id = tdj.id"
					 . " LEFT JOIN " . Rebond::TABLE . " r ON a.specifique_id = r.id"
					 . " LEFT JOIN " . Formation::TABLE_FORMATION_JOUEUR . " fj ON a.joueur_acteur_id = fj.joueur_id"
					 . " LEFT JOIN " . Formation::TABLE . " f ON ((fj.formation_id = m.formation1_id AND f.id = fj.formation_id) OR (fj.formation_id = m.formation2_id AND f.id = fj.formation_id))"
					 . " LEFT JOIN " . Formation::TABLE . " fa ON ((m.formation1_id = fa.id AND formation1_id <> f.id) OR (m.formation2_id = fa.id AND formation2_id <> f.id))";
					 
			
			$requete = $requete
					 . " WHERE f.equipe_id = '" . $this->get("id") . "'"
					 . "   AND a.type = '" . $ACTION_TYPE_REBOND . "'";
					 
					 
			if ($type != 0)
			{
				$requete = $requete
					 . " AND r.type = '" . $type . "'";
			}
			
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
				else
				{
					$requete = $requete
						. " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'";
				}
			}
			
			if ($tournoi)
			{
				$requete = $requete
					 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'";
			}
					 
			if ($equipe_adverse)
			{
				$requete = $requete
					 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			if ($poule)
			{
				$requete = $requete
					 . " AND pm.poule_id = '" . $poule->get("id") . "'";
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
	
	// Récupère le nombre de passes décisives de l'équipe dans un match
	public function nbPassesParMatch($match_id, $temps_de_jeu_id = null, $poule = null, $equipe_adverse = null, $tournoi = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_PASSE;
			
			$requete = "SELECT count(*) AS nbPasses"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Formation::TABLE . " f";
					 
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
					 . " WHERE a.type = '" . $ACTION_TYPE_PASSE . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND tdj.match_id = m.id"
					 . " AND ((fj.formation_id = m.formation1_id AND f.id = fj.formation_id AND f.equipe_id = '" . $this->get("id") . "')"
					 . "   OR (fj.formation_id = m.formation2_id AND f.id = fj.formation_id AND f.equipe_id = '" . $this->get("id") . "'))"
					 . " AND a.joueur_acteur_id = fj.joueur_id";
			
			
			if ($poule)
			{
				$requete = $requete
						 . " AND pm.poule_id = '" . $poule->get("id") . "'"
						 . " AND pm.match_id = m.id";
			}
			
			if ($tournoi)
			{
				$requete = $requete
						 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'"
						 . " AND m.phase_id = p.id";
			}
								
			if ($equipe_adverse)
			{
				$requete = $requete
						 . " AND (m.formation1_id = fa.id"
						 . "   OR m.formation2_id = fa.id)"
						 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			
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
	
	// Récupère le nombre de contres de l'équipe dans un match
	public function nbContresParMatch($match_id, $temps_de_jeu_id = null, $poule = null, $equipe_adverse = null, $tournoi = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_CONTRE;
			
			$requete = "SELECT count(*) AS nbContres"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Formation::TABLE . " f";
					 
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
					 . " WHERE a.type = '" . $ACTION_TYPE_CONTRE . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND tdj.match_id = m.id"
					 . " AND ((fj.formation_id = m.formation1_id AND f.id = fj.formation_id AND f.equipe_id = '" . $this->get("id") . "')"
					 . "   OR (fj.formation_id = m.formation2_id AND f.id = fj.formation_id AND f.equipe_id = '" . $this->get("id") . "'))"
					 . " AND a.joueur_acteur_id = fj.joueur_id";
			
			
			if ($poule)
			{
				$requete = $requete
						 . " AND pm.poule_id = '" . $poule->get("id") . "'"
						 . " AND pm.match_id = m.id";
			}
			
			if ($tournoi)
			{
				$requete = $requete
						 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'"
						 . " AND m.phase_id = p.id";
			}
									
			if ($equipe_adverse)
			{
				$requete = $requete
						 . " AND (m.formation1_id = fa.id"
						 . "   OR m.formation2_id = fa.id)"
						 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			
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
	
	// Récupère le nombre d'interceptions de l'équipe dans un match
	public function nbInterceptionsParMatch($match_id, $temps_de_jeu_id = null, $poule = null, $equipe_adverse = null, $tournoi = null)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $ACTION_TYPE_INTERCEPTION;
			
			$requete = "SELECT count(*) AS nbInterceptions"
					 . " FROM " . Action::TABLE . " a,"
					 . "      " . TempsDeJeu::TABLE . " tdj,"
					 . "      " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE_FORMATION_JOUEUR . " fj,"
					 . "      " . Formation::TABLE . " f";
					 
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
					 . " WHERE a.type = '" . $ACTION_TYPE_INTERCEPTION . "'"
					 . " AND a.temps_de_jeu_id = tdj.id"
					 . " AND tdj.match_id = m.id"
					 . " AND ((fj.formation_id = m.formation1_id AND f.id = fj.formation_id AND f.equipe_id = '" . $this->get("id") . "')"
					 . "   OR (fj.formation_id = m.formation2_id AND f.id = fj.formation_id AND f.equipe_id = '" . $this->get("id") . "'))"
					 . " AND a.joueur_acteur_id = fj.joueur_id";
			
			if ($poule)
			{
				$requete = $requete
						 . " AND pm.poule_id = '" . $poule->get("id") . "'"
						 . " AND pm.match_id = m.id";
			}
			
			if ($tournoi)
			{
				$requete = $requete
						 . " AND p.tournoi_id = '" . $tournoi->get("id") . "'"
						 . " AND m.phase_id = p.id";
			}
							
			if ($equipe_adverse)
			{
				$requete = $requete
						 . " AND (m.formation1_id = fa.id"
						 . "   OR m.formation2_id = fa.id)"
						 . " AND fa.equipe_id = '" . $equipe_adverse->get("id") . "'";
			}
			
			
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
	
	// Calcule le nombre de points d'une équipe au sein d'une poule
	public function nbPointsPoule($poule)
	{
		if ($this->_vientdelabase == true) {
			$nb_points = 
			  + $this->recupNbMatchs(1, null, null, $poule) * $poule->get("points_victoire")
			  + $this->recupNbMatchs(2, null, null, $poule) * $poule->get("points_defaite")
			  +	$this->recupNbMatchs(3, null, null, $poule) * $poule->get("points_nul");
			  
			return $nb_points;
		}
		else
		{
			return null;
		}
	}
	
	// Calcule le goal average d'une équipe au sein d'une poule
	public function goalAveragePoule($poule)
	{
		if ($this->_vientdelabase == true) {
			global $BDD, $MATCH_RESULTAT_AJOUER;
					
			$requete = "(SELECT score1 - score2 AS goal_average"
					 . " FROM " . Poule::TABLE_POULE_MATCH . " pm,"
					 . "      " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE . " f"
					 . " WHERE pm.poule_id = '" . $poule->get("id") . "'"
					 . " AND m.id = pm.match_id"
					 . " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'"
					 . " AND f.id = m.formation1_id"
					 . " AND f.equipe_id = '" . $this->get("id") . "')"
					 . "UNION ALL"
					 . "(SELECT score2 - score1 AS goal_average"
					 . " FROM " . Poule::TABLE_POULE_MATCH . " pm,"
					 . "      " . Match::TABLE . " m,"
					 . "      " . Formation::TABLE . " f"
					 . " WHERE pm.poule_id = '" . $poule->get("id") . "'"
					 . " AND m.id = pm.match_id"
					 . " AND m.resultat <> '" . $MATCH_RESULTAT_AJOUER . "'"
					 . " AND f.id = m.formation2_id"
					 . " AND f.equipe_id = '" . $this->get("id") . "')";
			  
			$resultat = $BDD->requeteMultiResultats($requete);
			if (!$resultat)
			{
				return 0;
			}
			else
			{
				$goal_average = 0;
				foreach($resultat as $ligne)
				{
					$goal_average = $goal_average + max(min($ligne["goal_average"], $poule->get("goal_average_ecart_max")), -$poule->get("goal_average_ecart_max"));
				}
				
				return $goal_average;
			}
		}
		else
		{
			return null;
		}
	}
	
	// Retrouve le départage d'une équipe pour une poule
	public function recupDepartagePoule($poule)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
					
			$requete = "SELECT d.points AS points"
					 . " FROM " . Poule::TABLE_DEPARTAGE . " d"
					 . " WHERE d.poule_id = '" . $poule->get("id") . "'"
					 . " AND d.equipe_id = '" . $this->get("id") . "'";
			  
			$resultat = $BDD->requeteMonoResultat($requete);
			if (!$resultat)
			{
				return null;
			}
			else
			{
				return $resultat["points"];
			}
		}
		else
		{
			return null;
		}
	}
	
	// Met en place une valeur de point pour un départage lors d'une poule
	public function indiqueDepartagePoule($poule, $points)
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
					
			$departage_poule = $this->recupDepartagePoule($poule);
			if (isset($departage_poule) && is_numeric($departage_poule))
			{
				$requete = "UPDATE " . Poule::TABLE_DEPARTAGE
						. " SET points = '" . $points . "'"
						. " WHERE poule_id = '" . $poule->get("id") . "'"
						. " AND equipe_id = '" . $this->get("id") . "'";
						
				$resultat = $BDD->requeteModification($requete);
			}
			else
			{
				$requete = "INSERT INTO " . Poule::TABLE_DEPARTAGE . "(poule_id, equipe_id, points)"
						. " VALUES ('" . $poule->get("id") . "', '" . $this->get("id") . "', '" . $points . "')";
						
				$resultat = $BDD->requeteInsertion($requete);
			}
			
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
	
	// Permet de récupérer la couleur de l'équipe sous forme de tableau RVB de 0 à 255
	public function recupCouleurBaseRVB()
	{
		if ($this->_vientdelabase == true) {
			$split_couleur_base = str_split($this->get("couleur_base"));
			if (sizeof($split_couleur_base == 7))
			{
				$rouge = (Equipe::valeurDecimalDeHexa($split_couleur_base[0]) * 16)
					    + Equipe::valeurDecimalDeHexa($split_couleur_base[1]);
				$vert  = (Equipe::valeurDecimalDeHexa($split_couleur_base[2]) * 16)
					    + Equipe::valeurDecimalDeHexa($split_couleur_base[3]);
				$bleu  = (Equipe::valeurDecimalDeHexa($split_couleur_base[4]) * 16)
					    + Equipe::valeurDecimalDeHexa($split_couleur_base[5]);
				
				return array($rouge, $vert, $bleu);
			}
		}
		else
		{
			return null;
		}
	}
	
	private static function valeurDecimalDeHexa($hexa)
	{
		if (is_numeric($hexa))
		{
			if ($hexa >= 0 && $hexa <= 9)
			{
				return $hexa;
			}
			else
			{
				return 0;
			}
		}
		else if ($hexa == "A" || $hexa == "a")
		{
			return 10;
		}
		else if ($hexa == "B" || $hexa == "b")
		{
			return 11;
		}
		else if ($hexa == "C" || $hexa == "c")
		{
			return 12;
		}
		else if ($hexa == "D" || $hexa == "d")
		{
			return 13;
		}
		else if ($hexa == "E" || $hexa == "e")
		{
			return 14;
		}
		else if ($hexa == "F" || $hexa == "f")
		{
			return 15;
		}
		else
		{
			return 0;
		}	
	}
	
}

?>