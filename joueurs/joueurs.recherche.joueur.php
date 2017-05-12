<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'utils/Tableur.php');
include_once ($RACINE . 'modele/Joueur.php');


if (isset($_POST["texteRecherche"]) && $_POST["texteRecherche"] != "")
{
	$joueurs = Joueur::cherche($_POST["texteRecherche"], $tri = "pseudo ASC");
}
else
{
	$joueurs = Joueur::recupTous($tri = "pseudo ASC");
}

if (sizeof($joueurs) > $NB_RESULTATS_RECHERCHE_JOUEURS)
{
	print ("Trop de résultats (" . sizeof($joueurs) . ") : Affinez votre recherche");
	print ("<BR/>");
	$joueurs = array_slice($joueurs, 0, $NB_RESULTATS_RECHERCHE_JOUEURS); 
}
		
Tableur::dessineTableau($joueurs, true
					  , array("Photo", "Nom", "Date de naissance", "Sexe", "Equipes jouées (matchs)", "Actions")
					  , array(function ($objet) { 	if ($objet->get("photo") && $objet->get("photo") != "")
													{
														return "<IMG class=\"image_petite\" src=\"images_upload/" . $objet->get("photo") . "\"></IMG>";
													}
													else
													{
														return "<IMG class=\"image_petite\" src=\"images/joueur_default_" . $objet->get("sexe") . ".jpg\"></IMG>";
													}
							  }
							, function ($objet) {   global $utilisateur_en_cours;
													$retour = $objet->get("pseudo");
													if (isset($utilisateur_en_cours))
													{
														$retour = $retour . " (" . $objet->get("prenom") . " " . $objet->get("nom") . ")";
													}
													return $retour;
							  }
					        , function ($objet) { 	global $utilisateur_en_cours;
													if (isset($utilisateur_en_cours))
													{
														return $objet->get("naissance");
													}
													else
													{
														return "##########";
													}
							  }
					        , function ($objet) { return $objet->get("sexe"); }
					        , function ($objet) { $retour = "";
												  $equipes_jouees = $objet->getMatchsJoueesParEquipe();
												  if ($equipes_jouees)
												  {
													foreach ($equipes_jouees as $equipe_jouee)
													{
													  $retour = $retour . "<A href=\"equipe.php?id=" . $equipe_jouee["equipe"]->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe_jouee["equipe"]->get("couleur_base") . "\">" . $equipe_jouee["equipe"]->get("nom") . " (" . $equipe_jouee["nb_matchs"] . ")</FONT></B></A> ";
													}
												  }
												  else
												  {
												    $retour = "Aucune";
												  }
												  return $retour; }
					        , function ($objet) { 
													global $utilisateur_en_cours;
													
													$retour = "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheJoueur(" . $objet->get("id") . ");\" src=\"images/validation.jpg\"></IMG>";
													if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 3))
													{	
													   $retour = $retour
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieJoueur(" . $objet->get("id") . ");\" src=\"images/modification.jpg\"></IMG>"
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeJoueur(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
													}
													return $retour;
												}
							 )
					  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");

?>
	