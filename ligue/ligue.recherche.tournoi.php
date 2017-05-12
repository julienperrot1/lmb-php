<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'utils/Tableur.php');
include_once ($RACINE . 'modele/Tournoi.php');


if (isset($_POST["ligueId"]) && $_POST["ligueId"] != "")
{
	$tournois = Tournoi::recupParChamp("ligue_id", $_POST["ligueId"], $tri = "id DESC");

	if ($tournois)
	{
		Tableur::dessineTableau($tournois, true
							  , array("Nom du tournoi", "Nombre d'équipe maximum", "Actions")
							  , array(function ($objet) { return $objet->get("libelle"); }
									, function ($objet) { return $objet->get("nb_equipe_max");  }
									, function ($objet) { 
													global $utilisateur_en_cours;
													
													$retour = "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheTournoi(" . $objet->get("id") . ");\" src=\"images/validation.jpg\"></IMG>";
													if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
													{	
													   $retour = $retour
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieTournoi(" . $objet->get("id") . ");\" src=\"images/modification.jpg\"></IMG>"
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeTournoi(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
													}
													return $retour;
												}
									 )
							  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");
	}
	else
	{
		print ("Aucun tournoi n'a été créé pour cette ligue");
	}
}

?>
	