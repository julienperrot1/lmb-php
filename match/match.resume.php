<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'modele/Action.php');
include_once ($RACINE . 'modele/Shoot.php');
include_once ($RACINE . 'modele/Faute.php');
include_once ($RACINE . 'modele/PasseDecisive.php');
include_once ($RACINE . 'modele/Rebond.php');
include_once ($RACINE . 'modele/Contre.php');
include_once ($RACINE . 'modele/Interception.php');


if (isset($_POST["tempsDeJeuId"]))
{
	$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
	$match = Match::recup($temps_de_jeu->get("match_id"));
	$actions = Action::recupParChamp("temps_de_jeu_id", $_POST["tempsDeJeuId"], "temps DESC, id DESC");
	
	print ("Résumé du temps de jeu :");
	print ("<BR/>");
	
	if ($actions)
	{
		foreach($actions as $action)
		{
			$temps = $action->get("temps");
			$minutes = floor($temps / 60);
			$secondes = $temps - ($minutes * 60);
			$joueur_acteur = Joueur::recup($action->get("joueur_acteur_id"));
			$joueur_cible = Joueur::recup($action->get("joueur_cible_id"));
			$type = $action->get("type");
			$specifique_id = $action->get("specifique_id");
			
			print (sprintf("%02d", $minutes) . " : " . sprintf("%02d", $secondes) . " - ");
			
			if ($type == $ACTION_TYPE_GENERIQUE)
			{
				print ("Action de " . $joueur_acteur->get("pseudo") . " ()");
				if ($joueur_cible)
				{
					print (" sur " . $joueur_cible->get("pseudo") . " ()");
				}
				print (" : " . $action->get("commentaire"));
			}
			else if ($type == $ACTION_TYPE_SHOOT && $specifique_id)
			{
				$shoot = Shoot::recup($specifique_id);
				$shoot_type = $shoot->get("type");
				$shoot_reussi = $shoot->get("reussi");
				
				print ($SHOOT_TYPE_DESC[$shoot_type]);
				print (" de " . $joueur_acteur->get("pseudo") . " ()");
				print (" : " . $ACTION_REUSSITE_DESC[$shoot_reussi]);
				if ($shoot_reussi)
				{
					print (", " . $shoot_type . " point(s) pour l'équipe");
				}
			}
			else if ($type == $ACTION_TYPE_FAUTE && $specifique_id)
			{
				$faute = Faute::recup($specifique_id);
				$faute_type = $faute->get("type");
				
				print ($FAUTE_TYPE_DESC[$faute_type]);
				print (" de " . $joueur_acteur->get("pseudo") . " ()");
				if ($joueur_cible)
				{
					print (" sur " . $joueur_cible->get("pseudo") . " ()");
				}
			}
			else if ($type == $ACTION_TYPE_PASSE && $specifique_id)
			{
				$passe_decisive = PasseDecisive::recup($specifique_id);
				
				print ("Passe décisive");
				print (" de " . $joueur_acteur->get("pseudo") . " ()");
				print (" à " . $joueur_cible->get("pseudo") . " ()");
			}
			else if ($type == $ACTION_TYPE_REBOND && $specifique_id)
			{
				$rebond = Rebond::recup($specifique_id);
				$rebond_type = $rebond->get("type");
				
				print ($REBOND_TYPE_DESC[$rebond_type]);
				print (" de " . $joueur_acteur->get("pseudo") . " ()");
			}
			else if ($type == $ACTION_TYPE_CONTRE && $specifique_id)
			{
				$contre = Contre::recup($specifique_id);
				
				print ("Contre");
				print (" de " . $joueur_acteur->get("pseudo") . " ()");
				print (" sur " . $joueur_cible->get("pseudo") . " ()");
			}
			else if ($type == $ACTION_TYPE_INTERCEPTION && $specifique_id)
			{
				$interception = Interception::recup($specifique_id);
				
				print ("Interception");
				print (" de " . $joueur_acteur->get("pseudo") . " ()");
				print (" sur " . $joueur_cible->get("pseudo") . " ()");
			}
			else
			{
				print ("Action corrompue : A supprimer");
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{	
				print (" <DIV class=\"champ_a_cliquer_nok\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeAction(" . $action->get("id") . ");\">Annuler</DIV>");
			}
			
			print ("<BR/>");
		}
	} 
	else
	{
		print ("Aucune action n'est actuellement enregistrée pour ce temps de jeu");
		print ("<BR/>");	
	}
}
else
{
	print ("<DIV class=\"messageErreur\" >Erreur lors du chargement du résumé du temps de jeu</DIV>");
}

?>
	