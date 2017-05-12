<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/ClassementTournoi.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'utils/Tableur.php');
include_once ($RACINE . 'utils/Regle.php');


if (isset($_POST["tournoiId"]) && $_POST["tournoiId"] != "")
{
	$tournoi = Tournoi::recup($_POST["tournoiId"]);
	$classements_tournoi = ClassementTournoi::recupParChamp("tournoi_id", $_POST["tournoiId"], $tri = "place ASC");

	print ("<DIV class=\"soustitre\">Classement du tournoi</DIV>");
	print ("<BR/>");
		
	if ($classements_tournoi)
	{
		Tableur::dessineTableau($classements_tournoi, true
										  , array("Place", "Equipe", "Points", "Actions")
										  , array(function ($objet) { return $objet->get("place"); }
										        , function ($objet)
												{ 
													global $utilisateur_en_cours, $tournoi;
													$retour = "";
													
													if ($objet->get("equipe_id") != null && $objet->get("equipe_id") > 0)
													{
														$equipe = Equipe::recup($objet->get("equipe_id")); 
														
														$retour = $retour . "<A href=\"equipe.php?id=" . $equipe->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B></A>";
														if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
														{
															$retour = $retour . " <IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveEquipeClassement(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
														}
													}
													else if ($objet->get("regle_equipe") != null)
													{
														$retour = $retour . Regle::versString($objet->get("regle_equipe"));
														if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
														{
															$retour = $retour . " <IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveRegleClassement(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
														}
													}
													else
													{
														if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
														{
															$selecteur_regle = Regle::recupSelecteurRegle($tournoi);				
															
															if ($selecteur_regle)
															{
																$retour = $retour .
																	  "Ajouter une règle : "
																	. "<SELECT id=\"tournoi.classement." . $objet->get("id") . ".regle.ajoute"
																	. "\" onchange=\"ajouteRegleClassement(" . $objet->get("id") . ");\">"
																	. $selecteur_regle
																	. "</SELECT><BR/>";
															}
														}
													}
													
													return $retour;
												}
												, function ($objet) { return $objet->get("points"); }
												, function ($objet) {
													global $utilisateur_en_cours;
													$retour = " ";
													
													if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
													{
														$retour = $retour
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieClassementTournoi(" . $objet->get("id") . ");\" src=\"images/modification.jpg\"></IMG>"
															. "<IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeClassementTournoi(" . $objet->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>";
													}
													
													return $retour;
												}
											)
										  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");											
	}
	else
	{
		print ("<DIV class=\"texte\">Aucun classement pour l'instant</DIV>");
		print ("<BR/>");
	}
		
	if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
	{
		print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.classement.creation.classement\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeClassementTournoi();\">Création d'une place au classement</DIV>");	
		
		print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.phase.resolution.regles\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"resoutReglesClassement();\">Résolution des régles</DIV>");	
	}	
}

?>
	