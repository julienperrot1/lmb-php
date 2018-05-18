<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Shoot.php');
include_once ($RACINE . 'modele/Faute.php');
include_once ($RACINE . 'modele/PasseDecisive.php');
include_once ($RACINE . 'modele/Rebond.php');
include_once ($RACINE . 'modele/Contre.php');
include_once ($RACINE . 'modele/Interception.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Stat.php');
	
/*
  Représente une action générique lors d'un match
*/
class Action extends Objet
{
	const TYPE 		= "Action";
	const TABLE 	= "monobasket_action";
	const CHAMPS 	= "temps_de_jeu_id,temps,joueur_acteur_id,joueur_cible_id,commentaire,type,specifique_id";
	
	// Surcharge de la méthode de suppression afin de supprimer l'action générique mais également l'action spécifique liée
	public function supprimeAvecSpecifique()
	{
    global $ACTION_TYPE_SHOOT, $ACTION_TYPE_FAUTE, $ACTION_TYPE_PASSE, $ACTION_TYPE_REBOND, $ACTION_TYPE_CONTRE, $ACTION_TYPE_INTERCEPTION;
    
		$action_type = $this->get("type");
		$action_specifique_id = $this->get("specifique_id");
		
		if ($action_type == $ACTION_TYPE_SHOOT)
		{
			Shoot::supprime($action_specifique_id);
		}
		
		if ($action_type == $ACTION_TYPE_FAUTE)
		{
			Faute::supprime($action_specifique_id);
		}
		
		if ($action_type == $ACTION_TYPE_PASSE)
		{
			PasseDecisive::supprime($action_specifique_id);
		}
		
		if ($action_type == $ACTION_TYPE_REBOND)
		{
			Rebond::supprime($action_specifique_id);
		}
		
		if ($action_type == $ACTION_TYPE_CONTRE)
		{
			Contre::supprime($action_specifique_id);
		}
		
		if ($action_type == $ACTION_TYPE_INTERCEPTION)
		{
			Interception::supprime($action_specifique_id);
		}
		
		return (Action::supprime($this->get("id")));
	}
  
  public function supprimeStat()
	{
    global $ACTION_TYPE_SHOOT, $ACTION_TYPE_FAUTE, $ACTION_TYPE_PASSE, $ACTION_TYPE_REBOND, $ACTION_TYPE_CONTRE, $ACTION_TYPE_INTERCEPTION;
  
    /* Récupération des informations de l'action en cours de suppression */
    $action_type = $this->get("type");
    $joueur_acteur_id = $this->get("joueur_acteur_id");
    $action_specifique_id = $this->get("specifique_id");
	  $temps_de_jeu = TempsDeJeu::recup($this->get("temps_de_jeu_id"));
    $match = Match::recup($temps_de_jeu->get("match_id"));
    $phase = Phase::recup($match->get("phase_id"));
    $tournoi = Tournoi::recup($phase->get("tournoi_id"));
    $formation1 = Formation::recup($match->get("formation1_id"));
    $formation2 = Formation::recup($match->get("formation2_id"));
    $formation_source = null;
    $formation_cible = null;
    
    $numero_joueur_formation1 = $formation1->getNumeroJoueur($joueur_acteur_id);
    if (isset($numero_joueur_formation1))
    {
      $formation_source = $formation1;
      $formation_cible = $formation2;
    }
    else
    {
      $formation_source = $formation2;
      $formation_cible = $formation1;
    }

    /* Suppression des stats correspondantes */
    //  Cas du shoot
    if ($action_type == $ACTION_TYPE_SHOOT)
    {
      $shoot = Shoot::recup($action_specifique_id);
      if ($shoot->get("type") != 1)
      {
        Stat::enleveStats("SHOOT", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);						
        if ($shoot->get("reussi") == 1)
        {
          Stat::enleveStats("SHOOT-REUSSI", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
        }
      }
      
      Stat::enleveStats("SHOOT-" . $shoot->get("type"), $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
      if ($shoot->get("reussi") == 1)
      {
        Stat::enleveStats("POINT", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), $shoot->get("type"), 0);
        Stat::enleveStats("SHOOT-" . $shoot->get("type") . "-REUSSI", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
      }
    }
    
    //  Cas de la faute
    if ($action_type == $ACTION_TYPE_FAUTE)
    {
      Stat::enleveStats("FAUTE", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
    }
    
    //  Cas de la passe décisive
    if ($action_type == $ACTION_TYPE_PASSE)
    {
      Stat::enleveStats("PASSE", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
    }
    
    //  Cas du rebond
    if ($action_type == $ACTION_TYPE_REBOND)
    {
      $rebond = Rebond::recup($action_specifique_id);
      Stat::enleveStats("REBOND-" . $rebond->get("type"), $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
      Stat::enleveStats("REBOND", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
    }
    
    //  Cas du contre
    if ($action_type == $ACTION_TYPE_CONTRE)
    {
      Stat::enleveStats("CONTRE", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);	
    }
    
    //  Cas de l'interception
    if ($action_type == $ACTION_TYPE_INTERCEPTION)
    {
      Stat::enleveStats("INTERCEPTION", $joueur_acteur_id, $formation_source->get("equipe_id"), $temps_de_jeu->get("id"), $match->get("id"), $tournoi->get("id"), $formation_cible->get("equipe_id"), 1, 0);
    }
    
    return true;
  }   
}

?>