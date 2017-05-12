<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'utils/Tableur.php');
include_once ($RACINE . 'modele/Ligue.php');


if (isset($_POST["texteRecherche"]) && $_POST["texteRecherche"] != "")
{
	$ligues = Ligue::recupParChampPartiel("libelle", $_POST["texteRecherche"], $tri = "id DESC");
}
else
{
	$ligues = Ligue::recupTous($tri = "id DESC");
}

if (sizeof($ligues) > $NB_RESULTATS_RECHERCHE_LIGUES)
{
	print ("Trop de résultats (" . sizeof($ligues) . ") : Affinez votre recherche");
	print ("<BR/>");
	$ligues = array_slice($ligues, 0, $NB_RESULTATS_RECHERCHE_LIGUES); 
}
		
Tableur::dessineTableau($ligues, true
					  , array("Nom de la ligue", "Type de ligue", "Nb tournois classement", "Actions")
					  , array(function ($objet) { return $objet->get("libelle"); }
					        , function ($objet) { global $LIGUE_TYPE_DESC;
							                      return $LIGUE_TYPE_DESC[$objet->get("type")]; }
					        , function ($objet) { return $objet->get("nb_tournoi_class"); }
					        , function ($objet) { 
													global $utilisateur_en_cours;
													
													$retour = "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheLigue(" . $objet->get("id") . ");\" src=\"images/validation.jpg\"></IMG>";
													if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
													{	
													   $retour = $retour
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieLigue(" . $objet->get("id") . ");\" src=\"images/modification.jpg\"></IMG>"
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeLigue(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
													}
													return $retour;
												}
							 )
					  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");

?>
	