<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'utils/Tableur.php');
include_once ($RACINE . 'modele/Equipe.php');


if (isset($_POST["texteRecherche"]) && $_POST["texteRecherche"] != "")
{
	$equipes = Equipe::recupParChampPartiel("nom", $_POST["texteRecherche"], $tri = "nom ASC");
}
else
{
	$equipes = Equipe::recupTous($tri = "nom ASC");
}

if (sizeof($equipes) > $NB_RESULTATS_RECHERCHE_EQUIPES)
{
	print ("Trop de résultats (" . sizeof($equipes) . ") : Affinez votre recherche");
	print ("<BR/>");
	$equipes = array_slice($equipes, 0, $NB_RESULTATS_RECHERCHE_EQUIPES); 
}
		
Tableur::dessineTableau($equipes, true
					  , array("Logo", "Nom de l'équipe", "Nombre de tournoi", "Nombre de matchs joués", "Actions")
					  , array(function ($objet) { 	if ($objet->get("photo") && $objet->get("photo") != "")
													{
														return "<IMG class=\"image_petite\" src=\"images_upload/" . $objet->get("photo") . "\"></IMG>";
													}
													return "<IMG class=\"image_petite\" src=\"images/equipe_default.jpg\"></IMG>";
												  
							  }
					        , function ($objet) { return "<A href=\"equipe.php?id=" . $objet->get("id") . "\"><B><FONT color=\"#" . $objet->get("couleur_base") . "\">" . $objet->get("nom") . "</FONT></B></A>"; }
							, function ($objet) { return $objet->recupNbTournois(); }
					        , function ($objet) { return $objet->recupNbMatchs(); }
					        , function ($objet) { 
													global $utilisateur_en_cours;
													
													$retour = "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheEquipe(" . $objet->get("id") . ");\" src=\"images/validation.jpg\"></IMG>";
													if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
													{	
													   $retour = $retour
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieEquipe(" . $objet->get("id") . ");\" src=\"images/modification.jpg\"></IMG>"
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeEquipe(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
													}
													return $retour;
												}
							)
					  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");

?>
	