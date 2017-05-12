<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
include_once ($RACINE . 'modele/Shoot.php');
include_once ($RACINE . 'modele/Faute.php');
include_once ($RACINE . 'modele/PasseDecisive.php');
include_once ($RACINE . 'modele/Rebond.php');
include_once ($RACINE . 'modele/Contre.php');
include_once ($RACINE . 'modele/Interception.php');
	
/*
  Représente une action générique lors d'un match
*/
class Action extends Objet
{
	const TYPE 		= "Action";
	const TABLE 	= "monobasket_action";
	const CHAMPS 	= "temps_de_jeu_id,temps,joueur_acteur_id,joueur_cible_id,commentaire,type,specifique_id";
	
	// Surcharge de la méthode de suppression afin de supprimer l'action générique mais également l'action spécifique liée
	public static function supprimeAvecSpecifique($action_id)
	{
		$action = Action::recup($action_id);
		$action_type = $action->get("type");
		$action_specifique_id = $action->get("specifique_id");
		
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
		
		return (Action::supprime($action_id));
	}
}

?>